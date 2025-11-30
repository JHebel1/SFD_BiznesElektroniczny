<?php
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class PosWishListMyWishListModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	public function __construct()
	{
		parent::__construct();
		$this->context = Context::getContext();
		include_once($this->module->getLocalPath().'classes/WishListClass.php');
	}

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();
		$action = Tools::getValue('action');

		if($action == 'delete'){
			$this->ajaxDeleteProduct();
		}else{
			$this->assign();
		}
	}
	public function ajaxDeleteProduct(){
		$id_product = Tools::getValue('id_product');
		$id_product_attribute = Tools::getValue('id_product_attribute');
		WishListClass::removeProduct((int)$this->context->customer->id, $id_product, $id_product_attribute);
		$current_number = (int)Db::getInstance()->getValue('SELECT count(id_wishlist_product) FROM '._DB_PREFIX_.'poswishlist w, '._DB_PREFIX_.'poswishlist_product wp where w.id_wishlist = wp.id_wishlist and w.id_customer='.(int)$this->context->customer->id); 

		die(json_encode(array(
			'id_product' => $id_product , 
			'id_product_attribute' => $id_product_attribute,
			'current_number' => $current_number
		)));
		
	}
	/**
	 * Assign wishlist template
	 */
	public function assign()
	{
		$errors = array();

		if ($this->context->customer->isLogged())
		{
			$products = array();
			$results = WishListClass::getProductByIdCustomer($this->context->customer->id, $this->context->language->id);
			foreach($results as $result){
				$pvar = $this->getProductByID($result['id_product']);
				$pvar[0]['id_product_attribute'] = $result['id_product_attribute'];
				$pvar[0]['attributes_small'] = $result['attributes_small'];
				$products[] = $pvar[0];
			}

			$assembler = new ProductAssembler($this->context);
	        $presenterFactory = new ProductPresenterFactory($this->context);
	        $presentationSettings = $presenterFactory->getPresentationSettings();
	        $presenter = new ProductListingPresenter(
	            new ImageRetriever(
	                $this->context->link
	            ),
	            $this->context->link,
	            new PriceFormatter(),
	            new ProductColorsRetriever(),
	            $this->context->getTranslator()
	        );
	        $products_for_template = [];
	        foreach ($products as $rawProduct) {
	            $products_for_template[] = $presenter->present(
	                $presentationSettings,
	                $assembler->assembleProduct($rawProduct),
	                $this->context->language
	            );
	        }
	        $id_customer = (isset($this->context->customer) ? (int) $this->context->customer->id : 0);
	        $id_group = (int) Group::getCurrent()->id;
	        $id_country = $id_customer ? (int) Customer::getCurrentCountry($id_customer) : (int) Tools::getCountry();
	        $id_currency = (int) $this->context->cookie->id_currency;
	        $id_product_attribute = null;
	        $id_shop = $this->context->shop->id;
	        $priceFormatter = new PriceFormatter();
	        foreach($products_for_template as &$product){
	            $quantity_discounts = SpecificPrice::getQuantityDiscounts($product['id_product'], $id_shop, $id_currency, $id_country, $id_group, $id_product_attribute, false, (int) $this->context->customer->id);
	            if(!empty($quantity_discounts)) {
	                $txdiscounts = array();
	                foreach($quantity_discounts as $quantity_discount){
	                    if($quantity_discount['reduction_type'] == 'amount') {
	                        $discount_price = $product['regular_price_amount'] - $quantity_discount['reduction'];
	                    }else{
	                        $discount_price = $product['regular_price_amount'] - ($product['regular_price_amount'] * $quantity_discount['reduction']);
	                    }

	                    $txdiscounts[] = array(
	                        'price' => $priceFormatter->format($discount_price),
	                        'quantity' => $quantity_discount['from_quantity']
	                    );
	                }
	                $product['txdiscounts'] = $txdiscounts;
	            }

	        }
			
		}
		else
			Tools::redirect('index.php?controller=authentication&back='.urlencode($this->context->link->getModuleLink('poswishlist', 'mywishlist')));

		$wishlist = WishListClass::getByIdCustomer((int)$this->context->customer->id);
		//echo '<pre>'; print_r($products_for_template); echo '</pre>'; die('x_x');
		if($wishlist){
			$this->context->smarty->assign(array(
				'id_customer' => (int)$this->context->customer->id,
				'errors' => $errors,
				'token_wish' => $wishlist['0']['token'],
				'form_link' => $errors,
				'products' => $products_for_template,
				'home_url' => _PS_BASE_URL_
			));
		}else{
			$this->context->smarty->assign(array(
				'id_customer' => (int)$this->context->customer->id,
				'products' => ''
			));
		}

		$this->setTemplate('module:poswishlist/views/templates/front/mywishlist.tpl');
	}

	public function getProductByID($id_product){
       $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang =(int) Context::getContext()->language->id;
        $id_shop = (int) Context::getContext()->shop->id;

        $sql = 'SELECT p.*, product_shop.*,  pl.`description`, pl.`description_short`, pl.`available_now`,
                    pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, image_shop.`id_image` id_image,
                    il.`legend` as legend, m.`name` AS manufacturer_name,
                    DATEDIFF(product_shop.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
                    INTERVAL '.(int)$nb_days_new_product.' DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `'._DB_PREFIX_.'product` p
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                    ON (pl.`id_product` = '.$id_product.'
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
                LEFT JOIN `'._DB_PREFIX_.'product_shop` product_shop
                    ON product_shop.`id_product` = '.$id_product.'
                LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
                    ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.$id_shop.')
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il
                    ON (image_shop.`id_image` = il.`id_image`
                    AND il.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
                    ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE product_shop.`id_shop` = '.$id_shop.'
                    AND p.`id_product` = '.(int)$id_product;

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, false);

        return Product::getProductsProperties($id_lang, $result);
    }

}
