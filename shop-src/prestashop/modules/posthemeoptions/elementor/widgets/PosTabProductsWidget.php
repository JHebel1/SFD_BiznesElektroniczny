<?php  

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Configuration;
use Posthemes\Module\Poselements\WidgetHelper;

class PosTabProductsWidget extends WidgetHelper { 
	public function getName() {
		return 'pos_tab_products';
	}
	public function getTitle() {
		return $this->l('Pos Tab Products');
	}
	public function getIcon() { 
		return 'fa fa-indent';
	}
	public function getCategories() {
		return [ 'posthemes' ];
	}
 
	protected function _registerControls() {
		$this->startControlsSection(
			'tab_products_section',
			[
				'label' => $this->l( 'Content' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);

		$this->addControl(
            'tab_list',
            array(
                'label' => '',
                'type' => ControlsManager::REPEATER,
                'fields' => array(
                    array(
                		'name' => 'tab_title',
						'label' => $this->l( 'Title' ),
						'type' => ControlsManager::TEXT,
						'default' => $this->l( 'Tab Title'  ),
						'label_block' => true,
                    ),
                    array(
		                'name' => 'listing',
		                'label' => $this->l('Listing'),
		                'type' => ControlsManager::SELECT,
		                'default' => 'category',
		                'options' => $this->getListingOptions(),
		                'separator' => 'before',
                    ),
                    array(
						'name' => 'products',
                    	'label' => $this->l('Product'),
		                'label_block' => true,
		                'type' => ControlsManager::SELECT2,
		                'options' => $this->getProductOptions(),
		                'default' => 2,
		                'multiple' => true,
		                'condition' => [
		                    'listing' => 'products',
		                ],
                    ),
                    array(
		                'name' => 'category_id',
		                'label' => $this->l('Category'),
		                'label_block' => true,
		                'type' => ControlsManager::SELECT2,
		                'options' => $this->getCategoryOptions(),
		                'default' => 2,
		                'condition' => [
		                    'listing' => 'category',
		                ],
                    ),
                    array(
                    	'name' => 'order_by',
		                'label' => $this->l('Order By'),
		                'type' => ControlsManager::SELECT,
		                'default' => 'position',
		                'options' => [
		                    'name' => $this->l('Name'),
		                    'price' => $this->l('Price'),
		                    'position' => $this->l('Popularity'),
		                    'quantity' => $this->l('Sales Volume'),
		                    'date_add' => $this->l('Arrival'),
		                    'date_upd' => $this->l('Update'),
		                ],
		                'condition' => [
		                    'listing!' => 'products',
		                ],
		       		),
		        	array(
		        		'name' => 'order_dir',
		                'label' => $this->l('Order Direction'),
		                'type' => ControlsManager::SELECT,
		                'default' => 'asc',
		                'options' => [
		                    'asc' => $this->l('Ascending'),
		                    'desc' => $this->l('Descending'),
		                ],
		                'condition' => [
		                    'listing!' => 'products',
		                ],
		        	),
			        array(
			        	'name' => 'randomize',
		                'label' => $this->l('Randomize'),
		                'type' => ControlsManager::SWITCHER,
		                'label_on' => $this->l('Yes'),
		                'label_off' => $this->l('No'),
		                'condition' => [
		                    'listing' => ['category', 'products'],
		                ],
			        ),
			        array(
			        	'name' => 'limit',
		                'label' => $this->l('Product Limit'),
		                'type' => ControlsManager::NUMBER,
		                'min' => 1,
		                'default' => 8,
		                'condition' => [
		                    'listing!' => 'products',
		                ],
			        ),
                ),
                'title_field' => '{{{ tab_title }}}',
            )
        );
		
		
		$this->endControlsSection();
		$this->startControlsSection(
			'layout_section',
			[
				'label' => $this->l( 'Layout' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			
			$this->addControl(
				'enable_slider',
				[
					'label' 		=> $this->l('Enable Slider'),
					'type' 			=> ControlsManager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> 'yes', 
				]
			);
			$this->addResponsiveControl(
				'columns',
				[
					'label' => $this->l( 'Columns' ),
					'type' => ControlsManager::SLIDER,
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'size_units' => ['item'],
					'range' => [
						'item' => [
							'min' => 1,
							'max' => 6,
							'step' => 1,
						],
					],
					'desktop_default' => [
						'size' => 4,
						'unit' => 'item',
					],
					'tablet_default' => [
						'size' => 3,
						'unit' => 'item',
					],
					'mobile_default' => [
						'size' => 2,
						'unit' => 'item',
					],
					'condition' 	=> [
						'enable_slider!' => 'yes',
					],
				]
			);
			$product_display = array(
				'default' => 'Defautl',
				'grid1' => 'Grid 1',
				'grid2' => 'Grid 2',
				'grid3' => 'Grid 3',
				'grid4' => 'Grid 4',
				'list'  => 'List',
			);
			$this->addControl(
				'product_display',
				[
					'label' => $this->l( 'Product display' ),
					'type' => ControlsManager::SELECT,
					'options' => $product_display,
					'default' => 'default',
					'description' => $this->l('Default: use themesettings configuration')
				]
			);
		$this->endControlsSection();
		//Slider Setting
		$this->addCarouselControls($this->getName(), 4);

		$this->startControlsSection(
			'section_tp_style',
			[
				'label' 		=> $this->l('Title Style'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
				'title_type',
				[
					'label' => $this->l( 'Title type' ),
					'type' => ControlsManager::SELECT,
					'default' => 'normal',
					'prefix_class' => 'title-',
					'options' => [
						'normal'  => $this->l( 'Normal' ),
						'absolute' => $this->l( 'Absolute' ),
					],
				]
			);
			$this->addResponsiveControl(
				'title_absolute',
				[
					'label' => $this->l( 'Title absolute' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => -20,
					],
					'selectors' => [
						'{{WRAPPER}} .tab-titles' => 'top: {{SIZE}}{{UNIT}};',
					],
					'condition'    	=> [
						'title_type' => 'absolute',
					],
				]
			);
			$this->addControl(
				'title_align',
				[
					'label' => $this->l( 'Title alignment' ),
					'type' => ControlsManager::CHOOSE,
					'options' => [
						'left' => [
							'title' => $this->l( 'Left' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => $this->l( 'Center' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => $this->l( 'Right' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'left',
					'selectors' => [
						'{{WRAPPER}} .tab-titles' => 'text-align: {{VALUE}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'tab_title_typography',
					'selector' => '{{WRAPPER}} .tab-titles li a',
				]
			);
			
			$this->addResponsiveControl(
				'title_padding',
				[
					'label' => $this->l( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tab-titles li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);
			$this->addResponsiveControl(
				'title_space',
				[
					'label' => $this->l( 'Title space' ),
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
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .tab-titles li a' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'title_space_bottom',
				[
					'label' => $this->l( 'Title space bottom' ),
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
						'size' => 25,
					],
					'selectors' => [
						'{{WRAPPER}} .tab-titles' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->startControlsTabs('tabs_title_style');
				$this->startControlsTab(
					'title_normal',
					[
						'label' => $this->l( 'Normal' ),
					]
				);
					$this->addControl(
						'title_color',
						[
							'label' => $this->l( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li a' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'bg_color',
						[
							'label' => $this->l( 'Background color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li a' => 'background-color: {{VALUE}};',
							],
						]
					);
				$this->endControlsTab();
				$this->startControlsTab(
					'title_active',
					[
						'label' => $this->l( 'Active' ),
					]
				);
					$this->addControl(
						'title_active_color',
						[
							'label' => $this->l( 'Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li a.active, {{WRAPPER}} .tab-titles li a:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'bg_active_color',
						[
							'label' => $this->l( 'Background color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li a.active, {{WRAPPER}} .tab-titles li a:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'border_active_color',
						[
							'label' => $this->l( 'Border color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li a.active, {{WRAPPER}} .tab-titles li a:hover' => 'border-color: {{VALUE}};',
							],
						]
					);
				$this->endControlsTab();
			$this->endControlsTabs();
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' => 'border',
					'selector' => '{{WRAPPER}} .tab-titles li a',
					'separator' => 'before',
				]
			);
			$this->addControl(
				'border_radius',
				[
					'label' => $this->l( 'Border Radius' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tab-titles li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBoxShadow::getType(),
				[
					'name' => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .tab-titles li',
				]
			);
			
		$this->endControlsSection();
	}
	protected function render() {
		if (is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}
		$context = \Context::getContext();
		$out_put = '';
		$settings = $this->getSettings();

		if($settings['enable_slider']){
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
				'autoplay'     => $settings['autoplay'] ? true : false,
				'infinite'     => $settings['infinite'] ? true : false,
				'arrows'       => $settings['arrows'] ? true : false,
				'dots'         => $settings['dots'] ? true : false,
				'rows'         => (int) $settings['rows'] ? $settings['rows'] : 1,
				'autoplay_speed' => (int) $settings['autoplay_speed'] ? $settings['autoplay_speed'] : 3000,
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
		}else{
			$context->smarty->assign(
				array(
					'columns_desktop' => ($settings['columns']['size'] == 5) ? '2-4' : (12/$settings['columns']['size']),
					'columns_tablet' => ($settings['columns_tablet']['size'] == 5) ? '2-4' : (12/$settings['columns_tablet']['size']),
					'columns_mobile' => ($settings['columns_mobile']['size'] == 5) ? '2-4' : (12/$settings['columns_mobile']['size']),
				)
			);
		}

		$tab_titles = array();
		$tab_contents = array();

		foreach ( $settings['tab_list'] as $index => $tab ){
			$tab_titles[] = array(
				'id' => $tab['_id'],
				'title' => $tab['tab_title']
			);

			if ($tab['randomize'] && ('category' === $tab['listing'] || 'products' === $tab['listing'])) {
	            $tab['order_by'] = 'rand';
	        }

	        $products = $this->getProducts(
	            $tab['listing'],
	            $tab['order_by'],
	            $tab['order_dir'],
	            $tab['limit'],
	            $tab['category_id'],
	            $tab['products']
	        );
			$tab_contents[] = array(
				'id' => $tab['_id'],
				'products' => $products,
			);
		}
		$default_display = Configuration::get('posthemeoptionsp_display');
		$name_length = Configuration::get('posthemeoptionsp_name_length');
		if(!isset($name_length)) $name_length = 0;
		if($settings['product_display'] == 'default'){
			if(isset($default_display)){
				$display = _PS_THEME_DIR_ . 'templates/catalog/_partials/miniatures/_product/grid'. $default_display .'.tpl';
			}else{
				$display = _PS_THEME_DIR_ . 'templates/catalog/_partials/miniatures/_product/grid1.tpl';
			}
			
		}else{
			$display = _PS_THEME_DIR_ . 'templates/catalog/_partials/miniatures/_product/'. $settings['product_display'] .'.tpl';
		}

		$context->smarty->assign(
			array(
				'tab_titles'         => $tab_titles,
				'tab_contents'       => $tab_contents,
				'elementprefix'       => 'single-product',
				'theme_template_path' => $display,
				'carousel_active' => $settings['enable_slider'],
				'name_length' => $name_length ,
			)
		);
		
		$template_file_name = _PS_MODULE_DIR_ . 'posthemeoptions/views/templates/front/producttabs.tpl';
		
		$out_put .= $context->smarty->fetch( $template_file_name );

		echo $out_put;
	}
	 
	protected function get_products(array $settings){
		//echo '<pre>'; print_r($settings); echo '</pre>'; die('x_x');
		
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