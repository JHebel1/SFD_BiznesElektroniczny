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

class WidgetContactForm extends WidgetPremiumBase
{
    public function getName()
    {
        return 'contact-form';
    }

    public function getTitle()
    {
        return __('Contact Form', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-form-horizontal';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/87-contact-form';
    }
}
