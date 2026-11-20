<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor\Asset;

class Script extends Asset
{
    public function register(): bool
    {
        return wp_register_script(
            $this->handle,
            $this->url,
            $this->dependencies,
            $this->version,
            $this->args
        );
    }
    public function enqueue(bool $registered = false): void
    {
        if (!$registered) {
            $this->register();
        }
        wp_enqueue_script($this->handle);
    }
}
