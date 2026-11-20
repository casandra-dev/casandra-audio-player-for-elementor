<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor\MetaBox;

class ApfeTrackMetaBox implements MetaBox
{
    public array $fields;

    public function __construct()
    {
        $this->fields = [
            'artist' => esc_html__('Artist', 'casandra-audio-player-for-elementor'),
            'audio_file' => esc_html__('Audio File', 'casandra-audio-player-for-elementor'),
            'duration_ms' => esc_html__('Duration (ms)', 'casandra-audio-player-for-elementor'),
            'priority_score' => esc_html__('Priority Score', 'casandra-audio-player-for-elementor'),
            'status' => esc_html__('Status', 'casandra-audio-player-for-elementor'),
        ];
    }
    public function registerMetaBox($postTypeSlug): void
    {
        add_meta_box(
            'apfe_track_meta',
            __('Track Details', 'casandra-audio-player-for-elementor'),
            [$this, 'renderMetaBox'],
            $postTypeSlug,
            'normal',
            'high'
        );
    }
    public function renderMetaBox($post): void
    {
        wp_nonce_field('apfe_track_meta_nonce', 'apfe_track_meta_nonce_field');
        $values = [];
        foreach ($this->fields as $key => $label) {
            $values[$key] = get_post_meta($post->ID, $key, true);
        }
        ?>
        <p>
            <label><?php esc_html_e('Audio File:', 'casandra-audio-player-for-elementor'); ?></label>
            <br>
            <input name="audio_file" required id="apfe_upload_audio_file" type="text" size="" value="<?php echo esc_url($values['audio_file']); ?>" class="widefat" />
            <input id="apfe_upload_file_btn" type="button" value="<?php esc_html_e('Upload', 'casandra-audio-player-for-elementor'); ?>" class="widefat" />
        </p>
        <p>
            <label><?php esc_html_e('Artist:', 'casandra-audio-player-for-elementor'); ?></label>
            <br>
            <input id="apfe_artist" required type="text" name="artist" value="<?php echo esc_attr($values['artist']); ?>" class="widefat">
        </p>
        <p>
            <label><?php esc_html_e('Duration (ms):', 'casandra-audio-player-for-elementor'); ?></label>
            <br>
            <input id="apfe_duration_ms"  required type="number" name="duration_ms" value="<?php echo esc_attr($values['duration_ms']); ?>" class="widefat">
        </p>

        <p>
            <label><?php esc_html_e('Priority Score:', 'casandra-audio-player-for-elementor'); ?></label>
            <br>
            <input type="number" name="priority_score" value="<?php echo esc_attr($values['priority_score']); ?>" class="widefat">
        </p>

        <p>
            <label><?php esc_html_e('Status:', 'casandra-audio-player-for-elementor'); ?></label>
            <br>
            <select required name="status" class="widefat">
                <?php
                $statuses = [
                        'waiting' => esc_html__('Waiting', 'casandra-audio-player-for-elementor'),
                        'playlist' => esc_html__('Playlist', 'casandra-audio-player-for-elementor')
                    ];
                foreach ($statuses as $status => $statusText) {
                    echo '<option value="' . esc_attr($status) . '" ' . selected($values['status'], $status, false) . '>' .
                        esc_html($statusText) .
                        '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }

    public function saveMeta(int $postId): void
    {
        if (
            !isset($_POST['apfe_track_meta_nonce_field']) ||
            !wp_verify_nonce(
                sanitize_text_field(wp_unslash($_POST['apfe_track_meta_nonce_field'])),
                'apfe_track_meta_nonce'
            )
        ) {
            return;
        }
        foreach ($this->fields as $key => $label) {
            if (isset($_POST[$key])) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                if ($key === 'audio_file') {
                    update_post_meta($postId, $key, sanitize_url(wp_unslash($_POST[$key])));
                } elseif (in_array($key, ['duration_ms', 'priority_score'])) {
                    update_post_meta($postId, $key, (int) wp_unslash($_POST[$key])); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                } else {
                    update_post_meta($postId, $key, sanitize_text_field(wp_unslash($_POST[$key])));
                }
            }
        }
    }

    public function addColumns($columns)
    {
        $columns['artist'] = esc_html__('Artist', 'casandra-audio-player-for-elementor');
        $columns['title'] = esc_html__('Title', 'casandra-audio-player-for-elementor');
        $columns['priority_score'] = esc_html__('Priority', 'casandra-audio-player-for-elementor');
        $columns['status'] = esc_html__('Status', 'casandra-audio-player-for-elementor');
        return $columns;
    }

    public function renderColumns($column, $postId)
    {
        $value = get_post_meta($postId, $column, true);
        echo esc_html($value);
    }

    public function sortableColumns($columns)
    {
        $columns['priority_score'] = 'priority_score';
        $columns['queued_at'] = 'queued_at';
        return $columns;
    }

    public function columnOrderBy($query)
    {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }
        $orderBy = $query->get('orderby');
        if (in_array($orderBy, ['priority_score', 'queued_at'])) {
            $query->set('meta_key', $orderBy);
            $query->set('orderby', 'meta_value');
        }
    }
}
