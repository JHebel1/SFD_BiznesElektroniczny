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

class WidgetProductBox extends WidgetPremiumBase
{
    public function getName()
    {
        return 'product-box';
    }

    public function getTitle()
    {
        return __('Product Box', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-info-box';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/81-product-box';
    }
}
