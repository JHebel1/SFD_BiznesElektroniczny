{if isset($rotator_img)}
    {foreach from=$rotator_img item=image name=thumbnails}
		  {assign var=imageIds value="`$product.id_product`-`$image.id_image`"}
          <img class="img-responsive second-image {$class_name} lazyload" 
		  {if isset($imagesize)}
			data-src="{$link->getImageLink($product.link_rewrite, $imageIds, $imagesize)|escape:'html':'UTF-8'}" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
		  {else}
			data-src="{$link->getImageLink($product.link_rewrite, $imageIds, 'home_default')|escape:'html':'UTF-8'}" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
		  {/if}
		  alt="" itemprop="image"  />
    {/foreach}
{/if}		