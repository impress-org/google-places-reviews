/**
 *  JavaScripts for the admin side of the widget.
 */

var gpr_ajax_object;

(function ($) {
    'use strict';

    $(document).ready(function () {
        // loadAPI( init )
        init();
    });

    /**
     * Initialize
     *
     * Initialize the API Request Method widget radio input toggles
     */
    function init() {
        gpr_widget_toggles();
        gpr_initialize_autocomplete();
        gpr_tipsy();
    }

    /**
     * Detect Google Maps API Authentication Error
     *
     * Google Authentication Callback in case there was an error
     *
     * @see: https://developers.google.com/maps/documentation/javascript/events#auth-errors
     * @see: https://developers.google.com/maps/documentation/javascript/events#auth-errors
     */

    window.gm_authFailure = function () {
        $('p.gpr-autocomplete').after(
            '<div class="notice gpr-notice-error error"><p>' + gpr_ajax_object.i18n.google_auth_error + '</p></div>'
        );
    };

    /**
     * Clear Cache Button
     *
     * When clicked it clears the transients for this specific business
     */
    $(document).on('click', '.gpr-clear-cache', function (e) {
        e.preventDefault();
        var $this = $(this);

        $this.next('.cache-clearing-loading').fadeIn('fast');
        var data = {
            action: 'gpr_free_clear_widget_cache',
            transient_id_1: $(this).data('transient-id-1'),
            transient_id_2: $(this).data('transient-id-2'),
        };

        $.post(ajaxurl, data, function (response) {
            console.log(response);
            $('.cache-clearing-loading').hide();
            $this.prev('.cache-message').text(response).fadeIn('fast').delay(2000).fadeOut();
        });
    });

    /**
     * AJAX Success
     *
     * Function to Refresh jQuery toggles upon saving specific widget
     */
    // $( document ).ajaxSuccess(
    // 	function( e, xhr, settings ) {
    // 		gpr_widget_toggles();
    // 		gpr_initialize_autocomplete();
    // 		gpr_tipsy();
    // 	}
    // );

    /**
     * Widget Toggles
     */
    function gpr_widget_toggles() {
        // Advanced Options Toggle (Bottom-gray panels)
        $(document).on('click', '.gpr-widget-toggler', function () {
            $(this).toggleClass('toggled');
            $(this).next().slideToggle();
        });

        // Review character limit toggle
        $('.limit-reviews-option').each(function () {
            var review_char_option = $(this).find('input');
            var review_char_option_value = review_char_option.prop('checked');
            var review_char_set_wrap = $(this).next('.review-character-limit');

            // if clicked now
            review_char_option.on('click', function () {
                review_char_set_wrap.slideToggle();
            });
        });
    }

    /**
     * Initialize Google Places Autocomplete Field
     */
    function gpr_initialize_autocomplete() {
        var input = $('.gpr-autocomplete'),
            types = $('.gpr-types');

        input.each(function (index, value) {
            var type = 'all';

            // Handle type select field
            $(types[index]).on('change', function () {
                // Set type
                type = $(this).val();
                $(input).val('');
            });

            var autocomplete = new google.maps.places.Autocomplete(input[index], [type]);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();

                if (!place.place_id) {
                    alert('No place reference found for this location.');
                    return false;
                }

                // set location and Place ID hidden input value
                $(input).parents().find('.location').val(place.name);
                $(input).parents().find('.place_id').val(place.place_id);
                $(input).parents().find('.set-business').slideDown();
            });

            // Tame the enter key to not save the widget while using the autocomplete input
            $(input).keydown(function (e) {
                if (e.which === 13) {
                    return false;
                }
            });
        });
    }

    /**
     * Tipsy
     */
    function gpr_tipsy() {
        // Tooltips for admins
        $('.tooltip-info').tipsy({
            fade: false,
            html: true,
            gravity: 's',
            delayOut: 0,
            delayIn: 0,
        });
    }
})(jQuery);
