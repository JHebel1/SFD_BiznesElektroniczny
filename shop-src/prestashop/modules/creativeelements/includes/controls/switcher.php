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
 * A Switcher (on/off) control - basically a fancy UI representation of a checkbox.
 *
 * @param string $label_off           The label for off status
 *                                    Default ''
 * @param string $label_on            The label for on status
 *                                    Default ''
 * @param string $return_value        The value of the control
 *                                    Default 'yes'
 *
 * @since 1.0.0
 */
class ControlSwitcher extends ControlBase
{
    public function getType()
    {
        return 'switcher';
    }

    public function contentTemplate()
    {
        ob_start();
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <label class="elementor-switch">
                    <input type="checkbox" data-setting="{{ data.name }}" class="elementor-switch-input" value="{{ data.return_value }}">
                    <span class="elementor-switch-label" data-on="{{ data.label_on }}" data-off="{{ data.label_off }}"></span>
                    <span class="elementor-switch-handle"></span>
                </label>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
        ob_end_flush();
    }

    protected function getDefaultSettings()
    {
        return array(
            'label_off' => '',
            'label_on' => '',
            'return_value' => 'yes',
        );
    }
}
