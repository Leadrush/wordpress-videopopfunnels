(function ( $ ) {
	"use strict";

	$(function () {
		
		var page_to_input = {
			
			radio_on: 'on_pages',
			radio_off: 'off_pages'
		},
		overlay_to_page = {
			overlay_on_pages: 'radio_on',
			overlay_off_pages: 'radio_off'
		},
		page_change = {
			radio_on: 'radio_off',
			radio_off: 'radio_on'
		},
		last = {
			on_pages: '',
			off_pages: ''
		},
		create_input_overlay = function() {
			$('#on_pages,#off_pages').each(function(i,el) {
				var $this = $(this),
					$overlay = $('<div />'),
					zi = $this.is(':disabled')?10000:-1;
					
				$overlay.attr('id', 'overlay_'+$this.attr('id')).addClass('r-overlay')
				.css({
					position: 'absolute',
					top: $this.position().top,
					left: $this.position().left,
					width: $this.outerWidth() + 40,
					height: $this.outerHeight() + 40,
					zIndex: zi,
					backgroundColor: '#fff',
					opacity: 0,
					cursor: 'pointer'
				})
				$this.parent().append($overlay);
			});
		};
		
		create_input_overlay();

		$('[name=pages_radio]').change(function() {
			var id = $('[name=pages_radio]:checked').attr('id'),
				new_input = $('#'+page_to_input[id]),
				old_input = $('#'+page_to_input[page_change[id]]);
			
			last[page_to_input[page_change[id]]] = old_input.val();
			
			old_input.val('').prop('disabled',true);
			new_input.val(last[page_to_input[id]]).prop('disabled',false).focus();
			
			// toggle invisible overlay
			$('#overlay_'+page_to_input[id]).css('z-index',-1);
			$('#overlay_'+page_to_input[page_change[id]]).css('z-index',100000);
			
		});
		$('.r-overlay').click(function() {
			$('#'+overlay_to_page[$(this).attr('id')]).prop('checked',true).change();
		});
		
	});

}(jQuery));
