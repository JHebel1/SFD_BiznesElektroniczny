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
 * A base control for controls that return more than a single value. Extends `ControlBase`.
 * Each value of a multiple-value control will be returned as an item in a key => value array
 *
 * @since 1.0.0
 */
abstract class ControlBaseMultiple extends ControlBase
{
    public function getDefaultValue()
    {
        return array();
    }

    public function getValue($control, $widget)
    {
        $value = parent::getValue($control, $widget);

        if (empty($control['default'])) {
            $control['default'] = array();
        }

        if (!is_array($value)) {
            $value = array();
        }

        $control['default'] = array_merge(
            $this->getDefaultValue(),
            $control['default']
        );

        return array_merge(
            $control['default'],
            $value
        );
    }

    public function getStyleValue($css_property, $control_value)
    {
        return $control_value[$css_property];
    }
}
