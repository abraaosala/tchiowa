<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Core\Session\Session;

class FlashService
{
    private Session $session;
    private array $cache = [];

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function success(string $message): void
    {
        $this->session->flash('success', $message);
    }

    public function error(string $message): void
    {
        $this->session->flash('error', $message);
    }

    public function info(string $message): void
    {
        $this->session->flash('info', $message);
    }

    public function setOldInput(array $input): void
    {
        $this->session->set('_old_input', $input);
    }

    public function getOldInput(string $key, mixed $default = null): mixed
    {
        $old = $this->session->get('_old_input', []);
        return $old[$key] ?? $default;
    }

    public function getSuccess(): ?string
    {
        if (!isset($this->cache['success'])) {
            $this->cache['success'] = $this->session->pullFlash('success');
        }
        return $this->cache['success'];
    }

    public function getError(): ?string
    {
        if (!isset($this->cache['error'])) {
            $this->cache['error'] = $this->session->pullFlash('error');
        }
        return $this->cache['error'];
    }

    public function clearOldInput(): void
    {
        $this->session->remove('_old_input');
    }
}
