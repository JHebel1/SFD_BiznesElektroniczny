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
<div id="_desktop_cart_block">
  <div class="blockcart cart-preview {if $cart_layout == '1'}cart-default{else}cart-sidebar{/if}" data-refresh-url="{$refresh_url}" data-cartitems="{$cart.products_count}">
     <a rel="nofollow" href="{$cart_url}">
        {if isset($icon)}
        <i class="{$icon}"></i>
        {else}
        <i class="icon-rt-FullShoppingCart"></i>
        {/if}
        <span class="cart-products-total">{$cart.totals.total.value}</span>
        <span class="cart-products-count">{$cart.products_count}</span>
    </a>
	 {if $page.page_name != 'cart'}
    {if $cart_layout == '1'}
      <div class="popup_cart popup-dropdown">
          <ul>
            {foreach from=$cart.products item=product}
            <li>{include 'module:posshoppingcart/posshoppingcart-product-line.tpl' product=$product}</li>
            {/foreach}
          </ul>
          <div class="price_content">
            {block name='cart_totals'}
              {include file='checkout/_partials/cart-detailed-totals.tpl' cart=$cart}
            {/block}
          </div>
          <div class="checkout">
            <a href="{$cart_url}" class="btn btn-primary">{l s='Checkout' d='Shop.Theme.Actions'}</a> 
          </div>
      </div>
    {else}
     <div class="popup_cart popup-sidebar">
		<div class="title-cart flex-layout space-between">
			<span>{l s='My cart' d='Shop.Theme.Actions'}</span>
			<a href="javascript:void(0)" class="close-cart"><i class="icon-rt-close-outline"></i></a>
		</div>
		<div class="content-sidebar">
			{if $cart.products_count != '0'}
			  <ul>
				{foreach from=$cart.products item=product}
				<li>{include 'module:posshoppingcart/posshoppingcart-product-line.tpl' product=$product}</li>
				{/foreach}
			  </ul>
			  <div class="price_content">
				{block name='cart_totals'}
	              {include file='checkout/_partials/cart-detailed-totals.tpl' cart=$cart}
	            {/block}
			  </div>
			  <div class="checkout">
				<a href="{$cart_url}" class="btn btn-primary">{l s='Checkout' d='Shop.Theme.Actions'}</a> 
			  </div>
			{else}
				<div class="empty-cart">
					<i class="icon-rt-handbag"></i>
					{l s='Your cart is empty.' d='Shop.Theme.Actions'}
				</div>
			{/if}
		</div>
      </div>
    {/if}
	{/if}
  </div>
</div>
