<div id="menu-icon"><i class="icon-rt-bars-solid"></i></div> 
<div class="menu-mobile-content" id="mobile_menu_wrapper">
	{hook h='displayMegamenuMobileTop'} 
	<div class="menu-close"> 
		{l s='Close' mod='posthemeoptions'} <i class="material-icons float-xs-right">arrow_back</i>
	</div>
	{if $vmenu}
	<ul class="nav nav-mobile-menu" role="tablist"> 
		<li class="nav-item">
			<a class="nav-link active"  data-toggle="tab" href="#tab-mobile-megamenu" role="tab" aria-controls="mobile-megamenu" aria-selected="true">{l s='Menu' mod='posthemeoptions'} Menu</a>
			
		</li>
		<li class="nav-item">
			<a class="nav-link"  data-toggle="tab" href="#tab-mobile-vegamenu" role="tab" aria-controls="mobile-vegamenu" aria-selected="true">{l s='Categories' mod='posthemeoptions'}</a>
		</li>
	</ul>
	{/if}
	{if $vmenu}
	<div class="tab-content">
		  <div class="tab-pane fade active in" id="tab-mobile-megamenu" role="tabpanel" aria-labelledby="megamenu-tab">
	{/if}
		{$hmenu nofilter}	
	{if $vmenu}
		</div>
		<div class="tab-pane fade" id="tab-mobile-vegamenu" role="tabpanel" aria-labelledby="vegamenu-tab">
		{$vmenu nofilter}
		</div>
	</div>
	{/if}
	{hook h='displayMegamenuMobileBottom'}
</div>