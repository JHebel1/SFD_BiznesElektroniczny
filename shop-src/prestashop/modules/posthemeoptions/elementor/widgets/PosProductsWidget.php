<?php  

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Configuration;
use Posthemes\Module\Poselements\WidgetHelper;

class PosProductsWidget extends WidgetHelper { 
	public function getName() {
		return 'pos_products';
	}

	public function getTitle() {
		return 'Pos Products';
	}

	public function getIcon() { 
		return 'fa fa-area-chart';
	}

	public function getCategories() {
		return [ 'posthemes' ];
	}
	

	protected function _registerControls() { 
		 
		// Resource
		$this->startControlsSection(
			'products_section',
			[
				'label' => $this->l( 'Resource' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
	            'listing',
	            [
	                'label' => $this->l('Listing'),
	                'type' => ControlsManager::SELECT,
	                'default' => 'category',
	                'options' => $this->getListingOptions(),
	                'separator' => 'before',
	            ]
	        );

	        $this->addControl(
	            'products',
	            [
	                'label' => $this->l('Product'),
	                'label_block' => true,
	                'type' => ControlsManager::SELECT2,
	                'options' => $this->getProductOptions(),
	                'default' => 2,
	                'multiple' => true,
	                'condition' => [
	                    'listing' => 'products',
	                ],
	            ]
	        );
	        $this->addControl(
	            'category_id',
	            [
	                'label' => $this->l('Category'),
	                'label_block' => true,
	                'type' => ControlsManager::SELECT2,
	                'options' => $this->getCategoryOptions(),
	                'default' => 2,
	                'condition' => [
	                    'listing' => 'category',
	                ],
	            ]
	        );

	        $this->addControl(
	            'order_by',
	            [
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
	            ]
	        );

	        $this->addControl(
	            'order_dir',
	            [
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
	            ]
	        );

	        $this->addControl(
	            'randomize',
	            [
	                'label' => $this->l('Randomize'),
	                'type' => ControlsManager::SWITCHER,
	                'label_on' => $this->l('Yes'),
	                'label_off' => $this->l('No'),
	                'condition' => [
	                    'listing' => ['category', 'products'],
	                ],
	            ]
	        );

	        $this->addControl(
	            'limit',
	            [
	                'label' => $this->l('Product Limit'),
	                'type' => ControlsManager::NUMBER,
	                'min' => 1,
	                'default' => 8,
	                'condition' => [
	                    'listing!' => 'products',
	                ],
	            ]
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
					'label_on' 		=> $this->l('Yes'),
	                'label_off' 	=> $this->l('No'),
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
					'default' => 'grid1',
					'description' => $this->l('Default: use themesettings configuration')
				]
			);

		$this->endControlsSection();
		 
		
		//Slider Setting
		$this->addCarouselControls($this->getName(), 4);
		
		
		$this->startControlsSection(
			'style_section',
			[
				'label' => $this->l( 'Style' ),
				'tab' => ControlsManager::TAB_STYLE,
				'condition' => [
					'product_display' => 'list',
				],
			]
		);	
			$this->addControl(
				'padding',
				[
					'label' => $this->l( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .style_product_list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' 			=> 'button_border',
					'selector' 		=> '{{WRAPPER}} .style_product_list',
				]
			);
		$this->endControlsSection();

	}
	protected function render() {

		$context = Context::getContext();
		$out_put  = '';

		if (is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}
		if (empty($this->context->currency->id)) {
            return;
        }

        $settings = $this->getSettings();

        if ($settings['randomize'] && ('category' === $settings['listing'] || 'products' === $settings['listing'])) {
            $settings['order_by'] = 'rand';
        }

        $products = $this->getProducts(
            $settings['listing'],
            $settings['order_by'],
            $settings['order_dir'],
            $settings['limit'],
            $settings['category_id'],
            $settings['products']
        );

        if (empty($products)) {
            echo '<p>There is no product. Please configure and select product source again.</p>'; return;
        }

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
				'slidesToScroll' => ($settings['slides_to_scroll'] == '1') ? (int) $settings['items'] : 1,
				'autoplay'     => ($settings['autoplay'] == 'yes') ? true : false,
				'infinite'     => ($settings['infinite'] == 'yes') ? true : false,
				'arrows'       => ($settings['arrows'] == 'yes') ? true : false,
				
				'dots'         => ($settings['dots'] == 'yes') ? true : false,
				'rows'         => (int) $settings['rows'] ? $settings['rows'] : 1,
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
		}else{
			$context->smarty->assign(
				array(
					'columns_desktop' => ($settings['columns']['size'] == 5) ? '2-4' : (12/$settings['columns']['size']),
					'columns_tablet' => ($settings['columns_tablet']['size'] == 5) ? '2-4' : (12/$settings['columns_tablet']['size']),
					'columns_mobile' => ($settings['columns_mobile']['size'] == 5) ? '2-4' : (12/$settings['columns_mobile']['size']),
				)
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
				'vc_products'         => $products,
				'vc_title'            => '',
				'elementprefix'       => 'single-product',
				'theme_template_path' => $display,
				'carousel_active' => $settings['enable_slider'],
				'name_length' => $name_length ,
			)
		);
		
		$template_file_name = _PS_MODULE_DIR_ . 'posthemeoptions/views/templates/front/products.tpl';

		$out_put .= $context->smarty->fetch( $template_file_name );

		echo $out_put;
		
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