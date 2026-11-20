<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor\Widget\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Icons_Manager;

defined('ABSPATH') || exit;

class ApfeWidget extends Widget_Base
{
    public function get_name()
    {
        return 'apfe_widget';
    }

    public function get_title()
    {
        return __('Audio Player', 'casandra-audio-player-for-elementor');
    }

    public function get_icon()
    {
        return 'eicon-play apfe-elementor-icon';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function get_script_depends()
    {
        return ['apfe-script'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'apfe_content_section',
            [
                'label' => __('Content', 'casandra-audio-player-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        /* Select Track */
        $this->add_control(
            'apfe_select_tracks',
            [
                'label' => __('Tracks', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_apfe_tracks_for_control(),
                'multiple' => true,
                'label_block' => true,
                'description' => __(
                    'Leave empty to load automatically based on filters below.',
                    'casandra-audio-player-for-elementor'
                ),
            ]
        );
        $category_options = $this->apfe_get_tracks_categories();
        $this->add_control(
            'apfe_select_categories',
            [
                'label' => __('Filter by Categories', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $category_options,
                'default' => [],
                'label_block' => true,
            ]
        );
        $tag_options = $this->apfe_get_tracks_tags();
        $this->add_control(
            'apfe_select_tags',
            [
                'label' => __('Filter by Tags', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $tag_options,
                'default' => [],
                'label_block' => true,
            ]
        );
        $this->add_control(
            'apfe_select_filter_status',
            [
                'label'   => __('Filter by Status', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''          => __('All', 'casandra-audio-player-for-elementor'),
                    'waiting'   => __('Waiting', 'casandra-audio-player-for-elementor'),
                    'playlist'   => __('Playlist', 'casandra-audio-player-for-elementor'),
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_select_track_count',
            [
                'label' => __('Number of Tracks', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 100,
            ]
        );
        $this->add_control(
            'apfe_select_order_by',
            [
                'label' => __('Order by', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default (menu_order/ID)', 'casandra-audio-player-for-elementor'),
                    'priority_score' => __('Priority (meta)', 'casandra-audio-player-for-elementor'),
               ],
            ]
        );
        $this->add_control(
            'apfe_select_order',
            [
                'label' => __('Order', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => [
                    'ASC'  => __('Ascending', 'casandra-audio-player-for-elementor'),
                    'DESC' => __('Descending', 'casandra-audio-player-for-elementor'),
               ],
            ]
        );
        $this->end_controls_section();

        /* Layout */
        $this->start_controls_section(
            'apfe_layout',
            [
                'label' => __('Layout', 'casandra-audio-player-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control(
            'apfe_layout_flex_direction',
            [
                'label'   => __('Direction (with Sidebar, Topbar, etc.)', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row'            => __('Row', 'casandra-audio-player-for-elementor'),
                    'row-reverse'    => __('Row Reverse', 'casandra-audio-player-for-elementor'),
                    'column'         => __('Column', 'casandra-audio-player-for-elementor'),
                    'column-reverse' => __('Column Reverse', 'casandra-audio-player-for-elementor'),
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_layout_container_max_height',
            [
                'label'      => __('Container Max Height', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-player-container' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_layout_container_min_height',
            [
                'label'      => __('Container Min Height', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => 40,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-player-container' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_layout_sidebar_min_height',
            [
                'label'      => __('Sidebar/Topbar Min Height', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => 40,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-cover-card' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_layout_sidebar_max_height',
            [
                'label'      => __('Sidebar/Topbar Max Height', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-cover-card' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_layout_playlist_max_height',
            [
                'label'      => __('Playlist Max Height', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-playlist' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_layout_sidebar_flex',
            [
                'label'   => __('Sidebar/Topbar Space Share', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 10,
                'step'    => 1,
                'default' => 1,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_layout_main_flex',
            [
                'label'   => __('Main (Playlist + Player) Space Share', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 10,
                'step'    => 1,
                'default' => 2,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_layout_stick_bottom',
            [
                'label' => __('Stick to Bottom', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_layout_stick_bottom_width',
            [
                'label'      => __('Sticky Player Width', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%'],
                'range'      => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'apfe_layout_stick_bottom' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_section();

        /* Options */
        $this->start_controls_section(
            'apfe_options',
            [
                'label' => __('Options', 'casandra-audio-player-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        /* Player Options Player */
        $this->add_control(
            'apfe_options_player',
            [
                'label' => __('Player', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'apfe_options_player_disallow_control_playlist',
            [
                'label' => __('Disallow user playlist control', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'apfe_options_player_add_shuffle',
            [
                'label' => __('Add shuffle button', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'apfe_options_player_disallow_control_playlist!' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_player_add_loop',
            [
                'label' => __('Add loop button', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_player_disallow_control_playlist!' => 'yes'
                ],
            ]
        );
        $this->add_control(
            'apfe_options_player_start_from_first',
            [
                'label' => __('Always start with the first track (turn off for radio)', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'apfe_options_player_select_start',
            [
                'label' => __('Starting Point of Playback', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'condition' => [
                    'apfe_options_player_start_from_first!' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_options_player_timezone',
            [
                'label'   => __('Reference Time Zone', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'options' => array_combine(
                    timezone_identifiers_list(),
                    timezone_identifiers_list()
                ),
                'default' => 'UTC',
                'condition' => [
                    'apfe_options_player_start_from_first!' => 'yes',
               ],
            ]
        );
        $this->add_control(
            'apfe_options_player_start_date',
            [
                'label' => __('Date/Time at Playback Start', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DATE_TIME,
                'condition' => [
                    'apfe_options_player_start_from_first!' => 'yes'
                ],
                'default' => gmdate('Y-m-d h:m:s \U\T\C', time())
            ]
        );
        $this->add_control(
            'apfe_options_player_start_time_offset',
            [
                'label'       => __('Start Time Offset (seconds)', 'casandra-audio-player-for-elementor'),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 0,
                'max'         => 100000000,
                'step'        => 1,
                'default'     => 0,
                'condition' => [
                    'apfe_options_player_start_from_first!' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_options_player_show_duration',
            [
                'label' => __('Show duration time', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_player_show_playing_section',
            [
                'label' => __('Show playing section', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'apfe_options_player_show_playing_section__mobile',
            [
                'label' => __('Show playing section on mobile', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_player_show_playing_section_thumbnail',
            [
                'label' => __('Show playing section thumbnail', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'apfe_options_player_show_playing_section' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_player_playing_section_header',
            [
                'label'       => __('Playing Section Header Text', 'casandra-audio-player-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => __('Enter your text (or leave empty)', 'casandra-audio-player-for-elementor'),
                'label_block' => true,
                'condition' => [
                    'apfe_options_player_show_playing_section' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_options_player_show_time_slider',
            [
                'label' => __('Show time slider', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'apfe_options_player_time_slider_top',
            [
                'label' => __('Time Slider at the Top', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'apfe_options_player_show_time_slider' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_options_player_show_prev_next',
            [
                'label' => __('Show next and prev', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_player_disallow_control_playlist!' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_player_show_volume_control',
            [
                'label' => __('Show volume control', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'classes' => 'apfe-pro'
            ]
        );

        $this->add_control(
            'apfe_options_player_controllers_symmetric',
            [
                'label' => __('Symmetric Controllers', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name'     => 'apfe_options_player_show_time_slider',
                            'operator' => '!==',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'apfe_options_player_time_slider_top',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                    ]
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_player_controllers_centered',
            [
                'label' => __('Centered Controllers', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name'     => 'apfe_options_player_show_time_slider',
                            'operator' => '!==',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'apfe_options_player_time_slider_top',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                    ]
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_player_full_height',
            [
                'label' => __('Full Height', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'apfe_options_playlist_show!' => 'yes'
                ]
            ]
        );

        /* Sidebar Options */
        $this->add_control(
            'apfe_options_sidebar',
            [
                'label' => __('Sidebar (or Topbar)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'apfe_options_sidebar_show',
            [
                'label' => __('Show', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'apfe_options_sidebar_hide__mobile',
            [
                'label' => __('Hide on mobile', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_sidebar_show_cover',
            [
                'label' => __('Show track image in the sidebar/topbar', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'apfe_options_sidebar_show_description',
            [
                'label' => __('Show track post content in sidebar/topbar', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                    ]
            ]
        );
        $this->add_control(
            'apfe_options_sidebar_show_custom',
            [
                'label' => __('Show custom content in sidebar/topbar', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                    'apfe_options_sidebar_show_description!' => 'yes',
                    'apfe_options_sidebar_show_cover!' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_options_sidebar_custom_content',
            [
                'label'       => __('HTML Content', 'casandra-audio-player-for-elementor'),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => '',
                'dynamic'     => ['active' => true ],
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                    'apfe_options_sidebar_show_description!' => 'yes',
                    'apfe_options_sidebar_show_cover!' => 'yes',
                    'apfe_options_sidebar_show_custom' => 'yes'
                ]
            ]
        );

        /* playlist Options */
        $this->add_control(
            'apfe_options_playlist',
            [
                'label' => __('Playlist', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'apfe_options_playlist_show',
            [
                'label' => __('Show playlist', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'apfe_options_playlist_show_thumbnail',
            [
                'label' => __('Show track cover thumbnail', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_playlist_show_artist',
            [
                'label' => __('Show artist name', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_playlist_show_bg_cover',
            [
                'label' => __('Show track cover image under playlist', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_options_playlist_show_bg_fixed',
            [
                'label' => __('Show a custom image under playlist', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'apfe_options_playlist_show_bg_cover!' => 'yes',
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_options_playlist_image_bg_fixed',
            [
                'label'   => __('Image', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'apfe_options_playlist_show_bg_fixed' => 'yes',
                    'apfe_options_playlist_show' => 'yes',
                    'apfe_options_playlist_show_bg_cover!' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_section();
        /* End Content */

        /* Style Section tab */
        $this->start_controls_section(
            'apfe_style_playlist',
            [
                'label' => __('Playlist', 'casandra-audio-player-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'apfe_style_playlist_main_bg',
            [
                'label' => __('Main Background Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_playlist_main_bg_color',
                'label'     => __('Main Background Color', 'casandra-audio-player-for-elementor'),
                'types' => ['classic'],
                'selector' => '{{WRAPPER}} .apfe-player-container',
            ]
        );

        $this->add_control(
            'apfe_style_playlist_bg_image',
            [
                'label' => __('Playlist Background Image', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_style_playlist_bg_image_position',
            [
                'label' => __('Background Position', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SELECT,
                'default' => 'left top',
                'options' => [
                    'left top'      => __('Left Top', 'casandra-audio-player-for-elementor'),
                    'left center'   => __('Left Center', 'casandra-audio-player-for-elementor'),
                    'left bottom'   => __('Left Bottom', 'casandra-audio-player-for-elementor'),
                    'center top'    => __('Center Top', 'casandra-audio-player-for-elementor'),
                    'center center' => __('Center Center', 'casandra-audio-player-for-elementor'),
                    'center bottom' => __('Center Bottom', 'casandra-audio-player-for-elementor'),
                    'right top'     => __('Right Top', 'casandra-audio-player-for-elementor'),
                    'right center'  => __('Right Center', 'casandra-audio-player-for-elementor'),
                    'right bottom'  => __('Right Bottom', 'casandra-audio-player-for-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-play-card-bg' => 'background-position: left top;',
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_playlist_bg_image_size',
            [
                'label' => __('Background Size', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'auto'    => __('Auto', 'casandra-audio-player-for-elementor'),
                    'cover'   => __('Cover', 'casandra-audio-player-for-elementor'),
                    'contain' => __('Contain', 'casandra-audio-player-for-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-play-card-bg' => 'background-size: cover;',
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_playlist_bg_image_opacity',
            [
                'label' => __('Opacity', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_padding',
            [
                'label'      => __('Playlist Padding', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .apfe-playlist' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ]
            ]
        );

        /* Style Playlist Item */
        $this->add_control(
            'apfe_style_playlist_item',
            [
                'label' => __('Playlist Item', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_min_height',
            [
                'label' => __('Min Height', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->start_controls_tabs('apfe_style_playlist_item_tabs');

        /* Style Playlist Item Normal */
        $this->start_controls_tab(
            'apfe_style_playlist_item_normal',
            ['label' => __('Normal', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'apfe_style_playlist_item_color',
            [
                'label'     => __('Text Color', 'casandra-audio-player-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} li' => 'color: {{VALUE}};',
               ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_playlist_item_bg_color',
                'label'     => __('Background Color', 'casandra-audio-player-for-elementor'),
                'types' => ['classic'],
                'selector' => '{{WRAPPER}} li',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_typography__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );

        $this->add_responsive_control(
            'apfe_style_playlist_item_margin',
            [
                'label'      => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_padding',
            [
                'label'      => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        /* Style Playlist Item Thumbnail */
        $this->add_control(
            'apfe_style_playlist_item_thumbnail',
            [
                'label' => __('Thumbnail', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_width',
            [
                'label' => __('Width', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_height',
            [
                'label' => __('Height', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_padding',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_margin',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_playlist_item_thumbnail_opacity',
            [
                'label' => __('Opacity', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_thumbnail_border__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_thumbnail_box_shadow__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_thumbnail_filter__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Playlist Item:hover */
        $this->start_controls_tab(
            'apfe_style_playlist_item__hover',
            ['label' => __('Hover', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'apfe_style_playlist_item_color__hover',
            [
                'label'     => __('Text Color', 'casandra-audio-player-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_playlist_item_bg_color__hover__apfe_pro',
                'label'     => __('Background Color', 'casandra-audio-player-for-elementor'),
                'types' => ['classic'],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_typography__hover__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_margin__hover',
            [
                'label'      => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_padding__hover',
            [
                'label'      => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        /* Style Playlist Item Thumbnail:hover */
        $this->add_control(
            'apfe_style_playlist_item_thumbnail__hover',
            [
                'label' => __('Thumbnail', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_width__hover',
            [
                'label' => __('Width', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_height__hover',
            [
                'label' => __('Height', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_padding__hover',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_margin__hover',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_playlist_item_thumbnail_opacity__hover',
            [
                'label' => __('Opacity', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_thumbnail_border__hover__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_border_radius__hover',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_thumbnail_box_shadow__hover__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_thumbnail_filter__hover__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Playlist Item:active */
        $this->start_controls_tab(
            'apfe_style_playlist_item__active',
            ['label' => __('Active', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_style_playlist_item_color__active',
            [
                'label'     => __('Text Color', 'casandra-audio-player-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_playlist_item_bg_color__active',
                'label'     => __('Background Color', 'casandra-audio-player-for-elementor'),
                'types' => ['classic'],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_typography__active__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_margin__active',
            [
                'label'      => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_padding__active',
            [
                'label'      => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        /* Style Playlist Item Thumbnail:hover */
        $this->add_control(
            'apfe_style_playlist_item_thumbnail__active',
            [
                'label' => __('Thumbnail', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_width__active',
            [
                'label' => __('Width', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_height__active',
            [
                'label' => __('Height', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_padding__active',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_margin__active',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_playlist_item_thumbnail_opacity__active',
            [
                'label' => __('Opacity', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_thumbnail_border__active__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_playlist_item_thumbnail_border_radius__active',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_thumbnail_box_shadow__active__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_playlist_item_thumbnail_filter__active__apfe_pro',
                'condition' => [
                    'apfe_options_playlist_show' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /* Style Sidebar */
        $this->start_controls_section(
            'apfe_style_sidebar',
            [
                'label' => __('Sidebar (or Topbar)', 'casandra-audio-player-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label'     => __('Background Color', 'casandra-audio-player-for-elementor'),
                'name' => 'apfe_sidebar_bg_color',
                'types' => ['classic'],
                'selector' => '{{WRAPPER}} .apfe-cover-card',
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'apfe_style_sidebar_image',
            [
                'label' => __('Cover Image', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ]
            ]
        );
        $this->add_responsive_control(
            'apfe_style_sidebar_image_width',
            [
                'label' => __('Width', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1000],
                    '%'  => ['min' => 0, 'max' => 100],
                    'vw'  => ['min' => 0, 'max' => 100],
                ],
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_sidebar_image_height',
            [
                'label' => __('Height', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1000],
                    '%'  => ['min' => 0, 'max' => 100],
                    'vh'  => ['min' => 0, 'max' => 100],
                ],
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_sidebar_image_object_fit',
            [
                'label' => __('Object Fit', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SELECT,
                'default' => 'fill',
                'options' => [
                    'fill'    => __('Fill', 'casandra-audio-player-for-elementor'),
                    'cover'   => __('Cover', 'casandra-audio-player-for-elementor'),
                    'contain' => __('Contain', 'casandra-audio-player-for-elementor'),
                    'scale-down' => __('Scale Down', 'casandra-audio-player-for-elementor'),
                    'none' => __('None', 'casandra-audio-player-for-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-track-cover img' => 'object-fit: fill;',
                ],
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_sidebar_image_border__apfe_pro',
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_sidebar_image_radius',
            [
                'label'      => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'apfe_style_sidebar_image_box_shadow__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_sidebar_image_opacity',
            [
                'label' => __('Opacity', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.1,
                   ],
               ],
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_sidebar_image_filters__apfe_pro',
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_sidebar_description',
            [
                'label' => __('Description', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'apfe_style_sidebar_description_color',
            [
                'label'     => __('Text Color', 'casandra-audio-player-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .apfe-track-description, {{WRAPPER}} .apfe-custom-content' => 'color: {{VALUE}};',
               ],
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'apfe_style_sidebar_description_typography__apfe_pro',
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_sidebar_description_margin',
            [
                'label'      => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_sidebar_description_padding',
            [
                'label'      => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'apfe_options_sidebar_show' => 'yes',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_section();

        /* Style Player */
        $this->start_controls_section(
            'apfe_style_player',
            [
                'label' => __('Player Section', 'casandra-audio-player-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_player_background',
                'types' => ['classic'],
                'selector' => '{{WRAPPER}} .mejs-container',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_border__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .mejs-container' =>
                        'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_padding',
            [
                'label'      => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_color',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'apfe_style_player_typography__apfe_pro',
                'selector' => '{{WRAPPER}} .mejs-mediaelement, .mejs-container, {{WRAPPER}} .mejs-currenttime, {{WRAPPER}} .mejs-duration, {{WRAPPER}} .apfe-out-of',
                'fields_options' => [
                    'font_family' => [
                        'default' => '',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => 13,
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '400',
                    ],
                    'line_height' => [
                        'default' => [
                            'size' => '13',
                            'unit' => 'px',
                        ],
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'size' => 0,
                            'unit' => 'px',
                        ],
                    ],
                    'text_transform' => [
                        'default' => '',
                    ],
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_time_slider',
            [
                'label' => __('Player Time Slider', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_player_time_slider_background__apfe_pro',
                'types' => ['classic'],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_time_slider_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_time_slider__loaded',
            [
                'label' => __('Player Time Slider (loaded)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_player_time_slider_background__loaded__apfe_pro',
                'types' => ['classic'],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_time_slider__current',
            [
                'label' => __('Player Time Slider (current)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_player_time_slider_background__current__apfe_pro',
                'types' => ['classic'],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_time_slider__hover',
            [
                'label' => __('Player Time Slider (hover)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_player_time_slider_background__hover__apfe_pro',
                'types' => ['classic'],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_volume',
            [
                'label' => __('Player Volume Bar', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_player_volume_background__apfe_pro',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .mejs-horizontal-volume-total',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_volume_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_volume__current',
            [
                'label' => __('Player Volume (current)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'apfe_style_player_volume_background__current__apfe_pro',
                'types' => ['classic'],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_playing_section_image',
            [
                'label' => __('Playing Image', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_playing_section_image_width',
            [
                'label' => __('Width', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1000],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_playing_section_image_height',
            [
                'label' => __('Height', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1000],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_playing_section_image_object_fit',
            [
                'label' => __('Object Fit', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SELECT,
                'default' => 'fill',
                'options' => [
                    'fill'    => __('Fill', 'casandra-audio-player-for-elementor'),
                    'cover'   => __('Cover', 'casandra-audio-player-for-elementor'),
                    'contain' => __('Contain', 'casandra-audio-player-for-elementor'),
                    'scale-down' => __('Scale Down', 'casandra-audio-player-for-elementor'),
                    'none' => __('None', 'casandra-audio-player-for-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-playing-section .apfe-playing-img img' => 'object-fit: fill;',
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_playing_section_image_border__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_playing_section_image_radius',
            [
                'label'      => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_playing_section_image_box_shadow__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_playing_section_image_opacity',
            [
                'label' => __('Opacity', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.1,
                    ],
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_playing_section_image_filters__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_playing_section_header_color',
            [
                'label'     => __('Playing Header', 'casandra-audio-player-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .apfe-playing-header' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'apfe_style_player_playing_section_header_typography__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_playing_section_title_color',
            [
                'label'     => __('Playing Title', 'casandra-audio-player-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .apfe-playing-track' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'apfe_style_player_playing_section_title_typography__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_section();

        /* Style Buttons */
        $this->start_controls_section(
            'apfe_style_player_buttons',
            [
                'label' => __('Player Buttons', 'casandra-audio-player-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        /* Style Buttons Play */
        $this->add_control(
            'apfe_style_player_buttons_play',
            [
                'label' => __('Play button', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_play_use_default',
            [
                'label' => __('Use default icon', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_play_icon',
            [
                'label'   => __('Play Icon', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa fa-play',
                    'library' => 'fa',
                ],
                'condition' => [
                    'apfe_style_player_buttons_play_use_default!' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_play_icon_width',
            [
                'label' => __('Width (SVG)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-play svg' => 'width: 14px;',
                ],
                'condition' => [
                    'apfe_style_player_buttons_play_use_default!' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->start_controls_tabs( 'apfe_style_player_buttons_play_tabs' );
        $this->start_controls_tab(
            'apfe_style_player_buttons_play__normal',
            ['label' => __('Normal', 'casandra-audio-player-for-elementor') ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_play_bg',
                'selector' => '{{WRAPPER}} .apfe-play',
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_play_color',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .apfe-play, {{WRAPPER}} .apfe-play svg' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_play_font_size',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-play' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_play_border',
                'selector' => '{{WRAPPER}} .apfe-play',
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_play_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .apfe-play' =>
                        'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_play_padding',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .apfe-play' =>
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_play_margin',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .apfe-play' =>
                        'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_play_text_shadow__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_play_filters__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Buttons Play:hover */
        $this->start_controls_tab(
            'apfe_style_player_buttons_play__hover',
            ['label' => __('Hover', 'casandra-audio-player-for-elementor') ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_play_bg__hover__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_play_color__hover',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_play_font_size__hover',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_play_border__hover__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_play_border_radius__hover',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_play_padding__hover',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_play_margin__hover',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_play_text_shadow__hover__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_play_filters__hover__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'apfe_style_player_buttons_play_transition',
            [
                'label' => __('Transition (ms)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::NUMBER,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .apfe-play, {{WRAPPER}} .apfe-play i, {{WRAPPER}} .apfe-play svg' =>
                        'transition: all 300ms ease;',
                ],
                'classes' => 'apfe-pro'
            ]
        );

        /* Style Buttons Pause */
        $this->add_control(
            'apfe_style_player_buttons_pause',
            [
                'label' => __('Pause button', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_pause_use_default',
            [
                'label' => __('Use default icon', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_pause_icon',
            [
                'label'   => __('Pause Icon', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa fa-pause',
                    'library' => 'fa',
                ],
                'condition' => [
                    'apfe_style_player_buttons_pause_use_default!' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_pause_icon_width',
            [
                'label' => __('Width (SVG)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-pause svg' => 'width: 14px;',
                ],
                'condition' => [
                    'apfe_style_player_buttons_pause_use_default!' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->start_controls_tabs( 'apfe_style_player_buttons_pause_tabs' );
        $this->start_controls_tab(
            'apfe_style_player_buttons_pause__normal',
            ['label' => __('Normal', 'casandra-audio-player-for-elementor') ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_pause_bg',
                'selector' => '{{WRAPPER}} .apfe-pause',
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_pause_color',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .apfe-pause, {{WRAPPER}} .apfe-pause svg' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_pause_font_size',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-pause' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_pause_border',
                'selector' => '{{WRAPPER}} .apfe-pause',
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_pause_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .apfe-pause' =>
                        'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_pause_padding',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .apfe-pause' =>
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_pause_margin',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .apfe-pause' =>
                        'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_pause_text_shadow__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_pause_filters__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Buttons Play:hover */
        $this->start_controls_tab(
            'apfe_style_player_buttons_pause__hover',
            ['label' => __('Hover', 'casandra-audio-player-for-elementor') ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_pause_bg__hover__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_pause_color__hover',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_pause_font_size__hover',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_pause_border__hover__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_pause_border_radius__hover',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_pause_padding__hover',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_pause_margin__hover',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_pause_text_shadow__hover__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_pause_filters__hover__apfe_pro',
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'apfe_style_player_buttons_pause_transition',
            [
                'label' => __('Transition (ms)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::NUMBER,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .apfe-pause svg, {{WRAPPER}} .apfe-pause i, {{WRAPPER}} .apfe-pause' =>
                        'transition: all 300ms ease;',
                ],
                'classes' => 'apfe-pro'
            ]
        );

        /* Style Buttons Prev */
        $this->add_control(
            'apfe_style_player_buttons_prev',
            [
                'label' => __('Prev button', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_prev_use_default',
            [
                'label' => __('Use default icon', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_prev_icon',
            [
                'label'   => __('Prev Icon', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa fa-step-backward',
                    'library' => 'fa',
                ],
                'condition' => [
                    'apfe_style_player_buttons_prev_use_default!' => 'yes',
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_prev_icon_width',
            [
                'label' => __('Width (SVG)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-prev svg' => 'width: 14px;',
                ],
                'condition' => [
                    'apfe_style_player_buttons_prev_use_default!' => 'yes',
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->start_controls_tabs( 'apfe_style_player_buttons_prev_tabs' );
        $this->start_controls_tab(
            'apfe_style_player_buttons_prev__normal',
            ['label' => __('Normal', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_prev_bg__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_prev_color',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_prev_font_size',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-prev' => 'font-size: 14px;',
                ],
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_prev_border__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_prev_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_prev_padding',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_prev_margin',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_prev_text_shadow__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_prev_filters__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Buttons Prev:hover */
        $this->start_controls_tab(
            'apfe_style_player_buttons_prev__hover',
            ['label' => __('Hover', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_prev_bg__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_prev_color__hover',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_prev_font_size__hover',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_prev_border__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_prev_border_radius__hover',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_prev_padding__hover',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_prev_margin__hover',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_prev_text_shadow__hover__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_prev_filters__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'apfe_style_player_buttons_prev_transition',
            [
                'label' => __('Transition (ms)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::NUMBER,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .apfe-prev' =>
                        'transition: all 300ms ease;',
                ],
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );

        /* Style Buttons Next */
        $this->add_control(
            'apfe_style_player_buttons_next',
            [
                'label' => __('Next Button', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_next_use_default',
            [
                'label' => __('Use default icon', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_next_icon',
            [
                'label'   => __('Next Icon', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa fa-step-forward',
                    'library' => 'fa',
                ],
                'condition' => [
                    'apfe_style_player_buttons_next_use_default!' => 'yes',
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_next_icon_width',
            [
                'label' => __('Width (SVG)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-next svg' => 'width: 14px;',
                ],
                'condition' => [
                    'apfe_style_player_buttons_next_use_default!' => 'yes',
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->start_controls_tabs( 'apfe_style_player_buttons_next_tabs' );
        $this->start_controls_tab(
            'apfe_style_player_buttons_next__normal',
            ['label' => __('Normal', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_next_bg__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_next_color',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_next_font_size',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-next' => 'font-size: 14px;',
                ],
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_next_border__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_next_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_next_padding',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_next_margin',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_next_text_shadow__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_next_filters__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Buttons Next:hover */
        $this->start_controls_tab(
            'apfe_style_player_buttons_next__hover',
            ['label' => __('Hover', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_next_bg__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_next_color__hover',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_next_font_size__hover',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_next_border__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_next_border_radius__hover',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_next_padding__hover',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_next_margin__hover',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_next_text_shadow__hover__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_next_filters__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'apfe_style_player_buttons_next_transition',
            [
                'label' => __('Transition (ms)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::NUMBER,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .apfe-next' =>
                        'transition: all 300ms ease;',
                ],
                'condition' => [
                    'apfe_options_player_show_prev_next' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );

        /* Style Buttons Volume */
        $this->add_control(
            'apfe_style_player_buttons_volume',
            [
                'label' => __('Volume button', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_volume_use_default',
            [
                'label' => __('Use default icon', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_volume_icon_volume',
            [
                'label'   => __('Volume Icon', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa fa-volume-up',
                    'library' => 'fa',
                ],
                'condition' => [
                    'apfe_style_player_buttons_volume_use_default!' => 'yes',
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_volume_icon_mute',
            [
                'label'   => __('Mute Icon', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa fa-volume-mute',
                    'library' => 'fa',
                ],
                'condition' => [
                    'apfe_style_player_buttons_volume_use_default!' => 'yes',
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_volume_icon_width',
            [
                'label' => __('Width (SVG)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-volume svg' => 'width: 14px;',
                ],
                'condition' => [
                    'apfe_style_player_buttons_volume_use_default!' => 'yes',
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'

            ]
        );
        $this->start_controls_tabs( 'apfe_style_player_buttons_volume_tabs' );
        $this->start_controls_tab(
            'apfe_style_player_buttons_volume__normal',
            ['label' => __('Normal', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_volume_bg__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_volume_color',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_volume_font_size',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-volume' => 'font-size: 14px;',
                ],
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_volume_border__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_volume_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_volume_padding',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_volume_margin',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_volume_text_shadow__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_volume_filters__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Buttons Volume:hover */
        $this->start_controls_tab(
            'apfe_style_player_buttons_volume__hover',
            ['label' => __('Hover', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_volume_bg__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_volume_color__hover',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_volume_font_size__hover',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_volume_border__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_volume_border_radius__hover',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_volume_padding__hover',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_volume_margin__hover',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_volume_text_shadow__hover__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_volume_filters__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'apfe_style_player_buttons_volume_transition',
            [
                'label' => __('Transition (ms)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::NUMBER,
                'default' => 300,
                'condition' => [
                    'apfe_options_player_show_volume_control' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );

        /* Style Buttons Shuffle */
        $this->add_control(
            'apfe_style_player_buttons_shuffle',
            [
                'label' => __('Shuffle button', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_shuffle_use_default',
            [
                'label' => __('Use default icon', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_shuffle_icon',
            [
                'label'   => __('Shuffle Icon', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa fa-random',
                    'library' => 'fa',
                ],
                'condition' => [
                    'apfe_style_player_buttons_shuffle_use_default!' => 'yes',
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_icon_width',
            [
                'label' => __('Width (SVG)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-shuffle svg' => 'width: 14px;',
                ],
                'condition' => [
                    'apfe_style_player_buttons_shuffle_use_default!' => 'yes',
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->start_controls_tabs( 'apfe_style_player_buttons_shuffle_tabs' );
        $this->start_controls_tab(
            'apfe_style_player_buttons_shuffle__normal',
            ['label' => __('Normal', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_bg__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_shuffle_color',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_shuffle_font_size',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_border__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_padding',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_margin',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_text_shadow__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_filters__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Buttons Shuffle:hover */
        $this->start_controls_tab(
            'apfe_style_player_buttons_shuffle__hover',
            ['label' => __('Hover', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_bg__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_shuffle_color__hover',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_shuffle_font_size',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_shuffle_icon_border_hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_border_radius__hover',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_padding__hover',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_margin__hover',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_text_shadow__hover__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_filters__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Buttons Shuffle:active */
        $this->start_controls_tab(
            'apfe_shuffle_icon_hover',
            ['label' => __('Active', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_bg__active__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_shuffle_color__active',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_shuffle_font_size__active',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_border__active__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_border_radius__active',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_padding__active',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_shuffle_margin__active',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_text_shadow__active__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_shuffle_filters__active__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'apfe_style_player_buttons_shuffle_transition',
            [
                'label' => __('Transition (ms)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::NUMBER,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .apfe-shuffle' =>
                        'transition: all 300ms ease;',
                ],
                'condition' => [
                    'apfe_options_player_add_shuffle' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );

        /* Style Buttons Loop */
        $this->add_control(
            'apfe_style_player_buttons_loop',
            [
                'label' => __('Loop Button', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_loop_use_default',
            [
                'label' => __('Use default icon', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'casandra-audio-player-for-elementor'),
                'label_off' => __('No', 'casandra-audio-player-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_loop_icon',
            [
                'label'   => __('Loop Icon', 'casandra-audio-player-for-elementor'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa fa-repeat',
                    'library' => 'fa',
                ],
                'condition' => [
                    'apfe_style_player_buttons_loop_use_default!' => 'yes',
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_icon_width',
            [
                'label' => __('Width (SVG)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-loop svg' => 'width: 14px;',
                ],
                'condition' => [
                    'apfe_style_player_buttons_loop_use_default!' => 'yes',
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->start_controls_tabs( 'apfe_style_player_buttons_loop_tabs' );
        $this->start_controls_tab(
            'apfe_style_player_buttons_loop__normal',
            ['label' => __('Normal', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_bg__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_loop_color',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_loop_font_size',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .apfe-loop' => 'font-size: 14px;',
                ],
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_border__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_border_radius',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_padding',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_margin',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_text_shadow__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_filters__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Buttons Loop:hover */
        $this->start_controls_tab(
            'apfe_style_player_buttons_loop__hover',
            ['label' => __('Hover', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_bg__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_loop_color__hover',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_loop_font_size__hover',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_border__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_border_radius__hover',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_padding__hover',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_margin__hover',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_text_shadow__hover__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_filters__hover__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();

        /* Style Buttons Loop:active */
        $this->start_controls_tab(
            'apfe_style_player_buttons_loop__active',
            ['label' => __('Active', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_bg__active__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_loop_color__active',
            [
                'label' => __('Color', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_control(
            'apfe_style_player_buttons_loop_font_size__active',
            [
                'label' => __('Font Size', 'casandra-audio-player-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_border__active__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_border_radius__active',
            [
                'label' => __('Border Radius', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_padding__active',
            [
                'label' => __('Padding', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_responsive_control(
            'apfe_style_player_buttons_loop_margin__active',
            [
                'label' => __('Margin', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_text_shadow__active__apfe_pro',
                'label'     => __('Shadow', 'casandra-audio-player-for-elementor'),
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'apfe_style_player_buttons_loop_filters__active__apfe_pro',
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'apfe_style_player_buttons_loop_transition',
            [
                'label' => __('Transition (ms)', 'casandra-audio-player-for-elementor'),
                'type'  => Controls_Manager::NUMBER,
                'default' => 300,
                'condition' => [
                    'apfe_options_player_add_loop' => 'yes'
                ],
                'classes' => 'apfe-pro'
            ]
        );
        
        $this->end_controls_section();
    }

    private function apfe_get_tracks_categories(): array {
        $apfe_track_ids = array_keys($this->get_apfe_tracks_for_control(-1, ['ids']));
        $category_options = [];
        if ($apfe_track_ids) {
            $categories = wp_get_object_terms(
                $apfe_track_ids,
                'apfe_track_cat',
                ['hide_empty' => true]
            );
            foreach ($categories as $cat) {
                $category_options[$cat->term_id] = $cat->name;
            }
        }
        return $category_options;
    }

    private function apfe_get_tracks_tags(): array {
        $apfe_track_ids = array_keys($this->get_apfe_tracks_for_control(-1, ['ids']));
        $tag_options = [];
        if ($apfe_track_ids) {
            $tags = wp_get_object_terms(
                $apfe_track_ids,
                'apfe_track_tag',
                ['hide_empty' => true]
            );
            foreach ($tags as $tag) {
                $tag_options[$tag->term_id] = $tag->name;
            }
        }
        return $tag_options;
    }

    private function get_apfe_tracks_for_control(int $posts_per_page = 100, array $fields = [], string $orderby = 'title'): array {
        $options = [];
        $posts = get_posts([
            'post_type' => 'apfe_track',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'orderby' => $orderby,
            'order' => 'ASC',
            'fields' => $fields
        ]);
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        do_action('casandra-audio-player-for-elementor/widget/render', $settings);
        $change_track = $settings['apfe_options_player_start_from_first'] !== 'yes';
        $allow_ontrol = $settings['apfe_options_player_disallow_control_playlist'] !== 'yes';
        $hide_playlist = $settings['apfe_options_playlist_show'] !== 'yes';
        $add_loop = $allow_ontrol && $settings['apfe_options_player_add_loop'] === 'yes';
        if ($change_track) {
            $timezone = $settings['apfe_options_player_timezone'];
            $startTime = $settings['apfe_options_player_start_time_offset'];
            $realDateTime = new \DateTime($settings['apfe_options_player_start_date'], new \DateTimeZone($timezone));
            $realPoint = $realDateTime->getTimestamp() + (int) $startTime;
        } else {
            $realPoint = 0;
        }
        $instance_id = $this->get_id();
        $show_playlist = $settings['apfe_options_playlist_show'] === 'yes';
        $show_sidebar = $settings['apfe_options_sidebar_show'] === 'yes';
        $add_cover_image = $show_sidebar && $settings['apfe_options_sidebar_show_cover'] === 'yes';
        $show_description = $show_sidebar && $settings['apfe_options_sidebar_show_description'] === 'yes';
        $show_playing_section = $settings['apfe_options_player_show_playing_section'] === 'yes';
        $playing_header = $settings['apfe_options_player_playing_section_header'];
        $player_full_height = !$show_playlist && $settings['apfe_options_player_full_height'] === 'yes';
        $player_slider_top = $settings['apfe_options_player_time_slider_top'] === 'yes';
        $show_time_slider = $allow_ontrol && $settings['apfe_options_player_show_time_slider'] === 'yes';
        $show_background = $show_playlist && $settings['apfe_options_playlist_show_bg_cover'] === 'yes';
        $use_custom_next_icon = $allow_ontrol && $settings['apfe_style_player_buttons_next_use_default'] !== 'yes';
        $use_custom_volume_icon = $settings['apfe_style_player_buttons_volume_use_default'] !== 'yes';
        $use_custom_mute_icon =  ($settings['apfe_style_player_buttons_mute_use_default'] ?? 'yes') === 'yes';
        $use_custom_shuffle_icon =  false;
        $use_custom_loop_icon = $add_loop && $settings['apfe_style_player_buttons_loop_use_default'] !== 'yes';
        $show_custom_sidebar =$show_sidebar &&  $settings['apfe_options_sidebar_show_custom'] === 'yes';
        $custom_sidebar_content = $settings['apfe_options_sidebar_custom_content'];
        $track_ids = $settings['apfe_select_tracks'];
        $track_count = intval($settings['apfe_select_track_count']);
        $order_by = $settings['apfe_select_order_by'];
        $order = $settings['apfe_select_order'];
        if (!empty($settings['apfe_select_categories'])) {
            $tax_query[] = [
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => $settings['apfe_select_categories'],
            ];
        }
        $tax_query = [];
        if (!empty($settings['apfe_select_tags'])) {
            $tax_query[] = [
                'taxonomy' => 'post_tag',
                'field' => 'term_id',
                'terms' => $settings['apfe_select_tags'],
            ];
        }
        $args = [
            'post_type'      => 'apfe_track',
            'posts_per_page' => $track_count,
            'post_status'    => 'publish',
            'orderby'        => 'ID',
            'order'          => $order,
        ];
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }
        if (!empty($track_ids)) {
            $args['post__in'] = $track_ids;
            $args['orderby']  = 'post__in';
        } else {
            if ($order_by === 'priority_score') {
                $args['meta_key'] = 'priority_score';
                $args['orderby'] = 'meta_value_num';
            }
        }

        $query = new \WP_Query($args);
        if ($query->have_posts()) {
            $user_id = get_current_user_id();
            $track_index = (int) 0;
            $play_from = (int) 10;
            if (!$track_index) {
                $track_index = 0;
            }
            if (!$play_from) {
                $play_from = 0;
            }
            do_action('casandra-audio-player-for-elementor/widget/render/print');
            echo '<div 
                class="apfe-player-container"
                data-user-id="' . (int) $user_id . '" 
                data-widget-id="' . esc_attr((string) $instance_id) . '"
                data-track-index="' . (int) $track_index . '"
                data-change-track="' . (int) $change_track . '"
                data-start-point="' . (int) $realPoint . '"
                data-allow-control="' . (int) $allow_ontrol . '"
                data-add-shuffle="0"
                data-add-loop="' . (int) $add_loop . '"
                data-hide-playlist="' . (int) $hide_playlist . '"
                data-show-duration="1"
                data-layout="row" 
                data-show-sidebar="' . (int) $show_sidebar . '"
                data-hide-sidebar-mobile="1"
                data-playing-section="' . (int) $show_playing_section . '"
                data-hide-playing-section-mobile="0"
                data-playing-section-thumb="0"
                data-playing-header="' . esc_attr((string) $playing_header) . '"
                data-symmetric="0"
                data-centered="0"
                data-slider-top="' . (int) $player_slider_top . '"
                data-show-slider="' . (int) $show_time_slider . '"
                data-show-volume="1"
                data-show-next-prev="1"
                data-play-full-height="' . (int) $player_full_height . '"
                data-stick-bottom="0"
                >';
            if ($add_cover_image || $show_description) {
                $classes = $add_cover_image ? 'apfe-with-image' : '';
                $classes .= $show_description ? ' apfe-with-desc' : '';
                echo '<div class="apfe-cover-card ' . esc_attr($classes) . '"></div>';
            } elseif ($show_custom_sidebar) {
                echo '<div class="apfe-cover-card"><div class="apfe-custom-content">' . wp_kses_post(do_shortcode(($custom_sidebar_content))) . '</div></div>';
            }
            echo '<div class="apfe-play-card">';
            if ($show_background) {
                echo '<div class="apfe-play-card-bg"></div>';
            }
            echo '<ul class="apfe-playlist apfe-without-thumb ' . esc_attr(($show_playlist ? '' : 'apfe-without-playlist ')). '">';

            while ($query->have_posts()) {
                $query->the_post();
                $title = get_the_title();
                $id = get_the_ID();
                $content = get_the_content();
                $image = get_the_post_thumbnail(null, 'post-thumbnail', ['width' => '', 'height' => '']);
                $url = get_post_meta(get_the_ID(), 'audio_file', true);
                $artist = get_post_meta(get_the_ID(), 'artist', true);
                $duration = get_post_meta(get_the_ID(), 'duration_ms', true);
                $priority = get_post_meta(get_the_ID(), 'priority_score', true);
                $status_val = get_post_meta(get_the_ID(), 'status', true);

                if ($url) {
                    echo '<li 
                        data-id="' .esc_attr($id) . '" 
                        data-src="' . esc_url($url) . '"
                        data-title="' . esc_html($title) . '"
                        data-artist="' . esc_attr($artist) . '" 
                        data-duration="' . esc_attr($duration) . '" 
                        data-priority="' . esc_attr($priority) . '" 
                        data-status="' . esc_attr($status_val) . '">
                        <div class="apfe-track-head">';

                        echo '<div class="apfe-track-title">
                            <span class="apfe-track">' . esc_html($title) . '</span>';
                        echo '</div>';

                    
                    echo   '</div>';
                    if ($add_cover_image || $show_description) {
                        echo '<div class="apfe-track-meta">';
                        if ($add_cover_image) {
                            echo '<div class="apfe-track-cover">';
                            echo esc_html($image);
                            echo '</div>';
                        }
                        if ($show_description && $content !='') {
                            echo '<p class="apfe-track-description">';
                            echo wp_kses_post($content);
                            echo '</p>';
                        }
                        echo '</div>';
                    }
                    echo '</li>';
                }
            }
            echo '</ul>';
            echo '<div class="apfe-icons" style="display: none;">';
            echo '<div class="apfe-icons-play"><div class="apfe-play">';

            echo '<i class="fa fa-play" aria-hidden="true"></i>';

            echo '</div></div>';
            echo '<div class="apfe-icons-pause"><div class="apfe-pause">';

            echo '<i class="fa fa-pause" aria-hidden="true"></i>';

            echo '</div></div>';
            echo '<div class="apfe-icons-prev">';

            echo '<i class="fa fa-step-backward" aria-hidden="true"></i>';

            echo '</div>';
            echo '<div class="apfe-icons-next">';

            echo '<i class="fa fa-step-forward" aria-hidden="true"></i>';

            echo '</div>';
            echo '<div class="apfe-icons-volume">';

            echo '<i class="fa fa-volume-up" aria-hidden="true"></i>';

            echo '</div>';
            echo '<div class="apfe-icons-mute">';

            echo '<i class="fa fa-volume-mute" aria-hidden="true"></i>';

            echo '</div>';
            echo '<div class="apfe-icons-shuffle">';

            echo '<i class="fa fa-random" aria-hidden="true"></i>';

            echo '</div>';
            echo '<div class="apfe-icons-repeat">';

            echo '<i class="fa fa-repeat" aria-hidden="true"></i>';

            echo '</div>';
            echo '</div>';
            echo '<audio class="apfe-player" controls preload="auto"></audio></div>';
        } else {

            echo sprintf(
                    '<p>' . esc_html__('No tracks found. Please add audio tracks', 'casandra-audio-player-for-elementor') .
                            ' <a href="%s">' . esc_html__('here', 'casandra-audio-player-for-elementor') . '.</a></p>',
                    esc_url(admin_url('edit.php?post_type=apfe_track'))
                );
        }
        echo '</div>';
        wp_reset_postdata();
    }
}
