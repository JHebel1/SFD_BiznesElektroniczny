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

class WidgetTextEditor extends WidgetBase
{
    public function getName()
    {
        return 'text-editor';
    }

    public function getTitle()
    {
        return __('Text Editor', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-align-left';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_editor',
            array(
                'label' => __('Text Editor', 'elementor'),
            )
        );

        $this->addControl(
            'editor',
            array(
                'label' => '',
                'type' => ControlsManager::WYSIWYG,
                'default' => '<p>' . __('I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor') . '</p>',
            )
        );

        $this->addControl(
            'drop_cap',
            array(
                'label' => __('Drop Cap', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Off', 'elementor'),
                'label_on' => __('On', 'elementor'),
                'prefix_class' => 'elementor-drop-cap-',
                'frontend_available' => true,
                'separator' => '',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style',
            array(
                'label' => __('Text Editor', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
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
                    'justify' => array(
                        'title' => __('Justified', 'elementor'),
                        'icon' => 'fa fa-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-text-editor' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}}' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $text_columns = range(1, 10);
        $text_columns = array_combine($text_columns, $text_columns);
        $text_columns[''] = __('Default', 'elementor');

        $this->addResponsiveControl(
            'text_columns',
            array(
                'label' => __('Columns', 'elementor'),
                'type' => ControlsManager::SELECT,
                'separator' => 'before',
                'options' => $text_columns,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-text-editor' => 'columns: {{VALUE}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'column_gap',
            array(
                'label' => __('Columns Gap', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', '%', 'em', 'vw'),
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                    '%' => array(
                        'max' => 10,
                        'step' => 0.1,
                    ),
                    'vw' => array(
                        'max' => 10,
                        'step' => 0.1,
                    ),
                    'em' => array(
                        'max' => 10,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-text-editor' => 'column-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_drop_cap',
            array(
                'label' => __('Drop Cap', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'drop_cap' => 'yes',
                ),
            )
        );

        $this->addControl(
            'drop_cap_view',
            array(
                'label' => __('View', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'default' => __('Default', 'elementor'),
                    'stacked' => __('Stacked', 'elementor'),
                    'framed' => __('Framed', 'elementor'),
                ),
                'default' => 'default',
                'prefix_class' => 'elementor-drop-cap-view-',
                'condition' => array(
                    'drop_cap' => 'yes',
                ),
            )
        );

        $this->addControl(
            'drop_cap_primary_color',
            array(
                'label' => __('Primary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}.elementor-drop-cap-view-stacked .elementor-drop-cap' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-drop-cap-view-framed .elementor-drop-cap, {{WRAPPER}}.elementor-drop-cap-view-default .elementor-drop-cap' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'condition' => array(
                    'drop_cap' => 'yes',
                ),
            )
        );

        $this->addControl(
            'drop_cap_secondary_color',
            array(
                'label' => __('Secondary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}.elementor-drop-cap-view-framed .elementor-drop-cap' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-drop-cap-view-stacked .elementor-drop-cap' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'drop_cap_view!' => 'default',
                ),
            )
        );

        $this->addControl(
            'drop_cap_size',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 5,
                ),
                'range' => array(
                    'px' => array(
                        'max' => 30,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-drop-cap' => 'padding: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'drop_cap_view!' => 'default',
                ),
            )
        );

        $this->addControl(
            'drop_cap_space',
            array(
                'label' => __('Space', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 10,
                ),
                'range' => array(
                    'px' => array(
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    'body:not(.rtl) {{WRAPPER}} .elementor-drop-cap' => 'margin-right: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .elementor-drop-cap' => 'margin-left: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'drop_cap_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('%', 'px'),
                'default' => array(
                    'unit' => '%',
                ),
                'range' => array(
                    '%' => array(
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-drop-cap' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'drop_cap_border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-drop-cap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'drop_cap_view' => 'framed',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'drop_cap_typography',
                'selector' => '{{WRAPPER}} .elementor-drop-cap-letter',
                'exclude' => array(
                    'letter_spacing',
                ),
                'condition' => array(
                    'drop_cap' => 'yes',
                ),
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $editor_content = $this->getSettings('editor');

        $editor_content = $this->parseTextEditor($editor_content);
        ?>
        <div class="elementor-text-editor elementor-clearfix"><?php echo $editor_content; ?></div>
        <?php
    }

    public function renderPlainContent()
    {
        // In plain mode, render without shortcode
        echo $this->getSettings('editor');
    }

    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-text-editor elementor-clearfix">{{{ settings.editor }}}</div>
        <?php
    }
}
