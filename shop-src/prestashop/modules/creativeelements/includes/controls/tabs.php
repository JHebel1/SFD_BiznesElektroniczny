<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2021 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or die;

/**
 * A UI only control. Render a tabs header for `tab` controls
 * Do not use it directly, instead use: `$widget->startControlsTabs()` and in the end `$widget->endControlsTabs()`
 *
 * @since 1.0.0
 */
class ControlTabs extends ControlBase
{
    public function getType()
    {
        return 'tabs';
    }

    public function contentTemplate()
    {
    }

    protected function getDefaultSettings()
    {
        return array(
            'separator' => 'none',
        );
    }
}
