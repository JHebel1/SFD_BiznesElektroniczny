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

class WidgetTabs extends WidgetBase
{
    public function getName()
    {
        return 'tabs';
    }

    public function getTitle()
    {
        return __('Tabs', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-tabs';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_tabs',
            array(
                'label' => __('Tabs', 'elementor'),
            )
        );

        $this->addControl(
            'type',
            array(
                'label' => __('Type', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'horizontal',
                'options' => array(
                    'horizontal' => __('Horizontal', 'elementor'),
                    'vertical' => __('Vertical', 'elementor'),
                ),
                'prefix_class' => 'elementor-tabs-view-',
            )
        );

        $this->addControl(
            'tabs',
            array(
                'label' => __('Tabs Items', 'elementor'),
                'type' => ControlsManager::REPEATER,
                'default' => array(
                    array(
                        'tab_title' => __('Tab #1', 'elementor'),
                        'tab_content' => __('I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor'),
                    ),
                    array(
                        'tab_title' => __('Tab #2', 'elementor'),
                        'tab_content' => __('I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor'),
                    ),
                ),
                'fields' => array(
                    array(
                        'name' => 'tab_title',
                        'label' => __('Title & Content', 'elementor'),
                        'type' => ControlsManager::TEXT,
                        'default' => __('Tab Title', 'elementor'),
                        'placeholder' => __('Tab Title', 'elementor'),
                        'label_block' => true,
                    ),
                    array(
                        'name' => 'tab_content',
                        'label' => __('Content', 'elementor'),
                        'default' => __('Tab Content', 'elementor'),
                        'type' => ControlsManager::WYSIWYG,
                        'show_label' => false,
                    ),
                ),
                'title_field' => '{{{ tab_title }}}',
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
            'section_tabs_style',
            array(
                'label' => __('Tabs', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'navigation_width',
            array(
                'label' => __('Navigation Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'unit' => '%',
                ),
                'range' => array(
                    '%' => array(
                        'min' => 10,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-tabs-wrapper' => 'width: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'type' => 'vertical',
                ),
            )
        );

        $this->addControl(
            'border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 1,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 10,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-tab-title, {{WRAPPER}} .elementor-tab-title:before, {{WRAPPER}} .elementor-tab-title:after, ' .
                    '{{WRAPPER}} .elementor-tab-content, {{WRAPPER}} .elementor-tabs-content-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-tab-mobile-title, {{WRAPPER}} .elementor-tab-desktop-title.active, ' .
                    '{{WRAPPER}} .elementor-tab-title:before, {{WRAPPER}} .elementor-tab-title:after, ' .
                    '{{WRAPPER}} .elementor-tab-content, {{WRAPPER}} .elementor-tabs-content-wrapper' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'background_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-tab-desktop-title.active' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-tabs-content-wrapper' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'heading_title',
            array(
                'label' => __('Title', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'tab_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-tab-title' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
            )
        );

        $this->addControl(
            'tab_active_color',
            array(
                'label' => __('Active Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-tab-title.active' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .elementor-tab-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            )
        );

        $this->addControl(
            'heading_content',
            array(
                'label' => __('Content', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'content_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-tab-content' => 'color: {{VALUE}};',
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
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .elementor-tab-content',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $tabs = $this->getSettings('tabs');
        ?>
        <div class="elementor-tabs" role="tablist">
            <?php $counter = 1;?>
            <div class="elementor-tabs-wrapper" role="tab">
                <?php foreach ($tabs as $item) : ?>
                    <div class="elementor-tab-title elementor-tab-desktop-title" data-tab="<?php echo $counter++; ?>"><?php echo $item['tab_title']; ?></div>
                <?php endforeach;?>
            </div>

            <?php $counter = 1;?>
            <div class="elementor-tabs-content-wrapper" role="tabpanel">
                <?php foreach ($tabs as $item) : ?>
                    <div class="elementor-tab-title elementor-tab-mobile-title" data-tab="<?php echo $counter; ?>"><?php echo $item['tab_title']; ?></div>
                    <div class="elementor-tab-content elementor-clearfix" data-tab="<?php echo $counter++; ?>"><?php echo $this->parseTextEditor($item['tab_content']); ?></div>
                <?php endforeach;?>
            </div>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-tabs" data-active-tab="{{ editSettings.activeItemIndex ? editSettings.activeItemIndex : 0 }}" role="tablist">
            <# if ( settings.tabs ) {
                var counter = 1; #>
                <div class="elementor-tabs-wrapper" role="tab">
                    <#
                    _.each( settings.tabs, function( item ) { #>
                        <div class="elementor-tab-title elementor-tab-desktop-title" data-tab="{{ counter }}">{{{ item.tab_title }}}</div>
                        <#
                        counter++;
                    } ); #>
                </div>

                <# counter = 1; #>
                <div class="elementor-tabs-content-wrapper" role="tabpanel">
                    <#
                    _.each( settings.tabs, function( item ) { #>
                        <div class="elementor-tab-title elementor-tab-mobile-title" data-tab="{{ counter }}">{{{ item.tab_title }}}</div>
                        <div class="elementor-tab-content elementor-clearfix elementor-repeater-item-{{ item._id }}" data-tab="{{ counter }}">{{{ item.tab_content }}}</div>
                        <#
                        counter++;
                    } ); #>
                </div>
            <# } #>
        </div>
        <?php
    }
}
