<?php  

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Tools;
use CE\Helper;

use Posthemes\Module\Poselements\WidgetHelper;

class PosSlideshowWidget extends WidgetHelper { 
	public function getName() {
		return 'pos_slideshow';
	}

	public function getTitle() {
		return 'Pos Simple Slideshow';
	}
	
	public function getIcon() {
		return 'fa fa-television';
	}

	public function getCategories() {
		return [ 'posthemes' ];
	}
	
	protected function _registerControls() {
		$animations = array(
			'' => $this->l('Default' ), 
			'bounceIn' => 'bounceIn',
			'bounceInDown' => 'bounceInDown',
			'bounceInLeft' => 'bounceInLeft',
			'bounceInRight' => 'bounceInRight',
			'bounceInUp' => 'bounceInUp',
			'fadeIn' => 'fadeIn',
			'fadeInDown' => 'fadeInDown',
			'fadeInLeft' => 'fadeInLeft',
			'fadeInRight' => 'fadeInRight',
			'fadeInUp' => 'fadeInUp',
			'zoomIn' => 'zoomIn',
			'zoomInDown' => 'zoomInDown',
			'zoomInLeft' => 'zoomInLeft',
			'zoomInRight' => 'zoomInRight',
			'zoomInUp' => 'zoomInUp',
			'rotateIn' => 'rotateIn',
			'rotateInDownLeft' => 'rotateInDownLeft',
			'rotateInDownRight' => 'rotateInDownRight',
			'rotateInUpLeft' => 'rotateInUpLeft',
			'rotateInUpRight' => 'rotateInUpRight',
			'pulse' => 'pulse',
			'flipInX' => 'flipInX',
			'jackInTheBox' => 'jackInTheBox',
			'rollIn' => 'rollIn',
		);
		//Tab Content
		$this->startControlsSection(
			'content_section',
			[
				'label' => $this->l( 'Content'),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addResponsiveControl( 
				'height_slideshow',
				[
					'label' => $this->l( 'Height slideshow' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2000,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 500,
					],
					'selectors' => [
						'{{WRAPPER}} .pos-slideshow .slider-item' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->addControl(
            'slideshow_list',
	            [
	                'type' => ControlsManager::REPEATER,
	                'fields' => [
	                    [
	                        'name' => 'slideshow_image',
	                        'label' => $this->l('Add Image'),
	                        'type' => ControlsManager::MEDIA,
	                        'seo' => 'true',
	                        'default' => [
	                            'url' => Utils::getPlaceholderImageSrc(),
	                        ],
	                    ],
	                    [
	                    	'name' => 'alignment',
	                    	'label' => $this->l('Alignment', 'elementor'),
			                'type' => ControlsManager::CHOOSE,
			                'options' => array(
			                    'left' => array(
			                        'title' => $this->l('Left', 'elementor'),
			                        'icon' => 'fa fa-align-left',
			                    ),
			                    'center' => array(
			                        'title' => $this->l('Center', 'elementor'),
			                        'icon' => 'fa fa-align-center',
			                    ),
			                    'right' => array(
			                        'title' => $this->l('Right', 'elementor'),
			                        'icon' => 'fa fa-align-right',
			                    ),
			                ),
			                'selectors' => array(
								'{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner' => 'text-align: {{VALUE}};',
			                ),
	                    ],
						[
	                        'name' => 'max-width',
							'label' => $this->l('Content max width'),
	                        'type' => ControlsManager::SLIDER, 
							'size_units' => array('px', '%'),
							'range' => array(
								'px' => array(
									'min' => 0,
									'max' => 2000,
								),
							),
							'default' => [
								'unit' => 'px',
							],
	                        'selectors' => [
								'{{WRAPPER}} .desc-banner .slideshow-content' => 'max-width: {{SIZE}}{{UNIT}};',
							],
	                    ],
	                    [
	                        'name' => 'x',
	                        'label' => _x('X Position', 'Background Control'),
	                        'type' => ControlsManager::SLIDER, 
							'size_units' => array('%', 'px'),
							'default' => [
								'unit' => '%',
							],
	                        'range' => [
	                            '%' => [
	                                'min' => 0,
	                                'max' => 100,
	                            ],
								'px' => array(
									'min' => 0,
									'max' => 1000,
								),
	                        ],
	                        'selectors' => [
	                            '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner' => 'left: {{SIZE}}{{UNIT}};',
	                        ],
	                    ],
	                    [
	                        'name' => 'y',
	                        'label' => _x('Y Position', 'Background Control'),
	                        'type' => ControlsManager::SLIDER, 
							'size_units' => array('%', 'px'),
							'default' => [
								'unit' => '%', 
							],
	                        'range' => [
	                            '%' => [
	                                'min' => 0,
	                                'max' => 100,
	                            ],
								'px' => array(
									'min' => 0,
									'max' => 1000,
								),
	                        ],
	                        'selectors' => [ 
	                            '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner' => 'top: {{SIZE}}{{UNIT}}',
	                        ],
	                    ],
	                    [
	                    	'name' => 'divider1',
	                    	'type' => ControlsManager::DIVIDER,
	                    ],
	                    // Title 1
	                    [
	                    	'name' => 'heading1',
	                    	'label' => $this->l('Title 1'),
	                    	'type' => ControlsManager::HEADING,
	                    ],
	                    [
	                        'name' => 'title1',
	                        'label' => '',
	                        'type' => ControlsManager::TEXT,
	                        'show_label' => false,	                    
	                        'label_block' => true,	                    
	                    ],
	                    [
							'name' => 'title1_color',
			            	'label' => $this->l('Color'),
			                'type' => ControlsManager::COLOR,
			                'scheme' => [
			                    'type' => SchemeColor::getType(),
			                    'value' => SchemeColor::COLOR_1,
			                ],
			                'selectors' => [
			                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title1' => 'color: {{VALUE}};',
			                ],
			            ],
	                    [
	                    	'name' => 'title1_animation',
	                    	'label' => $this->l('Animation'),
			                'type' => ControlsManager::ANIMATION,
			            ],
			            [
	                    	'name' => 'divider2',
	                    	'type' => ControlsManager::DIVIDER,
	                    ],
			            // Title 2
			            [
	                    	'name' => 'heading2',
	                    	'label' => $this->l('Title 2'),
	                    	'type' => ControlsManager::HEADING,
	                    ],
	                    [
	                        'name' => 'title2',
	                        'label' => '',
	                        'type' => ControlsManager::TEXT,
	                        'show_label' => false,	                    
	                        'label_block' => true,	
	                    ],
	                    
			            [
							'name' => 'title2_color',
			            	'label' => $this->l('Color'),
			                'type' => ControlsManager::COLOR,
			                'scheme' => [
			                    'type' => SchemeColor::getType(),
			                    'value' => SchemeColor::COLOR_1,
			                ],
			                'selectors' => [
			                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title2' => 'color: {{VALUE}};',
			                ],
			            ],
			            [
	                    	'name' => 'title2_animation',
	                    	'label' => $this->l('Animation'),
			                'type' => ControlsManager::SELECT,
			                'default' => '',
			                'options' => $animations,
			            ],
			            [
	                    	'name' => 'divider3',
	                    	'type' => ControlsManager::DIVIDER,
	                    ],
	                    // Title 3
			            [
	                    	'name' => 'heading3',
	                    	'label' => $this->l('Title 3'),
	                    	'type' => ControlsManager::HEADING,
	                    ],
	                    [
	                        'name' => 'title3',
	                        'label' => '',
	                        'type' => ControlsManager::TEXT,
	                        'show_label' => false,	                    
	                        'label_block' => true,	
	                    ],
	                    
			            [
							'name' => 'title3_color',
			            	'label' => $this->l('Color'),
			                'type' => ControlsManager::COLOR,
			                'scheme' => [
			                    'type' => SchemeColor::getType(),
			                    'value' => SchemeColor::COLOR_1,
			                ],
			                'selectors' => [
			                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title3' => 'color: {{VALUE}};',
			                ],
			            ],
			            [
	                    	'name' => 'title3_animation',
	                    	'label' => $this->l('Animation'),
			                'type' => ControlsManager::SELECT,
			                'default' => '',
			                'options' => $animations,
			            ],
			            [
	                    	'name' => 'divider4',
	                    	'type' => ControlsManager::DIVIDER,
	                    ],
			            // Subtitle
			            [
	                    	'name' => 'heading4',
	                    	'label' => $this->l('Subtitle'),
	                    	'type' => ControlsManager::HEADING,
	                    ],
	                    [
	                        'name' => 'subtitle',
	                        'label' => '',
	                        'type' => ControlsManager::TEXT,
	                        'show_label' => false,	                    
	                        'label_block' => true,	
	                    ],
	                    [
							'name' => 'subtitle_color',
			            	'label' => $this->l('Color'),
			                'type' => ControlsManager::COLOR,
			                'scheme' => [
			                    'type' => SchemeColor::getType(),
			                    'value' => SchemeColor::COLOR_1,
			                ],
			                'selectors' => [
			                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .subtitle' => 'color: {{VALUE}};',
			                ],
			            ],
	                    [
	                    	'name' => 'subtitle_animation',
	                    	'label' => $this->l('Animation'),
			                'type' => ControlsManager::SELECT,
			                'default' => '',
			                'options' => $animations,
			            ],
			            [
	                    	'name' => 'divider5',
	                    	'type' => ControlsManager::DIVIDER,
	                    ],
			            // Button
			            [
	                    	'name' => 'heading5',
	                    	'label' => $this->l('Button link'),
	                    	'type' => ControlsManager::HEADING,
	                    ],
	                    [
	                        'name' => 'button',
	                        'label' => '',
	                        'type' => ControlsManager::TEXT,
	                        'show_label' => false,	                    
	                        'label_block' => true,	
	                    ],
	                    [
	                        'name' => 'link',
	                        'label' => $this->l('Link'),
	                        'type' => ControlsManager::URL,
	                        'placeholder' => $this->l('https://your-link.com'),
	                    ],
	                    [
	                    	'name' => 'button_animation',
	                    	'label' => $this->l('Animation'),
			                'type' => ControlsManager::SELECT,
			                'default' => '',
			                'options' => $animations,
			            ],
	                ],
	                
	            ]
	        );
            
        $this->endControlsSection();

    	$this->startControlsSection(
		'title1_section',
			[
				'label' => $this->l( 'Title 1'),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
        	$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'title1_typography',
					'selector' => '{{WRAPPER}} .pos-slideshow-wrapper .title1',
					'separator' => 'none',
				]
			);

        	$this->addResponsiveControl(
            	'title1_space',
    			[
                    'label' => $this->l('Space'),
                    'type' => ControlsManager::SLIDER,
                    'default' => [
                        'size' => 10,
                        'unit' => 'px',
                    ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title1' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'none',
                ]
        	);
        $this->endControlsSection();
        $this->startControlsSection(
		'title2_section',
			[
				'label' => $this->l( 'Title 2'),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
        	$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'title2_typography',
					'selector' => '{{WRAPPER}} .pos-slideshow-wrapper .title2',
					'separator' => 'none',
				]
			);
        	$this->addResponsiveControl(
            	'title2_space',
    			[
                    'label' => $this->l('Space'),
                    'type' => ControlsManager::SLIDER,
                    'default' => [
                        'size' => 10,
                        'unit' => 'px',
                    ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .pos-slideshow-wrapper .title2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'none',
                ]
        	);
        $this->endControlsSection();
        $this->startControlsSection(
		'title3_section',
			[
				'label' => $this->l( 'Title 3'),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
        	$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'title3_typography',
					'selector' => '{{WRAPPER}} .pos-slideshow-wrapper .title3',
					'separator' => 'none',
				]
			);
        	$this->addResponsiveControl(
            	'title3_space',
    			[
                    'label' => $this->l('Space'),
                    'type' => ControlsManager::SLIDER,
                    'default' => [
                        'size' => 10,
                        'unit' => 'px',
                    ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .pos-slideshow-wrapper .title3' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'none',
                ]
        	);
        $this->endControlsSection();
        $this->startControlsSection(
		'subtitle_section',
			[
				'label' => $this->l( 'Subtitle'),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
        	$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'subtitle_typography',
					'selector' => '{{WRAPPER}} .pos-slideshow-wrapper .subtitle',
					'separator' => 'none',
				]
			);
        	$this->addResponsiveControl(
            	'subtitle_space',
    			[
                    'label' => $this->l('Space'),
                    'type' => ControlsManager::SLIDER,
                    'default' => [
                        'size' => 10,
                        'unit' => 'px',
                    ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'none',
                ]
        	);
        $this->endControlsSection();
        $this->startControlsSection(
		'button_section',
			[
				'label' => $this->l( 'Button'),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
        	$this->addGroupControl(
	            GroupControlTypography::getType(),
	            array(
	                'name' => 'typography',
	                'label' => __('Typography', 'elementor'),
	                'scheme' => SchemeTypography::TYPOGRAPHY_4,
	                'selector' => '{{WRAPPER}} a.slideshow-button',
	            )
	        );

	        $this->startControlsTabs('tabs_button_style');

	        $this->startControlsTab(
	            'tab_button_normal',
	            array(
	                'label' => __('Normal', 'elementor'),
	            )
	        );

	        $this->addControl(
	            'button_text_color',
	            array(
	                'label' => __('Text Color', 'elementor'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} a.slideshow-button' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => __('Background Color', 'elementor'),
	                'type' => ControlsManager::COLOR,
	                'scheme' => array(
	                    'type' => SchemeColor::getType(),
	                    'value' => SchemeColor::COLOR_1,
	                ),
	                'selectors' => array(
	                    '{{WRAPPER}} a.slideshow-button' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->endControlsTab();

	        $this->startControlsTab(
	            'tab_button_hover',
	            array(
	                'label' => __('Hover', 'elementor'),
	            )
	        );

	        $this->addControl(
	            'hover_color',
	            array(
	                'label' => __('Text Color', 'elementor'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} a.slideshow-button:hover' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'button_background_hover_color',
	            array(
	                'label' => __('Background Color', 'elementor'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} a.slideshow-button:hover' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'button_hover_border_color',
	            array(
	                'label' => __('Border Color', 'elementor'),
	                'type' => ControlsManager::COLOR,
	                'condition' => array(
	                    'border_border!' => '',
	                ),
	                'selectors' => array(
	                    '{{WRAPPER}} a.slideshow-button:hover' => 'border-color: {{VALUE}};',
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

	        $this->endControlsTab();

	        $this->endControlsTabs();

	        $this->addGroupControl(
	            GroupControlBorder::getType(),
	            array(
	                'name' => 'border',
	                'label' => __('Border', 'elementor'),
	                'placeholder' => '1px',
	                'default' => '1px',
	                'selector' => '{{WRAPPER}} a.slideshow-button',
	            )
	        );

	        $this->addControl(
	            'border_radius',
	            array(
	                'label' => __('Border Radius', 'elementor'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} a.slideshow-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );

	        $this->addGroupControl(
	            GroupControlBoxShadow::getType(),
	            array(
	                'name' => 'button_box_shadow',
	                'selector' => '{{WRAPPER}} a.slideshow-button',
	            )
	        );

	        $this->addResponsiveControl(
	            'text_padding',
	            array(
	                'label' => __('Text Padding', 'elementor'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', 'em', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} a.slideshow-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	                'separator' => 'before',
	            )
	        );
		$this->endControlsSection();
		
		//Tab Setting
		$this->startControlsSection(
			'setting_section',
			[
				'label' => $this->l( 'Slider'),
				'tab' => ControlsManager::TAB_SETTINGS,
			]
		);
			$this->addControl(
				'arrows',
				[
					'label' 		=> $this->l('Arrows', [], 'Admin.Global'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' 		=> 'yes',
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);

			$this->addControl(
				'dots',
				[
					'label' 		=> $this->l('Dots', [], 'Admin.Global'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' 		=> 'no',
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);
			$this->addControl(
				'autoplay',
				[
					'label' => $this->l( 'Autoplay'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' => 'false',  
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);
			$this->addControl(
				'autoplay_speed',
				[
					'label'     	=> $this->l('AutoPlay Transition Speed (ms)', [], 'Admin.Global'),
					'type'      	=> ControlsManager::NUMBER,
					'default'  	 	=> 3000,
				]
			);
			$this->addControl(
				'pause_on_hover',
				[
					'label' 		=> $this->l('Pause on Hover', [], 'Admin.Global'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' 		=> 'yes',
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);

			$this->addControl(
				'infinite',
				[
					'label'        	=> $this->l('Infinite Loop', [], 'Admin.Global'),
					'type'         	=> ControlsManager::SWITCHER,
					'default'      	=> 'no',
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);
			$this->addControl(
				'transition_speed',
				[
					'label'     	=> $this->l('Transition Speed (ms)', [], 'Admin.Global'),
					'type'      	=> ControlsManager::NUMBER,
					'default'  	 	=> 500,
				]
			);
		
		$this->endControlsSection();
	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	 
	protected function render() {

		$settings = $this->getSettings(); 

		// Data settings
        $slick_options = [
			'slidesToShow'   => 1,
			'slidesToScroll' => 1,
			'autoplay'       => ($settings['autoplay'] == 'yes') ? true : false,
			'autoplaySpeed'  => (int)$settings['autoplay_speed'] ? (int)$settings['autoplay_speed'] : 5000,
			'infinite'       => ($settings['infinite'] == 'yes') ? true : false,
			'pauseOnHover'   => ($settings['pause_on_hover'] == 'yes') ? true : false,
			'speed'          => (int)$settings['transition_speed'] ? (int)$settings['transition_speed'] : 500,
			'arrows'         => ($settings['arrows'] == 'yes') ? true : false,
			'dots'           => ($settings['dots'] == 'yes') ? true : false, 
			'fade'			 => true,
		]; 
		
		$slick_responsive = [
			'items_laptop'            => 1,
            'items_landscape_tablet'  => 1,
            'items_portrait_tablet'   => 1,
            'items_landscape_mobile'  => 1,
            'items_portrait_mobile'   => 1,
            'items_small_mobile'      => 1,
		];
	 
		
		$this->addRenderAttribute(
			'slideshow', 
			[
				'class' => ['pos-slideshow', 'slick-slider-block', 'column-desktop-1', 'column-tablet-1', 'column-mobile-1'],
				'data-slider_responsive' => json_encode($slick_responsive),
				'data-slider_options' => json_encode($slick_options),
			]
			
		);

		if ( $settings['slideshow_list'] ) { ?>
			<div class="pos-slideshow-wrapper">
				<div <?php echo $this->getRenderAttributeString('slideshow'); ?>>
				<?php foreach (  $settings['slideshow_list'] as $item ) :
					$image = Tools::safeOutput(Helper::getMediaLink($item['slideshow_image']['url']));

					$this->addRenderAttribute('class-item', 'class', ['slideshow-item','elementor-repeater-item-' . $item['_id']]); ?>
					<div <?php echo $this->getRenderAttributeString('class-item'); ?>>

						<div class="slider-item" style="background:url(<?= $image ?>);background-size: cover; background-position: center;">
							<div class="desc-banner">
								<div class="container">				
									<div class="slideshow-content">
										<?php if(isset($item['title1']) && $item['title1'] != '') : ?>
											<div class="title1" data-animation="animated <?= $item['title1_animation'] ?>">
												<?= $item['title1'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['title2']) && $item['title2'] != '') : ?>
											<div class="title2" data-animation="animated <?= $item['title2_animation'] ?>">
												<?= $item['title2'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['title2']) && $item['title3'] != '') : ?>
											<div class="title3" data-animation="animated <?= $item['title3_animation'] ?>">
												<?= $item['title3'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['subtitle']) && $item['subtitle'] != '') : ?>
											<div class="subtitle" data-animation="animated <?= $item['subtitle_animation'] ?>">
												<?= $item['subtitle'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['link']['url']) && $item['link']['url'] != '' && $item['button'] != '') : ?>
											<a class="slideshow-button" href="<?= $item['link']['url'] ?>" data-animation="animated <?= $item['button_animation'] ?>">
												<?= $item['button'] ?>
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
			<?php 
		}  
		

	} 


	protected function _contentTemplate() {
		
	}
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