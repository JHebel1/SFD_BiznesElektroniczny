{**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 *}

{capture ce_warning_multistore}<span id="ce-warning-multistore"></span>{/capture}

{capture ce_alert}<div class="alert alert-%s">%s</div>{/capture}

{capture ce_undefined_position}
	{ce__('Undefined Position!')}
	<a href="http://docs.webshopworks.com/creative-elements/79-troubleshooting/337-undefined-position" class="ce-read-more">{ce__('Learn More')}</a>
{/capture}

{capture ce_action_link}<a href="%s" target="%s"><i class="icon-%s"></i> %s</a>{/capture}

{function ce_preview_breadcrumb links=[]}
	{$last = array_pop($links)}
	{foreach $links as $link}
		<a>{$link['title']|cleanHtml}</a><span class="navigation-pipe">&gt;</span>
	{/foreach}
	{$last['title']|cleanHtml}
{/function}

{capture ce_inline_script}
	<script data-cfasync="false">
	%s
	</script>
{/capture}
