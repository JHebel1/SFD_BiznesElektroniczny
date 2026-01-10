<div class="pos-producttabs-widget">
  <ul class="tab-titles nav navtab-products">
    {foreach from=$tab_titles item=tab_title name=posTab}
      <li class="nav-item">
        <a class="nav-link {if $smarty.foreach.posTab.index == 0}active{/if}" data-toggle="tab" href="#tab{$tab_title.id}">{$tab_title.title}</a>
      </li>
    {/foreach}
  </ul>
  <div class="tab-content">
    {foreach from=$tab_contents item=tab_content name=posTab}
      <div class="{if $smarty.foreach.posTab.index == 0}active{/if} tab-pane fade in" id="tab{$tab_content.id}">
        {if $carousel_active}
          <div class="slick-slider-block {$class}" data-slider_options="{$slick_options}" data-slider_responsive="{$slick_responsive}">
            {foreach from=$tab_content.products item="product"}
              <div class="slick-slide1">
                      {include file="$theme_template_path" product=$product}
              </div>
            {/foreach}
          </div>
        {else}
          <div class="product-grid">
            {foreach from=$tab_content.products item="product"}
			  <div class="col-xl-{$columns_desktop} col-md-{$columns_tablet} col-xs-{$columns_mobile}">
              {include file="$theme_template_path" product=$product}
			  </div>
            {/foreach}
          </div>
        {/if}
      </div>
    {/foreach}
  </div>
</div>