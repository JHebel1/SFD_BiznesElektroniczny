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

class WidgetTestimonial extends WidgetBase
{
    public function getName()
    {
        return 'testimonial';
    }

    public function getTitle()
    {
        return __('Testimonial', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-testimonial';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_testimonial',
            array(
                'label' => __('Testimonial', 'elementor'),
            )
        );

        $this->addControl(
            'testimonial_content',
            array(
                'label' => __('Content', 'elementor'),
                'type' => ControlsManager::TEXTAREA,
                'rows' => '10',
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
            )
        );

        $this->addControl(
            'testimonial_image',
            array(
                'label' => __('Add Image', 'elementor'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => array(
                    'url' => Utils::getPlaceholderImageSrc(),
                ),
                'separator' => '',
            )
        );

        $this->addControl(
            'testimonial_name',
            array(
                'label' => __('Name', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => 'John Doe',
                'separator' => '',
            )
        );

        $this->addControl(
            'testimonial_job',
            array(
                'label' => __('Job', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => 'Designer',
                'separator' => '',
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => __('Link', 'elementor'),
                'type' => ControlsManager::URL,
                'placeholder' => __('https://your-link.com', 'elementor'),
            )
        );

        $this->addControl(
            'testimonial_image_position',
            array(
                'label' => __('Image Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'aside',
                'options' => array(
                    'aside' => __('Aside', 'elementor'),
                    'top' => __('Top', 'elementor'),
                ),
                'condition' => array(
                    'testimonial_image[url]!' => '',
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'testimonial_alignment',
            array(
                'label' => __('Alignment', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'default' => 'center',
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

        // Content
        $this->startControlsSection(
            'section_style_testimonial_content',
            array(
                'label' => __('Content', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'content_content_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'content_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-testimonial-content',
            )
        );

        $this->endControlsSection();

        // Image
        $this->startControlsSection(
            'section_style_testimonial_image',
            array(
                'label' => __('Image', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'image_size',
            array(
                'label' => __('Image Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 20,
                        'max' => 200,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img',
            )
        );

        $this->addControl(
            'image_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        // Name
        $this->startControlsSection(
            'section_style_testimonial_name',
            array(
                'label' => __('Name', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'name_text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-name' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'name_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-testimonial-name',
            )
        );

        $this->endControlsSection();

        // Job
        $this->startControlsSection(
            'section_style_testimonial_job',
            array(
                'label' => __('Job', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'job_text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ),
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-job' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'job_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-testimonial-job',
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        $this->addRenderAttribute('wrapper', 'class', 'elementor-testimonial-wrapper');

        if ($settings['testimonial_alignment']) {
            $this->addRenderAttribute('wrapper', 'class', 'elementor-testimonial-text-align-' . $settings['testimonial_alignment']);
        }

        $this->addRenderAttribute('meta', 'class', 'elementor-testimonial-meta');

        if ($settings['testimonial_image']['url']) {
            $this->addRenderAttribute('meta', 'class', 'elementor-has-image');
        }

        if ($settings['testimonial_image_position']) {
            $this->addRenderAttribute('meta', 'class', 'elementor-testimonial-image-position-' . $settings['testimonial_image_position']);
        }

        if (!empty($settings['link']['url'])) {
            $this->addRenderAttribute('link', 'href', $settings['link']['url']);

            if ($settings['link']['is_external']) {
                $this->addRenderAttribute('link', 'target', '_blank');
            }
        }

        $has_content = !!$settings['testimonial_content'];
        $has_image = !empty($settings['testimonial_image']['url']);
        $has_name = !!$settings['testimonial_name'];
        $has_job = !!$settings['testimonial_job'];

        if (!$has_content && !$has_image && !$has_name && !$has_job) {
            return;
        }
        ?>
        <div <?php echo $this->getRenderAttributeString('wrapper'); ?>>
        <?php if ($has_content) : ?>
            <div class="elementor-testimonial-content">
                <?php echo $settings['testimonial_content']; ?>
            </div>
        <?php endif;?>
        <?php if ($has_image || $has_name || $has_job) : ?>
            <div <?php echo $this->getRenderAttributeString('meta'); ?>>
                <div class="elementor-testimonial-meta-inner">
                <?php if ($has_image) : ?>
                    <div class="elementor-testimonial-image">
                        <?php
                        $image_html = GroupControlImageSize::getAttachmentImageHtml($settings, 'testimonial_image');
                        empty($settings['link']['url']) or $image_html = '<a ' . $this->getRenderAttributeString('link') . '>' . $image_html . '</a>';
                        echo $image_html;
                        ?>
                    </div>
                <?php endif;?>
                <?php if ($has_name || $has_job) : ?>
                    <div class="elementor-testimonial-details">
                    <?php if ($has_name) : ?>
                        <div class="elementor-testimonial-name">
                            <?php
                            $testimonial_name_html = $settings['testimonial_name'];
                            empty($settings['link']['url']) or $testimonial_name_html = '<a ' . $this->getRenderAttributeString('link') . '>' . $testimonial_name_html . '</a>';
                            echo $testimonial_name_html;
                            ?>
                        </div>
                    <?php endif;?>
                    <?php if ($has_job) : ?>
                        <div class="elementor-testimonial-job">
                            <?php
                            $testimonial_job_html = $settings['testimonial_job'];
                            empty($settings['link']['url']) or $testimonial_job_html = '<a ' . $this->getRenderAttributeString('link') . '>' . $testimonial_job_html . '</a>';
                            echo $testimonial_job_html;
                            ?>
                        </div>
                    <?php endif;?>
                    </div>
                <?php endif;?>
                </div>
            </div>
        <?php endif;?>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <#
        var hasImage = settings.testimonial_image.url ? ' elementor-has-image' : '',
            hasLink = settings.link && settings.link.url,
            testimonial_alignment = settings.testimonial_alignment ? ' elementor-testimonial-text-align-' + settings.testimonial_alignment : '',
            testimonial_image_position = settings.testimonial_image_position ? ' elementor-testimonial-image-position-' + settings.testimonial_image_position : '';
        #>
        <div class="elementor-testimonial-wrapper{{ testimonial_alignment }}">
            <# if ( '' !== settings.testimonial_content ) { #>
                <div class="elementor-testimonial-content">
                    {{{ settings.testimonial_content }}}
                </div>
            <# } #>

            <div class="elementor-testimonial-meta{{ hasImage }}{{ testimonial_image_position }}">
                <div class="elementor-testimonial-meta-inner">
                    <# if ( hasImage ) { #>
                    <div class="elementor-testimonial-image">
                        <# if ( hasLink ) { #><a href="{{ settings.link.url }}"><# } #>
                            <img src="{{ elementor.imagesManager.getImageUrl( settings.testimonial_image ) }}" alt="testimonial" />
                        <# if ( hasLink ) { #></a><# } #>
                    </div>
                    <# } #>

                    <div class="elementor-testimonial-details">
                    <# if ( '' !== settings.testimonial_name ) { #>
                        <div class="elementor-testimonial-name">
                            <# if ( hasLink ) { #><a href="{{ settings.link.url }}"><# } #>
                                {{{ settings.testimonial_name }}}
                            <# if ( hasLink ) { #></a><# } #>
                        </div>
                    <# } #>

                    <# if ( '' !== settings.testimonial_job ) { #>
                        <div class="elementor-testimonial-job">
                            <# if ( hasLink ) { #><a href="{{ settings.link.url }}"><# } #>
                                {{{ settings.testimonial_job }}}
                            <# if ( hasLink ) { #></a><# } #>
                        </div>
                    <# } #>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
