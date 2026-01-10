<div class="pos-languages-widget pos-dropdown-wrapper js-dropdown">
	<div class="pos-dropdown-toggle" data-toggle="dropdown">
		<img src="{Context::getContext()->link->getMediaLink(_THEME_LANG_DIR_)}{$current_language.id_lang}.jpg" alt="{$current_language.name_simple}" width="16" height="11"/>
		<span class="pos-dropdown-toggle-text">{$current_language.name_simple}</span>
		<span class="icon-toggle fa fa-angle-down"></span>
	</div>
	<div class="dropdown-menu pos-dropdown-menu">
		{foreach from=$languages item=language}
			<a data-btn-lang="{$language.id_lang}" href="{$language.url}" {if $language.id_lang == $current_language.id_lang} class="selected"{/if}>
				<img src="{Context::getContext()->link->getMediaLink(_THEME_LANG_DIR_)}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11"/>
				{$language.name_simple}
			</a>
		{/foreach}
	</div>
</div>