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
<div class="bootstrap" id="posquicklink_configuration">
    <!-- Module content -->
    <div id="modulecontent" class="clearfix">
        <!-- Tab panes -->
        <div class="tab-content row">
            {* Listing of reassurance blocks *}
            {include file="./content/listing.tpl"}

            {* Listing of reassurance blocks *}
            {include file="./content/config.tpl"}
        </div>
        <div class="tab-content row">
            <div class="panel panel-default col-lg-8 col-lg-offset-2 col-xs-12 col-xs-offset-0">
                <div class="panel-heading">
                    {l s='Customize Module Design' d='Modules.Blockreassurance.Admin'}
                </div>

                <div class="panel-body">
                    <div class="clearfix">

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                <div class="text-right">
                                    <label class="control-label">
                                        {l s='Icon color' d='Modules.Blockreassurance.Admin'}
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-2">
                                <div class="ps_colorpicker1"></div>
                                <input type="hidden" id="color_1" name="QM_ICON_COLOR" class="psr_icon_color"
                                       value="{if isset($psr_icon_color)}{$psr_icon_color|escape:'htmlall':'UTF-8'}{/if}" />
                            </div>

                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                <div class="text-right">
                                    <label class="control-label">
                                        {l s='Show text' d='Modules.Blockreassurance.Admin'}
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-2">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" name="QM_SHOW_TEXT" id="show_text_on" value="1" {if (isset($psr_show_text) &&  $psr_show_text != 0) }checked="checked"{/if}>
                                    <label for="new_window_on">Yes</label>
                                    <input type="radio" name="QM_SHOW_TEXT" id="show_text_off" value="0" {if (isset($psr_show_text) && $psr_show_text == 0)|| !$psr_show_text}checked="checked"{/if}>
                                    <label for="new_window_off">No</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                <div class="text-right">
                                    <label class="control-label">
                                        {l s='Text color' d='Modules.Blockreassurance.Admin'}
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-2">
                                <div class="ps_colorpicker2"></div>
                                <input type="hidden" id="color_2" name="QM_TEXT_COLOR" class="psr_text_color"
                                       value="{if isset($psr_text_color)}{$psr_text_color|escape:'htmlall':'UTF-8'}{/if}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">
                    <div class="col-xs-12 text-right">
                        <button name="saveConfiguration" id="saveConfiguration" type="submit" class="btn btn-primary">{l s='Save' d='Modules.Blockreassurance.Admin'}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #posquicklink_configuration .form-group{
        overflow: hidden;
    }
    .svg_chosed_here img{
        max-width: 50px
    }
</style>