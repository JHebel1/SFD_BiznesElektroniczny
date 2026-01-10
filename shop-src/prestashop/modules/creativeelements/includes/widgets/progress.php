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

class WidgetProgress extends WidgetBase
{
    public function getName()
    {
        return 'progress';
    }

    public function getTitle()
    {
        return __('Progress Bar', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-skill-bar';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_progress',
            array(
                'label' => __('Progress Bar', 'elementor'),
            )
        );

        $this->addControl(
            'title',
            array(
                'label' => __('Title', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your title', 'elementor'),
                'default' => __('My Skill', 'elementor'),
                'label_block' => true,
            )
        );

        $this->addControl(
            'progress_type',
            array(
                'label' => __('Type', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => array(
                    '' => __('Default', 'elementor'),
                    'info' => __('Info', 'elementor'),
                    'success' => __('Success', 'elementor'),
                    'warning' => __('Warning', 'elementor'),
                    'danger' => __('Danger', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'percent',
            array(
                'label' => __('Percentage', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 50,
                    'unit' => '%',
                ),
                'label_block' => true,
            )
        );

        $this->addControl(
            'display_percentage',
            array(
                'label' => __('Display Percentage', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'show',
                'options' => array(
                    'show' => __('Show', 'elementor'),
                    'hide' => __('Hide', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'inner_text',
            array(
                'label' => __('Inner Text', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('e.g. Web Designer', 'elementor'),
                'default' => __('Web Designer', 'elementor'),
                'label_block' => true,
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
            'section_progress_style',
            array(
                'label' => __('Progress Bar', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'bar_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-progress-wrapper .elementor-progress-bar' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'bar_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-progress-wrapper' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'bar_inline_color',
            array(
                'label' => __('Inner Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-progress-bar' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_title',
            array(
                'label' => __('Title Style', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-title' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .elementor-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        $this->addRenderAttribute('wrapper', 'class', 'elementor-progress-wrapper');

        if (!empty($settings['progress_type'])) {
            $this->addRenderAttribute('wrapper', 'class', 'progress-' . $settings['progress_type']);
        }

        $this->addRenderAttribute('progress-bar', array(
            'class' => 'elementor-progress-bar',
            'data-max' => $settings['percent']['size'],
        ));
        ?>
        <?php if (!empty($settings['title'])) : ?>
            <span class="elementor-title"><?php echo $settings['title']; ?></span>
        <?php endif; ?>
        <div <?php echo $this->getRenderAttributeString('wrapper'); ?> role="timer">
            <div <?php echo $this->getRenderAttributeString('progress-bar'); ?>>
                <span class="elementor-progress-text"><?php echo $settings['inner_text']; ?></span>
                <?php if ('hide' !== $settings['display_percentage']) : ?>
                    <span class="elementor-progress-percentage"><?php echo $settings['percent']['size']; ?>%</span>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <# if ( settings.title ) { #>
            <span class="elementor-title">{{{ settings.title }}}</span>
        <# } #>
        <div class="elementor-progress-wrapper progress-{{ settings.progress_type }}" role="timer">
            <div class="elementor-progress-bar" data-max="{{ settings.percent.size }}">
                <span class="elementor-progress-text">{{{ settings.inner_text }}}</span>
            <# if ( 'hide' !== settings.display_percentage ) { #>
                <span class="elementor-progress-percentage">{{{ settings.percent.size }}}%</span>
            <# } #>
            </div>
        </div>
        <?php
    }
}
