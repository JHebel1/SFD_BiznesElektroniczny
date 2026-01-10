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
 * A URL input control. with the ability to set the target of the link to `_blank` to open in a new tab.
 *
 * @param array $default {
 *         @type string $url         Default empty
 *         @type bool   $is_external Determine whether to open the url in the same tab or in a new one
 *                                   Default empty
 * }
 *
 * @param bool  $show_external       Whether to show the 'Is External' button
 *                                   Default true
 *
 * @since 1.0.0
 */
class ControlURL extends ControlBaseMultiple
{
    public function getType()
    {
        return 'url';
    }

    public function getDefaultValue()
    {
        return array(
            'is_external' => '',
            'url' => '',
        );
    }

    protected function getDefaultSettings()
    {
        return array(
            'label_block' => true,
            'show_external' => true,
        );
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field elementor-control-url-external-{{{ data.show_external ? 'show' : 'hide' }}}">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <input type="url" data-setting="url" placeholder="{{ data.placeholder }}" />
                <button class="elementor-control-url-target tooltip-target" data-tooltip="<?php _e('Open Link in new Tab', 'elementor');?>" title="<?php esc_attr_e('Open Link in new Tab', 'elementor');?>">
                    <span class="elementor-control-url-external" title="<?php esc_attr_e('New Window', 'elementor');?>"><i class="fa fa-external-link"></i></span>
                </button>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
