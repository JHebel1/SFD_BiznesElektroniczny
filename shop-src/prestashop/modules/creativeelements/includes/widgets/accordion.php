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

class WidgetAccordion extends WidgetBase
{
    public function getName()
    {
        return 'accordion';
    }

    public function getTitle()
    {
        return __('Accordion', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-accordion';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_title',
            array(
                'label' => __('Accordion', 'elementor'),
            )
        );

        $this->addControl(
            'tabs',
            array(
                'label' => __('Accordion Items', 'elementor'),
                'type' => ControlsManager::REPEATER,
                'default' => array(
                    array(
                        'tab_title' => __('Accordion #1', 'elementor'),
                        'tab_content' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor'),
                    ),
                    array(
                        'tab_title' => __('Accordion #2', 'elementor'),
                        'tab_content' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor'),
                    ),
                ),
                'fields' => array(
                    array(
                        'name' => 'tab_title',
                        'label' => __('Title & Content', 'elementor'),
                        'type' => ControlsManager::TEXT,
                        'default' => __('Accordion Title', 'elementor'),
                        'label_block' => true,
                    ),
                    array(
                        'name' => 'tab_content',
                        'label' => __('Content', 'elementor'),
                        'type' => ControlsManager::WYSIWYG,
                        'default' => __('Accordion Content', 'elementor'),
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
            'section_title_style',
            array(
                'label' => __('Accordion', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'icon_align',
            array(
                'label' => __('Icon Alignment', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => is_rtl() ? 'right' : 'left',
                'options' => array(
                    'left' => __('Left', 'elementor'),
                    'right' => __('Right', 'elementor'),
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
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-item' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-content' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-wrapper .elementor-accordion-title.active > span' => 'border-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-item' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-content' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-wrapper .elementor-accordion-title.active > span' => 'border-bottom-color: {{VALUE}};',
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
            'title_background',
            array(
                'label' => __('Background', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-title' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-title.active' => 'color: {{VALUE}};',
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
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-accordion .elementor-accordion-title',
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
            'content_background_color',
            array(
                'label' => __('Background', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-content' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'content_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-content' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .elementor-accordion .elementor-accordion-content',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();
        ?>
        <div class="elementor-accordion" role="tablist">
            <?php $counter = 1;?>
            <?php foreach ($settings['tabs'] as $item) : ?>
                <div class="elementor-accordion-item">
                    <div class="elementor-accordion-title" data-section="<?php echo $counter; ?>" role="tab">
                        <span class="elementor-accordion-icon elementor-accordion-icon-<?php echo $settings['icon_align']; ?>">
                            <i class="fa"></i>
                        </span>
                        <?php echo $item['tab_title']; ?>
                    </div>
                    <div class="elementor-accordion-content elementor-clearfix" data-section="<?php echo $counter; ?>" role="tabpanel"><?php echo $this->parseTextEditor($item['tab_content']); ?></div>
                </div>
                <?php $counter++;?>
            <?php endforeach;?>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-accordion" data-active-section="{{ editSettings.activeItemIndex ? editSettings.activeItemIndex : 0 }}" role="tablist">
            <#
            if ( settings.tabs ) {
                var counter = 1;
                _.each( settings.tabs, function( item ) { #>
                    <div class="elementor-accordion-item">
                        <div class="elementor-accordion-title" data-section="{{ counter }}" role="tab">
                            <span class="elementor-accordion-icon elementor-accordion-icon-{{ settings.icon_align }}">
                                <i class="fa"></i>
                            </span>
                            {{{ item.tab_title }}}
                        </div>
                        <div class="elementor-accordion-content elementor-clearfix" data-section="{{ counter }}" role="tabpanel">{{{ item.tab_content }}}</div>
                    </div>
                <#
                    counter++;
                } );
            } #>
        </div>
        <?php
    }
}
