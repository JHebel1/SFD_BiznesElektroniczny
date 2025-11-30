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
 * An 'Order By' select box control.
 *
 * @param array $default {
 *      @type string $order_by      The selected order
 *                                  Default empty
 *      @type string $reverse_order Whether to reverse the order
 *                                  Default empty
 * }
 *
 * @param array $options      Array of key & value pairs: `[ 'key' => 'value', ... ]`
 *                            Default empty
 *
 * @since 1.0.0
 */
class ControlOrder extends ControlBaseMultiple
{
    public function getType()
    {
        return 'order';
    }

    public function getDefaultValue()
    {
        return array(
            'order_by' => '',
            'reverse_order' => '',
        );
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <div class="elementor-control-oreder-wrapper">
                    <select data-setting="order_by">
                        <# _.each( data.options, function( option_title, option_value ) { #>
                            <option value="{{ option_value }}">{{{ option_title }}}</option>
                            <# } ); #>
                    </select>
                    <input id="elementor-control-order-input-{{ data._cid }}" type="checkbox" data-setting="reverse_order">
                    <label for="elementor-control-order-input-{{ data._cid }}" class="elementor-control-order-label">
                        <i class="fa fa-sort-amount-desc"></i>
                    </label>
                </div>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}