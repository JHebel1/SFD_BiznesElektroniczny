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

<div class="form-group">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label">
                {l s='Type' d='Modules.Blockreassurance.Admin'}
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-9">
        <div class="input-group col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-12 customradiodesign">
                <select class="quickmenu-type" name="quickmenu_type_{if isset($block)}{$block.id_quickmenu}{/if}">
                  <option value="0">{l s='Select an option' mod='posquickmenu'}</option>
                  <option value="1" {if isset($block) && $block.type_content == 1}selected{/if}>{l s='Prestashop link' mod='posquickmenu'}</option>
                  <option value="2" {if isset($block) && $block.type_content == 2}selected{/if}>{l s='Wishlist' mod='posquickmenu'}</option>
                  <option value="3" {if isset($block) && $block.type_content == 3}selected{/if}>{l s='Compare' mod='posquickmenu'}</option>
                  <option value="4" {if isset($block) && $block.type_content == 4}selected{/if}>{l s='Cart' mod='posquickmenu'}</option>
                  <option value="5" {if isset($block) && $block.type_content == 5}selected{/if}>{l s='Custom link' mod='posquickmenu'}</option>
                  <option value="6" {if isset($block) && $block.type_content == 6}selected{/if}>{l s='Custom content' mod='posquickmenu'}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-xs-12 help-block">
            <span class="help_text">{l s='Custom content : Open content when click' d='Modules.Blockreassurance.Admin'}</span>
        </div>
    <div class="clearfix"></div>
</div>
