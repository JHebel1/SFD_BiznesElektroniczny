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
<div class="images-container top">
  {block name='product_cover'}
    <div class="product-cover-container">
      {block name='product_flags'}
        <ul class="product-flag">
          {foreach from=$product.flags item=flag}
          <li class=" {$flag.type}">{$flag.label}</li>
          {/foreach}
        </ul>
      {/block}
      <div class="product-cover slick-block" data-item-top="{$postheme.productp_thumbnail_item_top}">
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
