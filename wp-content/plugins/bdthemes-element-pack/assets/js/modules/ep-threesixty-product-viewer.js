/**
 * Start threesixty product viewer widget script
 */

(function ($, elementor) {

    'use strict';

    var widgetTSProductViewer = function ($scope, $) {

        var $TSPV = $scope.find('.bdt-threesixty-product-viewer'),
            $settings = $TSPV.data('settings'),
            $container = $TSPV.find('> .bdt-tspv-container'),
            $fullScreenBtn = $TSPV.find('> .bdt-tspv-fb');

        if (!$TSPV.length) {
            return;
        }

        if ($settings.source_type === 'remote') {
            $settings.source = SpriteSpin.sourceArray($settings.source, { frame: $settings.frame_limit, digits: $settings.image_digits });
        }

        epObserveTarget($scope[0], function () {
            var $this = $($container);
            $this.spritespin($settings);
        });

        $($fullScreenBtn).on('click', function (e) {
            e.preventDefault();
            $($container).spritespin('api').requestFullscreen();
        });

    };


    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/bdt-threesixty-product-viewer.default', widgetTSProductViewer);
    });

}(jQuery, window.elementorFrontend));

/**
 * End threesixty product viewer widget script
 */

