<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

namespace CE;

defined('_PS_VERSION_') or die;

class WidgetModule extends WidgetPremiumBase
{
    public function getName()
    {
        return 'ps-widget-module';
    }

    public function getTitle()
    {
        return __('Module', 'elementor');
    }

    public function getIcon()
    {
        return 'fa fa-puzzle-piece';
    }

    public function getDemoLink()
    {
        return 'https://addons.prestashop.com/administrative-tools/44064-creative-elements-elementor-based-pagebuilder.html';
    }
}
