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

class WidgetToggle extends WidgetBase
{
    public function getName()
    {
        return 'toggle';
    }

    public function getTitle()
    {
        return __('Toggle', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-toggle';
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
                'label' => __('Toggle', 'elementor'),
            )
        );

        $this->addControl(
            'tabs',
            array(
                'label' => __('Toggle Items', 'elementor'),
                'type' => ControlsManager::REPEATER,
                'default' => array(
                    array(
                        'tab_title' => __('Toggle #1', 'elementor'),
                        'tab_content' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor'),
                    ),
                    array(
                        'tab_title' => __('Toggle #2', 'elementor'),
                        'tab_content' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor'),
                    ),
                ),
                'fields' => array(
                    array(
                        'name' => 'tab_title',
                        'label' => __('Title & Content', 'elementor'),
                        'type' => ControlsManager::TEXT,
                        'label_block' => true,
                        'default' => __('Toggle Title', 'elementor'),
                    ),
                    array(
                        'name' => 'tab_content',
                        'label' => __('Content', 'elementor'),
                        'type' => ControlsManager::WYSIWYG,
                        'default' => __('Toggle Content', 'elementor'),
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
                'label' => __('Toggle', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
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
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-title' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-content' => 'border-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-content' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-title' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-title' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-title.active' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .elementor-toggle .elementor-toggle-title',
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
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-content' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'content_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-content' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .elementor-toggle .elementor-toggle-content',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $tabs = $this->getSettings('tabs');
        ?>
        <div class="elementor-toggle">
            <?php $counter = 1;?>
            <?php foreach ($tabs as $item) : ?>
                <div class="elementor-toggle-title" data-tab="<?php echo $counter; ?>">
                    <span class="elementor-toggle-icon">
                        <i class="fa"></i>
                    </span>
                    <?php echo $item['tab_title']; ?>
                </div>
                <div class="elementor-toggle-content elementor-clearfix" data-tab="<?php echo $counter++; ?>"><?php echo $this->parseTextEditor($item['tab_content']); ?></div>
            <?php endforeach;?>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-toggle">
            <#
            if ( settings.tabs ) {
                var counter = 1;
                _.each(settings.tabs, function( item ) { #>
                    <div class="elementor-toggle-title" data-tab="{{ counter }}">
                        <span class="elementor-toggle-icon">
                        <i class="fa"></i>
                    </span>
                        {{{ item.tab_title }}}
                    </div>
                    <div class="elementor-toggle-content elementor-clearfix" data-tab="{{ counter }}">{{{ item.tab_content }}}</div>
                <#
                    counter++;
                } );
            } #>
        </div>
        <?php
    }
}
