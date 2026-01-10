{**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @Copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 *}

<!doctype html>
<html lang="{$iso_code|escape:'html':'UTF-8'}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		{if isset($ce_content)}
			<title>{$ce_content->title|escape:'html':'UTF-8'}</title>
			<meta name="description" content="{$ce_content->content|strip_tags|trim|escape:'html':'UTF-8'}">
		{/if}
		<meta name="viewport" content="width=device-width, initial-scale=1">
		{if !empty($favicon)}
			<link rel="icon" type="image/vnd.microsoft.icon" href="{$smarty.const._PS_IMG_|escape:'html':'UTF-8'}{$favicon|escape:'html':'UTF-8'}?{$favicon_update_time|intval}">
			<link rel="shortcut icon" type="image/x-icon" href="{$smarty.const._PS_IMG_|escape:'html':'UTF-8'}{$favicon|escape:'html':'UTF-8'}?{$favicon_update_time|intval}">
		{/if}
		<style>
		html, body { margin: 0; padding: 0; }
		</style>
		{include file="_partials/stylesheets.tpl" stylesheets=$stylesheets}
		{include file="_partials/javascript.tpl" javascript=$javascript.head vars=$js_custom_vars}
		<script>
		var baseDir = {$smarty.const.__PS_BASE_URI__|json_encode};
		</script>
	</head>
	<body id="maintenance" class="lang-{$iso_code|escape:'html':'UTF-8'} page-maintenance">
		<main>
			{$HOOK_MAINTENANCE|cleanHtml}
		</main>
		{include file="_partials/javascript.tpl" javascript=$javascript.bottom}
	</body>
</html>
