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

class WidgetCountdown extends WidgetPremiumBase
{
    public function getName()
    {
        return 'countdown';
    }

    public function getTitle()
    {
        return __('Countdown', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-countdown';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/90-countdown';
    }
}
