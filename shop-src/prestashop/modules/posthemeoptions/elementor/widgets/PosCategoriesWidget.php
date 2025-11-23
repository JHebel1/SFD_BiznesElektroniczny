<?php



namespace CE;



defined('_PS_VERSION_') or die;



use Category;

use Configuration;

use Context;

use DB;

use ImageType;

use Shop;

use Validate;

use Posthemes\Module\Poselements\WidgetHelper;



class PosCategoriesWidget extends WidgetHelper { 

	public function getName() {

		return 'pos_categories';

	}



	public function getTitle() {

		return $this->l('Pos Categories');

	}



	public function getIcon() { 

		return 'fa fa-server';

	}



	public function getCategories() {

		return [ 'posthemes' ];

	}

 

	



	protected function _registerControls() { 

		 

		// Product

		$this->startControlsSection(

			'categories_section',

			[

				'label' => $this->l( 'Categories' ),

				'tab' => ControlsManager::TAB_CONTENT,

			]

		);

			 

			$this->addControl(

	            'category_id',

	            [

	                'label' => $this->l('Category'),

	                'label_block' => true,

	                'type' => ControlsManager::SELECT2,

	                'options' => $this->adminGetCategories(),

	                'default' => 2,

	                'multiple' => true,

	            ]

	        );



			$designs = array('1' => 'Design 1','2' => 'Design 2','3' => 'Design 3');

			$this->addControl(

				'design',

				[

					'label' => $this->l( 'Select design' ),

					'type' => ControlsManager::SELECT,

					'options' => $designs,

					'frontend_available' => true,

					'default' => '1'

				]

			);

			

			$this->addControl(

				'show_count',

				[

					'label' 		=> $this->l('Show Count Products'),

					'type' 			=> ControlsManager::SWITCHER,

					'return_value' 	=> 'yes',

					'default' 		=> '', 

					'label_on'      => $this->l('Yes'),

                    'label_off'     => $this->l('No'),

				]

			);

			$this->addControl(

				'show_subcategories',

				[

					'label' 		=> $this->l('Show Subcategories'),

					'type' 			=> ControlsManager::SWITCHER,

					'return_value' 	=> 'yes',

					'default' 		=> '', 

					'label_on'      => $this->l('Yes'),

                    'label_off'     => $this->l('No'),

				]

			);

			$this->addControl(

				'limit_subcategories',

				[

					'label' => $this->l( 'Limit subcategories' ),

					'type' => ControlsManager::NUMBER,

					'min' => 1,

					'max' => 10,

					'step' => 1,

					'default' => 3,

					'condition'    	=> [

						'show_subcategories' => 'yes',

					],

				]

			);

			$this->addControl(

				'show_link',

				[

					'label' 		=> $this->l('Show Link View'),

					'type' 			=> ControlsManager::SWITCHER,

					'return_value' 	=> 'yes',

					'default' 		=> '', 

					'label_on'      => $this->l('Yes'),

                    'label_off'     => $this->l('No'),

				]

			);

			$this->addControl(

				'enable_slider',

				[

					'type' => ControlsManager::HIDDEN,

					'default' => 'yes'

				]

			);

		$this->endControlsSection(); 

		 // Start for style

        $this->startControlsSection(

            'section_general',

            [

                'label' => __('Item categories'),

                'tab' => ControlsManager::TAB_STYLE,

            ]

        );

		

            $this->addControl(

				'item_background',

				[

					'label' 		=> $this->l('background'),

					'type' 			=> ControlsManager::COLOR,

					'selectors' 	=> [

						'{{WRAPPER}} .category-item > div' => 'background: {{VALUE}};',

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

						'{{WRAPPER}} .category-item > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

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

						'{{WRAPPER}} .category-item > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

					],

				]

			);

			$this->addGroupControl(

				GroupControlBorder::getType(),

				[

					'name' 			=> 'item_border',

					'selector' 		=> '{{WRAPPER}} .category-item > div',

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

					'{{WRAPPER}} .category-item > div .category-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				],

			]

		);



        $this->addControl(

            'image_size',

            array(

                'label' => __('Image Size'),

                'type' => ControlsManager::SLIDER,

                'default' => array(

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

                    '{{WRAPPER}} .category-item > div .category-image' => 'width: {{SIZE}}{{UNIT}};',

                ),

            )

        );

		$this->addResponsiveControl(

			'img_border_radius',

			[

				'label' 		=> $this->l('Border Radius'),

				'type' 			=> ControlsManager::DIMENSIONS,

				'size_units' 	=> [ 'px', '%' ],

				'selectors' 	=> [

					'{{WRAPPER}} .category-item > div .category-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				],

			]

		);

		$this->addGroupControl(

			GroupControlBorder::getType(),

			[

				'name' 			=> 'img_border',

				'selector' 		=> '{{WRAPPER}} .category-item > div .category-image',

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

            'section_style_content',

            array(

                'label' => __('content'),

                'tab' => ControlsManager::TAB_STYLE,

            )

        );

		$this->addResponsiveControl(

		'content_padding',

			[

				'label' 		=> $this->l('Padding'),

				'type' 			=> ControlsManager::DIMENSIONS,

				'size_units' 	=> [ 'px', '%' ],

				'selectors' 	=> [

					'{{WRAPPER}} .categories-container .category-item .category-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				],

			]

		);

        $this->addResponsiveControl(

            'hor_align',

            array(

                'label' => __('Alignment', 'elementor'),

                'type' => ControlsManager::CHOOSE,

                'options' => array(

                    'flex-start' => array(

                        'title' => __('Left', 'elementor'),

                        'icon' => 'fa fa-align-left',

                    ),

                    'center' => array(

                        'title' => __('Center', 'elementor'),

                        'icon' => 'fa fa-align-center',

                    ),

                    'flex-end' => array(

                        'title' => __('Right', 'elementor'),

                        'icon' => 'fa fa-align-right',

                    ),

                ),

                'selectors' => array(

                    '{{WRAPPER}} .categories-container .category-item .category-content,{{WRAPPER}} .categories-container .category-item .category-content ul' => 'align-items: {{VALUE}};text-align: center;',

                ),

            )

        );

		$this->addControl(

			'ver_align',

			[

				'label' => $this->l( 'Vertical Alignment' ),

				'type' => ControlsManager::CHOOSE,

				'options' => [

					'flex-start' => [

						'title' => $this->l( 'Top' ),

						'icon' => 'fa fa-long-arrow-up',

					],

					'center' => [

						'title' => $this->l( 'Middle' ),

						'icon' => 'fa fa-arrows-h',

					],

					'flex-end' => [

						'title' => $this->l( 'Bottom' ),

						'icon' => 'fa fa-long-arrow-down',

					],

				],

				'default' => 'center',

				'toggle' => true,

				'selectors' => [

					'{{WRAPPER}} .categories-container .category-item .category-content' => 'justify-content: {{VALUE}};',

				],

			]

		);

		

        $this->endControlsSection();

        $this->startControlsSection(

			'section_name_cate',

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

						'{{WRAPPER}} .categories-container .category-item > div .category-content .name' => 'color: {{VALUE}};',

					],

				]

			);

			$this->addControl(

				'name_hover_color',

				[

					'label' 		=> $this->l('Hover Color'),

					'type' 			=> ControlsManager::COLOR,

					'selectors' 	=> [

						'{{WRAPPER}} .categories-container .category-item > div .category-content .name:hover' => 'color: {{VALUE}};',

					],

				]

			);

			$this->addGroupControl(

				GroupControlTypography::getType(),

				[

					'name' 			=> 'name_typo',

					'selector' 		=> '{{WRAPPER}} .categories-container .category-item > div .category-content .name',

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

						'{{WRAPPER}} .categories-container .category-item > div .category-content .name' => 'margin-bottom: {{SIZE}}{{UNIT}};',

					],

				]

			);

		$this->endControlsSection();

		$this->startControlsSection(

			'section_count_style',

			[

				'label' 		=> $this->l('Count Products	'),

				'tab' 			=> ControlsManager::TAB_STYLE,

			]

		);

		

			$this->addControl(

				'count_color',

				[

					'label' 		=> $this->l('Color'),

					'type' 			=> ControlsManager::COLOR,

					'selectors' 	=> [

						'{{WRAPPER}} .categories-container .category-item > div .category-content .count' => 'color: {{VALUE}};',

					],

				]

			);



			$this->addGroupControl(

				GroupControlTypography::getType(),

				[

					'name' 			=> 'count_typo',

					'selector' 		=> '{{WRAPPER}} .categories-container .category-item > div .category-content .count',

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

						'{{WRAPPER}} .categories-container .category-item > div .category-content .count' => 'margin-bottom: {{SIZE}}{{UNIT}};',

					],

				]

			);



        $this->endControlsSection();



		$this->startControlsSection(

			'section_subcate_style',

			[

				'label' 		=> $this->l('subcategories'),

				'tab' 			=> ControlsManager::TAB_STYLE,

			]

		);



			$this->addControl(

				'subcate_color',

				[

					'label' 		=> $this->l('Color'),

					'type' 			=> ControlsManager::COLOR,

					'selectors' 	=> [

						'{{WRAPPER}} .categories-container .category-item > div .category-content ul li a' => 'color: {{VALUE}};',

					],

				]

			);

			$this->addControl(

				'subcate_hover_color',

				[

					'label' 		=> $this->l('Hover Color'),

					'type' 			=> ControlsManager::COLOR,

					'selectors' 	=> [

						'{{WRAPPER}} .categories-container .category-item > div .category-content ul li a:hover' => 'color: {{VALUE}};',

					],

				]

			);

			$this->addGroupControl(

				GroupControlTypography::getType(),

				[

					'name' 			=> 'subcate_typo',

					'selector' 		=> '{{WRAPPER}} .categories-container .category-item > div .category-content ul li a',

				]

			);

			$this->addResponsiveControl(

				'subcate_spacing',

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

						'{{WRAPPER}} .categories-container .category-item > div .category-content ul' => 'margin-bottom: {{SIZE}}{{UNIT}};',

					],

				]

			);



        $this->endControlsSection();



        $this->startControlsSection(

			'section_link',

			[

				'label' 		=> $this->l('Link View'),

				'tab' 			=> ControlsManager::TAB_STYLE,

			]

		);

			$this->addGroupControl(

				GroupControlTypography::getType(),

				[

					'name' 			=> 'link_typo',

					'selector' 		=> '{{WRAPPER}} .categories-container .category-item > div .category-content .link',

				]

			);

			$this->addResponsiveControl(

				'link_padding',

				[

					'label' 		=> $this->l('Padding'),

					'type' 			=> ControlsManager::DIMENSIONS,

					'size_units' 	=> [ 'px', '%' ],

					'selectors' 	=> [

						'{{WRAPPER}} .categories-container .category-item > div .category-content .link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

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

						'{{WRAPPER}} .categories-container .category-item > div .category-content .link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

					],

				]

			);

			$this->addGroupControl(

				GroupControlBorder::getType(),

				[

					'name' 			=> 'link_border',

					'selector' 		=> '{{WRAPPER}} .categories-container .category-item > div .category-content .link',

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

								'{{WRAPPER}} .categories-container .category-item > div .category-content .link' => 'color: {{VALUE}};',

							],

						]

					);



					$this->addControl(

						'link_background',

						[

							'label' 		=> $this->l('Background color'),

							'type' 			=> ControlsManager::COLOR,

							'selectors' 	=> [

								'{{WRAPPER}} .categories-container .category-item > div .category-content .link' => 'background-color: {{VALUE}};',

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

								'{{WRAPPER}} .categories-container .category-item > div .category-content .link:hover , {{WRAPPER}} .categories-container .category-item > div .category-content .link:focus' => 'color: {{VALUE}};',

							],

						]

					);

					$this->addControl(

						'link_hover_background',

						[

							'label' 		=> $this->l('Background color'),

							'type' 			=> ControlsManager::COLOR,

							'selectors' 	=> [

								'{{WRAPPER}} .categories-container .category-item > div .category-content .link:hover, {{WRAPPER}} .categories-container .category-item > div .category-content .link:focus' => 'background-color: {{VALUE}};',

							],

						]

					);

					$this->addControl(

						'link_hover_border_color',

						[

							'label' 		=> $this->l('Border color'),

							'type' 			=> ControlsManager::COLOR,

							'selectors' 	=> [

								'{{WRAPPER}} .categories-container .category-item > div .category-content .link:hover, {{WRAPPER}} .categories-container .category-item > div .category-content .link:focus' => 'border-color: {{VALUE}};',

							],

						]

					);

					

				$this->endControlsTab();

			$this->endControlsTabs();

        $this->endControlsSection();

		

		//Slider Setting

		$this->addCarouselControls($this->getName() , 4);



	}

	

	protected function render() {

		if (is_admin()){

			return print '<div class="ce-remote-render"></div>';

		}

		$settings = $this->getSettings(); 

		if(empty($settings['category_id'])) {

			echo 'Please configure and select categories to show'; return false;

		}



		$context = \Context::getContext();

		$id_lang = $context->language->id;

		// Data settings

		$slick_options = [

			'slidesToShow'   => ($settings['items']) ? (int)$settings['items'] : 4,

			'slidesToScroll' => ($settings['slides_to_scroll']) ? (int)$settings['slides_to_scroll'] : 1,

			'autoplay'       => ($settings['autoplay'] == 'yes') ? true : false,

			'autoplaySpeed'  => ($settings['autoplay_speed']) ? (int)$settings['autoplay_speed'] : 5000,

			'infinite'       => ($settings['infinite'] == 'yes') ? true : false,

			'speed'          => ($settings['transition_speed']) ? (int)$settings['transition_speed'] : 500,

			'arrows'         => ($settings['arrows'] == 'yes') ? true : false,

			'dots'           => ($settings['dots'] == 'yes') ? true : false, 

			'rows'         	 => (int) $settings['rows'] ? $settings['rows'] : 1,

		];  



		$responsive = array();

		if($settings['responsive'] == 'default') {

			$responsive = $this->posDefaultResponsive((int)$settings['items']);

		}else{

			$default_responsive = $this->posDefaultResponsive((int)$settings['items']);

			$responsive = array(

				'xl' => $settings['items_laptop'] ? (int)$settings['items_laptop'] : $default_responsive['xl'],

				'lg' => $settings['items_landscape_tablet'] ? (int)$settings['items_landscape_tablet'] : $default_responsive['lg'],

				'md' => $settings['items_portrait_tablet'] ? (int)$settings['items_portrait_tablet'] : $default_responsive['md'],

				'sm' => $settings['items_landscape_mobile'] ? (int)$settings['items_landscape_mobile'] : $default_responsive['sm'],

				'xs' => $settings['items_portrait_mobile'] ? (int)$settings['items_portrait_mobile'] : $default_responsive['xs'],

				'xxs' => $settings['items_small_mobile'] ? (int)$settings['items_small_mobile'] : $default_responsive['xxs'],

			);

		}

		if($settings['slides_to_scroll'] == '1'){

			$scroll = true;

		}else{

			$scroll = false;

		}

		$slick_responsive = [

			'items_laptop'            => $responsive['xl'],

            'items_landscape_tablet'  => $responsive['lg'],

            'items_portrait_tablet'   => $responsive['md'],

            'items_landscape_mobile'  => $responsive['sm'],

            'items_portrait_mobile'   => $responsive['xs'],

            'items_small_mobile'      => $responsive['xxs'],

            'scroll' 				  => $scroll,

		];

		$this->addRenderAttribute(

			'data', 

			[

				'class' => ['categories-container', 'slick-slider-block', 'column-desktop-'. $responsive['xl'],'column-tablet-'. $responsive['md'],'column-mobile-'. $responsive['xs']],

				'data-slider_options' => json_encode($slick_options),

				'data-slider_responsive' => json_encode($slick_responsive),

			]

			

		);

		$content_checker = 0;

		$html = '';

		$html .= '<div '. $this->getRenderAttributeString('data') .'>'; 



				foreach($settings['category_id'] as $id_category) {



					$category  = new \Category( $id_category, (int) $context->language->id );

					if( !$category->id ) continue;

					$content_checker = 1;

					$link = new \link();

					$category_thumb =  $link->getBaseLink() .'img/c/'. $id_category .'_thumb.jpg';
					$thumb = _PS_CAT_IMG_DIR_. $id_category .'_thumb.jpg';
					if (! @getimagesize($thumb)) {

						$category_thumb = $link->getBaseLink() .'img/c/en.jpg';

					}

					$category_link = $category->getLink();



					$html .= '<div class="category-item">';

							if($settings['design'] == '1') {

								$html .= '<div class="style1">';

									$html .= '<div class="category-image">';

										if(!empty($category_thumb)){

											$html .= '<a href="'. $category_link .'"><img src="'. $category_thumb .'" alt=""/></a>';

										}

									$html .= '</div>';

									$html .= '<div class="category-content">';

										$html .= '<a class="name" href="'. $category_link .'">'. $category->name .'</a>';

										if($settings['show_count']) {

											$html .= '<p class="count">'. $category->getProducts(null, null, null, null, null, true) .' Products</p>';

										}



										if($settings['show_subcategories']) { 

											$subcategories = $category->getSubCategories($id_lang , true);

											$limit = 99;

											if((int)$settings['limit_subcategories']) $limit = (int)$settings['limit_subcategories'];

											$html .= '<ul>';

											foreach($subcategories as $key => $sub){

												if($key == $limit)  break;

												$subcategory = new \Category( $sub['id_category'] , (int) $context->language->id );

												$html .= '<li><a href="'. $subcategory->getLink() .'">'. $sub['name'] .'</a></li>';

											}

											$html .= '</ul>';

										}

										

										if($settings['show_link']) {

											$html .= '<a class="link" href="'. $category_link .'">'. $this->l( 'View all' ) .'</a>';

										}

									$html .= '</div>';

								$html .= '</div>';

							}

							if($settings['design'] == '2') {

								$html .= '<div class="style2">';

									$html .= '<div class="category-image">';

										if(!empty($category_thumb)){

											$html .= '<a href="'. $category_link .'"><img src="'. $category_thumb .'" alt=""/></a>';

										}

									$html .= '</div>';

									$html .= '<div class="category-content">';

										$html .= '<a class="name" href="'. $category_link .'">'. $category->name .'</a>';

										if($settings['show_count']) {

											$html .= '<p class="count">'. $category->getProducts(null, null, null, null, null, true) .' Products</p>';

										}

										if($settings['show_subcategories']) { 

											$subcategories = $category->getSubCategories($id_lang , true);

											$limit = 99;

											if($settings['limit_subcategories']) $limit = $settings['limit_subcategories'];

											$html .= '<ul>';

											foreach($subcategories as $key => $sub){

												if($key == $limit)  break;

												$subcategory = new \Category( $sub['id_category'] , (int) $context->language->id );

												$html .= '<li><a href="'. $subcategory->getLink() .'">'. $sub['name'] .'</a></li>';

											}

											$html .= '</ul>';

										}

										if($settings['show_link']) {

											$html .= '<a class="link" href="'. $category_link .'">'. $this->l( 'View all' ) .'</a>';

										}

										

										

										

									$html .= '</div>';

								$html .= '</div>';

							}

							if($settings['design'] == '3') {

								$html .= '<div class="style3">';

									$html .= '<div class="category-image">';

										if(!empty($category_thumb)){

											$html .= '<a href="'. $category_link .'"><img src="'. $category_thumb .'" alt=""/></a>';

										}

									$html .= '</div>';

									$html .= '<div class="category-content">';

										$html .= '<a class="name" href="'. $category_link .'">'. $category->name .'</a>';

										if($settings['show_count']) {

											$html .= '<p class="count">'. $category->getProducts(null, null, null, null, null, true) .' Products</p>';

										}

										if($settings['show_subcategories']) { 

											$subcategories = $category->getSubCategories($id_lang , true);

											$limit = 99;

											if($settings['limit_subcategories']) $limit = $settings['limit_subcategories'];

											$html .= '<ul>';

											foreach($subcategories as $key => $sub){

												if($key == $limit)  break;

												$subcategory = new \Category( $sub['id_category'] , (int) $context->language->id );

												$html .= '<li><a href="'. $subcategory->getLink() .'">'. $sub['name'] .'</a></li>';

											}

											$html .= '</ul>';

										}

										if($settings['show_link']) {

											$html .= '<a class="link" href="'. $category_link .'">'. $this->l( 'View all' ) .'</a>';

										}

										

										

										

									$html .= '</div>';

								$html .= '</div>';

							}

					$html .= '</div>';

				}

				 

		$html .= '</div>';



		if(!$content_checker) {

			echo 'Please configure and select categories to show'; return false;

		}

		echo $html;

	}



	public function adminGetCategories()

    {

        

        $range = '';

        $maxdepth = 5;

        $categories_list = [];

        $category = new \Category((int)Configuration::get('PS_HOME_CATEGORY'));



        if (Validate::isLoadedObject($category))

        {

            if ($maxdepth > 0)

            {

                $maxdepth += $category->level_depth;

            }

            $range = 'AND nleft >= '.(int)$category->nleft.' AND nright <= '.(int)$category->nright;

        }



        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('

            SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite

            FROM `'._DB_PREFIX_.'category` c

            INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.Context::getContext()->language->id.Shop::addSqlRestrictionOnLang('cl').')

            INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.Context::getContext()->shop->id.')

            WHERE (c.`active` = 1 OR c.`id_category` = '.(int)Configuration::get('PS_HOME_CATEGORY').')

            AND c.`id_category` != '.(int)Configuration::get('PS_ROOT_CATEGORY').'

            '.((int)$maxdepth != 0 ? ' AND `level_depth` <= '.(int)$maxdepth : '').'

            '.$range.'

            ORDER BY `level_depth` ASC, '.(Configuration::get('BLOCK_CATEG_SORT') ? 'cl.`name`' : 'cs.`position`').' '.(Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC'));



        foreach ($result as &$row)

        {

            $categories_list[$row['id_category']] = $row['name'];

        }



            

        

        return $categories_list;

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

        return translate($string, 'poselements', basename(__FILE__, '.php'));

    }

}