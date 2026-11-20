<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor\Asset;

class Style extends Asset
{
    public function register(): bool
    {
        return wp_register_style(
            $this->handle,
            $this->url,
            $this->dependencies,
            $this->version,
            $this->media
        );
    }
    public function enqueue(bool $registered = false): void
    {
        if (!$registered) {
            $this->register();
        }
        wp_enqueue_style($this->handle);
    }
}