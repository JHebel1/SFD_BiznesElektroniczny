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
 * A simple text input control.
 *
 * @param string $default     A default value
 *                            Default empty
 * @param string $input_type  any valid HTML5 input type: email, tel, etc.
 *                            Default 'text'
 *
 * @since 1.0.0
 */
class ControlText extends ControlBase
{
    public function getType()
    {
        return 'text';
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <input type="{{ data.input_type }}" class="tooltip-target" data-tooltip="{{ data.title }}" title="{{ data.title }}" data-setting="{{ data.name }}" placeholder="{{ data.placeholder }}" <# if ( data.input_list ) { #>list="{{ data.name }}-list"<# } #>/>
                <# if ( Array.isArray( data.input_list ) ) { #>
                    <datalist id="{{ data.name }}-list">
                    <# data.input_list.forEach( function ( val ) { #>
                        <option value="{{ val }}">
                    <# }); #>
                    </datalist>
                <# } #>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    public function getDefaultSettings()
    {
        return array(
            'input_type' => 'text',
            'input_list' => false,
        );
    }
}
