import "../scss/admin.scss";

jQuery(document).ready(function ($) {
    let fileFrame;
    let timeToMilliseconds = function (timeString) {
        if (!timeString) {
            return 0;
        }
        const parts = timeString.split(':').map(Number);
        let minutes = 0;
        let seconds = 0;
        if (parts.length === 2) {
            [minutes, seconds] = parts;
        } else if (parts.length === 1) {
            seconds = parts[0];
        }
        const totalMs = (minutes * 60 + seconds) * 1000;
        return totalMs;
    }
    $('#apfe_upload_file_btn').on('click', function (e) {
        e.preventDefault();
        if (fileFrame) {
            fileFrame.open();
            return;
        }
        fileFrame = wp.media({
            title: 'Select Audio File',
            button: {text: 'Use this audio'},
            library: {type: 'audio'},
            multiple: false
        });
        fileFrame.on('select', function () {
            const attachment = fileFrame.state().get('selection').first().toJSON();
            $('#apfe_upload_audio_file').val(attachment.url);
            if ($('input[name="post_title"]').val() === '' && Object.hasOwn(attachment, 'title') && attachment.title) {
                $('input[name="post_title"]').val(attachment.title);
                $('#title-prompt-text').addClass('screen-reader-text');
            }
            if ($('input[name="priority_score"]').val() === '') {
                $('input[name="priority_score"]').val('1');
            }
            if (Object.hasOwn(attachment, 'fileLength') && attachment.fileLength) {
                $('#apfe_duration_ms').val(timeToMilliseconds(attachment.fileLength));
            }
            if (Object.hasOwn(attachment, 'meta') && Object.hasOwn(attachment.meta, 'artist') && attachment.meta.artist) {
                $('#apfe_artist').val(attachment.meta.artist);
            }
        });
        fileFrame.open();
    });
    $('.wp-has-submenu a[href*="casandra.dev"]').each(function (){
        $(this).attr('target', '_blank');
    });
});
