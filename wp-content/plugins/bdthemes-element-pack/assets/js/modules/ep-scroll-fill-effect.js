(function ($, elementor) {

    'use strict';

    $(window).on('elementor/frontend/init', function ($) {
        var ModuleHandler = elementorModules.frontend.handlers.Base,
            ScrollFillEffect;

        ScrollFillEffect = ModuleHandler.extend({

            bindEvents: function () {
                this.run();
            },
            onElementChange: debounce(function (prop) {
                if (prop.indexOf('ep_widget_sf_fx_') !== -1) {
                    this.run();
                }
            }, 400),
            settings: function (key) {
                return this.getElementSettings('ep_widget_sf_fx_' + key);
            },
            run: function () {
                var $element = this.$element;

                if (this.settings('enable') !== 'yes') {
                    return;
                }

                epObserveTarget($element[0], function () {
                    var $selector = jQuery($element).find('.elementor-heading-title, .bdt-heading-tag span, .bdt-ep-advanced-heading-main-title-inner');
                    gsap.to($selector, {
                        scrollTrigger: {
                            trigger: $selector,
                            start: "bottom center+=50%",
                            end: "bottom center",
                            scrub: true,
                        },
                        backgroundSize: '100% 200%',
                    });
                }, {
                    root: null, // Use the viewport as the root
                    rootMargin: '0px', // No margin around the root
                    threshold: 0.8 // 80% visibility (1 - 0.8)
                });
               
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/heading.default', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(ScrollFillEffect, {
                $element: $scope
            });
        });
        elementorFrontend.hooks.addAction('frontend/element_ready/bdt-animated-heading.default', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(ScrollFillEffect, {
                $element: $scope
            });
        });
        elementorFrontend.hooks.addAction('frontend/element_ready/bdt-advanced-heading.default', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(ScrollFillEffect, {
                $element: $scope
            });
        });
    });

}(jQuery, window.elementorFrontend));