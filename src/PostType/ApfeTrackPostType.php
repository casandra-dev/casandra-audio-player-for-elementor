<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor\PostType;

class ApfeTrackPostType implements PostType
{
    private string $postTypeSlug;
    private string $label;
    private string $description;
    private string $capabilities;
    private array $labels;
    private string $menuIcon;
    private array $support;
    private array $taxonomies;
    private bool $public;
    private $rewrite;
    private bool $customCap;
    public function __construct(
        string $postTypeSlug,
        string $label,
        string $description,
        array $labels,
        string $menuIcon,
        array $support,
        array $taxonomies,
        $rewrite = false,
        bool $public = false,
        bool $customCap = false
    ) {
            $this->postTypeSlug = $postTypeSlug;
            $this->label = $label;
            $this->description = $description;
            $this->labels = $labels;
            $this->menuIcon = $menuIcon;
            $this->support = $support;
            $this->taxonomies = $taxonomies;
            $this->public = $public;
            $this->customCap = $customCap;
            $this->rewrite = $rewrite;
    }

    public function register(): void
    {
        if (!$this->customCap) {
            $args = [
                'labels' => $this->labels,
                'public' => $this->public,
                'show_ui' => true,
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'menu_position' => 5,
                'menu_icon' => $this->menuIcon,
                'supports' => $this->support,
                'taxonomies'  => $this->taxonomies,
                'rewrite'  => $this->rewrite
            ];
        } else {
            $capabilityType = [$this->postTypeSlug,  $this->postTypeSlug . 's'];
            $capabilities = [
                'edit_post'              => 'edit_' . $this->postTypeSlug,
                'read_post'              => 'read_' . $this->postTypeSlug,
                'delete_post'            => 'delete_' . $this->postTypeSlug,
                'edit_posts'             => 'edit_' . $this->postTypeSlug . 's',
                'edit_others_posts'      => 'edit_others_' . $this->postTypeSlug . 's',
                'publish_posts'          => 'publish_' . $this->postTypeSlug . 's',
                'read_private_posts'     => 'read_private_' . $this->postTypeSlug . 's',
                'delete_posts'           => 'delete_' . $this->postTypeSlug . 's',
                'delete_private_posts'   => 'delete_private_' . $this->postTypeSlug . 's',
                'delete_published_posts' => 'delete_published_' . $this->postTypeSlug . 's',
                'delete_others_posts'    => 'delete_others_' . $this->postTypeSlug . 's',
                'edit_private_posts'     => 'edit_private_' . $this->postTypeSlug . 's',
                'edit_published_posts'   => 'edit_published_' . $this->postTypeSlug . 's',
                'create_posts'           => 'create_' . $this->postTypeSlug . 's',
            ];
            $args = [
                'labels' => $this->labels,
                'public' => $this->public,
                'show_ui' => true,
                'capability_type' => $capabilityType,
                'capabilities' => $capabilities,
                'map_meta_cap' => true,
                'menu_position' => 5,
                'menu_icon' => $this->menuIcon,
                'supports' => $this->support,
                'taxonomies'  => $this->taxonomies,
                'rewrite'  => $this->rewrite
            ];
        }
        register_post_type($this->postTypeSlug, $args);
    }
}
