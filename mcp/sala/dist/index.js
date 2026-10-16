#!/usr/bin/env node
import { Server } from "@modelcontextprotocol/sdk/server/index.js";
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js";
import { CallToolRequestSchema, ListToolsRequestSchema, } from "@modelcontextprotocol/sdk/types.js";
import { execSync } from "node:child_process";
import { existsSync, readFileSync, readdirSync } from "node:fs";
import { join, resolve, basename } from "node:path";
// Resolve project root: mcp/sala/src/index.ts -> ../../..
const PROJECT_ROOT = resolve(import.meta.dirname ?? ".", "..", "..", "..");
function sh(cmd, cwd = PROJECT_ROOT) {
    try {
        return execSync(cmd, { cwd, encoding: "utf8", timeout: 8000, stdio: ["ignore", "pipe", "pipe"] }).trim();
    }
    catch (e) {
        const out = e?.stdout?.toString?.() ?? "";
        const err = e?.stderr?.toString?.() ?? e?.message ?? String(e);
        return (out + "\n" + err).trim().slice(0, 4000);
    }
}
function readJsonSafe(path) {
    try {
        if (!existsSync(path))
            return null;
        return JSON.parse(readFileSync(path, "utf8"));
    }
    catch {
        return null;
    }
}
function listModels() {
    const dir = join(PROJECT_ROOT, "app", "Infrastructure", "Persistence", "Eloquent");
    if (!existsSync(dir))
        return [];
    try {
        return readdirSync(dir)
            .filter((f) => f.endsWith(".php"))
            .map((f) => f.replace(/\.php$/, ""));
    }
    catch {
        return [];
    }
}
const server = new Server({ name: "sala-boost", version: "1.0.0" }, { capabilities: { tools: {} } });
server.setRequestHandler(ListToolsRequestSchema, async () => ({
    tools: [
        {
            name: "sala_info",
            description: "PHP + Illuminate/BladeOne/Phinx versions, DB driver, Eloquent models - Application Info (Boost equiv.)",
            inputSchema: { type: "object", properties: {}, additionalProperties: false },
        },
        {
            name: "sala_schema",
            description: "Database schema: SHOW TABLES + DESCRIBE <table>. Use table param to describe one table.",
            inputSchema: {
                type: "object",
                properties: { table: { type: "string", description: "Table name to describe (optional)" } },
                additionalProperties: false,
            },
        },
        {
            name: "sala_query",
            description: "Read-only SELECT via Capsule (blocks DROP/DELETE/UPDATE/INSERT without write:true).",
            inputSchema: {
                type: "object",
                properties: {
                    sql: { type: "string", description: "SQL - only SELECT allowed unless write:true" },
                    write: { type: "boolean", description: "Allow write statements (default false)" },
                },
                required: ["sql"],
                additionalProperties: false,
            },
        },
        {
            name: "sala_logs",
            description: "Tail storage/logs (last N lines).",
            inputSchema: {
                type: "object",
                properties: {
                    lines: { type: "integer", description: "Lines to tail (default 50, max 200)" },
                    file: { type: "string", description: "Log file name (default: auto-detect)" },
                },
                additionalProperties: false,
            },
        },
    ],
}));
server.setRequestHandler(CallToolRequestSchema, async (req) => {
    const { name, arguments: args } = req.params;
    const a = (args ?? {});
    if (name === "sala_info") {
        const phpV = sh("php -v");
        const composer = readJsonSafe(join(PROJECT_ROOT, "composer.json"));
        const pkg = readJsonSafe(join(PROJECT_ROOT, "package.json"));
        const phinx = readJsonSafe(join(PROJECT_ROOT, "phinx.php")) ? "phinx.php present" : "phinx.php missing";
        let dbInfo = "";
        try {
            const dbPhp = readFileSync(join(PROJECT_ROOT, "config", "database.php"), "utf8").slice(0, 800);
            dbInfo = dbPhp;
        }
        catch { }
        const models = listModels();
        const text = [
            "## sala_info",
            "### PHP",
            phpV.split("\n").slice(0, 3).join("\n"),
            "### composer.json (require)",
            composer ? JSON.stringify({ name: composer.name, require: composer.require, php: composer.require?.php }, null, 2) : "no composer.json",
            "### package.json",
            pkg ? JSON.stringify({ name: pkg.name, version: pkg.version }, null, 2) : "no package.json",
            "### phinx",
            phinx,
            "### config/database.php (head)",
            dbInfo || "(not readable)",
            "### Eloquent models",
            models.length ? models.join(", ") : "(none yet - run make:model)",
            "### project root",
            PROJECT_ROOT,
        ].join("\n\n");
        return { content: [{ type: "text", text }] };
    }
    if (name === "sala_schema") {
        const table = a.table?.trim();
        const phpCode = table
            ? `require 'vendor/autoload.php'; use Illuminate\\Database\\Capsule\\Manager as C; $c=new C; $cfg=require 'config/database.php'; $conn=$cfg['connections']['mysql']??$cfg['connections'][array_key_first($cfg['connections'])]; $c->addConnection($conn); $c->setAsGlobal(); $c->bootEloquent(); $rows=C::select('DESCRIBE \`'+addslashes(table)+'\`'); foreach($rows as $r) echo json_encode($r).PHP_EOL;`
            : `require 'vendor/autoload.php'; use Illuminate\\Database\\Capsule\\Manager as C; $c=new C; $cfg=require 'config/database.php'; $conn=$cfg['connections']['mysql']??$cfg['connections'][array_key_first($cfg['connections'])]; $c->addConnection($conn); $c->setAsGlobal(); $c->bootEloquent(); $rows=C::select('SHOW TABLES'); foreach($rows as $r) echo json_encode(array_values((array)$r)[0]).PHP_EOL;`;
        const out = table
            ? sh(`php -r "${phpCode.replace(/"/g, '\\"')}"`)
            : sh(`php -r "${phpCode.replace(/"/g, '\\"')}"`);
        if (out && !out.includes("Fatal") && !out.includes("Error")) {
            return { content: [{ type: "text", text: out.slice(0, 6000) || "(empty)" }] };
        }
        const migDir = join(PROJECT_ROOT, "database", "migrations");
        const migs = existsSync(migDir) ? readdirSync(migDir).slice(0, 20).join("\n") : "(no migrations)";
        return {
            content: [{ type: "text", text: `DB not reachable - fallback migrations list:\n${migs}\n\nError: ${out.slice(0, 800)}` }],
        };
    }
    if (name === "sala_query") {
        const sql = String(a.sql ?? "").trim();
        const write = Boolean(a.write);
        if (!sql)
            return { content: [{ type: "text", text: "Missing sql param" }], isError: true };
        const isSelect = /^\s*SELECT\b/i.test(sql);
        if (!isSelect && !write) {
            return { content: [{ type: "text", text: "Blocked: only SELECT allowed without write:true. Pass write:true to allow." }], isError: true };
        }
        const b64 = Buffer.from(sql, "utf8").toString("base64");
        const php = `require 'vendor/autoload.php'; use Illuminate\\Database\\Capsule\\Manager as C; $c=new C; $cfg=require 'config/database.php'; $k=$cfg['default']??'mysql'; $conn=$cfg['connections'][$k]??$cfg['connections']['mysql']; $c->addConnection($conn); $c->setAsGlobal(); $c->bootEloquent(); $sql=base64_decode('${b64}'); $rows=C::select($sql); echo json_encode($rows, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);`;
        const out = sh(`php -r "${php.replace(/"/g, '\\"')}"`);
        return { content: [{ type: "text", text: out.slice(0, 8000) || "(no rows)" }] };
    }
    if (name === "sala_logs") {
        const lines = Math.min(Math.max(Number(a.lines ?? 50), 1), 200);
        const file = a.file?.trim();
        const logDir = resolve(join(PROJECT_ROOT, "storage", "logs"));
        let target = "";
        if (file) {
            const base = basename(file);
            if (base !== file || file.includes("/") || file.includes("\\") || file.includes("..")) {
                return { content: [{ type: "text", text: "Invalid file name. Use only the log file name (e.g. app.log), no paths." }], isError: true };
            }
            if (!/^[\w.\-]+\.log$/.test(base)) {
                return { content: [{ type: "text", text: "Invalid file name. Allowed: letters, numbers, _, -, . and must end with .log" }], isError: true };
            }
            const candidate = resolve(logDir, base);
            const logDirResolved = resolve(logDir);
            if (!candidate.startsWith(logDirResolved)) {
                return { content: [{ type: "text", text: "Invalid file path. Path traversal blocked." }], isError: true };
            }
            if (existsSync(candidate)) {
                target = candidate;
            }
            else {
                return { content: [{ type: "text", text: `Log file not found: ${base}` }], isError: true };
            }
        }
        if (!target) {
            try {
                const files = readdirSync(logDir).filter((f) => f.endsWith(".log"));
                target = files.length ? join(logDir, files[0]) : "";
            }
            catch { }
            if (!target) {
                return { content: [{ type: "text", text: `No log file found in ${logDir}. Pass file param or check storage/logs.` }] };
            }
        }
        try {
            const content = readFileSync(target, "utf8");
            const allLines = content.split(/\r?\n/);
            const tail = allLines.slice(-lines).join("\n");
            return { content: [{ type: "text", text: `## ${target}\n\n${tail.slice(0, 8000)}` }] };
        }
        catch (e) {
            return { content: [{ type: "text", text: `Error reading log ${target}: ${e?.message ?? String(e)}` }], isError: true };
        }
    }
    return { content: [{ type: "text", text: `Unknown tool: ${name}` }], isError: true };
});
async function main() {
    const transport = new StdioServerTransport();
    await server.connect(transport);
    console.error(`[sala-boost] MCP ready - root: ${PROJECT_ROOT}`);
}
main().catch((e) => {
    console.error("[sala-boost] fatal:", e);
    process.exit(1);
});
//# sourceMappingURL=index.js.map