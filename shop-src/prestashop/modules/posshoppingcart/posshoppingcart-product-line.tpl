<div class="img_content">
	{if $product.default_image}
        <img class="product-image img-responsive" src="{$product.default_image.bySize.cart_default.url}" alt="{$product.name|escape:'quotes'}" loading="lazy">
    {else}
        <img class="product-image img-responsive" src="{$urls.no_picture_image.bySize.cart_default.url}" loading="lazy" />
    {/if}
	<span class="product-quantity">{$product.quantity}x</span>
</div>
<div class="right_block">
	<span class="product-name">{$product.name}</span>
	<span class="product-price">{$product.price}</span>
	<a class = "remove-from-cart" rel = "nofollow" href= "{$product.remove_from_cart_url}" data-link-action= "delete-from-cart" data-id-product= "{$product.id_product|escape:'javascript'}"
data-id-product-attribute= "{$product.id_product_attribute|escape:'javascript'}" data-id-customization= "{$product.id_customization|escape:'javascript'}">
            {if !isset($product.is_gift) || !$product.is_gift}
			<i class="icon-rt-close-circle-outline pull-xs-left"></i> 
            {/if}
          </a>
	<div class="attributes_content">
		{foreach from=$product.attributes item="property_value" key="property"}
		  <span><strong>{$property}</strong>: {$property_value}</span><br>
		{/foreach}
	</div>
</div>