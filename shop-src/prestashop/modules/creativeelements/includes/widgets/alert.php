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

class WidgetAlert extends WidgetBase
{
    public function getName()
    {
        return 'alert';
    }

    public function getTitle()
    {
        return __('Alert', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-alert';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_alert',
            array(
                'label' => __('Alert', 'elementor'),
            )
        );

        $this->addControl(
            'alert_type',
            array(
                'label' => __('Type', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'info',
                'options' => array(
                    'info' => __('Info', 'elementor'),
                    'success' => __('Success', 'elementor'),
                    'warning' => __('Warning', 'elementor'),
                    'danger' => __('Danger', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'alert_title',
            array(
                'label' => __('Title & Description', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Your Title', 'elementor'),
                'default' => __('This is Alert', 'elementor'),
                'label_block' => true,
            )
        );

        $this->addControl(
            'alert_description',
            array(
                'label' => __('Content', 'elementor'),
                'type' => ControlsManager::TEXTAREA,
                'placeholder' => __('Your Description', 'elementor'),
                'default' => __('I am description. Click edit button to change this text.', 'elementor'),
                'separator' => 'none',
                'show_label' => false,
            )
        );

        $this->addControl(
            'show_dismiss',
            array(
                'label' => __('Dismiss Button', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'show',
                'options' => array(
                    'show' => __('Show', 'elementor'),
                    'hide' => __('Hide', 'elementor'),
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

        $this->startControlsSection(
            'section_type',
            array(
                'label' => __('Alert Type', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'background',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-alert' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-alert' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'border_left-width',
            array(
                'label' => __('Left Border Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-alert' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ),
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
                'selectors' => array(
                    '{{WRAPPER}} .elementor-alert-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'alert_title',
                'selector' => '{{WRAPPER}} .elementor-alert-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_description',
            array(
                'label' => __('Description', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'description_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-alert-description' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'alert_description',
                'selector' => '{{WRAPPER}} .elementor-alert-description',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );
    }

    protected function render()
    {
        $settings = $this->getSettings();

        if (empty($settings['alert_title'])) {
            return;
        }

        if (!empty($settings['alert_type'])) {
            $this->addRenderAttribute('wrapper', 'class', 'elementor-alert elementor-alert-' . $settings['alert_type']);
        }

        echo '<div ' . $this->getRenderAttributeString('wrapper') . ' role="alert">';
        $html = sprintf('<span class="elementor-alert-title">%1$s</span>', $settings['alert_title']);

        if (!empty($settings['alert_description'])) {
            $html .= sprintf('<span class="elementor-alert-description">%s</span>', $settings['alert_description']);
        }

        if (!empty($settings['show_dismiss']) && 'show' === $settings['show_dismiss']) {
            $html .= '<button type="button" class="elementor-alert-dismiss">X</button>';
        }

        echo $html;

        echo '</div>';
    }

    protected function _contentTemplate()
    {
        ?>
        <#
        var html = '<div class="elementor-alert elementor-alert-' + settings.alert_type + '" role="alert">';
        if ( '' !== settings.title ) {
            html += '<span class="elementor-alert-title">' + settings.alert_title + '</span>';

            if ( '' !== settings.description ) {
                html += '<span class="elementor-alert-description">' + settings.alert_description + '</span>';
            }

            if ( 'show' === settings.show_dismiss ) {
                html += '<button type="button" class="elementor-alert-dismiss">X</button>';
            }

            html += '</div>';

            print( html );
        }
        #>
        <?php
    }
}
