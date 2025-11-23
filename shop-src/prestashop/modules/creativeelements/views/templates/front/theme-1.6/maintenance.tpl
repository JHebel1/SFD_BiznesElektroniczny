{**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @Copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 *}

<!doctype html>
<html lang="{$language_code|escape:'html':'UTF-8'}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		{if isset($ce_content)}
			<title>{$ce_content->title|escape:'html':'UTF-8'}</title>
			<meta name="description" content="{$ce_content->content|strip_tags|trim|escape:'html':'UTF-8'}">
		{/if}
		<meta name="viewport" content="width=device-width, initial-scale=1">
		{if !empty($favicon)}
			<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url|escape:'html':'UTF-8'}">
			<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url|escape:'html':'UTF-8'}">
		{/if}
		<style>
		html, body { margin: 0; padding: 0; }
		</style>
		{foreach from=$css_files key=css_uri item=media}
			<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}">
		{/foreach}
		{include file=$smarty.const._PS_ALL_THEMES_DIR_|cat:'javascript.tpl'}
		<script>
		var baseDir = {$smarty.const.__PS_BASE_URI__|json_encode};
		</script>
	</head>
	<body id="maintenance" class="lang-{$language_code|escape:'html':'UTF-8'} page-maintenance">
		<main>
			{$HOOK_MAINTENANCE|cleanHtml}
		</main>
	</body>
</html>
