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

class WidgetIconList extends WidgetBase
{
    public function getName()
    {
        return 'icon-list';
    }

    public function getTitle()
    {
        return __('Icon List', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-bullet-list';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_icon',
            array(
                'label' => __('Icon List', 'elementor'),
            )
        );

        $this->addControl(
            'icon_list',
            array(
                'label' => '',
                'type' => ControlsManager::REPEATER,
                'default' => array(
                    array(
                        'text' => __('List Item #1', 'elementor'),
                        'icon' => 'fa fa-check',
                    ),
                    array(
                        'text' => __('List Item #2', 'elementor'),
                        'icon' => 'fa fa-times',
                    ),
                    array(
                        'text' => __('List Item #3', 'elementor'),
                        'icon' => 'fa fa-dot-circle-o',
                    ),
                ),
                'fields' => array(
                    array(
                        'name' => 'text',
                        'label' => __('Text', 'elementor'),
                        'type' => ControlsManager::TEXT,
                        'label_block' => true,
                        'placeholder' => __('List Item', 'elementor'),
                        'default' => __('List Item', 'elementor'),
                    ),
                    array(
                        'name' => 'icon',
                        'label' => __('Icon', 'elementor'),
                        'type' => ControlsManager::ICON,
                        'label_block' => true,
                        'default' => 'fa fa-check',
                    ),
                    array(
                        'name' => 'link',
                        'label' => __('Link', 'elementor'),
                        'type' => ControlsManager::URL,
                        'label_block' => true,
                        'placeholder' => __('http://your-link.com', 'elementor'),
                    ),
                ),
                'title_field' => '<i class="{{ icon }}"></i> {{{ text }}}',
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

        $this->startControlsSection(
            'section_icon_list',
            array(
                'label' => __('List', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'space_between',
            array(
                'label' => __('Space Between', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
                ),
            )
        );

        $this->addResponsiveControl(
            'icon_align',
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
                'prefix_class' => 'elementor-align-',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-item, {{WRAPPER}} .elementor-icon-list-item a' => 'justify-content: {{VALUE}};',
                ),
                'selectors_dictionary' => array(
                    'left' => 'flex-start',
                    'right' => 'flex-end',
                ),
            )
        );

        $this->addControl(
            'divider',
            array(
                'label' => __('Divider', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Off', 'elementor'),
                'label_on' => __('On', 'elementor'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'content: ""',
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'divider_style',
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
                'condition' => array(
                    'divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'divider_weight',
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
                'condition' => array(
                    'divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'divider_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '#ddd',
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
                'condition' => array(
                    'divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-top-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'divider_width',
            array(
                'label' => __('Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'units' => array('%'),
                'default' => array(
                    'unit' => '%',
                ),
                'condition' => array(
                    'divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_icon_style',
            array(
                'label' => __('Icon', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'icon_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-icon i' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
            )
        );

        $this->addControl(
            'icon_size',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 14,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 6,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-icon' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-icon-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_text_style',
            array(
                'label' => __('Text', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'text_indent',
            array(
                'label' => __('Text Indent', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-icon-list-text' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'icon_typography',
                'label' => __('Typography', 'elementor'),
                'selector' => '{{WRAPPER}} .elementor-icon-list-item',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();
        ?>
        <ul class="elementor-icon-list-items">
            <?php foreach ($settings['icon_list'] as $item) : ?>
                <li class="elementor-icon-list-item" >
                    <?php
                    if (!empty($item['link']['url'])) {
                        $target = $item['link']['is_external'] ? ' target="_blank"' : '';
                        echo '<a href="' . $item['link']['url'] . '"' . $target . '>';
                    }
                    ?>
                    <?php if ($item['icon']) : ?>
                        <span class="elementor-icon-list-icon">
                            <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                        </span>
                    <?php endif;?>
                    <span class="elementor-icon-list-text"><?php echo $item['text']; ?></span>
                    <?php
                    if (!empty($item['link']['url'])) {
                        echo '</a>';
                    }
                    ?>
                </li>
            <?php endforeach;?>
        </ul>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <ul class="elementor-icon-list-items">
            <#
            if ( settings.icon_list ) {
                _.each( settings.icon_list, function( item ) { #>
                    <li class="elementor-icon-list-item">
                        <# if ( item.link && item.link.url ) { #>
                            <a href="{{ item.link.url }}">
                        <# } #>
                        <span class="elementor-icon-list-icon">
                            <i class="{{ item.icon }}"></i>
                        </span>
                        <span class="elementor-icon-list-text">{{{ item.text }}}</span>
                        <# if ( item.link && item.link.url ) { #>
                            </a>
                        <# } #>
                    </li>
                <#
                } );
            } #>
        </ul>
        <?php
    }
}
