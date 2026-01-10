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
 * A single Checkbox control
 *
 * @param string $default     Whether to initial it as checked. 'on' for checked, and '' (empty string) for unchecked
 *                            Default ''
 *
 * @since 1.0.0
 */
class ControlCheckbox extends ControlBase
{
    public function getType()
    {
        return 'checkbox';
    }

    public function contentTemplate()
    {
        ?>
        <label class="elementor-control-title">
            <input type="checkbox" data-setting="{{ data.name }}" />
            <span>{{{ data.label }}}</span>
        </label>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
