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

class GroupControlImageSize extends GroupControlBase
{
    protected static $fields;

    public static function getType()
    {
        return 'image-size';
    }

    /**
     * @param array  $settings [ image => [ url => '', alt => '', title => '' ], hover_animation => '' ]
     * @param string $setting_key
     * @param string $loading
     *
     * @return string
     */
    public static function getAttachmentImageHtml($settings, $setting_key = 'image', $loading = 'lazy')
    {
        if (empty($settings[$setting_key]['url'])) {
            return '';
        }
        $attr = array(
            'src="' . \Tools::safeOutput(Helper::getMediaLink($settings[$setting_key]['url'])) . '"',
            'loading="' . $loading . '"',
            'alt="' . ControlMedia::getImageAlt($settings[$setting_key]) . '"',
        );
        if ($title = ControlMedia::getImageTitle($settings[$setting_key])) {
            $attr[] = 'title="' . $title . '"';
        }
        empty($settings['hover_animation']) or $attr[] = 'class="elementor-animation-' . $settings['hover_animation'] . '"';

        return '<img ' . implode(' ', $attr) . '>';
    }

    // public static function getAllImageSizes() { ... }

    protected function getChildDefaultArgs()
    {
        return array(
            'include' => array(),
            'exclude' => array(),
        );
    }

    private function _getImageSizes()
    {
        // TODO
        return array();
    }

    protected function initFields()
    {
        $fields = array();

        $fields['size'] = array(
            'label' => _x('Image Size', 'Image Size Control', 'elementor'),
            'type' => ControlsManager::SELECT,
            'label_block' => false,
        );

        $fields['custom_dimension'] = array(
            'label' => _x('Image Dimension', 'Image Size Control', 'elementor'),
            'type' => ControlsManager::IMAGE_DIMENSIONS,
            'description' => __('You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.', 'elementor'),
            'condition' => array(
                'size' => array('custom'),
            ),
            'separator' => 'none',
        );

        return $fields;
    }

    protected function prepareFields($fields)
    {
        $image_sizes = $this->_getImageSizes();

        $args = $this->getArgs();

        if (!empty($args['default']) && isset($image_sizes[$args['default']])) {
            $default_value = $args['default'];
        } else {
            // Get the first item for default value
            $default_value = array_keys($image_sizes);
            $default_value = array_shift($default_value);
        }

        $fields['size']['options'] = $image_sizes;

        $fields['size']['default'] = $default_value;

        if (!isset($image_sizes['custom'])) {
            unset($fields['custom_dimension']);
        }

        return parent::prepareFields($fields);
    }

    public static function getAttachmentImageSrc($attachment_id, $group_name, $instance)
    {
        return false;
    }
}
