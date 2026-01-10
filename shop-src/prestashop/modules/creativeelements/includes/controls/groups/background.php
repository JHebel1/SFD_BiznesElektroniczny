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

class GroupControlBackground extends GroupControlBase
{
    protected static $fields;

    private static $background_types;

    public static function getType()
    {
        return 'background';
    }

    public static function getBackgroundTypes()
    {
        if (null === self::$background_types) {
            self::$background_types = self::initBackgroundTypes();
        }

        return self::$background_types;
    }

    private static function initBackgroundTypes()
    {
        return array(
            'none' => array(
                'title' => _x('None', 'Background Control', 'elementor'),
                'icon' => 'fa fa-ban',
            ),
            'classic' => array(
                'title' => _x('Classic', 'Background Control', 'elementor'),
                'icon' => 'fa fa-paint-brush',
            ),
            'gradient' => array(
                'title' => _x('Gradient', 'Background Control', 'elementor'),
                'icon' => 'fa fa-barcode',
            ),
            'video' => array(
                'title' => _x('Background Video', 'Background Control', 'elementor'),
                'icon' => 'fa fa-video-camera',
            ),
        );
    }

    public function initFields()
    {
        $fields = array();

        $fields['background'] = array(
            'label' => _x('Background Type', 'Background Control', 'elementor'),
            'type' => ControlsManager::CHOOSE,
            'label_block' => true,
        );

        $fields['color'] = array(
            'label' => _x('Color', 'Background Control', 'elementor'),
            'type' => ControlsManager::COLOR,
            'default' => '',
            'title' => _x('Background Color', 'Background Control', 'elementor'),
            'selectors' => array(
                '{{SELECTOR}}' => 'background-color: {{VALUE}};',
            ),
            'condition' => array(
                'background' => array('classic', 'gradient'),
            ),
        );

        $fields['color_stop'] = array(
            'label' => _x('Location', 'Background Control', 'elementor'),
            'type' => ControlsManager::SLIDER,
            'size_units' => array('%'),
            'default' => array(
                'unit' => '%',
                'size' => 0,
            ),
            'render_type' => 'ui',
            'separator' => '',
            'condition' => array(
                'background' => array('gradient'),
            ),
            'of_type' => 'gradient',
        );

        $fields['color_b'] = array(
            'label' => _x('Second Color', 'Background Control', 'elementor'),
            'type' => ControlsManager::COLOR,
            'default' => '#f2295b',
            'render_type' => 'ui',
            'condition' => array(
                'background' => array('gradient'),
            ),
            'of_type' => 'gradient',
        );

        $fields['color_b_stop'] = array(
            'label' => _x('Location', 'Background Control', 'elementor'),
            'type' => ControlsManager::SLIDER,
            'size_units' => array('%'),
            'default' => array(
                'unit' => '%',
                'size' => 100,
            ),
            'render_type' => 'ui',
            'separator' => '',
            'condition' => array(
                'background' => array('gradient'),
            ),
            'of_type' => 'gradient',
        );

        $fields['gradient_type'] = array(
            'label' => _x('Type', 'Background Control', 'elementor'),
            'type' => ControlsManager::SELECT,
            'options' => array(
                'linear' => _x('Linear', 'Background Control', 'elementor'),
                'radial' => _x('Radial', 'Background Control', 'elementor'),
            ),
            'default' => 'linear',
            'render_type' => 'ui',
            'condition' => array(
                'background' => array('gradient'),
            ),
            'of_type' => 'gradient',
        );

        $fields['gradient_angle'] = array(
            'label' => _x('Angle', 'Background Control', 'elementor'),
            'type' => ControlsManager::SLIDER,
            'size_units' => array('deg'),
            'default' => array(
                'unit' => 'deg',
                'size' => 180,
            ),
            'range' => array(
                'deg' => array(
                    'step' => 10,
                ),
            ),
            'separator' => '',
            'selectors' => array(
                '{{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
            ),
            'condition' => array(
                'background' => array('gradient'),
                'gradient_type' => 'linear',
            ),
            'of_type' => 'gradient',
        );

        $fields['gradient_position'] = array(
            'label' => _x('Position', 'Background Control', 'elementor'),
            'type' => ControlsManager::SELECT,
            'options' => array(
                'center center' => _x('Center Center', 'Background Control', 'elementor'),
                'center left' => _x('Center Left', 'Background Control', 'elementor'),
                'center right' => _x('Center Right', 'Background Control', 'elementor'),
                'top center' => _x('Top Center', 'Background Control', 'elementor'),
                'top left' => _x('Top Left', 'Background Control', 'elementor'),
                'top right' => _x('Top Right', 'Background Control', 'elementor'),
                'bottom center' => _x('Bottom Center', 'Background Control', 'elementor'),
                'bottom left' => _x('Bottom Left', 'Background Control', 'elementor'),
                'bottom right' => _x('Bottom Right', 'Background Control', 'elementor'),
            ),
            'default' => 'center center',
            'separator' => '',
            'selectors' => array(
                '{{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
            ),
            'condition' => array(
                'background' => array('gradient'),
                'gradient_type' => 'radial',
            ),
            'of_type' => 'gradient',
        );

        $fields['image'] = array(
            'label' => _x('Image', 'Background Control', 'elementor'),
            'type' => ControlsManager::MEDIA,
            'title' => _x('Background Image', 'Background Control', 'elementor'),
            'separator' => '',
            'selectors' => array(
                '{{SELECTOR}}' => 'background-image: url("{{URL}}");',
            ),
            'condition' => array(
                'background' => array('classic'),
            ),
        );

        $fields['position'] = array(
            'label' => _x('Position', 'Background Control', 'elementor'),
            'type' => ControlsManager::SELECT,
            'default' => '',
            'options' => array(
                '' => _x('Default', 'Background Control', 'elementor'),
                'top left' => _x('Top Left', 'Background Control', 'elementor'),
                'top center' => _x('Top Center', 'Background Control', 'elementor'),
                'top right' => _x('Top Right', 'Background Control', 'elementor'),
                'center left' => _x('Center Left', 'Background Control', 'elementor'),
                'center center' => _x('Center Center', 'Background Control', 'elementor'),
                'center right' => _x('Center Right', 'Background Control', 'elementor'),
                'bottom left' => _x('Bottom Left', 'Background Control', 'elementor'),
                'bottom center' => _x('Bottom Center', 'Background Control', 'elementor'),
                'bottom right' => _x('Bottom Right', 'Background Control', 'elementor'),
            ),
            'separator' => '',
            'selectors' => array(
                '{{SELECTOR}}' => 'background-position: {{VALUE}};',
            ),
            'condition' => array(
                'background' => array('classic'),
                'image[url]!' => '',
            ),
        );

        $fields['attachment'] = array(
            'label' => _x('Attachment', 'Background Control', 'elementor'),
            'type' => ControlsManager::SELECT,
            'default' => '',
            'options' => array(
                '' => _x('Default', 'Background Control', 'elementor'),
                'scroll' => _x('Scroll', 'Background Control', 'elementor'),
                'fixed' => _x('Fixed', 'Background Control', 'elementor'),
            ),
            'separator' => '',
            'selectors' => array(
                '{{SELECTOR}}' => 'background-attachment: {{VALUE}};',
            ),
            'condition' => array(
                'background' => array('classic'),
                'image[url]!' => '',
            ),
        );

        $fields['repeat'] = array(
            'label' => _x('Repeat', 'Background Control', 'elementor'),
            'type' => ControlsManager::SELECT,
            'default' => '',
            'options' => array(
                '' => _x('Default', 'Background Control', 'elementor'),
                'no-repeat' => _x('No-repeat', 'Background Control', 'elementor'),
                'repeat' => _x('Repeat', 'Background Control', 'elementor'),
                'repeat-x' => _x('Repeat-x', 'Background Control', 'elementor'),
                'repeat-y' => _x('Repeat-y', 'Background Control', 'elementor'),
            ),
            'separator' => '',
            'selectors' => array(
                '{{SELECTOR}}' => 'background-repeat: {{VALUE}};',
            ),
            'condition' => array(
                'background' => array('classic'),
                'image[url]!' => '',
            ),
        );

        $fields['size'] = array(
            'label' => _x('Size', 'Background Control', 'elementor'),
            'type' => ControlsManager::SELECT,
            'default' => '',
            'options' => array(
                '' => _x('Default', 'Background Control', 'elementor'),
                'auto' => _x('Auto', 'Background Control', 'elementor'),
                'cover' => _x('Cover', 'Background Control', 'elementor'),
                'contain' => _x('Contain', 'Background Control', 'elementor'),
            ),
            'separator' => '',
            'selectors' => array(
                '{{SELECTOR}}' => 'background-size: {{VALUE}};',
            ),
            'condition' => array(
                'background' => array('classic'),
                'image[url]!' => '',
            ),
        );

        $fields['video_link'] = array(
            'label' => _x('Video Link', 'Background Control', 'elementor'),
            'type' => ControlsManager::TEXT,
            'placeholder' => 'https://www.youtube.com/watch?v=9uOETcuFjbE',
            'description' => __('Insert YouTube link or video file (mp4 is recommended)', 'elementor'),
            'label_block' => true,
            'default' => '',
            'condition' => array(
                'background' => array('video'),
            ),
            'of_type' => 'video',
        );

        $fields['video_fallback'] = array(
            'label' => _x('Background Fallback', 'Background Control', 'elementor'),
            'description' => __('This cover image will replace the background video on mobile or tablet.', 'elementor'),
            'type' => ControlsManager::MEDIA,
            'label_block' => true,
            'separator' => '',
            'condition' => array(
                'background' => array('video'),
            ),
            'of_type' => 'video',
        );

        return $fields;
    }

    protected function getChildDefaultArgs()
    {
        return array(
            'types' => array('none', 'classic'),
        );
    }

    protected function filterFields()
    {
        $fields = parent::filterFields();

        $args = $this->getArgs();

        foreach ($fields as &$field) {
            if (isset($field['of_type']) && !in_array($field['of_type'], $args['types'])) {
                unset($field);
            }
        }

        return $fields;
    }

    protected function prepareFields($fields)
    {
        $args = $this->getArgs();

        $background_types = self::getBackgroundTypes();

        $choose_types = array();

        foreach ($args['types'] as $type) {
            if (isset($background_types[$type])) {
                $choose_types[$type] = $background_types[$type];
            }
        }

        $fields['background']['options'] = $choose_types;

        return parent::prepareFields($fields);
    }
}
