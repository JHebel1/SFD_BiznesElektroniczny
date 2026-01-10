{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
<div id="js-product-list-top" class=" products-selection">
<div class="row flex-layout center-vertical">
  <div class="col-xs-12 {if $postheme.cate_layout == '2'}col-sm-4 col-md-5 col-lg-6 {else}col-sm-4 col-md-6{/if} total-products">
    <ul class="display">
		<li id="grid" class="show_grid"> <i class="icon-rt-grid2"></i></i></li>
		<li id="list" class="show_list"> <i class="icon-rt-list-solid"></i></li>
	</ul>
    {if $listing.pagination.total_items > 1}
      <p class="hidden-sm-down">{l s='There are %product_count% products.' d='Shop.Theme.Catalog' sprintf=['%product_count%' => $listing.pagination.total_items]}</p>
    {else if $listing.pagination.total_items > 0}
      <p>{l s='There is 1 product.' d='Shop.Theme.Catalog'}</p> 
    {/if}
  </div>
  <div class="col-xs-12 {if $postheme.cate_layout == '2'}col-sm-8 col-md-7 col-lg-6{else}col-sm-8 col-md-6{/if}">
    <div class="row sort-by-row flex-end"> 

      {block name='sort_by'}
        {include file='catalog/_partials/sort-orders.tpl' sort_orders=$listing.sort_orders}
      {/block}
		{if $postheme.cate_layout == '1'}
        {if !empty($listing.rendered_facets)}
          <div class="col-sm-6 col-xs-4 hidden-md-up filter-button">
            <button id="search_filter_toggler" class="btn btn-secondary filter-mobile" href="#search_filters_wrapper">
              {l s='Filter' d='Shop.Theme.Actions'}
            </button>
          </div>
        {/if}
       {else}
        {if !empty($listing.rendered_facets)}
			<div class="filter-button col-sm-6 col-xs-4">
			  <button id="pos_search_filter_toggler" class="btn btn-secondary" href="#search_filters_wrapper">
				{l s='Filter' d='Shop.Theme.Actions'}
			  </button>
			</div>
        {/if}
      {/if}
      
    </div>
  </div>

</div> 
</div>
