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

class WidgetFlipBox extends WidgetPremiumBase
{
    public function getName()
    {
        return 'flip-box';
    }

    public function getTitle()
    {
        return __('Flip Box', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-flip-box';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/78-flip-box';
    }
}
