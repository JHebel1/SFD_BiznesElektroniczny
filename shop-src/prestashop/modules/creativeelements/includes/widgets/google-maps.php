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

class WidgetGoogleMaps extends WidgetBase
{
    public function getName()
    {
        return 'google_maps';
    }

    public function getTitle()
    {
        return __('Google Maps', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-google-maps';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_map',
            array(
                'label' => __('Map', 'elementor'),
            )
        );

        $default_address = __('London Eye, London, United Kingdom', 'elementor');
        $this->addControl(
            'address',
            array(
                'label' => __('Address', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $default_address,
                'default' => $default_address,
                'label_block' => true,
            )
        );

        $this->addControl(
            'zoom',
            array(
                'label' => __('Zoom Level', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 10,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 20,
                    ),
                ),
            )
        );

        $this->addControl(
            'height',
            array(
                'label' => __('Height', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 300,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 40,
                        'max' => 1440,
                    ),
                ),
                'selectors' => array(
                    "{{WRAPPER}} \x69frame" => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'prevent_scroll',
            array(
                'label' => __('Prevent Scroll', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'selectors' => array(
                    "{{WRAPPER}} \x69frame" => 'pointer-events: none;',
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
    }

    protected function render()
    {
        $settings = $this->getSettings();

        if (empty($settings['address'])) {
            return;
        }

        if (0 === absint($settings['zoom']['size'])) {
            $settings['zoom']['size'] = 10;
        }

        printf(
            '<div class="elementor-custom-embed">' .
            '<%s src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near"' .
            ' frameborder="0" scrolling="no" marginheight="0" marginwidth="0" loading="lazy"></%1$s>' .
            '</div>',
            "\x69frame",
            urlencode($settings['address']),
            absint($settings['zoom']['size'])
        );
    }
}
