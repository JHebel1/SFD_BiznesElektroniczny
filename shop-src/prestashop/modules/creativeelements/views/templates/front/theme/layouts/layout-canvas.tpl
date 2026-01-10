{**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @Copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 *}

<!doctype html>
<html lang="{$language.iso_code|escape:'html':'UTF-8'}">
	<head>
		{block name='head'}
			{include file='_partials/head.tpl'}
		{/block}
	</head>
	<body id="{$page.page_name|escape:'html':'UTF-8'}" class="{$page.body_classes|classnames}">
		<main>
			{block name='notifications'}
				{include file='_partials/notifications.tpl'}
			{/block}
			{$ce_desc['description']|cleanHtml}
		</main>
		{block name='javascript_bottom'}
			{include file="_partials/javascript.tpl" javascript=$javascript.bottom}
		{/block}
	</body>
</html>
