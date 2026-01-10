<div class="pos-sale-product-widget">
  {if $carousel_active}
    <div class="slick-slider-block" data-slider_options="{$slick_options}" data-slider_responsive="{$slick_responsive}">
        {foreach from=$vc_products item="product"}
			<div>
			{include file="$product_loop_file" product=$product}
			</div>
        {/foreach}
    </div>
  {else}
    <div class="product-grid">
        {foreach from=$vc_products item="product"}
          <div class="col-lg-{$columns_desktop} col-md-{$columns_tablet} col-xs-{$columns_mobile}">
          {include file="$product_loop_file" product=$product}
          </div>
        {/foreach}
    </div>
  {/if}
</div>