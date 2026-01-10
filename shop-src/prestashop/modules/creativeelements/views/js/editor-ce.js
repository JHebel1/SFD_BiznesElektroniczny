/*!
 * Creative Elements - Elementor based PageBuilder [in-stock]
 * Copyright 2019-2021 WebshopWorks.com
 */

function Class(c) {
	return (c.constructor.prototype = c).constructor;
}

// fix for multiple select val(): when no options are selected, return [] instead of null
$.fn.val = (function(parent) {
	return function val(value) {
		return void 0 === value && this[0] && this[0].multiple && parent.call(this) === null ? [] : parent.apply(this, arguments);
	}
})($.fn.val);

// for advanced content templates
CeView = Class({
	constructor: function CeView() {
		this.attr = {};
	},

	addRenderAttribute: function(elem, key, value) {
		elem in this.attr || (this.attr[elem] = {});
		key in this.attr[elem] || (this.attr[elem][key] = []);
		$.isArray(value) || (value = [value]);

		this.attr[elem][key] = this.attr[elem][key].concat(value);
	},

	getRenderAttributeString: function( elem ) {
		if (!this.attr[elem]) return '';
		var key, attr = [];

		for (key in this.attr[elem]) {
			attr.push(key + '="' + this.attr[elem][key].join(' ') + '"');
		}
		return attr.join(' ');
	}
});

$(function onReady() {
	// init File Manager
	elementor.fileManager = elementor.dialogsManager.createWidget('lightbox', {
		id: 'ce-file-manager-modal',
		closeButton: true,
		headerMessage: tinyMCE.i18n.translate('File manager'),

		onReady: function() {
			var $message = this.getElements('message').html(
				'<iframe id="ce-file-manager" width="858" height="568"></iframe>'
			);
			this.iframe = $message.children().get(0);
			this.url = baseAdminDir + 'filemanager/dialog.php?type=1';

			this.open = function(fieldId) {
				this.fieldId = fieldId;

				if (this.iframe.contentWindow) {
					this.initFrame();
					this.getElements('widget').appendTo = $.noop;
				} else {
					$message.prepend(
						$('#tmpl-elementor-template-library-loading').html()
					);
					this.iframe.src = this.url + '&fldr=' + (localStorage.ceImgFldr || '');
				}
				this.show();
			};
			this.initFrame = function() {
				var $doc = $(this.iframe).contents();

				localStorage.ceImgFldr = $doc.find('#fldr_value').val();

				$doc.find('a.link').attr('data-field_id', this.fieldId);

				this.iframe.contentWindow.close_window = this.hide.bind(this);
			};
			this.iframe.onload = this.initFrame.bind(this);
		},

		onHide: function() {
			var $input = $('#' + this.fieldId);

			$input.val(
				$input.val().replace(location.origin, '')
			).trigger('input');
		},
	});

	// init widgets
	ceFrontend.hooks.addAction('frontend/element_ready/widget', function($widget, $) {
		// remote render fix
		if ($widget.find('.ce-remote-render').length) {
			elementor.helpers.getModelById($widget.data('id')).renderRemoteServer();
		}
	});

	// helper for get model by id
	elementor.helpers.getModelById = function(id, models) {
		models = models || elementor.elements.models;

		for (var i = models.length; i--;) {
			if (models[i].id === id) {
				return models[i];
			}
			if (models[i].attributes.elements.models.length) {
				var model = elementor.helpers.getModelById(id, models[i].attributes.elements.models);

				if (model) {
					return model;
				}
			}
		}
	};

	// fix: add home_url to relative image path
	elementor.imagesManager.getImageUrl = function(image) {
		var url = image.url;

		if (!/^(https?:)?\/\//i.test(url)) {
			url = elementor.config.home_url + url;
		}
		return url;
	};

	elementor.on('preview:loaded', function onPreviewLoaded() {
		// fix for View Page in force edit mode
		var href = elementor.$preview[0].contentWindow.location.href;

		if (~href.indexOf('&force=1&')) {
			elementor.config.post_permalink = href.replace(/&force=1&.*/, '');
		}

		// Add multistore warning
		elementor.$previewContents.find('#ce-warning-multistore').html(elementor.config.i18n.multistore);

		// scroll to content area
		var contentTop = elementor.$previewContents.find('#elementor .elementor-section-wrap').offset().top;
		if (contentTop > $(window).height() * 0.66) {
			elementor.$previewContents.find('html, body').animate({
				scrollTop: contentTop - 30
			}, 400);
		}

		// fix for multiple Global colors / fonts
		elementor.$previewContents.find('#elementor-global-css, link[href*="css/ce/global-"]').remove();

		// init Edit with CE buttons
		elementor.$previewContents.find('.ce-edit-btn').on('click.ce', function() {
			location.href = this.href;
		});

		// init Read More link
		elementor.$previewContents.find('.ce-read-more').on('click.ce', function() {
			window.open(this.href);
		});

		// fix for redirecting preview
		elementor.$previewContents.find('a[href]').on('click.ce', function(e) {
			e.preventDefault();
		});
	});
});

$(window).on('load.ce', function onLoadWindow() {
	// init Keyboard Shortcuts
	elementor.hotkeysDialog = elementor.dialogsManager.createWidget('alert', {
		id: 'elementor-hotkeys-dialog',
		headerMessage: elementor.translate('keyboard_shortcuts'),
	});
	elementor.hotkeyTemplate = function(name, key, shift) {
		return '<div class="elementor-template-library-template-local">\
			<div class="elementor-template-library-template-name">' + elementor.translate(name) + '</div>\
			<div class="elementor-template-library-template-controls">\
				<a class="elementor-button elementor-button-default">' + elementor.hotkeyTemplate.metaKey + '</a> + ' +
				(shift ? '<a class="elementor-button elementor-button-default">Shift</a> + ' : '') + '\
				<a class="elementor-button elementor-button-default">' + key + '</a>\
			</div>\
		</div>';
	};
	elementor.hotkeyTemplate.metaKey = ~navigator.platform.indexOf('Mac') ? 'Cmd' : 'Ctrl';
	elementor.hotkeysDialog.setMessage(
		elementor.hotkeyTemplate('save', 'S') +
		elementor.hotkeyTemplate('show_hide_panel', 'P') +
		elementor.hotkeyTemplate('templates_library', 'L', true) +
		elementor.hotkeyTemplate('revision_history', 'H', true) +
		elementor.hotkeyTemplate('responsive_mode', 'M', true)
	);

	// init language switcher
	var $context = $('#ce-context'),
		$langs = $('#ce-langs'),
		$languages = $langs.children().remove(),
		built = $langs.data('built'),
		lang = $langs.data('lang');

	elementor.shopContext = $context.length
		? $context.val()
		: 's-' + parseInt(elementor.config.post_id.slice(-2))
	;
	elementor.helpers.filterLangs = function() {
		var ctx = $context.length ? $context.val() : elementor.shopContext,
			id_group = 'g' === ctx[0] ? parseInt(ctx.substr(2)) : 0,
			id_shop = 's' === ctx[0] ? parseInt(ctx.substr(2)) : 0,
			dirty = elementor.shopContext != ctx;

		$langs.html('');

		var id_shops = id_group ? $context.find(':selected').nextUntil('[value^=g]').map(function() {
			return parseInt(this.value.substr(2));
		}).get() : [id_shop];

		$languages.each(function() {
			if (!ctx || $(this).data('shops').filter(function(id) { return ~id_shops.indexOf(id) }).length) {
				var $lang = $(this).clone().appendTo($langs),
					id_lang = $lang.data('lang'),
					active = !dirty && lang == id_lang;

				var uid = elementor.config.post_id.replace(/\d\d(\d\d)$/, function(m, shop) {
					return ('0' + id_lang).slice(-2) + ('0' + id_shop).slice(-2);
				});
				$lang.attr('data-uid', uid).data('uid', uid);

				active && $lang.addClass('active');

				if (active || !id_shop || !built[id_shop] || !built[id_shop][id_lang]) {
					$lang.find('.elementor-button').remove();
				}
			}
		});
	};
	elementor.helpers.filterLangs();
	$context.on('change.ce-ctx', elementor.helpers.filterLangs);

	$langs.on('click.ce-lang', '.ce-lang', function onChangeLanguage() {
		var uid = $(this).data('uid'),
			href = location.href.replace(/uid=\d+/, 'uid=' + uid);

		if ($context.length && $context.val() != elementor.shopContext) {
			document.context.action = href;
			document.context.submit();
		} else if (uid != elementor.config.post_id) {
			location = href;
		}
	}).on('click.ce-lang-get', '.elementor-button', function onGetLanguageContent(e) {
		e.stopImmediatePropagation();
		var $icon = $('i', this);

		if ($icon.hasClass('fa-spin')) {
			return;
		}
		$icon.attr('class', 'fa fa-spin fa-circle-o-notch');

		elementor.ajax.send('getLanguageContent', {
			data: {
				uid: $(this).closest('[data-uid]').data('uid')
			},
			success: function(data) {
				$icon.attr('class', 'eicon-file-download');

				elementor.getRegion('sections').currentView.addChildModel(data);
			},
			error: function(data) {
				elementor.templates.showErrorDialog(data);
			}
		});
	});

	// handle permission errors for AJAX requests
	$(document).ajaxSuccess(function onAjaxSuccess(e, xhr, conf, res) {
		if (false === res.success && res.data && res.data.permission) {
			NProgress.done();
			$('.elementor-button-state').removeClass('elementor-button-state');

			try {
				elementor.templates.showTemplates();
			} catch (ex) {}

			elementor.templates.getErrorDialog()
				.setMessage('<center>' + res.data.permission + '</center>')
				.show()
			;
		}
	});
});

// fix for full height section video background
$('#elementor-panel').on('change.ce', '.elementor-control-under-section select[data-setting=height]', function() {
	if ($(this).val() === 'full') {
		$(elementor.$preview[0].contentWindow).resize();
	}
});

// init layerslider widget
$('#elementor-panel').on('change.ls', '.ls-selector select', function onChangeSlider() {
	var $ = elementor.$previewContents[0].defaultView.jQuery;

	$('.elementor-element-' + elementor.panel.currentView.content.currentView.model.id)
		.addClass('elementor-widget-empty')
		.append('<i class="elementor-widget-empty-icon eicon-insert-image">')
		.find('.ls-container').layerSlider('destroy').remove()
	;
}).on('click.ls-new', '.elementor-control-ls-new button', function addSlider(e) {
	var title = prompt(ls.NameYourSlider);

	null === title || $.post(ls.url, {
		'ls-add-new-slider': 1,
		'title': title
	}, function onSuccessNewSlider(data) {
		var id = (data.match(/name="slider_id" value="(\d+)"/) || []).pop();
		if (id) {
			var option = '#' + id + ' - ' + title;
			elementor.config.widgets['ps-widget-LayerSlider'].controls.slider.options[id] = option;
			$('.ls-selector select')
				.append('<option value="' + id + '">' + option + '</option>')
				.val(id)
				.change()
			;
			$('.elementor-control-ls-edit button').trigger('click.ls-edit');
		}
	});
}).on('click.ls-edit', '.elementor-control-ls-edit button', function editSlider(e) {
	var lsUpdate,
		lsId = $('.ls-selector select').val();

	$.fancybox({
		width: '100%',
		height: '100%',
		padding: 0,
		href: ls.url + '&action=edit&id=' + lsId,
		type: 'iframe',
		afterLoad: function onAfterLoadSlider() {
			var win = $('.fancybox-iframe').contents()[0].defaultView;

			win.jQuery(win.document).ajaxSuccess(function(e, xhr, args, res) {
				if (args.data && args.data.indexOf('action=ls_save_slider') === 0 && '{"status":"ok"}' === res) {
					lsUpdate = true;
				}
			});
			$(win.document.head).append('<style>\
				#header, #nav-sidebar, .add-new-h2, .ls-save-shortcode { display: none; }\
				#main { padding-top: 0; }\
				#main #content { margin-left: 0; }\
			</style>');
		},
		beforeClose: function onBeforeCloseSlider() {
			var win = $('.fancybox-iframe').contents()[0].defaultView,
				close = win.LS_editorIsDirty ? confirm(ls.ChangesYouMadeMayNotBeSaved) : true;

			if (close && win.LS_editorIsDirty) {
				win.LS_editorIsDirty = false;
			}
			return close;
		},
		afterClose: function onAfterCloseSlider() {
			lsUpdate && $('.ls-selector select')
				.val(0).change()
				.val(lsId).change()
			;
		}
	});
});
