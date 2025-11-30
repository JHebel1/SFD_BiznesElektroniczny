<?php

namespace CE;

defined('_PS_VERSION_') or die;

class PosHeaderContactWidget extends WidgetBase
{
    public function getName()
    {
        return 'pos_contact';
    }

    public function getTitle()
    {
        return $this->l('Contact Box');
    }

    public function getIcon()
    {
        return 'fa fa-phone-square';
    }

    public function getCategories()
    {
        return array('posthemes_header');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image',
            array(
                'label' => $this->l('Image Box'),
            )
        );

        $this->addControl(
            'image',
            array(
                'label' => $this->l('Choose Image'),
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
                'label' => $this->l('Title'),
                'type' => ControlsManager::TEXT,
                'default' => $this->l('Call us:'),
                'placeholder' => $this->l('Your Title'),
                'label_block' => true,
            )
        );

        $this->addControl(
            'phone',
            array(
                'label' => $this->l('Phone'),
                'type' => ControlsManager::TEXT,
                'default' => '+01 (123) 888999',
                'separator' => 'none',
                'show_label' => false,
            )
        );

        $this->addControl(
            'position',
            array(
                'label' => $this->l('Image Position'),
                'type' => ControlsManager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left' => array(
                        'title' => $this->l('Left'),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'top' => array(
                        'title' => $this->l('Top'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'right' => array(
                        'title' => $this->l('Right'),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'prefix_class' => 'contact-position-',
                'toggle' => false,
            )
        );
        $this->addControl(
            'view',
            array(
                'label' => $this->l('View'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'traditional',
            )
        );

        $this->endControlsSection();
		// Start for style
        $this->startControlsSection(
            'section_general',
            [
                'label' => __('General'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );
            $this->addControl(
            'search_width',
                [
                    'label' => __('Width'),
                    'type' => ControlsManager::SELECT,
                    'default' => 'inline',
                    'options' => [ 
                        'fullwidth' => __('Full width 100%'),
                        'inline' => __('Inline (auto)')
                    ],
                    'prefix_class' => 'pewidth-',
                    'render_type' => 'template',
                    'frontend_available' => true
                ]
            );
        $this->endControlsSection();  
        $this->startControlsSection(
            'section_style_image',
            array(
                'label' => $this->l('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'image_space',
            array(
                'label' => $this->l('Image Spacing'),
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
                    '{{WRAPPER}}.contact-position-right .box-contact .contact-img' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.contact-position-left .box-contact .contact-img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.contact-position-top .box-contact .contact-img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'image_size',
            array(
                'label' => $this->l('Image Size'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('%'),
                'range' => array(
                    '%' => array(
                        'min' => 5,
                        'max' => 100,
                    ),
                ),
				'default' => array(
                    'size' => 40,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .box-contact .contact-img' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'image_opacity',
            array(
                'label' => $this->l('Opacity (%)'),
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
                    '{{WRAPPER}} .box-contact .contact-img img' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->addControl(
            'hover_animation',
            array(
                'label' => $this->l('Animation'),
                'type' => ControlsManager::HOVER_ANIMATION,
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            array(
                'label' => $this->l('Content'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'text_align',
            array(
                'label' => $this->l('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => $this->l('Left'),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => $this->l('Center'),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => $this->l('Right'),
                        'icon' => 'fa fa-align-right',
                    ),
                    'justify' => array(
                        'title' => $this->l('Justified'),
                        'icon' => 'fa fa-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .box-contact' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'content_display',
            array(
                'label' => $this->l('Content display'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    '2-row' => $this->l('2 rows'),
                    '1-row' => $this->l('1 row'),
                ),
                'default' => '2-row',
                'prefix_class' => 'contact-display-', 
            )
        );

        $this->addControl(
            'content_vertical_alignment',
            array(
                'label' => $this->l('Vertical Alignment'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'top' => $this->l('Top'),
                    'middle' => $this->l('Middle'),
                    'bottom' => $this->l('Bottom'),
                ),
                'default' => 'top',
                'prefix_class' => 'contact-vertical-align-', 
            )
        );

        $this->addControl(
            'heading_title',
            array(
                'label' => $this->l('Title'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addResponsiveControl(
            'title_bottom_space',
            array(
                'label' => $this->l('Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .box-contact .title-contact' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.contact-display-1-row .box-contact .title-contact' => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => $this->l('Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .box-contact .title-contact' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .box-contact .title-contact',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            )
        );
		
        $this->addControl(
            'heading_phone',
            array(
                'label' => $this->l('Phone number'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'number_color',
            array(
                'label' => $this->l('Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .box-contact .number-contact' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .box-contact .number-contact',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        $has_content = !empty($settings['title_text']) || !empty($settings['phone']);

        $html = '<div class="box-contact">';
        $image_html = GroupControlImageSize::getAttachmentImageHtml($settings);

        if ($image_html) {
            $html .= '<figure class="contact-img">' . $image_html . '</figure>';
        }

        if ($has_content) {
            $html .= '<div class="contact-content">';
            if (!empty($settings['title_text'])){
                $html .= '<p class="title-contact">'. $settings['title_text'] .'</p>';
            }
            if (!empty($settings['phone'])) {
                $html .= sprintf('<a class="number-contact" href="tel:%s">%s</a>', $settings['phone'], $settings['phone']);
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        echo $html;
    }
    
    protected function l($string)
    {
        return translate($string, 'posthemeoptions', basename(__FILE__, '.php'));
    }
}
