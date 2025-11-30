{**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @Copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 *}

{extends file='page.tpl'}

{block name='page_content_container'}
	{if isset($ce_content)}
		{$ce_content.content|cefilter}
	{else}
		{$ce_template.content|cefilter}
	{/if}
{/block}
