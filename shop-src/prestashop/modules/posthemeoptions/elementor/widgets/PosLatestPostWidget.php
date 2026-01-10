<?php  

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Employee;
use Posthemes\Module\Poselements\WidgetHelper;

class PosLatestPostWidget extends WidgetHelper { 
	public function getName() {
		return 'pos_latestpost';
	}

	public function getTitle() {
		return $this->l('Pos Latest Posts');
	}

	public function getIcon() { 
		return 'fa fa-leanpub';
	}

	public function getCategories() {
		return [ 'posthemes' ];
	}

	protected function _registerControls() { 
		
		//Elements
		$this->startControlsSection(
            'section_content',
            [
                'label' => $this->l('Content'),
            ]
		);

			$this->addControl(
				'limit',
				[
					'label' 		=> $this->l('Limit display'),
					'type' 			=> ControlsManager::NUMBER,
					'default' 		=> 6,
					'separator' 	=> 'before',
				]
			);
			$this->addControl(
				'design',
				[
					'label' => $this->l( 'Design' ),
					'type' => ControlsManager::SELECT,
					'options' => [
						'1'  => $this->l( 'Design 1' ),
						'2'  => $this->l( 'Design 2' ),
						'3'  => $this->l( 'Design 3' ),
						'4'  => $this->l( 'Design 4' ),
						'5'  => $this->l( 'Design 5' ),
					],
					'frontend_available' => true,
					'default' => '1'
				]
			);

			$this->addControl(
				'enable_slider',
				[
					'label' 		=> $this->l('Enable Slider'),
					'type' 			=> ControlsManager::HIDDEN,
					'default' 		=> 'yes', 
				]
			);

		$this->endControlsSection();
		 // Start for style
        $this->startControlsSection(
            'section_item',
            [
                'label' => __('Item blog'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
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
                ),
                'selectors' => array(
                    '{{WRAPPER}} .post-item' => 'text-align: {{VALUE}};',
                ),
            )
        );
            $this->addControl(
				'item_background',
				[
					'label' 		=> $this->l('background'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .post-item' => 'background: {{VALUE}};',
					],
				]
			);
			$this->addResponsiveControl(
			'item_padding',
				[
					'label' 		=> $this->l('Padding'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'item_border_radius',
				[
					'label' 		=> $this->l('Border Radius'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .post-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' 			=> 'item_border',
					'selector' 		=> '{{WRAPPER}} .post-item',
				]
			);
        $this->endControlsSection();
		$this->startControlsSection(
            'section_style_image',
            array(
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
			'images_margin',
			[
				'label' 		=> $this->l('Margin'),
				'type' 			=> ControlsManager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .post-item .post-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->addResponsiveControl(
			'img_border_radius',
			[
				'label' 		=> $this->l('Border Radius'),
				'type' 			=> ControlsManager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .post-item .post-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->addGroupControl(
			GroupControlBorder::getType(),
			[
				'name' 			=> 'img_border',
				'selector' 		=> '{{WRAPPER}} .post-item .post-image',
			]
		);
		$this->addControl(
		'hover_animation',
			[
				'label' => __('Hover animation'),
				'type' => ControlsManager::SELECT,
				'default' => 'animation',
				'options' => [ 
					'animation' => __('animation'),
					'none' => __('none')
				],
				'prefix_class' => 'hover-',
				'render_type' => 'template',
				'frontend_available' => true
			]
		);
		
        $this->endControlsSection();
        $this->startControlsSection(
			'section_name_blog',
			[
				'label' 		=> $this->l('Name'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);

			$this->addControl(
				'name_color',
				[
					'label' 		=> $this->l('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .post-item .post-content .post-title' => 'color: {{VALUE}};',
					],
				]
			);
			$this->addControl(
				'name_hover_color',
				[
					'label' 		=> $this->l('Hover Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .post-item .post-content .post-title:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'name_typo',
					'selector' 		=> '{{WRAPPER}} .post-item .post-content .post-title',
				]
			);
			$this->addResponsiveControl(
				'name_spacing',
				[
					'label' => $this->l( 'Spacing' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .post-item .post-content .post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
			'section_meta_style',
			[
				'label' 		=> $this->l('meta'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
		
			$this->addControl(
				'count_color',
				[
					'label' 		=> $this->l('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .post-item .post-content .post-meta' => 'color: {{VALUE}};',
					],
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'count_typo',
					'selector' 		=> '{{WRAPPER}} .post-item .post-content .post-meta',
				]
			);
			$this->addResponsiveControl(
				'count_spacing',
				[
					'label' => $this->l( 'Spacing' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .post-item .post-content .post-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

        $this->endControlsSection();

		$this->startControlsSection(
			'section_desc_style',
			[
				'label' 		=> $this->l('desc'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);

			$this->addControl(
				'desc_color',
				[
					'label' 		=> $this->l('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .post-item .post-content .post-description' => 'color: {{VALUE}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'desc_typo',
					'selector' 		=> '{{WRAPPER}} .post-item .post-content .post-description',
				]
			);
			$this->addResponsiveControl(
				'desc_spacing',
				[
					'label' => $this->l( 'Spacing' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .post-item .post-content .post-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

        $this->endControlsSection();

        $this->startControlsSection(
			'section_link',
			[
				'label' 		=> $this->l('Read more'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'link_typo',
					'selector' 		=> '{{WRAPPER}} .post-item .post-content .read_more a',
				]
			);
			$this->addResponsiveControl(
				'link_padding',
				[
					'label' 		=> $this->l('Padding'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .post-item .post-content .read_more a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'link_border_radius',
				[
					'label' 		=> $this->l('Border Radius'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .post-item .post-content .read_more a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' 			=> 'link_border',
					'selector' 		=> '{{WRAPPER}} .post-item .post-content .read_more a',
				]
			);
			$this->startControlsTabs('tabs_link_style');
				$this->startControlsTab(
					'tab_link_normal',
					[
						'label' 		=> $this->l('Normal'),
					]
				);
					$this->addControl(
						'link_color',
						[
							'label' 		=> $this->l('Color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .post-item .post-content .read_more a' => 'color: {{VALUE}};',
							],
						]
					);

					$this->addControl(
						'link_background',
						[
							'label' 		=> $this->l('Background color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .post-item .post-content .read_more a' => 'background-color: {{VALUE}};',
							],
						]
					);
					
					
				$this->endControlsTab();
				$this->startControlsTab(
					'tab_hover_normal',
					[
						'label' 		=> $this->l('Hover'),
					]
				);
					$this->addControl(
						'link_hover_color',
						[
							'label' 		=> $this->l('Color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .post-item .post-content .read_more a:hover , {{WRAPPER}} .post-item .post-content .read_more a:focus' => 'color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'link_hover_background',
						[
							'label' 		=> $this->l('Background color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .post-item .post-content .read_more a:hover, {{WRAPPER}} .post-item .post-content .read_more a:focus' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'link_hover_border_color',
						[
							'label' 		=> $this->l('Border color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .post-item .post-content .read_more a:hover, {{WRAPPER}} .post-item .post-content .read_more a:focus' => 'border-color: {{VALUE}};',
							],
						]
					);
					
				$this->endControlsTab();
			$this->endControlsTabs();
        $this->endControlsSection();
		//Slider Setting
		$this->addCarouselControls($this->getName() , 3);
	}

	protected function render() {

		if(! \Module::isInstalled('smartblog') || ! \Module::isEnabled('smartblog')) return;

		$settings = $this->getSettings();
		$context = \Context::getContext();
		$output = '';

		
		$responsive = array();
		if($settings['responsive'] == 'default') {
			$responsive = $this->posDefaultResponsive((int)$settings['items']);
		}else{
			$default_responsive = $this->posDefaultResponsive((int)$settings['items']);
			
			$responsive = array(
				'xl' => $settings['items_laptop'] ? $settings['items_laptop'] : $default_responsive['xl'],
				'lg' => $settings['items_landscape_tablet'] ? $settings['items_landscape_tablet'] : $default_responsive['lg'],
				'md' => $settings['items_portrait_tablet'] ? $settings['items_portrait_tablet'] : $default_responsive['md'],
				'sm' => $settings['items_landscape_mobile'] ? $settings['items_landscape_mobile'] : $default_responsive['sm'],
				'xs' => $settings['items_portrait_mobile'] ? $settings['items_portrait_mobile'] : $default_responsive['xs'],
				'xxs' => $settings['items_small_mobile'] ? $settings['items_small_mobile'] : $default_responsive['xxs'],
			);
		};

		$slick_options = [
			'slidesToShow' => (int) $settings['items'],
			'rows'         => (int) $settings['rows'] ? $settings['rows'] : 1,
			'autoplay'     => ($settings['autoplay'] == 'yes') ? true : false,
			'infinite'     => ($settings['infinite'] == 'yes') ? true : false,
			'arrows'       => ($settings['arrows'] == 'yes') ? true : false,
			'dots'         => ($settings['dots'] == 'yes') ? true : false,
			'autoplaySpeed' => (int) $settings['autoplay_speed'] ? $settings['autoplay_speed'] : 3000,
			'speed'=> (int) $settings['transition_speed'] ? $settings['transition_speed'] : 3000,
		];
		if($settings['slides_to_scroll'] == '1'){
			$scroll = true;
		}else{
			$scroll = false;
		}
		$slick_responsive = [
			'items_laptop'           => (int)$responsive['xl'],
			'items_landscape_tablet' => (int)$responsive['lg'],
			'items_portrait_tablet'  => (int)$responsive['md'],
			'items_landscape_mobile' => (int)$responsive['sm'],
			'items_portrait_mobile'  => (int)$responsive['xs'],
			'items_small_mobile'     => (int)$responsive['xxs'],
			'scroll' 				 => $scroll,
		];
		$context->smarty->assign(
			array(
				'slick_options' => json_encode($slick_options) ,
				'slick_responsive' => json_encode($slick_responsive),
				'class' => 'column-desktop-'. $settings['items'] . ' column-tablet-'. $slick_responsive['items_landscape_tablet'] .' column-mobile-'. $slick_responsive['items_portrait_mobile'],
			)
		);
		

		$limit =  4;
		if((int)$settings['limit']){
			$limit = (int)$settings['limit'];
		}
		$posts = \SmartBlogPost::GetPostLatestHome($limit);
		
		$smart_blog_link = new \SmartBlogLink();
		$i = 0;
		$imageType = 'home-default';
		$images = \BlogImageType::GetImageByType($imageType);

		foreach ($posts as $post) {
            $posts[$i]['url']          = $smart_blog_link->getSmartBlogPostLink($posts[$i]['id'], $posts[$i]['link_rewrite']);
            $posts[$i]['image']['url'] = $smart_blog_link->getImageLink($posts[$i]['link_rewrite'], $posts[$i]['id'], $imageType);
            
            foreach ($images as $image) {
                if ($image['type'] == 'post') {
                    $posts[$i]['image']['type']   = 'blog_post_'.$imageType;
                    $posts[$i]['image']['width']  = $image['width'];
                    $posts[$i]['image']['height'] = $image['height'];
                    break;
                }
            }
            $i++;
        }
        //echo '<pre>'; print_r($posts); echo '</pre>'; die('x_x');
		$context->smarty->assign(
			array(
				'posts'  => $posts,
				'smartbloglink' => $smart_blog_link,
				'design' => _PS_MODULE_DIR_ . 'posthemeoptions/views/templates/front/blog/blog'.$settings['design'].'.tpl',
			)
		);
		$template_file_name = _PS_MODULE_DIR_ . 'posthemeoptions/views/templates/front/latestposts.tpl';

		$output .= $context->smarty->fetch( $template_file_name );
		echo $output;
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