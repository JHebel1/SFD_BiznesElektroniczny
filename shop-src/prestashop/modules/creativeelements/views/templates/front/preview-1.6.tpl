{**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @Copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 *}

{if isset($ce_content)}
	{$ce_content.content|cefilter}
{else}
	{$ce_template.content|cefilter}
{/if}
