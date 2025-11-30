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

class WidgetProductGrid extends WidgetPremiumBase
{
    public function getName()
    {
        return 'product-grid';
    }

    public function getTitle()
    {
        return __('Product Grid', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-posts-grid';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/72-product-grid';
    }
}
