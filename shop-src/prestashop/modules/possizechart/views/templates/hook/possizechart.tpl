{if isset($content)}
<div class="pos-sizechart">
	<div class="pos-sizechart__title">
		<p>{l s='Size Chart' d='Shop.Theme.Global'}
	</div>
	<div class="pos-sizechart__content">
		{$content nofilter}
	</div>
</div>
{/if}