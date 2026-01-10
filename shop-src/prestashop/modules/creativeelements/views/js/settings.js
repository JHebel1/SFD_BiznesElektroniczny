/*!
 * Creative Elements - Elementor based PageBuilder [in-stock]
 * Copyright 2019-2021 WebshopWorks.com
 */

$(function() {
	var $regenerate = $('#page-header-desc-configuration-regenerate-css');

	$regenerate
		.attr({
			title: '<p style="width: 190px;">' + $regenerate.attr('onclick').substr(2) + '</p>',
		})
		.tooltip({
			html: true,
			placement: 'bottom',
		})
		.on('click.ce', function onClickRegenerateCss() {
			if ($regenerate.find('.process-icon-loading').length) {
				return;
			}
			$regenerate.find('i').attr('class', 'process-icon-loading');

			$.post(
				location.href,
				{
					ajax: true,
					action: 'regenerate_css',
				},
				function onSuccessRegenerateCss(resp) {
					$regenerate.find('i').attr('class', 'process-icon-ok');
				},
				'json'
			);
		})
		.removeAttr('onclick')
	;

	var $replace = $(document.replace_url).on('submit', function onSubmitReplaceUrl(event) {
		event.preventDefault();

		if ($replace.find('.icon-spin').length) {
			return;
		}
		$replace.find('i').attr('class', 'icon-spin icon-circle-o-notch');

		$.post(
			location.href,
			$(this).serialize(),
			function onSuccessReplaceUrl(resp) {
				$replace.find('i').attr('class', 'icon-refresh');

				$replace.find('.alert').attr({
					'class': 'alert alert-' + (resp.success ? 'success' : 'danger')
				}).html(resp.data);
			},
			'json'
		);
	});
});
