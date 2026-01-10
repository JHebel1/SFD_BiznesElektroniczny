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
 * A Select box control based on select2 jQuery plugin @see https://select2.github.io/ .
 *
 * @param string|array $default  The selected option key, or an array of selected values if multiple == true
 *                               Default empty
 * @param array        $options  Array of of key & value pairs: `[ 'key' => 'value', ... ]`
 *                               Default empty
 * @param bool         $multiple Whether to allow multi choices
 *                               Default false
 *
 * @since 1.0.0
 */
class ControlSelect2 extends ControlBase
{
    public function getType()
    {
        return 'select2';
    }

    public function getDefaultSettings()
    {
        return array(
            'multiple' => false,
        );
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
                    <# _.each( data.options, function( option_title, option_value ) {
                        var value = data.controlValue;
                        if ( typeof value == 'string' ) {
                            var selected = ( option_value === value ) ? 'selected' : '';
                        } else if ( null !== value ) {
                            var value = _.values( value );
                            var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
                        }
                        #>
                    <option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
                    <# } ); #>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
