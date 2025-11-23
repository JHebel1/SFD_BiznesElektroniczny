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

class WidgetImageSlider extends WidgetPremiumBase
{
    public function getName()
    {
        return 'image-slider';
    }

    public function getTitle()
    {
        return __('Image Slider', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-slideshow';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/74-image-slider';
    }
}
