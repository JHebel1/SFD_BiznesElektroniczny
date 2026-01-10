<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Posthemes\Module\Poselements\WidgetHelper;

class PosImageHotspotWidget extends WidgetHelper
{
    public function getName()
    {
        return 'pos_image_hotspot';
    }

    public function getTitle()
    {
        return $this->l('Pos Image Hotspot');
    }

    public function getIcon()
    {
        return 'fa fa-image';
    }

    public function getCategories()
    {
        return ['posthemes'];
    }

    public function getKeywords()
    {
        return ['image', 'photo', 'hotspot'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image',
            [
                'label' => $this->l('Image'),
            ]
        );

        $this->addControl(
            'image',
            [
                'label' => $this->l('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
            ]
        );

        $this->addResponsiveControl(
            'align',
            [
                'label' => $this->l('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => $this->l('Left'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => $this->l('Center'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => $this->l('Right'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_hotspots',
            [
                'label' => $this->l('Hotspots'),
            ]
        );

        $this->addControl(
            'hotspots',
            [
                'label' => '',
                'type' => ControlsManager::REPEATER,
                'default' => [
                    [
                        '_id' => Utils::generateRandomString(),
                        'title' => $this->l('Hotspot #1'),
                        'description' =>
                            '<p>' . $this->l('Lorem ipsum dolor sit amet, consectetur adipiscing elit.') . '</p>',
                        'x' => [
                            'size' => 25,
                            'unit' => '%',
                        ],
                        'y' => [
                            'size' => 50,
                            'unit' => '%',
                        ],
                        'link' => [
                            'url' => '',
                        ],
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'type',
                        'label' => $this->l('Type of content'),
                        'type' => ControlsManager::SELECT,
                        'options' => [
                            'html' => $this->l('HTML'),
                            'product' => $this->l('Product'),
                        ],
                        'default' => 'product',
                    ],

                    [
                        'name' => 'product',
                        'label' => $this->l('Product'),
                        'label_block' => true,
                        'type' => ControlsManager::SELECT2,
                        'options' => $this->getProductOptions(),
                        'condition' => [
                            'type' => 'product',
                        ],
                    ],
                    [
                        'name' => 'title',
                        'label' => $this->l('Title & Description'),
                        'type' => ControlsManager::TEXT,
                        'default' => $this->l('Hotspot Title'),
                        'label_block' => true,
                        'condition'     => [
                            'type' => 'html',
                        ],
                    ],
                    [
                        'name' => 'description',
                        'type' => ControlsManager::WYSIWYG,
                        'default' => '<p>' . $this->l('Hotspot Description') . '</p>',
                        'show_label' => false,
                        'condition'     => [
                            'type' => 'html',
                        ],
                    ],
                    [
                        'name' => 'x',
                        'label' => _x('X Position', 'Background Control'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 50,
                            'unit' => '%',
                        ],
                        'range' => [
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .pos-image-hotspot-wrapper{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                        ],
                    ],
                    [
                        'name' => 'y',
                        'label' => _x('Y Position', 'Background Control'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 50,
                            'unit' => '%',
                        ],
                        'range' => [
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .pos-image-hotspot-wrapper{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                        ],
                    ],
                    [
                        'name' => 'link',
                        'label' => $this->l('Link'),
                        'type' => ControlsManager::URL,
                        'default' => ['url' => ''],
                        'placeholder' => 'http://your-link.com',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            [
                'label' => $this->l('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'image_size',
            [
                'label' => $this->l('Size (%)'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'image_opacity',
            [
                'label' => $this->l('Opacity (%)'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot > img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'label' => $this->l('Image Border'),
                'selector' => '{{WRAPPER}} .pos-image-hotspot > img',
            ]
        );

        $this->addControl(
            'image_border_radius',
            [
                'label' => $this->l('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .pos-image-hotspot > img',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_icon',
            [
                'label' => $this->l('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'icon_size',
            [
                'label' => $this->l('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 22,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hp-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_padding',
            [
                'label' => $this->l('Icon Padding'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0.4,
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hp-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_rotate',
            [
                'label' => $this->l('Icon Rotate'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .hp-icon:before' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->addControl(
            'icon_border_width',
            [
                'label' => $this->l('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .hp-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_border_radius',
            [
                'label' => $this->l('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hp-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->startControlsTabs('icon_tabs');

        $this->startControlsTab(
            'icon_normal',
            [
                'label' => $this->l('Normal'),
            ]
        );

        $this->addControl(
            'icon_primary_color',
            [
                'label' => $this->l('Primary Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}}.pos-view-stacked .hp-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.pos-view-framed .hp-icon, {{WRAPPER}}.pos-view-default .hp-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color',
            [
                'label' => $this->l('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}}.pos-view-framed .hp-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.pos-view-stacked .hp-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'icon_hover',
            [
                'label' => $this->l('Hover'),
            ]
        );

        $this->addControl(
            'hover_primary_color',
            [
                'label' => $this->l('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.pos-view-stacked .hp-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.pos-view-framed .hp-icon:hover, {{WRAPPER}}.pos-view-default .hp-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'hover_secondary_color',
            [
                'label' => $this->l('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.pos-view-framed .hp-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.pos-view-stacked .hp-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'icon_animation',
            [
                'label' => $this->l('Animation'),
                'type' => ControlsManager::HOVER_ANIMATION,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_box',
            [
                'label' => $this->l('Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'box_width',
            [
                'label' => $this->l('Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot-content' => 'width: {{SIZE}}px',
                ],
            ]
        );

        $this->addResponsiveControl(
            'box_padding',
            [
                'label' => $this->l('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'box_background',
                'types' => ['classic'],
                'selector' => '{{WRAPPER}} .pos-image-hotspot-content',
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .pos-image-hotspot-content',
            ]
        );

        $this->addControl(
            'box_border_radius',
            [
                'label' => $this->l('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->addControl(
            'box_shadow_type',
            [
                'label' => _x('Box Shadow', 'Box Shadow Control'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => $this->l('Default'),
                    'outset' => $this->l('Custom'),
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'box_shadow',
            [
                'type' => ControlsManager::BOX_SHADOW,
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot-content' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ],
                'condition' => [
                    'box_shadow_type!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            [
                'label' => $this->l('Content'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'text_align',
            [
                'label' => $this->l('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => $this->l('Left'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => $this->l('Center'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => $this->l('Right'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => $this->l('Justified'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'heading_title',
            [
                'label' => $this->l('Title'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'title_bottom_space',
            [
                'label' => $this->l('Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => $this->l('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .pos-image-hotspot-title',
            ]
        );

        $this->addControl(
            'heading_description',
            [
                'label' => $this->l('Description'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_color',
            [
                'label' => $this->l('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-image-hotspot .pos-image-hotspot-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .pos-image-hotspot .pos-image-hotspot-description',
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();
        if (empty($settings['image']['url'])) {
            return;
        }

        empty($settings['icon']) or $this->addRenderAttribute('icon', 'class', $settings['icon']);
        
        ?>
        <div class="pos-image-hotspot">
            <?= GroupControlImageSize::getAttachmentImageHtml($settings) ?>
            <?php foreach ($settings['hotspots'] as $i => $item) : ?>
                <div class="pos-image-hotspot-wrapper elementor-repeater-item-<?= $item['_id'] ?>">
                    <?php
                    $icon_tag = 'div';
                    $this->addRenderAttribute("icon-wrapper-$i", 'class', 'hp-icon');

                    empty($settings['icon_animation']) or $this->addRenderAttribute("icon-wrapper-$i", [
                        'class' => 'pos-animation-' . $settings['icon_animation'],
                    ]);

                    if (!empty($item['link']['url'])) {
                        $icon_tag = 'a';
                        $this->addRenderAttribute("icon-wrapper-$i", 'href', $item['link']['url']);

                        empty($item['link']['is_external']) or $this->addRenderAttribute("icon-wrapper-$i", [
                            'target' => '_blank',
                        ]);
                        empty($item['link']['nofollow']) or $this->addRenderAttribute("icon-wrapper-$i", [
                            'rel' => 'nofollow',
                        ]);
                    }
                    ?>
                    <i class="hp-icon fa fa-plus-circle"></i>
                    <div class="pos-image-hotspot-content">
                        <?php if($item['type'] == 'product') : 
                            $product = $this->getProduct($item['product']);
                            ?>
                            <div class="pos-image-hotspot-product">
                                <img src="<?= $product['cover']['bySize']['home_default']['url'] ?>" alt="<?= $product['name'] ?>" width="<?= $product['cover']['bySize']['home_default']['width'] ?>" height="<?= $product['cover']['bySize']['home_default']['height'] ?>"/>
                                <a href="<?= $product['link'] ?>"><?= $product['name'] ?></a>
                            </div>
                        <?php else : ?>
                            <?php if (!empty($item['title'])) : ?>
                                <div class="pos-image-hotspot-title">
                                    <?= $item['title'] ?>
                                </div>
                            <?php endif ?>
                            <?php if (!empty($item['description'])) : ?>
                                <div class="pos-image-hotspot-description"><?= $item['description'] ?></div>
                            <?php endif ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <?php
    }

    protected function _contentTemplate(){}

    /**
     * Get translation for a given widget text
     *
     * @access protected
     *
     * @param string $string    String to translate
     *
     * @return string Translation
     */
    protected function l($string)
    {
        return translate($string, 'posthemeoptions', basename(__FILE__, '.php'));
    }
}
