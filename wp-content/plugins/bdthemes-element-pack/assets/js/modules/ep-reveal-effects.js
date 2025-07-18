(function ($, elementor) {

    'use strict';
    $(window).on('elementor/frontend/init', function () {
        var ModuleHandler = elementorModules.frontend.handlers.Base,
            RevealFX;

        RevealFX = ModuleHandler.extend({
            bindEvents: function () {
                this.run();
            },
            settings: function (key) {
                return this.getElementSettings('element_pack_reveal_effects_' + key);
            },
            run: function () {

                if ('yes' !== this.settings('enable')) {
                    return;
                }

                var options = this.getDefaultSettings(),
                    widgetID = this.$element.data('id'),
                    widgetContainer = $('.elementor-element-' + widgetID);

                $(widgetContainer).attr('data-ep-reveal', 'ep-reveal-' + widgetID + '');

                const revealID = '*[data-ep-reveal="ep-reveal-' + widgetID + '"]';
                const revealWrapper = document.querySelector(revealID);
                const revealFX = new RevealFx(revealWrapper, {
                    revealSettings: {
                        bgColors: this.settings('color') ? [this.settings('color')] : ['#333'],
                        direction: this.settings('direction') ? String(this.settings('direction')) : String('c'),
                        duration: this.settings('speed') ? Number(this.settings('speed.size') * 100) : Number(500),
                        easing: this.settings('easing') ? String(this.settings('easing')) : String('easeOutQuint'),
                        onHalfway: function (contentEl, ngsrevealerEl) {
                            contentEl.style.opacity = 1;
                        }
                    }
                });
                
                var runReveal = function () {
                    revealFX.reveal();
                    this.destroy();
                };

                epObserveTarget(revealWrapper, function () {
                    revealFX.reveal();
                }, {
                    root: null, // Use the viewport as the root
                    rootMargin: '0px', // No margin around the root
                    threshold: 0.8 // 80% visibility (1 - 0.8)
                });
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(RevealFX, {
                $element: $scope
            });
        });
    });

}(jQuery, window.elementorFrontend));