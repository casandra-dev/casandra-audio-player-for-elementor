<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor;

use Casandra\AudioPlayerForElementor\Asset\Script;
use Casandra\AudioPlayerForElementor\Asset\Style;
use Casandra\AudioPlayerForElementor\Menu\ApfeMenu;
use Casandra\AudioPlayerForElementor\MetaBox\ApfeTrackMetaBox;
use Casandra\AudioPlayerForElementor\PostType\ApfeTrackPostType;
use Casandra\AudioPlayerForElementor\Taxonomy\ApfeTaxonomy;
use Casandra\AudioPlayerForElementor\Widget\Elementor\ApfeWidget;

defined('ABSPATH') || exit;

/**
 * Plugin is a class to initiate WordPress Radio
 *
 * @package Casandra\AudioPlayerForElementor
 */
class Plugin
{
    private static self $plugin;
    public string $optionsSlug;
    public string $baseUrl;
    public string $basePath;
    private function __construct()
    {
        $this->basePath = plugin_dir_path(__DIR__ . '../');
        $this->baseUrl = plugin_dir_url(__DIR__ . '../');
        $this->optionsSlug = 'casandra-audio-player-for-elementor';
    }

    public static function getPlugin(): self
    {
        if (!isset(self::$plugin)) {
            self::$plugin = new self();
        }
        return self::$plugin;
    }

    public function init(): void
    {
        $apfePostTypeSlug = 'apfe_track';
        $data    = get_file_data( __FILE__, array( 'Version' => 'Version' ), 'plugin' );
        $pluginVersion = $data['Version'];
        add_action(
            'plugins_loaded',
            static function () : void {
                //load_plugin_textdomain('casandra-audio-player-for-elementor');
            }
        );
        $adminScript = new Script(
            'apfe-admin-script',
            $this->baseUrl . 'assets/js/admin.js',
            ['jquery', 'media-upload', 'thickbox'],
            $pluginVersion,
            [
                'in_footer' => true,
                'strategy' => 'defer'
            ]
        );
        $adminStyle = new Style(
            'apfe-admin-style',
            $this->baseUrl . 'assets/css/admin.min.css',
            ['thickbox'],
            $pluginVersion
        );

        $script = new Script(
            'apfe-script',
            $this->baseUrl . 'assets/js/front.js',
            ['jquery', 'mediaelement', 'elementor-frontend'],
            $pluginVersion,
            [
                'in_footer' => true,
                'strategy' => 'defer'
            ]
        );
        add_action( 'elementor/frontend/after_register_scripts', function () use ($script) {
            $script->register();
        });
        $style = new Style(
            'apfe-styles',
            $this->baseUrl . 'assets/css/front.min.css',
            ['mediaelement'],
            $pluginVersion
        );
        $assets = [$style];
        add_action(
            'wp_enqueue_scripts',
            static function () use ($assets): void {
                foreach ($assets as $asset) {
                    $asset->enqueue();
                }
            }
        );
        $adminAssets = [$adminScript, $adminStyle];
        add_action(
            'admin_enqueue_scripts',
            static function () use ($adminAssets): void {
                wp_enqueue_media();
                foreach ($adminAssets as $asset) {
                    $asset->enqueue();
                }
            }
        );
        add_action('elementor/editor/after_enqueue_styles', [$adminStyle, 'enqueue']);
        $labels = [
            'name' => _x('Tracks', 'Post Type General Name', 'casandra-audio-player-for-elementor'),
            'singular_name' => _x('Track', 'Post Type Singular Name', 'casandra-audio-player-for-elementor'),
            'menu_name' => __('Tracks', 'casandra-audio-player-for-elementor'),
            'name_admin_bar' => __('Track', 'casandra-audio-player-for-elementor'),
            'archives' => __('Track Archives', 'casandra-audio-player-for-elementor'),
            'attributes' => __('Track Attributes', 'casandra-audio-player-for-elementor'),
            'parent_item_colon' => __('Parent Track:', 'casandra-audio-player-for-elementor'),
            'all_items' => __('All Tracks', 'casandra-audio-player-for-elementor'),
            'add_new_item' => __('Add New Track', 'casandra-audio-player-for-elementor'),
            'add_new' => __('Add New', 'casandra-audio-player-for-elementor'),
            'new_item' => __('New Track', 'casandra-audio-player-for-elementor'),
            'edit_item' => __('Edit Track', 'casandra-audio-player-for-elementor'),
            'update_item' => __('Update Track', 'casandra-audio-player-for-elementor'),
            'view_item' => __('View Track', 'casandra-audio-player-for-elementor'),
            'view_items' => __('View Tracks', 'casandra-audio-player-for-elementor'),
            'search_items' => __('Search Track', 'casandra-audio-player-for-elementor'),
            'not_found' => __('Not found', 'casandra-audio-player-for-elementor'),
            'not_found_in_trash' => __('Not found in Trash', 'casandra-audio-player-for-elementor'),
            'featured_image' => __('Featured Image', 'casandra-audio-player-for-elementor'),
            'set_featured_image' => __('Set featured image', 'casandra-audio-player-for-elementor'),
            'remove_featured_image' => __('Remove featured image', 'casandra-audio-player-for-elementor'),
            'use_featured_image' => __('Use as featured image', 'casandra-audio-player-for-elementor'),
            'insert_into_item' => __('Insert into track', 'casandra-audio-player-for-elementor'),
            'uploaded_to_this_item' => __('Uploaded to this track', 'casandra-audio-player-for-elementor'),
            'items_list' => __('Tracks list', 'casandra-audio-player-for-elementor'),
            'items_list_navigation' => __('Tracks list navigation', 'casandra-audio-player-for-elementor'),
            'filter_items_list' => __('Filter tracks list', 'casandra-audio-player-for-elementor'),
        ];
        $apfeTrackPostType = new ApfeTrackPostType(
            $apfePostTypeSlug,
            __('Track', 'casandra-audio-player-for-elementor'),
            __('Music Tracks', 'casandra-audio-player-for-elementor'),
            $labels,
            'dashicons-format-audio',
            ['title', 'editor', 'thumbnail'],
            ['apfe_track_cat', 'apfe_track_tag'],
            ['slug' => 'apfe-track']
        );
        $catLabels = [
            'name' => _x('Track Categories', 'taxonomy general name', 'casandra-audio-player-for-elementor'),
            'singular_name' => _x('Track Category', 'taxonomy singular name', 'casandra-audio-player-for-elementor'),
            'search_items' => __('Search Track Categories', 'casandra-audio-player-for-elementor'),
            'all_items' => __('All Track Categories', 'casandra-audio-player-for-elementor'),
            'parent_item' => __('Parent Track Category', 'casandra-audio-player-for-elementor'),
            'parent_item_colon' => __('Parent Track Category:', 'casandra-audio-player-for-elementor'),
            'edit_item' => __('Edit Track Category', 'casandra-audio-player-for-elementor'),
            'update_item' => __('Update Track Category', 'casandra-audio-player-for-elementor'),
            'add_new_item' => __('Add New Track Category', 'casandra-audio-player-for-elementor'),
            'new_item_name' => __('New Track Category Name', 'casandra-audio-player-for-elementor'),
            'menu_name' => __('Track Categories', 'casandra-audio-player-for-elementor'),
        ];
        $apfeTaxonomyCat = new ApfeTaxonomy(
            'apfe_track_cat',
            [$apfePostTypeSlug],
            __('Music Track Categories', 'casandra-audio-player-for-elementor'),
            $catLabels,
            ['slug' => 'apfe-track-category']
        );
        $tagLabels = array(
            'name' => _x('Track Tags', 'taxonomy general name', 'casandra-audio-player-for-elementor'),
            'singular_name' => _x('Track Tag', 'taxonomy singular name', 'casandra-audio-player-for-elementor'),
            'search_items' => __('Search Track Tags', 'casandra-audio-player-for-elementor'),
            'popular_items' => __('Popular Track Tags', 'casandra-audio-player-for-elementor'),
            'all_items' => __('All Track Tags', 'casandra-audio-player-for-elementor'),
            'edit_item' => __('Edit Track Tag', 'casandra-audio-player-for-elementor'),
            'update_item' => __('Update Track Tag', 'casandra-audio-player-for-elementor'),
            'add_new_item' => __('Add New Track Tag', 'casandra-audio-player-for-elementor'),
            'new_item_name' => __('New Track Tag Name', 'casandra-audio-player-for-elementor'),
            'separate_items_with_commas' => __('Separate tags with commas', 'casandra-audio-player-for-elementor'),
            'add_or_remove_items' => __('Add or remove tags', 'casandra-audio-player-for-elementor'),
            'choose_from_most_used' => __('Choose from the most used tags', 'casandra-audio-player-for-elementor'),
            'menu_name' => __('Track Tags', 'casandra-audio-player-for-elementor'),
        );
        $apfeTaxonomytag = new ApfeTaxonomy(
            'apfe_track_tag',
            [$apfePostTypeSlug],
            __('Music Track Tags', 'casandra-audio-player-for-elementor'),
            $tagLabels,
            ['slug' => 'apfe-track-tag']
        );
        add_action('init', [$apfeTrackPostType, 'register']);
        add_action('init', [$apfeTaxonomyCat, 'register']);
        add_action('init', [$apfeTaxonomytag, 'register']);
        $apfeTrackMetaBox = new ApfeTrackMetaBox();
        add_action('add_meta_boxes', static function () use ($apfeTrackMetaBox, $apfePostTypeSlug) {
            $apfeTrackMetaBox->registerMetaBox($apfePostTypeSlug);
        });
        add_action("save_post_{$apfePostTypeSlug}", [$apfeTrackMetaBox, 'saveMeta']);
        add_filter("manage_{$apfePostTypeSlug}_posts_columns", [$apfeTrackMetaBox, 'addColumns']);
        add_action("manage_{$apfePostTypeSlug}_posts_custom_column", [$apfeTrackMetaBox, 'renderColumns'], 10, 2);
        add_filter("manage_edit-{$apfePostTypeSlug}_sortable_columns", [$apfeTrackMetaBox, 'sortableColumns']);
        add_action("pre_get_posts", [$apfeTrackMetaBox, 'columnOrderBy']);
        add_action('elementor/widgets/register', function ($widgets_manager) {
            $apfeWidget = new ApfeWidget();
            do_action('casandra-audio-player-for-elementor/widget/register', $apfeWidget);
            $widgets_manager->register($apfeWidget);
            do_action('casandra-audio-player-for-elementor/widget/after_register');
        });
        $apfeMenu = new ApfeMenu(
            __('Audio Player, Music Player, Radio and Podcast Player', 'casandra-audio-player-for-elementor'),
            __('Audio Player + Radio', 'casandra-audio-player-for-elementor'),
            'manage_options',
            'casandra-audio-player-for-elementor',
            [$this, 'menuRender'],
            $this->baseUrl . '/assets/img/logo-white.svg'
        );
        $apfeSubmenuDoc = new ApfeMenu(
            __('Documentation', 'casandra-audio-player-for-elementor'),
            __('Documentation', 'casandra-audio-player-for-elementor'),
            'manage_options',
            'https://casandra.dev/audio-player-music-player-and-radio-for-elementor-documentation/',
            '',
            '',
            1,
            $apfeMenu
        );
        $apfeSubmenuContact = new ApfeMenu(
            __('Contact us', 'casandra-audio-player-for-elementor'),
            __('Contact us', 'casandra-audio-player-for-elementor'),
            'manage_options',
            'https://casandra.dev/contact/',
            '',
            '',
            2,
            $apfeMenu
        );
        $apfeSubmenuPro = new ApfeMenu(
            __('Go Pro', 'casandra-audio-player-for-elementor'),
            __('Go Pro', 'casandra-audio-player-for-elementor'),
            'manage_options',
            'https://casandra.dev/audio-player-music-player-and-radio-for-elementor/',
            '',
            '',
            3,
            $apfeMenu
        );
        $menus = [$apfeMenu, $apfeSubmenuDoc, $apfeSubmenuContact, $apfeSubmenuPro];
        add_action(
            'admin_menu',
            static function () use ($menus): void {
                foreach ($menus as $menu) {
                    $menu->add();
                }
            }
        );
    }

    public function menuRender()
    {
        require __DIR__ . '/../static/admin/audio-player-for-elementor.php';
    }
}
