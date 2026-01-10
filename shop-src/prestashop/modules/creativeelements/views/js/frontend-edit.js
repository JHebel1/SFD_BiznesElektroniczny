/*!
 * Creative Elements - Elementor based PageBuilder [in-stock]
 * Copyright 2019-2021 WebshopWorks.com
 */

document.addEventListener('DOMContentLoaded', function() {
	var $script = $('script[data-ce-editor]'),
		editor = $script.data('ceEditor'),
		title = $script.data('ceTitle');
	$script.removeAttr('data-ce-editor');

	editor && $('.elementor').each(function() {
		var uid = (this.className.match(/elementor-(\d+)/) || {})[1];
		if (uid) {
			$(this).addClass('ce-edit-wrapper');
			$('<a class="ce-edit-btn"><i class="ce-icon">').attr({
				href: editor + '&uid=' + uid,
				title: title
			}).appendTo(this);
			$('<div class="ce-edit-outline">').appendTo(this);
		}
	});
});
