<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2021 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or die;
?>
<script type="text/template" id="tmpl-elementor-panel-elements">
    <div id="elementor-panel-elements-navigation" class="elementor-panel-navigation">
        <div id="elementor-panel-elements-navigation-all" class="elementor-panel-navigation-tab active" data-view="categories"><?php echo __('Elements', 'elementor'); ?></div>
        <div id="elementor-panel-elements-navigation-global" class="elementor-panel-navigation-tab" data-view="global"><?php echo __('Global', 'elementor'); ?></div>
    </div>
    <div id="elementor-panel-elements-languageselector-area"></div>
    <div id="elementor-panel-elements-search-area"></div>
    <div id="elementor-panel-elements-wrapper"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-categories">
    <div id="elementor-panel-categories"></div>

    <div id="elementor-panel-get-pro-elements" class="elementor-panel-nerd-box">
        <i class="elementor-panel-nerd-box-icon eicon-hypster"></i>
        <div class="elementor-panel-nerd-box-message"><?php _e('Get more with Creative Elements', 'elementor');?></div>
        <a class="elementor-button elementor-button-default elementor-panel-nerd-box-link" target="_blank" href="https://addons.prestashop.com/administrative-tools/44064-creative-elements-elementor-based-pagebuilder.html"><?php _e('Buy License', 'elementor');?></a>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-elements-category">
    <div class="panel-elements-category-title panel-elements-category-title-{{ name }}">{{{ title }}}</div>
    <div class="panel-elements-category-items"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-element-search">
    <input id="elementor-panel-elements-search-input" placeholder="<?php _e('Search Widget...', 'elementor');?>" />
    <i class="fa fa-search"></i>
</script>

<script type="text/template" id="tmpl-elementor-element-library-element">
    <div class="elementor-element">
        <div class="icon">
            <i class="{{ icon }}"></i>
        </div>
        <div class="elementor-element-title-wrapper">
            <div class="title">{{{ title }}}</div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-global">
    <div class="elementor-panel-nerd-box">
        <i class="elementor-panel-nerd-box-icon eicon-hypster"></i>
        <div class="elementor-panel-nerd-box-title"><?php echo __('Meet Our Global Widget', 'elementor'); ?></div>
        <div class="elementor-panel-nerd-box-message"><?php echo __('With this feature, you can save a widget as global, then add it to multiple areas. All areas will be editable from one single place.', 'elementor'); ?></div>
        <div class="elementor-panel-nerd-box-message"><?php echo __('This feature is only available on Elementor Pro.', 'elementor'); ?></div>
        <a class="elementor-button elementor-button-default elementor-panel-nerd-box-link" href="https://go.elementor.com/pro-global/" target="_blank"><?php echo __('Go Pro', 'elementor'); ?></a>
    </div>
</script>
