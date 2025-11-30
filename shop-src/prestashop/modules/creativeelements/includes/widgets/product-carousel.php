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

class WidgetProductCarousel extends WidgetPremiumBase
{
    public function getName()
    {
        return 'product-carousel';
    }

    public function getTitle()
    {
        return __('Product Carousel', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-posts-carousel';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/73-product-carousel';
    }
}
