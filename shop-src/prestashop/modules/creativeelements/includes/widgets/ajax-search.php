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

class WidgetAjaxSearch extends WidgetPremiumBase
{
    public function getName()
    {
        return 'ajax-search';
    }

    public function getTitle()
    {
        return __('AJAX Search');
    }

    public function getIcon()
    {
        return 'eicon-search';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/70-ajax-search';
    }
}
