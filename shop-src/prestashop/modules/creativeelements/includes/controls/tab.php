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
 * A UI only control. Shows a tab header for a set of controls.
 * Do not use it directly, instead use: `$widget->startControlsTab()` and in the end `$widget->endControlsTab()`
 *
 * @since 1.0.0
 */
class ControlTab extends ControlBase
{
    public function getType()
    {
        return 'tab';
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-panel-tab-heading">
            {{{ data.label }}}
        </div>
        <?php
    }

    protected function getDefaultSettings()
    {
        return array(
            'separator' => 'none',
        );
    }
}
