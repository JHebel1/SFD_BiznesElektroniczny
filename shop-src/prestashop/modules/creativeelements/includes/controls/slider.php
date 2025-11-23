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
 * A draggable Range Slider control.
 *
 * @param array  $default    {
 *         @type integer $size     The initial value of slider
 *                                 Default empty
 * }
 *
 * @since              1.0.0
 */
class ControlSlider extends ControlBaseUnits
{
    public function getType()
    {
        return 'slider';
    }

    public function getDefaultValue()
    {
        return array_merge(parent::getDefaultValue(), array(
            'size' => '',
        ));
    }

    protected function getDefaultSettings()
    {
        return array_merge(parent::getDefaultSettings(), array(
            'label_block' => true,
        ));
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <?php $this->printUnitsTemplate();?>
            <div class="elementor-control-input-wrapper elementor-clearfix">
                <div class="elementor-slider"></div>
                <div class="elementor-slider-input">
                    <input type="number" min="{{ data.min }}" max="{{ data.max }}" step="{{ data.step }}" data-setting="size" />
                </div>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
