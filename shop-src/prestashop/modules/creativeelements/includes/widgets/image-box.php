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

class WidgetImageBox extends WidgetBase
{
    public function getName()
    {
        return 'image-box';
    }

    public function getTitle()
    {
        return __('Image Box', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-image-box';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image',
            array(
                'label' => __('Image Box', 'elementor'),
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

        $this->addControl(
            'title_text',
            array(
                'label' => __('Title & Description', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => __('This is the heading', 'elementor'),
                'placeholder' => __('Your Title', 'elementor'),
                'label_block' => true,
            )
        );

        $this->addControl(
            'description_text',
            array(
                'label' => __('Content', 'elementor'),
                'type' => ControlsManager::TEXTAREA,
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor'),
                'placeholder' => __('Your Description', 'elementor'),
                'title' => __('Input image text here', 'elementor'),
                'separator' => 'none',
                'rows' => 10,
                'show_label' => false,
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => __('Link to', 'elementor'),
                'type' => ControlsManager::URL,
                'placeholder' => __('http://your-link.com', 'elementor'),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'position',
            array(
                'label' => __('Image Position', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'default' => 'top',
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'elementor'),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'top' => array(
                        'title' => __('Top', 'elementor'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'right' => array(
                        'title' => __('Right', 'elementor'),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'prefix_class' => 'elementor-position-',
                'toggle' => false,
            )
        );

        $this->addControl(
            'title_size',
            array(
                'label' => __('Title HTML Tag', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'h1' => __('H1', 'elementor'),
                    'h2' => __('H2', 'elementor'),
                    'h3' => __('H3', 'elementor'),
                    'h4' => __('H4', 'elementor'),
                    'h5' => __('H5', 'elementor'),
                    'h6' => __('H6', 'elementor'),
                    'div' => __('div', 'elementor'),
                    'span' => __('span', 'elementor'),
                    'p' => __('p', 'elementor'),
                ),
                'default' => 'h3',
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
            'image_space',
            array(
                'label' => __('Image Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 15,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-position-right .elementor-image-box-img' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-position-left .elementor-image-box-img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-position-top .elementor-image-box-img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'image_size',
            array(
                'label' => __('Image Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 30,
                    'unit' => '%',
                ),
                'size_units' => array('%'),
                'range' => array(
                    '%' => array(
                        'min' => 5,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-box-wrapper .elementor-image-box-img' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'image_opacity',
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
                    '{{WRAPPER}} .elementor-image-box-wrapper .elementor-image-box-img img' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->addControl(
            'hover_animation',
            array(
                'label' => __('Animation', 'elementor'),
                'type' => ControlsManager::HOVER_ANIMATION,
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            array(
                'label' => __('Content', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'text_align',
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
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-box-wrapper' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'content_vertical_alignment',
            array(
                'label' => __('Vertical Alignment', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'top' => __('Top', 'elementor'),
                    'middle' => __('Middle', 'elementor'),
                    'bottom' => __('Bottom', 'elementor'),
                ),
                'default' => 'top',
                'prefix_class' => 'elementor-vertical-align-',
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

        $this->addResponsiveControl(
            'title_bottom_space',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-box-content .elementor-image-box-title' => 'color: {{VALUE}};',
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
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-image-box-content .elementor-image-box-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            )
        );

        $this->addControl(
            'heading_description',
            array(
                'label' => __('Description', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'description_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-box-content .elementor-image-box-description' => 'color: {{VALUE}};',
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
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .elementor-image-box-content .elementor-image-box-description',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        $has_content = !empty($settings['title_text']) || !empty($settings['description_text']);

        $html = '<div class="elementor-image-box-wrapper">';
        $image_html = GroupControlImageSize::getAttachmentImageHtml($settings);

        if ($image_html) {
            if (!empty($settings['link']['url'])) {
                $target = '';
                if (!empty($settings['link']['is_external'])) {
                    $target = ' target="_blank"';
                }
                $image_html = sprintf('<a href="%s"%s>%s</a>', $settings['link']['url'], $target, $image_html);
            }

            $html .= '<figure class="elementor-image-box-img">' . $image_html . '</figure>';
        }

        if ($has_content) {
            $html .= '<div class="elementor-image-box-content">';

            if (!empty($settings['title_text'])) {
                $title_html = $settings['title_text'];

                if (!empty($settings['link']['url'])) {
                    $target = '';

                    if (!empty($settings['link']['is_external'])) {
                        $target = ' target="_blank"';
                    }

                    $title_html = sprintf('<a href="%s"%s>%s</a>', $settings['link']['url'], $target, $title_html);
                }

                $html .= sprintf('<%1$s class="elementor-image-box-title">%2$s</%1$s>', $settings['title_size'], $title_html);
            }

            if (!empty($settings['description_text'])) {
                $html .= sprintf('<div class="elementor-image-box-description">%s</div>', $settings['description_text']);
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        echo $html;
    }

    protected function _contentTemplate()
    {
        ?>
        <#
        var html = '<div class="elementor-image-box-wrapper">';

        if ( settings.image.url ) {
            var imageSrc = elementor.imagesManager.getImageUrl( settings.image );
            var imageHtml = '<img src="' + imageSrc + '"  alt="' + settings.caption + '" class="elementor-animation-' + settings.hover_animation + '" />';

            if ( settings.link.url ) {
                imageHtml = '<a href="' + settings.link.url + '">' + imageHtml + '</a>';
            }

            html += '<figure class="elementor-image-box-img">' + imageHtml + '</figure>';
        }

        var hasContent = !! ( settings.title_text || settings.description_text );

        if ( hasContent ) {
            html += '<div class="elementor-image-box-content">';

            if ( settings.title_text ) {
                var title_html = settings.title_text;

                if ( settings.link.url ) {
                    title_html = '<a href="' + settings.link.url + '">' + title_html + '</a>';
                }

                html += '<' + settings.title_size  + ' class="elementor-image-box-title">' + title_html + '</' + settings.title_size  + '>';
            }

            if ( settings.description_text ) {
                html += '<div class="elementor-image-box-description">' + settings.description_text + '</div>';
            }

            html += '</div>';
        }

        html += '</div>';

        print( html );
        #>
        <?php
    }
}
