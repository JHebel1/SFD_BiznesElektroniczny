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

class WidgetCallToAction extends WidgetPremiumBase
{
    public function getName()
    {
        return 'call-to-action';
    }

    public function getTitle()
    {
        return __('Call to Action', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-image-rollover';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/69-call-to-action';
    }
}
