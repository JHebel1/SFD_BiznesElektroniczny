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
 * A simple number input control
 *
 * @param integer $default  The default value
 *                          Default empty
 * @param integer $min      The minimum number (Only affects the spinners, the user can still type a lower value)
 *                          Default empty
 * @param integer $max      The maximum number (Only affects the spinners, the user can still type a higher value)
 *                          Default empty
 * @param integer $step     The intervals value that will be incremented or decremented when using the controls' spinners
 *                          Default empty (The value will be incremented by 1)
 *
 * @since 1.0.0
 */
class ControlNumber extends ControlBase
{
    public function getType()
    {
        return 'number';
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <input type="number" min="{{ data.min }}" max="{{ data.max }}" step="{{ data.step }}" class="tooltip-target" data-tooltip="{{ data.title }}" title="{{ data.title }}" data-setting="{{ data.name }}" placeholder="{{ data.placeholder }}" />
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
