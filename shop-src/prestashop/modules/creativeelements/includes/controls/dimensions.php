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
 * A group of Dimensions settings (Top, Right, Bottom, Left) With the option to link them together
 *
 * @param array  $default {
 *         @type integer       $top                     Default empty
 *         @type integer       $right                   Default empty
 *         @type integer       $bottom                  Default empty
 *         @type integer       $left                    Default empty
 *         @type string        $unit                    The selected CSS Unit. 'px', '%', 'em'
 *                                                         Default 'px'
 *         @type bool          $isLinked                Whether to link them together ( prevent set different values )
 *                                                         Default true
 * }
 *
 * @param array|string $allowed_dimensions      Which fields to show, 'all' | 'horizontal' | 'vertical' | [ 'top', 'left' ... ]
 *                                              Default 'all'
 *
 * @since                         1.0.0
 */
class ControlDimensions extends ControlBaseUnits
{
    public function getType()
    {
        return 'dimensions';
    }

    public function getDefaultValue()
    {
        return array_merge(parent::getDefaultValue(), array(
            'top' => '',
            'right' => '',
            'bottom' => '',
            'left' => '',
            'isLinked' => true,
        ));
    }

    protected function getDefaultSettings()
    {
        return array_merge(parent::getDefaultSettings(), array(
            'label_block' => true,
            'allowed_dimensions' => 'all',
            'placeholder' => '',
        ));
    }

    public function contentTemplate()
    {
        $dimensions = array(
            'top' => __('Top', 'elementor'),
            'right' => __('Right', 'elementor'),
            'bottom' => __('Bottom', 'elementor'),
            'left' => __('Left', 'elementor'),
        );
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <?php $this->printUnitsTemplate();?>
            <div class="elementor-control-input-wrapper">
                <ul class="elementor-control-dimensions">
                    <?php foreach ($dimensions as $dimension_key => $dimension_title) : ?>
                        <li class="elementor-control-dimension">
                            <input type="number" data-setting="<?php echo esc_attr($dimension_key); ?>" placeholder="<#
                                if ( _.isObject( data.placeholder ) ) {
                                    if ( ! _.isUndefined( data.placeholder.<?php echo $dimension_key; ?> ) ) {
                                        print( data.placeholder.<?php echo $dimension_key; ?> );
                                    }
                                } else {
                                    print( data.placeholder );
                                } #>"
                                <# if ( -1 === _.indexOf( allowed_dimensions, '<?php echo $dimension_key; ?>' ) ) { #>disabled<# } #>/>
                            <span><?php echo $dimension_title; ?></span>
                        </li>
                    <?php endforeach;?>
                    <li>
                        <button class="elementor-link-dimensions tooltip-target" data-tooltip="<?php _e('Link values together', 'elementor');?>">
                            <span class="elementor-linked"><i class="fa fa-link"></i></span>
                            <span class="elementor-unlinked"><i class="fa fa-chain-broken"></i></span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
