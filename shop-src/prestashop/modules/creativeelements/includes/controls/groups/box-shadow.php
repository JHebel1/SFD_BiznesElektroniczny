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

class GroupControlBoxShadow extends GroupControlBase
{
    protected static $fields;

    public static function getType()
    {
        return 'box-shadow';
    }

    protected function initFields()
    {
        $controls = array();

        $controls['box_shadow_type'] = array(
            'label' => _x('Box Shadow', 'Box Shadow Control', 'elementor'),
            'type' => ControlsManager::SWITCHER,
            'label_on' => __('Yes', 'elementor'),
            'label_off' => __('No', 'elementor'),
            'return_value' => 'yes',
            'separator' => 'before',
        );

        $controls['box_shadow'] = array(
            'label' => _x('Box Shadow', 'Box Shadow Control', 'elementor'),
            'type' => ControlsManager::BOX_SHADOW,
            'selectors' => array(
                '{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
            ),
            'condition' => array(
                'box_shadow_type!' => '',
            ),
        );

        return $controls;
    }
}
