{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="pos-search-wrapper">
	<form class="pos-search {if $show_categories && $search_type != 'minimal'}pos-search-categories{/if} {if $search_type == 'dropdown' || $search_type == 'topbar'}js-dropdown{/if} search-{$search_type}" role="search" action="{$search_controller_url}" data-search-controller-url="{$search_controller_url}" method="get">
		{if $search_type == 'dropdown' || $search_type == 'topbar'}
            <div class="pos-search__toggle" data-toggle="dropdown">
                <i class="{$icon}" aria-hidden="true"></i>
            </div>
            <div class="dropdown-menu">
        {/if}
				<div class="pos-search__container">
					<div class="search-input-container">
						{if $search_type == 'minimal'}<i class="icon-minimal {$icon}" aria-hidden="true"></i>{/if}
						<input type="hidden" name="order" value="product.position.desc">
						<input class="pos-search__input" type="search" name="s" autocomplete="off" placeholder="{$placeholder}" />
						{if $show_categories && $search_type != 'minimal'}
							<input type="hidden" name="cat" value="" id="search-cat">
							<div class="search-category-items">             	
								<a href="#" class="search-cat-value" data-id="0"><span>{l s='All categories' mod='possearchproducts'}</span><i class="icon-rt-arrow-down"></i></a> 
								<ul class="dropdown-search">
									<li><a href="#" class="search-cat-value" data-id="0">{l s='All categories' mod='possearchproducts'}</a></li>
									{$options nofilter}
								</ul>
							</div>
						{/if}
						<span class="search-clear unvisible"></span> 
					</div>
					{if $search_type == 'classic' || $search_type == 'topbar'}
					<button class="pos-search__submit" type="submit">
						{if $button_type == 'icon'}
							<i class="{$icon}" aria-hidden="true"></i>
						{else}
							{$button_text}
						{/if}
					</button>
					{/if}
					{if $search_type == 'dropdown'}
					<button class="pos-search__submit" type="submit">
						<i class="{$icon}" aria-hidden="true"></i>
					</button>
					{/if}
					{if $search_type == 'topbar'}
						<div class="dialog-lightbox-close-button dialog-close-button">
							<i class="icon-rt-close-outline" aria-hidden="true"></i> 
						</div>
					{/if}
				</div>
				<div class="pos-search__result unvisible"></div>
        {if $search_type == 'dropdown' || $search_type == 'topbar'}
        	</div>
        {/if}
	</form>
</div>
