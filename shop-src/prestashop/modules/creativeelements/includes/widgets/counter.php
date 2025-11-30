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

class WidgetCounter extends WidgetBase
{
    public function getName()
    {
        return 'counter';
    }

    public function getTitle()
    {
        return __('Counter', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-counter';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    public function getScriptDepends()
    {
        return array('jquery-numerator');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_counter',
            array(
                'label' => __('Counter', 'elementor'),
            )
        );

        $this->addControl(
            'starting_number',
            array(
                'label' => __('Starting Number', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'default' => 0,
            )
        );

        $this->addControl(
            'ending_number',
            array(
                'label' => __('Ending Number', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'default' => 100,
            )
        );

        $this->addControl(
            'prefix',
            array(
                'label' => __('Number Prefix', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => '',
                'placeholder' => 1,
            )
        );

        $this->addControl(
            'suffix',
            array(
                'label' => __('Number Suffix', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => '',
                'placeholder' => __('Plus', 'elementor'),
            )
        );

        $this->addControl(
            'duration',
            array(
                'label' => __('Animation Duration', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'default' => 2000,
                'min' => 100,
                'step' => 100,
            )
        );

        $this->addControl(
            'thousand_separator',
            array(
                'label' => __('Thousand Separator', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
            )
        );

        $this->addControl(
            'title',
            array(
                'label' => __('Title', 'elementor'),
                'type' => ControlsManager::TEXT,
                'label_block' => true,
                'default' => __('Cool Number', 'elementor'),
                'placeholder' => __('Cool Number', 'elementor'),
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
            'section_number',
            array(
                'label' => __('Number', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'number_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-counter-number-wrapper' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'typography_number',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-counter-number-wrapper',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_title',
            array(
                'label' => __('Title', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-counter-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'typography_title',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-counter-title',
            )
        );

        $this->endControlsSection();
    }

    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-counter">
            <div class="elementor-counter-number-wrapper">
                <span class="elementor-counter-number-prefix">{{{ settings.prefix }}}</span>
                <span class="elementor-counter-number" data-duration="{{ settings.duration }}" data-to-value="{{ settings.ending_number }}" data-delimiter="{{ settings.thousand_separator ? ',' : '' }}">{{{ settings.starting_number }}}</span>
                <span class="elementor-counter-number-suffix">{{{ settings.suffix }}}</span>
            </div>
            <# if ( settings.title ) { #>
                <div class="elementor-counter-title">{{{ settings.title }}}</div>
            <# } #>
        </div>
        <?php
    }

    public function render()
    {
        $settings = $this->getSettings();

        $this->addRenderAttribute('counter', array(
            'class' => 'elementor-counter-number',
            'data-duration' => $settings['duration'],
            'data-to-value' => $settings['ending_number'],
        ));

        if (!empty($settings['thousand_separator'])) {
            $this->addRenderAttribute('counter', 'data-delimiter', ',');
        }
        ?>
        <div class="elementor-counter">
            <div class="elementor-counter-number-wrapper">
                <span class="elementor-counter-number-prefix"><?php echo $settings['prefix']; ?></span>
                <span <?php echo $this->getRenderAttributeString('counter'); ?>><?php echo $settings['starting_number']; ?></span>
                <span class="elementor-counter-number-suffix"><?php echo $settings['suffix']; ?></span>
            </div>
            <?php if ($settings['title']) : ?>
                <div class="elementor-counter-title"><?php echo $settings['title']; ?></div>
            <?php endif;?>
        </div>
        <?php
    }
}
