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

class WidgetTrustedShopsReviews extends WidgetPremiumBase
{
    public function getName()
    {
        return 'trustedshops-reviews';
    }

    public function getTitle()
    {
        return __('TrustedShops Reviews', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-carousel';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/71-trustedshops-reviews';
    }
}
