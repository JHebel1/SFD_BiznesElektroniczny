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
 * A UI only control. Show a text heading between controls.
 *
 * @param string $label   The label to show
 *
 * @since 1.0.0
 */
class ControlHeading extends ControlBase
{
    public function getType()
    {
        return 'heading';
    }

    protected function getDefaultSettings()
    {
        return array(
            'label_block' => true,
        );
    }

    public function contentTemplate()
    {
        ?>
        <h3 class="elementor-control-title">{{ data.label }}</h3>
        <?php
    }
}
