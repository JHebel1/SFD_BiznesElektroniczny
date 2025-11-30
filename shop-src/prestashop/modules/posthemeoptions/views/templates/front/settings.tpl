<div class="pos-settings-widget js-dropdown">
	<div class="pos-dropdown-toggle" data-toggle="dropdown">
		<i class="{$icon}"></i>
	</div>
	<div class="dropdown-menu pos-dropdown-menu">
		{foreach from=$settings_content item=block}
			<h5>{$block.title}</h5>
			{if $block.type == 'language' && isset($block.content.languages)}
				{foreach from=$block.content.languages item=language}
					<a data-btn-lang="{$language.id_lang}" href="{$language.url}" {if $language.id_lang == $block.content.current_language.id_lang} class="selected"{/if}>
						<img src="{Context::getContext()->link->getMediaLink(_THEME_LANG_DIR_)}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11"/>
						{$language.name_simple}
					</a>
				{/foreach}
			{/if}
			{if $block.type == 'currency' && isset($block.content.currencies)}
				{foreach from=$block.content.currencies item=currency}
				<a data-btn-currency="{$currency.id}" href="javascript:void(0)" {if $block.content.current_currency.current} class="selected"{/if}>
					{$currency.iso_code} {$currency.sign}
				</a>
				{/foreach}
			{/if}
			{if $block.type == 'account'}
				{if $block.content.logged}
					<a href="{$block.content.identity_url}">{l s='Your personal information' d='Shop.Theme.Customeraccount'}</a>
					<a href="{$block.content.history}">{l s='Order history' d='Shop.Theme.Customeraccount'}</a>
					<a href="{$block.content.account_url}">{l s='Your account' d='Shop.Theme.Customeraccount'}</a> 
					<a href="{$block.content.logout}">{l s='Sign out' d='Shop.Theme.Actions'}</a>
				{else}
					<a href="{$block.content.create_account}" class="user_register">
						{l s='Register' d='Shop.Theme.Actions'}
					</a>
					<a class="login" href="{$block.content.account_url}" rel="nofollow" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}">{l s='Sign in' d='Shop.Theme.Actions'}</a>
				{/if}
			{/if}
		{/foreach}
	</div>
</div>