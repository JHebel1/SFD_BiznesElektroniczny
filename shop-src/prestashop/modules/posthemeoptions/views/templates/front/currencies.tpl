<div class="pos-currency-widget pos-dropdown-wrapper js-dropdown">
	<div class="pos-dropdown-toggle" data-toggle="dropdown">
		<span class="symbol-currency">{$current_currency.sign}</span>
		<span class="text-currency">{$current_currency.iso_code}</span>
		<span class="icon-toggle fa fa-angle-down"></span>
	</div>
	<div class="dropdown-menu pos-dropdown-menu">
		{foreach from=$currencies item=currency}
			<a data-btn-currency="{$currency.id}" href="{$currency.url}" {if $currency.current} class="selected"{/if}>
				{$currency.iso_code} {$currency.sign}
			</a>
		{/foreach}
	</div>
</div>