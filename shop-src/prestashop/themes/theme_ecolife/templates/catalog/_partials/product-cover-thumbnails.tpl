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
{if $postheme.productp_layout == '2'}
  {include file='catalog/_partials/product-cover-thumbnails-grid.tpl'}
{elseif $postheme.productp_layout == '4'}
  {include file='catalog/_partials/product-cover-thumbnails-top.tpl'}
{else}
  {if $postheme.productp_image_position == '0'}
    <div class="images-container default">
      {block name='product_cover'}
        <div class="product-cover-container">
          {block name='product_flags'}
            <ul class="product-flag">
              {foreach from=$product.flags item=flag}
              <li class=" {$flag.type}">{$flag.label}</li>
              {/foreach}
            </ul>
          {/block}
          <div class="product-cover slick-block column-desktop-1 column-tablet-1 column-mobile-1">
            {foreach from=$product.images item=image}
              <div class="cover-item">
              {if $product.cover}
                <div class="easyzoom easyzoom--overlay">
                <a href="{$image.bySize.large_default.url}">
                 <img class="" src="{$image.bySize.large_default.url}" alt="{$image.legend}" title="{$image.legend}" itemprop="image">
                </a>
                </div>

              {else}
              <img src="{$urls.no_picture_image.bySize.large_default.url}" style="width:100%;">
              {/if}
              </div>
            {/foreach}
          </div>
        </div>
      {/block}

      {block name='product_images'}
          <ul class="product-images slick-block column-desktop-4 column-tablet-4 column-mobile-4" data-item="{$postheme.productp_thumbnail_item}">
          {foreach from=$product.images item=image}
            <div class="image-item">
            <img
              class="thumb js-thumb {if $image.id_image == $product.cover.id_image} selected {/if}"
              src="{$image.bySize.home_default.url}"
              alt="{$image.legend}"
              title="{$image.legend}"
              width="100"
              itemprop="image">
            </div>
          {/foreach}
          </ul>
      {/block}
    {hook h='displayAfterProductThumbs'}
    </div>
  {elseif $postheme.productp_image_position == '1'}
    <div class="images-container left-vertical">
	   {block name='product_images'}
          <ul class="product-images slick-block" data-item="{$postheme.productp_thumbnail_item}">
          {foreach from=$product.images item=image}
            <div class="image-item">
            <img
              class="thumb js-thumb {if $image.id_image == $product.cover.id_image} selected {/if}"
              src="{$image.bySize.home_default.url}"
              alt="{$image.legend}"
              title="{$image.legend}"
              width="100"
              itemprop="image">
            </div>
          {/foreach}
          </ul>
      {/block}
      {block name='product_cover'}
        <div class="product-cover-container">
          {block name='product_flags'}
            <ul class="product-flag">
              {foreach from=$product.flags item=flag}
              <li class=" {$flag.type}">{$flag.label}</li>
              {/foreach}
            </ul>
          {/block}
          <div class="product-cover slick-block">
            {foreach from=$product.images item=image}
              <div class="cover-item">
              {if $product.cover}
                <div class="easyzoom easyzoom--overlay">
                <a href="{$image.bySize.large_default.url}">
                 <img class="" src="{$image.bySize.large_default.url}" alt="{$image.legend}" title="{$image.legend}" itemprop="image">
                </a>
                </div>

              {else}
              <img src="{$urls.no_picture_image.bySize.large_default.url}" style="width:100%;">
              {/if}
              </div>
            {/foreach}
          </div>
        </div>
      {/block}
    {hook h='displayAfterProductThumbs'}
    </div>
  {elseif $postheme.productp_image_position == '2' || $postheme.productp_layout == '3'}
    <div class="images-container right-vertical">
	  {block name='product_images'}
          <ul class="product-images slick-block" data-item="{$postheme.productp_thumbnail_item}">
          {foreach from=$product.images item=image}
            <div class="image-item">
            <img
              class="thumb js-thumb {if $image.id_image == $product.cover.id_image} selected {/if}"
              src="{$image.bySize.home_default.url}"
              alt="{$image.legend}"
              title="{$image.legend}"
              width="100"
              itemprop="image">
            </div>
          {/foreach}
          </ul>
      {/block}
      {block name='product_cover'}
        <div class="product-cover-container">
          {block name='product_flags'}
            <ul class="product-flag">
              {foreach from=$product.flags item=flag}
              <li class=" {$flag.type}">{$flag.label}</li>
              {/foreach}
            </ul>
          {/block}
          <div class="product-cover slick-block">
            {foreach from=$product.images item=image}
              <div class="cover-item">
              {if $product.cover}
                <div class="easyzoom easyzoom--overlay">
                <a href="{$image.bySize.large_default.url}">
                 <img class="" src="{$image.bySize.large_default.url}" alt="{$image.legend}" title="{$image.legend}" itemprop="image">
                </a>
                </div>

              {else}
              <img src="{$urls.no_picture_image.bySize.large_default.url}" style="width:100%;">
              {/if}
              </div>
            {/foreach}
          </div>
        </div>
      {/block}
    {hook h='displayAfterProductThumbs'}
    </div>
  {/if}
{/if}