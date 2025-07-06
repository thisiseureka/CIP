(function ($) {
	'use strict';

	jQuery('.uacf7-custom-column-insert').on('click', function () {
		var column = '[uacf7-row]';
		jQuery('.column-width').each(function () {
			var width = jQuery(this).val();
			column += '[uacf7-col ' + width + '] --your code-- [/uacf7-col]';
		});
		column += '[/uacf7-row]';
		
		var columnLength = jQuery('.column-width').length;
		
		if( columnLength != 0 ) {
			jQuery('.uacf7-column-tag-insert').val(column);
			jQuery('.insert-tag.uacf7-column-insert-tag').trigger('click');
		}
		
	});

})(jQuery);
