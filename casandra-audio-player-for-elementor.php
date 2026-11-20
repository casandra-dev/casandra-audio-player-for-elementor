<?php

/**
 * Plugin Name: Casandra Audio Player for Elementor – Music Player, Radio & Podcast Player
 * Requires Plugins: elementor
 * Description: A WordPress plugin to create audio player, music player, podcast player, and radio with an all-in-one Elementor widget
 * Version: 1.0.0
 * License: GPLv2 or later
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Author: Casandra Development
 * Author URI: https://casandra.dev/
 * Plugin URI: https://casandra.dev/audio-player-music-player-and-radio-for-elementor/
 * Primary Branch: main
 * Text Domain: casandra-audio-player-for-elementor
 * Domain Path: /languages
 */

declare(strict_types=1);

use Casandra\AudioPlayerForElementor;

defined('ABSPATH') || exit;

do_action('casandra-audio-player-for-elementor/active');

/**
 * @return bool
 */
function apfeCheckRequirements(): bool
{
    $minPhpVersion = '7.4';
    $currentPhpVersion = phpversion();
    $phpRequirement = version_compare($currentPhpVersion, $minPhpVersion, '>=');
    $elementorRequirement =
        did_action('elementor/loaded') ||
        array_reduce(
            get_option( 'active_plugins'),
            function ($carry, $pluginPath) {
                return $carry ||
                    strpos($pluginPath, '/elementor.php') !== false ||
                    strpos($pluginPath, DIRECTORY_SEPARATOR . 'elementor.php') !== false;
            },
        );
    
    $apfeProActive =
        did_action('casandra-audio-player-for-elementor-pro/active') ||
        array_reduce(
            get_option( 'active_plugins'),
            function ($carry, $pluginPath) {
                return $carry ||
                    strpos($pluginPath, '/casandra-audio-player-for-elementor-pro.php') !== false ||
                    strpos($pluginPath, DIRECTORY_SEPARATOR . 'casandra-audio-player-for-elementor-pro.php') !== false;
            },
        );
    if (!$phpRequirement) {
        apfeAdminNotice(
            sprintf(
            /* translators: %1$s is the min PHP-version, %2$s the current PHP-version */
                __(
                    'Casandra Audio Player for Elementor requires PHP version %1$1s at least. Your server\'s PHP version is %2$2s.',
                    'casandra-audio-player-for-elementor'
                ),
                $minPhpVersion,
                $currentPhpVersion
            )
        );
    }
    if (!$elementorRequirement) {
        apfeAdminNotice(
            __(
                'Casandra Audio Player for Elementor requires Elementor plugin to be activated.',
                'casandra-audio-player-for-elementor'
            ),
            'warning is-dismissible'
        );
    }
    if ($apfeProActive) {
        apfeAdminNotice(
            __(
                'Casandra Audio Player for Elementor - Pro is activated. Disable the free version and enjoy the pro plugin.',
                'casandra-audio-player-for-elementor'
            ),
            'warning is-dismissible'
        );
    }
    if (!$phpRequirement || $apfeProActive) {
        return false;
    }
    $autoloader = __DIR__ . '/vendor/autoload.php';
    if (!file_exists($autoloader)) {
        apfeAdminNotice(
            __(
                'Casandra Audio Player for Elementor requires PHP Composer installed packages located in vendor folder',
                'casandra-audio-player-for-elementor'
            ),
        );
        return false;
    }
    return true;
}

/**
 * @param string $message
 * @param string $type
 */
function apfeAdminNotice(string $message, string $type = 'error'): void
{
    $printNotice = static function () use ($message, $type): void {
        printf(
            '<div class="notice notice-%1$s"><p>%2$s</p></div>',
            esc_html($type),
            esc_html($message)
        );
    };
    array_map(
        "add_action",
        ['admin_notices', 'network_admin_notices'],
        [$printNotice, $printNotice]
    );
}

if (apfeCheckRequirements()) {
    require __DIR__ . '/vendor/autoload.php';
    $plugin = Casandra\AudioPlayerForElementor\Plugin::getPlugin();
    do_action('casandra-audio-player-for-elementor/init');
    $plugin->init();
}
