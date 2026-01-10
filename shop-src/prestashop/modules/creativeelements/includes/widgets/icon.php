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

class WidgetIcon extends WidgetBase
{
    public function getName()
    {
        return 'icon';
    }

    public function getTitle()
    {
        return __('Icon', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-favorite';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_icon',
            array(
                'label' => __('Icon', 'elementor'),
            )
        );

        $this->addControl(
            'view',
            array(
                'label' => __('View', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'default' => __('Default', 'elementor'),
                    'stacked' => __('Stacked', 'elementor'),
                    'framed' => __('Framed', 'elementor'),
                ),
                'default' => 'default',
                'prefix_class' => 'elementor-view-',
            )
        );

        $this->addControl(
            'icon',
            array(
                'label' => __('Icon', 'elementor'),
                'type' => ControlsManager::ICON,
                'label_block' => true,
                'default' => 'fa fa-star',
            )
        );

        $this->addControl(
            'shape',
            array(
                'label' => __('Shape', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'circle' => __('Circle', 'elementor'),
                    'square' => __('Square', 'elementor'),
                ),
                'default' => 'circle',
                'condition' => array(
                    'view!' => 'default',
                ),
                'prefix_class' => 'elementor-shape-',
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => __('Link', 'elementor'),
                'type' => ControlsManager::URL,
                'placeholder' => 'http://your-link.com',
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
                'default' => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-wrapper' => 'text-align: {{VALUE}};',
                ),
            )
        );
        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_icon',
            array(
                'label' => __('Icon', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'primary_color',
            array(
                'label' => __('Primary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
            )
        );

        $this->addControl(
            'secondary_color',
            array(
                'label' => __('Secondary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'condition' => array(
                    'view!' => 'default',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'size',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 6,
                        'max' => 300,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'icon_padding',
            array(
                'label' => __('Icon Padding', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ),
                'default' => array(
                    'size' => 1.5,
                    'unit' => 'em',
                ),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                    ),
                ),
                'condition' => array(
                    'view!' => 'default',
                ),
            )
        );

        $this->addControl(
            'rotate',
            array(
                'label' => __('Rotate', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 0,
                    'unit' => 'deg',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ),
            )
        );

        $this->addControl(
            'border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'view' => 'framed',
                ),
            )
        );

        $this->addControl(
            'border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'view!' => 'default',
                ),
            )
        );
        $this->endControlsSection();

        $this->startControlsSection(
            'section_hover',
            array(
                'label' => __('Icon Hover', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'hover_primary_color',
            array(
                'label' => __('Primary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'hover_secondary_color',
            array(
                'label' => __('Secondary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'condition' => array(
                    'view!' => 'default',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'hover_animation',
            array(
                'label' => __('Animation', 'elementor'),
                'type' => ControlsManager::HOVER_ANIMATION,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        $this->addRenderAttribute('wrapper', 'class', 'elementor-icon-wrapper');

        $this->addRenderAttribute('icon-wrapper', 'class', 'elementor-icon');

        if (!empty($settings['hover_animation'])) {
            $this->addRenderAttribute('icon-wrapper', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }

        $icon_tag = 'div';

        if (!empty($settings['link']['url'])) {
            $this->addRenderAttribute('icon-wrapper', 'href', $settings['link']['url']);
            $icon_tag = 'a';

            if (!empty($settings['link']['is_external'])) {
                $this->addRenderAttribute('icon-wrapper', 'target', '_blank');
            }
        }

        if (!empty($settings['icon'])) {
            $this->addRenderAttribute('icon', 'class', $settings['icon']);
        }

        ?>
        <div <?php echo $this->getRenderAttributeString('wrapper'); ?>>
            <<?php echo $icon_tag . ' ' . $this->getRenderAttributeString('icon-wrapper'); ?>>
                <i <?php echo $this->getRenderAttributeString('icon'); ?>></i>
            </<?php echo $icon_tag; ?>>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <# var link = settings.link.url ? 'href="' + settings.link.url + '"' : '', iconTag = link ? 'a' : 'div'; #>
        <div class="elementor-icon-wrapper">
            <{{{ iconTag }}} class="elementor-icon elementor-animation-{{ settings.hover_animation }}" {{{ link }}}>
                <i class="{{ settings.icon }}"></i>
            </{{{ iconTag }}}>
        </div>
        <?php
    }
}
