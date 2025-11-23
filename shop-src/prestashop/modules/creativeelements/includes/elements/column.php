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

class ElementColumn extends ElementBase
{
    protected static $_edit_tools;

    protected static function getDefaultEditTools()
    {
        return array(
            'duplicate' => array(
                'title' => __('Duplicate', 'elementor'),
                'icon' => 'fa fa-files-o',
            ),
            'add' => array(
                'title' => __('Add', 'elementor'),
                'icon' => 'fa fa-plus',
            ),
            'remove' => array(
                'title' => __('Remove', 'elementor'),
                'icon' => 'fa fa-times',
            ),
        );
    }

    public function getName()
    {
        return 'column';
    }

    public function getTitle()
    {
        return __('Column', 'elementor');
    }

    public function getIcon()
    {
        return 'columns';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_style',
            array(
                'label' => __('Background & Border', 'elementor'),
                'type' => ControlsManager::SECTION,
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            array(
                'name' => 'background',
                'types' => array('none', 'classic', 'gradient'),
                'selector' => '{{WRAPPER}} > .elementor-element-populated',
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border',
                'selector' => '{{WRAPPER}} > .elementor-element-populated',
            )
        );

        $this->addControl(
            'border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} > .elementor-element-populated' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} > .elementor-element-populated',
            )
        );

        $this->endControlsSection();

        // Section Column Background Overlay
        $this->startControlsSection(
            'section_background_overlay',
            array(
                'label' => __('Background Overlay', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'background_background' => array('classic', 'gradient', 'video'),
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            array(
                'name' => 'background_overlay',
                'types' => array('none', 'classic', 'gradient'),
                'selector' => '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
                'condition' => array(
                    'background_background' => array('classic', 'gradient', 'video'),
                ),
            )
        );

        $this->addControl(
            'background_overlay_opacity',
            array(
                'label' => __('Opacity (%)', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => .5,
                ),
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'step' => 0.01,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay' => 'opacity: {{SIZE}};',
                ),
                'condition' => array(
                    'background_overlay_background' => array('classic', 'gradient'),
                ),
            )
        );

        $this->endControlsSection();

        // Section Layout
        $this->startControlsSection(
            'layout',
            array(
                'label' => __('Layout', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );
        $this->addControl(
            'content_position',
            array(
                'label' => __('Content Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => array(
                    '' => __('Default', 'elementor'),
                    'top' => __('Top', 'elementor'),
                    'center' => __('Middle', 'elementor'),
                    'bottom' => __('Bottom', 'elementor'),
                ),
                'selectors_dictionary' => array(
                    'top' => 'flex-start',
                    'bottom' => 'flex-end',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-column .elementor-column-wrap .elementor-widget-wrap' => 'align-content: {{VALUE}}',
                ),
            )
        );
        $this->addResponsiveControl(
            'align_position',
            array(
                'label' => __('Horizontal Align', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => array(
                    '' => __('Default', 'elementor'),
                    'flex-start' => __('Start', 'elementor'),
                    'center' => __('Center', 'elementor'),
                    'flex-end' => __('End', 'elementor'),
                    'space-between' => __('Space Between', 'elementor'),
                    'space-around' => __('Space Around', 'elementor'),
                    'space-evenly' => __('Space Evenly', 'elementor'),
                ),
                'label_block' => true,
                'selectors_dictionary' => array(
                    'top' => 'flex-start',
                    'bottom' => 'flex-end',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-column .elementor-column-wrap .elementor-widget-wrap' => 'justify-content: {{VALUE}}',
                ),
            )
        );

        $this->endControlsSection();

        // Section Typography
        $this->startControlsSection(
            'section_typo',
            array(
                'label' => __('Typography', 'elementor'),
                'type' => ControlsManager::SECTION,
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        // if (in_array(SchemeColor::getType(), SchemesManager::getEnabledSchemes())) {
        //     $this->addControl(
        //         'colors_warning',
        //         array(
        //             'type' => ControlsManager::RAW_HTML,
        //             'raw' => __('Note: The following colors won\'t work if Global Colors are enabled.', 'elementor'),
        //             'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
        //         )
        //     );
        // }

        $this->addControl(
            'heading_color',
            array(
                'label' => __('Heading Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-element-populated .elementor-heading-title' => 'color: {{VALUE}};',
                ),
                'separator' => 'none',
            )
        );

        $this->addControl(
            'color_text',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} > .elementor-element-populated' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'color_link',
            array(
                'label' => __('Link Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-element-populated a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'color_link_hover',
            array(
                'label' => __('Link Hover Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-element-populated a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'text_align',
            array(
                'label' => __('Text Align', 'elementor'),
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
                'selectors' => array(
                    '{{WRAPPER}} > .elementor-element-populated' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->endControlsSection();

        // Section Advanced
        $this->startControlsSection(
            'section_advanced',
            array(
                'label' => __('Advanced', 'elementor'),
                'type' => ControlsManager::SECTION,
                'tab' => ControlsManager::TAB_ADVANCED,
            )
        );

        $this->addResponsiveControl(
            'margin',
            array(
                'label' => __('Margin', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} > .elementor-element-populated' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} > .elementor-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'animation',
            array(
                'label' => __('Entrance Animation', 'elementor'),
                'type' => ControlsManager::ANIMATION,
                'default' => '',
                'prefix_class' => 'animated ',
                'label_block' => true,
            )
        );

        $this->addControl(
            'animation_duration',
            array(
                'label' => __('Animation Duration', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => array(
                    'slow' => __('Slow', 'elementor'),
                    '' => __('Normal', 'elementor'),
                    'fast' => __('Fast', 'elementor'),
                ),
                'prefix_class' => 'animated-',
                'separator' => '',
                'condition' => array(
                    'animation!' => '',
                ),
            )
        );

        $this->addControl(
            '_element_id',
            array(
                'label' => __('CSS ID', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => '',
                'label_block' => true,
                'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor'),
            )
        );

        $this->addControl(
            'css_classes',
            array(
                'label' => __('CSS Classes', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => '',
                'prefix_class' => '',
                'label_block' => true,
                'title' => __('Add your custom class WITHOUT the dot. e.g: my-class', 'elementor'),
                'separator' => '',
            )
        );

        $this->endControlsSection();

        // Section Responsive
        $this->startControlsSection(
            'section_responsive',
            array(
                'label' => __('Responsive', 'elementor'),
                'tab' => ControlsManager::TAB_ADVANCED,
            )
        );
        $this->addResponsiveControl(
            'column_size',
            [
                'label' => __('CustomColumn Width') . ' (%)',
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'max' => 100,
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{VALUE}}% !important',
                ],
                'description' => 'Only apply for tablet and mobile',
            ]
        );
        $responsive_points = array(
            'screen_sm' => array(
                'title' => __('Mobile Width', 'elementor'),
                'class_prefix' => 'elementor-sm-',
                'classes' => '',
                'description' => '',
            ),
        );

        foreach ($responsive_points as $point_name => $point_data) {
            $this->addControl(
                $point_name,
                array(
                    'label' => $point_data['title'],
                    'type' => ControlsManager::SELECT,
                    'default' => 'default',
                    'options' => array(
                        'default' => __('Default', 'elementor'),
                        'custom' => __('Custom', 'elementor'),
                    ),
                    'description' => $point_data['description'],
                    'classes' => $point_data['classes'],
                )
            );

            $this->addControl(
                $point_name . '_width',
                array(
                    'label' => __('Column Width', 'elementor'),
                    'type' => ControlsManager::SELECT,
                    'options' => array(
                        '10' => '10%',
                        '11' => '11%',
                        '12' => '12%',
                        '14' => '14%',
                        '16' => '16%',
                        '20' => '20%',
                        '25' => '25%',
                        '30' => '30%',
                        '33' => '33%',
                        '40' => '40%',
                        '50' => '50%',
                        '60' => '60%',
                        '66' => '66%',
                        '70' => '70%',
                        '75' => '75%',
                        '80' => '80%',
                        '83' => '83%',
                        '90' => '90%',
                        '100' => '100%',
                    ),
                    'default' => '100',
                    'condition' => array(
                        $point_name => array('custom'),
                    ),
                    'prefix_class' => $point_data['class_prefix'],
                    'selectors' => [
                        '{{WRAPPER}}' => 'width: {{VALUE}}%',
                    ],
                )
            );
        }

        $this->endControlsSection();

        Plugin::$instance->controls_manager->addCustomCssControls($this);
    }

    protected function _renderSettings()
    {
        ?>
        <div class="elementor-element-overlay">
            <div class="column-title"></div>
            <div class="elementor-editor-element-settings elementor-editor-column-settings">
                <ul class="elementor-editor-element-settings-list elementor-editor-column-settings-list">
                    <li class="elementor-editor-element-setting elementor-editor-element-trigger">
                        <a href="#" title="<?php _e('Drag Column', 'elementor');?>"><?php _e('Column', 'elementor');?></a>
                    </li>
                    <?php foreach (self::getEditTools() as $edit_tool_name => $edit_tool) : ?>
                        <li class="elementor-editor-element-setting elementor-editor-element-<?php echo $edit_tool_name; ?>">
                            <a href="#" title="<?php _e($edit_tool['title']);?>">
                                <span class="elementor-screen-only"><?php _e($edit_tool['title']);?></span>
                                <i class="<?php echo $edit_tool['icon']; ?>"></i>
                            </a>
                        </li>
                    <?php endforeach;?>
                </ul>
                <ul class="elementor-editor-element-settings-list  elementor-editor-section-settings-list">
                    <li class="elementor-editor-element-setting elementor-editor-element-trigger">
                        <a href="#" title="<?php _e('Drag Section', 'elementor');?>"><?php _e('Section', 'elementor');?></a>
                    </li>
                    <?php foreach (ElementSection::getEditTools() as $edit_tool_name => $edit_tool) : ?>
                        <li class="elementor-editor-element-setting elementor-editor-element-<?php echo $edit_tool_name; ?>">
                            <a href="#" title="<?php _e($edit_tool['title']);?>">
                                <span class="elementor-screen-only"><?php _e($edit_tool['title']);?></span>
                                <i class="<?php echo $edit_tool['icon']; ?>"></i>
                            </a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-column-wrap">
            <div class="elementor-background-overlay"></div>
            <div class="elementor-widget-wrap"></div>
        </div>
        <?php
    }

    public function beforeRender()
    {
        ?>
        <div <?php echo $this->getRenderAttributeString('_wrapper'); ?>>
            <div class="elementor-column-wrap<?php echo $this->getChildren() ? ' elementor-element-populated' : '' ?>">
            <?php if (in_array($this->getSettings('background_overlay_background'), array('classic', 'gradient'))) : ?>
                <div class="elementor-background-overlay"></div>
            <?php endif;?>
                <div class="elementor-widget-wrap">
        <?php
    }

    public function afterRender()
    {
        ?>
                </div>
            </div>
        </div>
        <?php
    }

    protected function _addRenderAttributes()
    {
        parent::_addRenderAttributes();

        $is_inner = $this->getData('isInner');

        $column_type = !empty($is_inner) ? 'inner' : 'top';

        $settings = $this->getSettings();

        $this->addRenderAttribute('_wrapper', 'class', array(
            'elementor-column',
            'elementor-col-' . $settings['_column_size'],
            'elementor-' . $column_type . '-column',
        ));

        $this->addRenderAttribute('_wrapper', 'data-element_type', $this->getName());

        if ($settings['animation']) {
            $this->addRenderAttribute('_wrapper', 'data-animation', $settings['animation']);
        }
    }

    protected function _getDefaultChildType(array $element_data)
    {
        if ('section' === $element_data['elType']) {
            return Plugin::$instance->elements_manager->getElementTypes('section');
        }

        return Plugin::$instance->widgets_manager->getWidgetTypes($element_data['widgetType']);
    }
}
