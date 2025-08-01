jQuery(document).ready(function($) {
    // Initialize color pickers
    $('.wcd-color-picker').wpColorPicker();

    // Handle media uploads for icons
    $('.wcd-upload-button').click(function(e) {
        e.preventDefault();
        
        var button = $(this);
        var container = button.closest('.wcd-icon-upload');
        var input = container.find('.wcd-icon-input');
        var preview = container.find('.wcd-icon-preview');

        var frame = wp.media({
            title: 'Select or Upload Icon',
            button: {
                text: 'Use this icon'
            },
            multiple: false,
            library: {
                type: [ 'image' ]
            }
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            input.val(attachment.url);
            preview.html('<img src="' + attachment.url + '" alt="">');
        });

        frame.open();
    });

    // Handle icon type selection
    $('.wcd-icon-type-radio').change(function() {
        var container = $(this).closest('.wcd-icon-setting');
        var type = $(this).val();
        
        container.find('.wcd-icon-upload, .wcd-dashicons-list, .wcd-elementor-icons-list').hide();
        
        if (type === 'custom') {
            container.find('.wcd-icon-upload').show();
        } else if (type === 'dashicons') {
            container.find('.wcd-dashicons-list').show();
        } else if (type === 'elementor') {
            container.find('.wcd-elementor-icons-list').show();
        }
    });

    // Handle dashicon selection
    $('.wcd-dashicon-select').change(function() {
        var icon = $(this).val();
        var container = $(this).closest('.wcd-icon-setting');
        var input = container.find('.wcd-icon-input');
        input.val(icon);
    });

    // Handle Elementor icon selection
    $('.wcd-elementor-icon-select').change(function() {
        var icon = $(this).val();
        var container = $(this).closest('.wcd-icon-setting');
        var input = container.find('.wcd-icon-input');
        input.val(icon);
    });
});
