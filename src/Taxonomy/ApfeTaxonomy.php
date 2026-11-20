<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor\Taxonomy;

class ApfeTaxonomy implements Taxonomy
{
    private string $slug;
    private array $postTypeSlug;
    private string $description;
    private array $labels;
    private array $rewrite;
    private bool $public;
    public function __construct(
        string $slug,
        array $postTypeSlug,
        string $description,
        array $labels,
        $rewrite = false,
        bool $public = false
    ) {
        $this->slug = $slug;
        $this->postTypeSlug = $postTypeSlug;
        $this->description = $description;
        $this->labels = $labels;
        $this->rewrite = $rewrite;
        $this->public = $public;
    }
    public function register(): void
    {
        $args = [
            'hierarchical' => false,
            'labels' => $this->labels,
            'rewrite' => $this->rewrite,
            'public' => $this->public,
            'show_ui' => true,
        ];
        register_taxonomy($this->slug, $this->postTypeSlug, $args);
    }
}
