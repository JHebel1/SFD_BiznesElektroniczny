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

class WidgetImageGallery extends WidgetBase
{
    public function getName()
    {
        return 'image-gallery';
    }

    public function getTitle()
    {
        return __('Image Gallery', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-gallery-grid';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_gallery',
            array(
                'label' => __('Image Gallery', 'elementor'),
            )
        );

        $this->addControl(
            'wp_gallery',
            array(
                'label' => __('Add Images', 'elementor'),
                'type' => ControlsManager::GALLERY,
            )
        );

        $this->addGroupControl(
            GroupControlImageSize::getType(),
            array(
                'name' => 'thumbnail',
                'exclude' => array('custom'),
            )
        );

        $gallery_columns = range(1, 10);
        $gallery_columns = array_combine($gallery_columns, $gallery_columns);

        $this->addControl(
            'gallery_columns',
            array(
                'label' => __('Columns', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 4,
                'options' => $gallery_columns,
            )
        );

        $this->addControl(
            'gallery_link',
            array(
                'label' => __('Link to', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'file',
                'options' => array(
                    'file' => __('Media File', 'elementor'),
                    'attachment' => __('Attachment Page', 'elementor'),
                    'none' => __('None', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'gallery_rand',
            array(
                'label' => __('Ordering', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    '' => __('Default', 'elementor'),
                    'rand' => __('Random', 'elementor'),
                ),
                'default' => '',
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
            'section_gallery_images',
            array(
                'label' => __('Images', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'image_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    '' => __('Default', 'elementor'),
                    'custom' => __('Custom', 'elementor'),
                ),
                'prefix_class' => 'gallery-spacing-',
                'default' => '',
            )
        );

        $columns_margin = is_rtl() ? '0 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}};' : '0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} 0;';
        $columns_padding = is_rtl() ? '0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};' : '0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0;';

        $this->addControl(
            'image_spacing_custom',
            array(
                'label' => __('Image Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'show_label' => false,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'size' => 15,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .gallery-item' => 'padding:' . $columns_padding,
                    '{{WRAPPER}} .gallery' => 'margin: ' . $columns_margin,
                ),
                'condition' => array(
                    'image_spacing' => 'custom',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'image_border',
                'label' => __('Image Border', 'elementor'),
                'selector' => '{{WRAPPER}} .gallery-item img',
            )
        );

        $this->addControl(
            'image_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .gallery-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_caption',
            array(
                'label' => __('Caption', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'gallery_display_caption',
            array(
                'label' => __('Display', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => array(
                    '' => __('Show', 'elementor'),
                    'none' => __('Hide', 'elementor'),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .gallery-item .gallery-caption' => 'display: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'align',
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
                    'justify' => array(
                        'title' => __('Justified', 'elementor'),
                        'icon' => 'fa fa-align-justify',
                    ),
                ),
                'default' => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .gallery-item .gallery-caption' => 'text-align: {{VALUE}};',
                ),
                'condition' => array(
                    'gallery_display_caption' => '',
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
                    '{{WRAPPER}} .gallery-item .gallery-caption' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'gallery_display_caption' => '',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .gallery-item .gallery-caption',
                'condition' => array(
                    'gallery_display_caption' => '',
                ),
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        if (!$settings['wp_gallery']) {
            return;
        }

        // TODO
    }
}
