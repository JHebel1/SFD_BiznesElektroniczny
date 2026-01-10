{*
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="quickmenu-mobile">
    <div class="quickmenu-mobile-wrapper">
        {foreach from=$blocks item=$block key=$key name=blocks}
            {if $block['type_content'] == 2}
                <div class="quickmenu-item quickmenu-wishlist" {if $wishlist_url} onclick="window.open('{$wishlist_url}','_self')"{/if}>
                    <div class="block-icon">
                        {if $block['icon'] != 'undefined'}
                            {if $block['icon']}
                                <img class="svg {$block['pack']} invisible" src="{$block['icon']}">
                            {elseif $block['custom_icon']}
                                <img {if $block['is_svg']}class="svg {$block['pack']} invisible" {/if}src="{$block['custom_icon']}">
                            {/if}
                        {/if}
                        <span class="qm-count" id="qmwishlist-count">{$wishlist_count}</span>
                    </div>
                    {if $block['title'] && $show_text}<div class="block-title">{$block['title']}</div>{/if}

                </div>
            {elseif $block['type_content'] == 3}
                <div class="quickmenu-item quickmenu-compare" {if $wishlist_url} onclick="window.open('{$compare_url}','_self')"{/if}>
                    <div class="block-icon">
                        {if $block['icon'] != 'undefined'}
                            {if $block['icon']}
                                <img class="svg {$block['pack']} invisible" src="{$block['icon']}">
                            {elseif $block['custom_icon']}
                                <img {if $block['is_svg']}class="svg {$block['pack']} invisible" {/if}src="{$block['custom_icon']}">
                            {/if}
                        {/if}
                        <span class="qm-count" id="qmcompare-count"></span>
                    </div>
                    {if $block['title'] && $show_text}<div class="block-title">{$block['title']}</div>{/if}

                </div>
            {elseif $block['type_content'] == 4}
                <div class="quickmenu-item quickmenu-cart" {if $wishlist_url} onclick="window.open('{$cart_url}','_self')"{/if}>
                    <div class="block-icon">
                        {if $block['icon'] != 'undefined'}
                            {if $block['icon']}
                                <img class="svg {$block['pack']} invisible" src="{$block['icon']}">
                            {elseif $block['custom_icon']}
                                <img {if $block['is_svg']}class="svg {$block['pack']} invisible" {/if}src="{$block['custom_icon']}">
                            {/if}
                        {/if}
                        <span class="qm-count" id="qmcart-count"></span>
                    </div>
                    {if $block['title'] && $show_text}<div class="block-title">{$block['title']}</div>{/if}

                </div>
            {elseif $block['type_content'] == 1 || $block['type_content'] == 5}
                <div class="quickmenu-item quickmenu-link" {if !empty($block['link'])}  onclick="window.open('{$block['link']}','_self')"{/if}>
                    <div class="block-icon">
                        {if $block['icon'] != 'undefined'}
                            {if $block['icon']}
                                <img class="svg {$block['pack']} invisible" src="{$block['icon']}">
                            {elseif $block['custom_icon']}
                                <img {if $block['is_svg']}class="svg {$block['pack']} invisible" {/if}src="{$block['custom_icon']}">
                            {/if}
                        {/if}
                    </div>
                    {if $block['title'] && $show_text}<div class="block-title">{$block['title']}</div>{/if}
                </div>
            {else}
                <div class="quickmenu-item quickmenu-custom">
                    <div class="block-icon">
                        {if $block['icon'] != 'undefined'}
                            {if $block['icon']}
                                <img class="svg {$block['pack']} invisible" src="{$block['icon']}">
                            {elseif $block['custom_icon']}
                                <img {if $block['is_svg']}class="svg {$block['pack']} invisible" {/if}src="{$block['custom_icon']}">
                            {/if}
                        {/if}
                    </div>
                    {if $block['title'] && $show_text}
                    <div class="block-title">{$block['title']}</div>
                    {/if}
                    <div class="quickmenu-item_content">
                        {if !empty($block['html_content'])}
                            {$block['html_content'] nofilter}
                        {/if}
                    </div>
                </div>
            {/if}
            
        {/foreach}
    </div>
</div>
