<?php 

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Configuration;
use DB;
use FrontController;
use Group;
use Product;
use ProductCore;
use Shop;
use Tools;
use Validate;
use Posthemes\Module\Poselements\WidgetHelper;

class PosSaleProductsWidget extends WidgetHelper { 
	public function getName() {
		return 'pos_sale_products';
	}

	public function getTitle() {
		return $this->l('Pos Deal Products');
	}

	public function getIcon() { 
		return 'fa fa-tags';
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
				'product_type',
				[
					'label' => $this->l( 'Resource' ),
					'type' => ControlsManager::SELECT,
					'description' => $this->l( 'Select resource' ),
					'options' => [
						'onsale_products' => $this->l( 'All sale Products' ),
						'select_products' => $this->l( 'Select Products' ), 
					] ,
					'default' => 'onsale_products',
				]
			);
			
			$this->addControl(
				'products',
				[
					'label'   		=> __('Add product ID'),
					'description'	=> __('Add product ID separate by comma . Example: 1,2,3,4,5'),
					'type'    		=> ControlsManager::TEXT,
					'label_block' 	=> true,
					'condition' => [
	                    'product_type' => 'select_products',
	                ],
				]
			);
			$this->addControl(
				'limit',
				[
					'label' 		=> $this->l('Limit'),
					'type' 			=> ControlsManager::NUMBER,
					'default' 		=> 6,
					'separator' 	=> 'before',
					'condition'    	=> [
						'product_type!' => 'select_products',
					],
				]
			);

			$this->addControl(
				'order',
				[
					'label' 		=> $this->l('Order'),
					'type' 			=> ControlsManager::SELECT,
					'default' 		=> '',
					'options' 		=> [
						'' 			=> $this->l('Default'),
						'DESC' 		=> $this->l('DESC'),
						'ASC' 		=> $this->l('ASC'),
					],
					'condition'    	=> [
						'product_type!' => 'select_products',
					],
				]
			); 
			

			$this->addControl(
				'orderby',
				[
					'label' 		=> $this->l('Order By'),
					'type' 			=> ControlsManager::SELECT,
					'default' 		=> '',
					'options' 		=> [
						'' 				=> $this->l('Default'),
						'date' 			=> $this->l('Date'),
						'id' 			=> $this->l('ID'),
						'title' 		=> $this->l('Title'), 
						'rand' 			=> $this->l('Random'),  
						'popularity'	=> $this->l('Popularity'),
					],
					'condition'    	=> [
						'product_type!' => 'select_products',
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
					'return_value' 	=> 'yes',
					'default' 		=> 'yes', 
				]
			);
		
			$this->addControl(
				'columns',
				[
					'label' => $this->l( 'Columns' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => ['item'],
					'range' => [
						'item' => [
							'min' => 1,
							'max' => 6,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'item',
						'size' => 4,
					],
					'condition' 	=> [
						'enable_slider!' => 'yes',
					],
				]
			);

			
			$this->addControl(
				'product_display',
				[
					'label' => $this->l( 'Product display' ),
					'type' => ControlsManager::CHOOSE,
					'options' => [
						'grid' => [
							'title' => $this->l( 'Grid' ),
							'icon' => 'fa fa-th',
						],
						'list' => [
							'title' => $this->l( 'List' ),
							'icon' => 'fa fa-list',
						],
					],
					'default' => 'grid',
					'toggle' => true,
				]
			);
			$designs_countdown = array('1' => 'Design 1','2' => 'Design 2');
			$this->addControl(
				'design_countdown',
				[
					'label' => $this->l( 'design' ),
					'type' => ControlsManager::SELECT,
					'options' => $designs_countdown, 
					'prefix_class' => 'design-countdown-',
					'frontend_available' => true,
					'default' => '1'
				]
			);
			$this->addControl(
				'title',
				[
					'label'   		=> $this->l('Text before countdown'),
					'type'    		=> ControlsManager::TEXT,
					'label_block' 	=> true,
				]
			);
			$this->addControl(
				'show_stock',
				[
					'label' => $this->l( 'Show product Stock' ),
					'type' => ControlsManager::SWITCHER,
					'label_on' => $this->l( 'Show' ),
					'label_off' => $this->l( 'Hide' ),
					'prefix_class' => 'show-stock-',
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);
			

		$this->endControlsSection();
		$this->addCarouselControls($this->getName() , 1);
		$this->startControlsSection(
			'style_section',
			[
				'label' => $this->l( 'Item style' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
				'padding',
				[
					'label' => $this->l( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .product-miniature' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		$this->endControlsSection();
	}

	protected function render() {
		if (is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}
		$settings = $this->getSettings(); 

		$context = \Context::getContext();
		$out_put  = '';

		$products = $this->get_products();
		//echo '<pre>'; print_r($products);die;
		if ( ! $products ) {
			echo '<p>There is no deal product. Please configure products and add special price to products.</p>'; return false;
		}
		$assembler = new \ProductAssembler( $context );
		$presenterFactory     = new \ProductPresenterFactory( $context );
		$presentationSettings = $presenterFactory->getPresentationSettings();
		$presenter            = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
			new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
				$context->link
			),
			$context->link,
			new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
			new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
			$context->getTranslator()
		);
		$products_for_template = array();
		foreach ( $products as $rawProduct ) {
			$products_for_template[] = $presenter->present(
				$presentationSettings,
				$assembler->assembleProduct( $rawProduct ),
				$context->language
			);
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
				'autoplay'     => $settings['autoplay'] ? true : false,
				'infinite'     => $settings['infinite'] ? true : false,
				'arrows'       => $settings['arrows'] ? true : false,
				'dots'         => $settings['dots'] ? true : false,
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
				)
			);
		}else{
			$context->smarty->assign(
				array(
					'columns_desktop' => 12/$settings['columns']['size'],
					'columns_tablet' => 12/$settings['columns_tablet']['size'],
					'columns_mobile' => 12/$settings['columns_mobile']['size'],
				)
			);
		}

		$product_loop_file = _PS_MODULE_DIR_ . 'posthemeoptions/views/templates/front/sale-products-loop.tpl';
		$context->smarty->assign(
			array(
				'vc_products'         => $products_for_template,
				'title'            	  => $settings['title'],
				'carousel_active' 	  => $settings['enable_slider'],
				'product_loop_file' => $product_loop_file,
				'product_display' => $settings['product_display']
			)
		);
		
		$template_file_name = _PS_MODULE_DIR_ . 'posthemeoptions/views/templates/front/sale-products.tpl';
		

		$out_put .= $context->smarty->fetch( $template_file_name );

		echo $out_put;

	}
	protected function get_products(){
		$settings = $this->getSettings();
		$limit = $settings['limit'] ? (int)$settings['limit'] : 8;
		$orderby = $settings['orderby'];
		$orderway = $settings['order'];

		$context = \Context::getContext();
		$id_lang = $context->language->id;

		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}

		switch ($settings['product_type']) {
			case 'onsale_products':
				$products = $this->getDealProduct((int) $context->language->id, 0, $limit, $orderby , $orderway);	
				break;
			case 'select_products':
				$ids_array = array();
				$list_ids = $settings['products'];
				$order_by_prefix = '';
				$orderby = $settings['orderby'];
				if ( $orderby == 'id_product' || $orderby == 'price' || $orderby == 'date_add' || $orderby == 'date_upd' ) {
					$order_by_prefix = 'p';
				} elseif ( $orderby == 'name' ) {
					$order_by_prefix = 'pl';
				}
				$sql = 'SELECT p.*, product_shop.*, pl.*, image_shop.`id_image`, il.`legend`, m.`name` AS manufacturer_name, s.`name` AS supplier_name
					FROM `' . _DB_PREFIX_ . 'product` p
					' . \Shop::addSqlAssociation( 'product', 'p' ) . '
					LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
					LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
					LEFT JOIN `' . _DB_PREFIX_ . 'supplier` s ON (s.`id_supplier` = p.`id_supplier`)
	                                LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)' .
				\Shop::addSqlAssociation( 'image', 'i', false, 'image_shop.cover=1' ) . '
					LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) \Context::getContext()->language->id . ')
					WHERE pl.`id_lang` = ' . (int) $id_lang .
				' AND p.`id_product` IN( ' . $list_ids . ')' .
				( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
				' AND ((image_shop.id_image IS NOT NULL OR i.id_image IS NULL) OR (image_shop.id_image IS NULL AND i.cover=1))' .
				' AND product_shop.`active` = 1';

				$sql .= " ORDER BY FIELD(`p`.`id_product`,". $list_ids .")";
				
				$rq = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql );
				$products = \Product::getProductsProperties( $id_lang, $rq );
				
				break;
		}
		return $products;
	}
	public static function getDealProduct(
        $id_lang,
        $page_number = 0,
        $nb_products = 10,
        $order_by = null,
        $order_way = null,
        $beginning = false,
        $ending = false,
        Context $context = null
    ) {
        
        if (!$context) {
            $context = Context::getContext();
        }
        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'price';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        
        $current_date = date('Y-m-d H:i:00');
        $ids_product = array();
        $ids = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT sp.`id_product` FROM `' . _DB_PREFIX_ . 'specific_price` sp WHERE sp.`to` >= \''.$current_date .'\' AND sp.`from` <= \''. $current_date . '\'');
        foreach($ids as $id){
        	$ids_product[] = $id['id_product'];
        }

        $tab_id_product = [];
        foreach ($ids_product as $product) {
            if (is_array($product)) {
                $tab_id_product[] = (int) $product['id_product'];
            } else {
                $tab_id_product[] = (int) $product;
            }
        }

        $front = true;
        if (!in_array($context->controller->controller_type, ['front', 'modulefront'])) {
            $front = false;
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]) . '.`' . pSQL($order_by[1]) . '`';
        }
        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
            JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '=' . (int) Group::getCurrent()->id) . ')
            WHERE cp.`id_product` = p.`id_product`)';
        }

        $sql = '
        SELECT
            p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`,
            IFNULL(product_attribute_shop.id_product_attribute, 0) id_product_attribute,
            pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
            pl.`name`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
            DATEDIFF(
                p.`date_add`,
                DATE_SUB(
                    "' . date('Y-m-d') . ' 00:00:00",
                    INTERVAL ' . (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY
                )
            ) > 0 AS new
        FROM `' . _DB_PREFIX_ . 'product` p
        ' . Shop::addSqlAssociation('product', 'p') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop
            ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id . ')
        ' . Product::sqlStock('p', 0, false, $context->shop) . '
        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . '
        )
        LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
            ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`) 
        WHERE product_shop.`active` = 1
        AND product_shop.`show_price` = 1
        ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
        ' . ((!$beginning && !$ending) ? ' AND p.`id_product` IN (' . ((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0) . ')' : '') . '
        ' . $sql_groups;

        if ($order_by != 'price') {
            $sql .= '
				ORDER BY ' . (isset($order_by_prefix) ? pSQL($order_by_prefix) . '.' : '') . pSQL($order_by) . ' ' . pSQL($order_way) . '
				LIMIT ' . (int) (($page_number - 1) * $nb_products) . ', ' . (int) $nb_products;
        }

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result) {
            return false;
        }

        if ($order_by === 'price') {
            Tools::orderbyPrice($result, $order_way);
            $result = array_slice($result, (int) (($page_number - 1) * $nb_products), (int) $nb_products);
        }

        return Product::getProductsProperties($id_lang, $result);
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