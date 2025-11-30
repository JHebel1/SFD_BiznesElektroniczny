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

class WidgetImage extends WidgetBase
{
    public function getName()
    {
        return 'image';
    }

    public function getTitle()
    {
        return __('Image', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-insert-image';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image',
            array(
                'label' => __('Image', 'elementor'),
            )
        );

        $this->addControl(
            'image',
            array(
                'label' => __('Choose Image', 'elementor'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => array(
                    'url' => Utils::getPlaceholderImageSrc(),
                ),
            )
        );

        $this->addResponsiveControl(
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
                ),
                'default' => 'center',
                'selectors' => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'caption',
            array(
                'label' => __('Caption', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => '',
                'placeholder' => __('Enter your caption about the image', 'elementor'),
                'title' => __('Input image caption here', 'elementor'),
            )
        );

        $this->addControl(
            'link_to',
            array(
                'label' => __('Link to', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'none',
                'options' => array(
                    'none' => __('None', 'elementor'),
                    'file' => __('Media File', 'elementor'),
                    'custom' => __('Custom URL', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => __('Link to', 'elementor'),
                'type' => ControlsManager::URL,
                'placeholder' => __('http://your-link.com', 'elementor'),
                'condition' => array(
                    'link_to' => 'custom',
                ),
                'show_label' => false,
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
            'section_style_image',
            array(
                'label' => __('Image', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'space',
            array(
                'label' => __('Size (%)', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 100,
                    'unit' => '%',
                ),
                'size_units' => array('%'),
                'range' => array(
                    '%' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'opacity',
            array(
                'label' => __('Opacity (%)', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 1,
                ),
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image img' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->addControl(
            'hover_animation',
            array(
                'label' => __('Hover Animation', 'elementor'),
                'type' => ControlsManager::HOVER_ANIMATION,
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'image_border',
                'label' => __('Image Border', 'elementor'),
                'selector' => '{{WRAPPER}} .elementor-image img',
            )
        );

        $this->addControl(
            'image_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-image img',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_caption',
            array(
                'label' => __('Caption', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'caption_align',
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
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
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
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .widget-image-caption',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        if (empty($settings['image']['url'])) {
            return;
        }

        $has_caption = !empty($settings['caption']);

        $this->addRenderAttribute('wrapper', 'class', 'elementor-image');

        if (!empty($settings['shape'])) {
            $this->addRenderAttribute('wrapper', 'class', 'elementor-image-shape-' . $settings['shape']);
        }

        $link = $this->getLinkUrl($settings);

        if ($link) {
            $this->addRenderAttribute('link', 'href', $link['url']);

            if (!empty($link['is_external'])) {
                $this->addRenderAttribute('link', 'target', '_blank');
            }
        }
        ?>
        <div <?php echo $this->getRenderAttributeString('wrapper'); ?>>
        <?php if ($has_caption) : ?>
            <figure class="wp-caption">
        <?php endif;?>

        <?php if ($link) : ?>
            <a <?php echo $this->getRenderAttributeString('link'); ?>>
        <?php endif;?>

        <?php echo GroupControlImageSize::getAttachmentImageHtml($settings); ?>

        <?php if ($link) : ?>
            </a>
        <?php endif;?>

        <?php if ($has_caption) : ?>
            <figcaption class="widget-image-caption wp-caption-text"><?php echo $settings['caption']; ?></figcaption>
        <?php endif;?>

        <?php if ($has_caption) : ?>
            </figure>
        <?php endif;?>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <# if ( settings.image.url ) {
            var image_url = elementor.imagesManager.getImageUrl( settings.image ),
                link_url;

            if ( 'custom' === settings.link_to ) {
                link_url = settings.link.url;
            }

            if ( 'file' === settings.link_to ) {
                link_url = image_url;
            }

            #><div class="elementor-image{{ settings.shape ? ' elementor-image-shape-' + settings.shape : '' }}"><#
            var imgClass = '',
                hasCaption = '' !== settings.caption;

            if ( '' !== settings.hover_animation ) {
                imgClass = 'elementor-animation-' + settings.hover_animation;
            }

            if ( hasCaption ) {
                #><figure class="wp-caption"><#
            }

            if ( link_url ) {
                #><a href="{{ link_url }}"><#
            }
            #><img src="{{ image_url }}" class="{{ imgClass }}" /><#

            if ( link_url ) {
                #></a><#
            }

            if ( hasCaption ) {
                    #><figcaption class="widget-image-caption wp-caption-text">{{{ settings.caption }}}</figcaption><#
            }

            if ( hasCaption ) {
                #></figure><#
            }

            #></div><#
        } #>
        <?php
    }

    private function getLinkUrl($instance)
    {
        if ('none' === $instance['link_to']) {
            return false;
        }

        if ('custom' === $instance['link_to']) {
            if (empty($instance['link']['url'])) {
                return false;
            }
            return $instance['link'];
        }

        return array(
            'url' => Helper::getMediaLink($instance['image']['url']),
        );
    }
}
