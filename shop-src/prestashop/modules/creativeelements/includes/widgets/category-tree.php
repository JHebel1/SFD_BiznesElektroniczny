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

class WidgetCategoryTree extends WidgetPremiumBase
{
    public function getName()
    {
        return 'category-tree';
    }

    public function getTitle()
    {
        return __('Category Tree', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-toggle';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/76-category-tree-links';
    }
}
