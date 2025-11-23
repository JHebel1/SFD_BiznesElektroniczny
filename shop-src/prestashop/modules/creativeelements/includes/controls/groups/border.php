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

class GroupControlBorder extends GroupControlBase
{
    protected static $fields;

    public static function getType()
    {
        return 'border';
    }

    protected function initFields()
    {
        $fields = array();

        $fields['border'] = array(
            'label' => _x('Border Type', 'Border Control', 'elementor'),
            'type' => ControlsManager::SELECT,
            'options' => array(
                '' => __('None', 'elementor'),
                'solid' => _x('Solid', 'Border Control', 'elementor'),
                'double' => _x('Double', 'Border Control', 'elementor'),
                'dotted' => _x('Dotted', 'Border Control', 'elementor'),
                'dashed' => _x('Dashed', 'Border Control', 'elementor'),
            ),
            'selectors' => array(
                '{{SELECTOR}}' => 'border-style: {{VALUE}};',
            ),
            'separator' => 'before',
        );

        $fields['width'] = array(
            'label' => _x('Width', 'Border Control', 'elementor'),
            'type' => ControlsManager::DIMENSIONS,
            'selectors' => array(
                '{{SELECTOR}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
            'condition' => array(
                'border!' => '',
            ),
        );

        $fields['color'] = array(
            'label' => _x('Color', 'Border Control', 'elementor'),
            'type' => ControlsManager::COLOR,
            'default' => '',
            'selectors' => array(
                '{{SELECTOR}}' => 'border-color: {{VALUE}};',
            ),
            'condition' => array(
                'border!' => '',
            ),
        );

        return $fields;
    }
}
