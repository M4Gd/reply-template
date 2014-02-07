(function ( $ ) {
	"use strict";

	$(function () {

		// get reply key list
		var $key_list	= $('#axi-replay-keys');
		// get reply form field
		var $editor		= $('#bbp_reply_content');
		if ( ! $key_list.length || ! $editor.length ) return;

		// get templates content
		var $value_list = $('#axi-replay-templates').children('li');

		// decorate drop down if selecize is available
		if( typeof Selectize == 'function' ) {
			$key_list.selectize();
		}
		
		// add template to editor id dropdown item selected
		$key_list.change(function(){
			if(this.value !== '') {
				var selected_reply_template = $value_list.filter('[data-reply-id="'+this.value+'"]').html();
				// append selected text to editor
				$editor.val(function(i, text) {
					return text + selected_reply_template;
				});
			}
		});

	});

}(jQuery));