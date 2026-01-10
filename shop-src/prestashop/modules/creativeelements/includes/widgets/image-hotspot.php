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

class WidgetImageHotspot extends WidgetPremiumBase
{
    public function getName()
    {
        return 'image-hotspot';
    }

    public function getTitle()
    {
        return __('Image Hotspot', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-image-hotspot';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/77-image-hotspot';
    }
}
