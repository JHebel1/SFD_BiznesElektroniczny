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

class WidgetEmailSubscription extends WidgetPremiumBase
{
    public function getName()
    {
        return 'email-subscription';
    }

    public function getTitle()
    {
        return __('Email Subscription', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-email-field';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/75-email-subscription';
    }
}
