<div class="pos-product-widget">
  {if $vc_products}
    {if $carousel_active}
      <div class="slick-slider-block {$class}" data-slider_options="{$slick_options}" data-slider_responsive="{$slick_responsive}">
          {foreach from=$vc_products item="product"}
            <div class="slick-slide1">
              <div class="slick-slide-inner1">
                {include file="$theme_template_path" product=$product}
              </div>
            </div>
          {/foreach}
      </div>
    {else}
      <div class="product-grid">
          {foreach from=$vc_products item="product"}
            <div class="col-xl-{$columns_desktop} col-md-{$columns_tablet} col-xs-{$columns_mobile}">
            {include file="$theme_template_path" product=$product}
            </div>
          {/foreach}
      </div>
    {/if}
  {else}
    <p>There's no product</p>
  {/if}
</div>