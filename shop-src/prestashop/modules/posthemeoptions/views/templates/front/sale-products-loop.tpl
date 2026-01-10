{if $product_display == 'grid'}
  {block name='product_miniature_item'}
	<article class="thumbnail-container style_product_default product-miniature js-product-miniature item_in" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" >
		<div class="img_block">
		    {block name='product_thumbnail'}
				{if $product.cover}
				<a href="{$product.url}" class="thumbnail product-thumbnail">
				  <img class="first-image lazyload"
					data-src = "{$product.cover.bySize.large_default.url}" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
					alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
					data-full-size-image-url = "{$product.cover.large.url}"
				  >
				   {hook h="rotatorImg" product=$product}	
				</a>
				{else}
				  <a href="{$product.url}" class="thumbnail product-thumbnail">
					<img src="{$urls.no_picture_image.bySize.large_default.url}" /> 
				  </a>
				{/if}
				{/block}
				 <div class="quick-view">
					{block name='quick_view'}
					<a class="quick_view" href="#" data-link-action="quickview" title="{l s='Quick view' d='Shop.Theme.Actions'}">
					 <span>{l s='Quick view' d='Shop.Theme.Actions'}</span>
					</a>
					{/block}
				</div>
				{block name='product_flags'}
				<ul class="product-flag">
				{foreach from=$product.flags item=flag}
					<li class="{$flag.type}"><span>{$flag.label}</span></li>
				{/foreach}
				</ul>
			{/block}
		</div>
		<div class="product_desc">
			<div class="inner_desc">
				{if isset($product.id_manufacturer)}
				 <div class="manufacturer"><a href="{url entity='manufacturer' id=$product.id_manufacturer }">{Manufacturer::getnamebyid($product.id_manufacturer)}</a></div>
				{/if}
				{block name='product_name'}
				  <h3 ><a href="{$product.url}" class="product_name" title="{$product.name}">{$product.name|truncate:50:'...'}</a></h3> 
				{/block}
				 {block name='product_reviews'}
					<div class="hook-reviews">
					{hook h='displayProductListReviews' product=$product}
					</div>
				{/block}
				{block name='product_price_and_shipping'}
				  {if $product.show_price}
					<div class="product-price-and-shipping">
					  {if $product.has_discount}
						{hook h='displayProductPriceBlock' product=$product type="old_price"}
						<span class="regular-price" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price}</span>
					  {/if}

					  {hook h='displayProductPriceBlock' product=$product type="before_price"}

					  <span class="price {if $product.has_discount}price-sale{/if}" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">
						{capture name='custom_price'}{hook h='displayProductPriceBlock' product=$product type='custom_price' hook_origin='products_list'}{/capture}
						{if '' !== $smarty.capture.custom_price}
						  {$smarty.capture.custom_price nofilter}
						{else}
						  {$product.price}
						{/if}
					  </span>
						
					  {hook h='displayProductPriceBlock' product=$product type='unit_price'}

					  {hook h='displayProductPriceBlock' product=$product type='weight'}
					  {if $product.has_discount}
						{if $product.discount_type === 'percentage'}
						  <span class="discount-percentage discount-product">{$product.discount_percentage}</span>
						{elseif $product.discount_type === 'amount'}
						  <span class="discount-amount discount-product">{$product.discount_amount_to_display}</span>
						{/if}
					  {/if}
					</div>
				  {/if}
				{/block}
				<div class="cart">
					{include file='catalog/_partials/customize/button-cart.tpl' product=$product}
				</div>
			</div>	
			<div class="availability"> 
			{if $product.show_availability }
				{if $product.quantity > 0}
				<div class="availability-list in-stock">{l s='Availability' d='Shop.Theme.Actions'}: <span>{$product.quantity} {l s='In Stock' d='Shop.Theme.Actions'}</span></div>

				{else}

				<div class="availability-list out-of-stock">{l s='Availability' d='Shop.Theme.Actions'}: <span>{l s='Out of stock' d='Shop.Theme.Actions'}</span></div> 
				{/if}
			{/if}
			</div>
			
			{block name='product_variants'}
			{if $product.main_variants}
			<div class="variant-links">
			{include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
			</div>
			{/if}
			{/block} 
			{if isset($product.specific_prices.to) && $product.specific_prices.to|strtotime > $smarty.now && $product.specific_prices.from|strtotime < $smarty.now}
			<div class="countdown" >
			  <div class="title_countdown">{$title}</div>
			  <div class="time_count_down">
				<span class="specific-prices-timer" data-date-y ='{$product.specific_prices.to|date_format:"%Y"}' data-date-m ='{$product.specific_prices.to|date_format:"%m"}' data-date-d='{$product.specific_prices.to|date_format:"%d"}' data-date-h = '{$product.specific_prices.to|date_format:"%H"}' data-date-mi = '{$product.specific_prices.to|date_format:"%M"}' data-date-s= '{$product.specific_prices.to|date_format:"%S"}' ></span>
			  </div>
			</div>
			{/if}
		</div>
	</article>
{/block}
{else}
  {block name='product_miniature_item'}
	<article class="thumbnail-container style_product_default style_product_list_sale product-miniature js-product-miniature item_in" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" >
		<div class="img_block">
		    {block name='product_thumbnail'}
				{if $product.cover}
				<a href="{$product.url}" class="thumbnail product-thumbnail">
				  <img class="first-image lazyload"
					data-src = "{$product.cover.bySize.large_default.url}" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
					alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
					data-full-size-image-url = "{$product.cover.large.url}"
				  >
				   {hook h="rotatorImg" product=$product}	
				</a>
				{else}
				  <a href="{$product.url}" class="thumbnail product-thumbnail">
					<img src="{$urls.no_picture_image.bySize.large_default.url}" /> 
				  </a>
				{/if}
				{/block}
				 <div class="quick-view">
					{block name='quick_view'}
					<a class="quick_view" href="#" data-link-action="quickview" title="{l s='Quick view' d='Shop.Theme.Actions'}">
					 <span>{l s='Quick view' d='Shop.Theme.Actions'}</span>
					</a>
					{/block}
				</div>
				{block name='product_flags'}
				<ul class="product-flag">
				{foreach from=$product.flags item=flag}
					<li class="{$flag.type}"><span>{$flag.label}</span></li>
				{/foreach}
				</ul>
			{/block}
		</div>
		<div class="product_desc">
			<div class="inner_desc">
				{if isset($product.id_manufacturer)}
				 <div class="manufacturer"><a href="{url entity='manufacturer' id=$product.id_manufacturer }">{Manufacturer::getnamebyid($product.id_manufacturer)}</a></div>
				{/if}
				{block name='product_name'}
				  <h3 ><a href="{$product.url}" class="product_name" title="{$product.name}">{$product.name|truncate:50:'...'}</a></h3> 
				{/block}
				 {block name='product_reviews'}
					<div class="hook-reviews">
					{hook h='displayProductListReviews' product=$product}
					</div>
				{/block}
				{block name='product_price_and_shipping'}
				  {if $product.show_price}
					<div class="product-price-and-shipping">
					  {if $product.has_discount}
						{hook h='displayProductPriceBlock' product=$product type="old_price"}
						<span class="regular-price" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price}</span>
					  {/if}

					  {hook h='displayProductPriceBlock' product=$product type="before_price"}

					  <span class="price {if $product.has_discount}price-sale{/if}" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">
						{capture name='custom_price'}{hook h='displayProductPriceBlock' product=$product type='custom_price' hook_origin='products_list'}{/capture}
						{if '' !== $smarty.capture.custom_price}
						  {$smarty.capture.custom_price nofilter}
						{else}
						  {$product.price}
						{/if}
					  </span>
						
					  {hook h='displayProductPriceBlock' product=$product type='unit_price'}

					  {hook h='displayProductPriceBlock' product=$product type='weight'}
					  {if $product.has_discount}
						{if $product.discount_type === 'percentage'}
						  <span class="discount-percentage discount-product">{$product.discount_percentage}</span>
						{elseif $product.discount_type === 'amount'}
						  <span class="discount-amount discount-product">{$product.discount_amount_to_display}</span>
						{/if}
					  {/if}
					</div>
				  {/if}
				{/block}
				<div class="cart">
					{include file='catalog/_partials/customize/button-cart.tpl' product=$product}
				</div>
			</div>	
			<div class="availability"> 
			{if $product.show_availability }
				{if $product.quantity > 0}
				<div class="availability-list in-stock">{l s='Availability' d='Shop.Theme.Actions'}: <span>{$product.quantity} {l s='In Stock' d='Shop.Theme.Actions'}</span></div>

				{else}

				<div class="availability-list out-of-stock">{l s='Availability' d='Shop.Theme.Actions'}: <span>{l s='Out of stock' d='Shop.Theme.Actions'}</span></div> 
				{/if}
			{/if}
			</div>
			{block name='product_variants'}
			{if $product.main_variants}
			<div class="variant-links">
			{include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
			</div>
			{/if}
			{/block} 
			{if isset($product.specific_prices.to) && $product.specific_prices.to|strtotime > $smarty.now && $product.specific_prices.from|strtotime < $smarty.now}
			<div class="countdown" >
			  <div class="title_countdown">{$title}</div>
			  <div class="time_count_down">
				<span class="specific-prices-timer" data-date-y ='{$product.specific_prices.to|date_format:"%Y"}' data-date-m ='{$product.specific_prices.to|date_format:"%m"}' data-date-d='{$product.specific_prices.to|date_format:"%d"}' data-date-h = '{$product.specific_prices.to|date_format:"%H"}' data-date-mi = '{$product.specific_prices.to|date_format:"%M"}' data-date-s= '{$product.specific_prices.to|date_format:"%S"}' ></span>
			  </div>
			</div>
			{/if}
		</div>
	</article>
{/block}
{/if}