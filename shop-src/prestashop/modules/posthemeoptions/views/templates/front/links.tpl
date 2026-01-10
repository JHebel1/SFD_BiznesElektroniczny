<div class="pos-links-widget">
	{if $title}	
	<h2 class="links-widget-title hidden-sm-down">
		{if $title_url.url}<a href="{$title_url.url}">{/if}
			<span>{$title}</span>
		{if $title_url}</a>{/if}
	</h2>
	
    <h2 class="links-widget-title hidden-md-up" data-target="#footer_linkslist_{$id}" data-toggle="collapse" aria-expanded="false">
        {if $title_url.url}<a href="{$title_url.url}">{/if}
			<span>{$title}</span>
		{if $title_url}</a>{/if}
		<span class="navbar-toggler collapse-icons">
			<i class="material-icons add">&#xE313;</i>
			<i class="material-icons remove">&#xE316;</i>
		</span>
    </h2>
	{/if}
	<ul id="footer_linkslist_{$id}" class="{if !$title}not-title-links{/if} links-widget-content collapse">
		{foreach from=$links item=link}
		<li>
			{if $link.title}
				{if $link.type_link == 'custom'}
					{if $link.title && $link.custom_link.url}
						<a href="{$link.custom_link.url}">{$link.title}</a>
					{/if}
				{elseif $link.type_link == 'static'}
					<a href="{$link.static_link}">{$link.title}</a>
				{else}
					<a href="{$link.page_link}">{$link.title}</a>
				{/if}
			{else}
				<span>Title missed</span>
			{/if}
		</li>
		{/foreach}
	</ul>
</div>