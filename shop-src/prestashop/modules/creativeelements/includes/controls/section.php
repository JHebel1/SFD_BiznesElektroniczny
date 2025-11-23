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
 * A UI only control. Shows a header that functions as a toggle to show or hide a set of controls.
 * Do not use it directly, instead use: `$widget->startControlsSection()` and `$widget->endControlsSection()` to wrap
 * a set of controls.
 *
 * @since 1.0.0
 */
class ControlSection extends ControlBase
{
    public function getType()
    {
        return 'section';
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-panel-heading">
            <div class="elementor-panel-heading-toggle elementor-section-toggle" data-collapse_id="{{ data.name }}">
                <i class="fa"></i>
            </div>
            <div class="elementor-panel-heading-title elementor-section-title">{{{ data.label }}}</div>
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
