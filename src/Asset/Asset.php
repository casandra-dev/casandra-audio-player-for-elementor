<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor\Asset;

abstract class Asset
{
    protected string $url;
    protected string $handle;
    protected array $dependencies;
    protected string $version;
    protected array $args;
    protected string $media;
    public abstract function register();
    public abstract function enqueue(bool $registered = true);
    public function __construct(
        string $handle,
        string $url = '',
        array $dependencies = [],
        string $version = '',
        array $args = [],
        string $media = 'all'
    ) {
        $this->handle = $handle;
        $this->url = $url;
        $this->dependencies = $dependencies;
        $this->version = $version;
        $this->args = $args;
        $this->media = $media;
    }
}
