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

class WidgetDivider extends WidgetBase
{
    public function getName()
    {
        return 'divider';
    }

    public function getTitle()
    {
        return __('Divider', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-divider';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_divider',
            array(
                'label' => __('Divider', 'elementor'),
            )
        );

        $this->addControl(
            'style',
            array(
                'label' => __('Style', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'solid' => __('Solid', 'elementor'),
                    'double' => __('Double', 'elementor'),
                    'dotted' => __('Dotted', 'elementor'),
                    'dashed' => __('Dashed', 'elementor'),
                ),
                'default' => 'solid',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-divider-separator' => 'border-top-style: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'weight',
            array(
                'label' => __('Weight', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 1,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 10,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-divider-separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-divider-separator' => 'border-top-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'width',
            array(
                'label' => __('Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 100,
                    'unit' => '%',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-divider-separator' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'align',
            array(
                'label' => __('Alignment', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'elementor'),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center', 'elementor'),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __('Right', 'elementor'),
                        'icon' => 'fa fa-align-right',
                    ),
                ),
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-divider' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'gap',
            array(
                'label' => __('Gap', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 15,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 2,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-divider' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'view',
            array(
                'label' => __('View', 'elementor'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'traditional',
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        ?>
        <div class="elementor-divider">
            <span class="elementor-divider-separator"></span>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-divider">
            <span class="elementor-divider-separator"></span>
        </div>
        <?php
    }
}
