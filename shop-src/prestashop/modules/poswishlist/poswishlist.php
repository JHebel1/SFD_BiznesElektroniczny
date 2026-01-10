<?php
/*
 * @package   	poswishlist
 * @version   	1.0.0
 * @author   	http://posthemes.com/
 * @copyright 	Copyright (C) June 2021 posthemes.com
 * All rights reserved.
 * @license   GNU General Public License version 1

 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_'))
	exit;

include_once(dirname(__FILE__).'/classes/WishListClass.php');

class PosWishList extends Module implements WidgetInterface
{
	public function __construct()
	{
		$this->name = 'poswishlist';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Posthemes';
		$this->need_instance = 0;

		$this->controllers = array('mywishlist', 'view');

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Pos Wishlist');
		$this->description = $this->l('Adds a block containing the customers wishlists.');
		$this->default_wishlist_name = $this->l('My wishlist');
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
		$this->html = '';
	}

	public function install()
	{
		if (parent::install() &&
			$this->installTables() &&
			$this->registerHook('displayProductActions') &&
			$this->registerHook('displayAfterButtonCart') &&
			$this->registerHook('cart') &&
			$this->registerHook('customerAccount') &&
			$this->registerHook('displayHeader') &&
			$this->registerHook('displayNav') &&
			$this->registerHook('displayAdminCustomers') &&
			$this->registerHook('displayProductListFunctionalButtons')
		){
			return true;
		}
		return false;
	}
	private function installTables(){
		$sqlQueries = array(
			'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'poswishlist` (
				`id_wishlist` int(10) unsigned NOT NULL auto_increment, 
				`id_customer` int(10) unsigned NOT NULL, 
				`token` varchar(64) character set utf8 NOT NULL, 
				`name` varchar(64) character set utf8 NOT NULL, 
				`id_shop` int(10) unsigned default 1, 
				`id_shop_group` int(10) unsigned default 1,
		  		PRIMARY KEY (`id_wishlist`)
			) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8',
			'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'poswishlist_product` (
				`id_wishlist_product` int(10) NOT NULL auto_increment, 
				`id_wishlist` int(10) unsigned NOT NULL, 
				`id_product` int(10) unsigned NOT NULL, 
				`id_product_attribute` int(10) unsigned NOT NULL, 
				`quantity` int(10) unsigned NOT NULL, 
				`priority` int(10) unsigned NOT NULL, 
				 PRIMARY KEY (`id_wishlist_product`) 
			) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8',
		);
		foreach ($sqlQueries as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        return true;
	}

	public function uninstall()
	{
		return (parent::uninstall() && $this->deleteTables());
	}

	private function deleteTables(){
		return Db::getInstance()->execute(
			'DROP TABLE IF EXISTS
			`'._DB_PREFIX_.'poswishlist`,
			`'._DB_PREFIX_.'poswishlist_product`'
		);
	}

	public function renderWidget($hookName = null, array $configuration = []){
		
	}
	public function getWidgetVariables($hookName = null, array $configuration = [])
    {

    }
	public function hookDisplayProductListFunctionalButtons($params)
	{	
		//TODO : Add cache
		if ($this->context->customer->isLogged())
			$this->smarty->assign('wishlists', WishListClass::getByIdCustomer($this->context->customer->id));

		$this->smarty->assign('product', $params['product']);
		return $this->fetch('module:poswishlist/views/templates/hook/poswishlist_button.tpl');
	}

	public function hookHeader($params)
	{
		$this->context->controller->addJS(($this->_path).'js/ajax-wishlist.js');

		$this->smarty->assign(array('wishlist_link' => $this->context->link->getModuleLink('poswishlist', 'mywishlist')));

		if ($this->context->customer->isLogged())
		{
			$wishlists = WishListClass::getByIdCustomer($this->context->customer->id);
			if (empty($this->context->cookie->id_wishlist) === true ||
				WishListClass::exists($this->context->cookie->id_wishlist, $this->context->customer->id) === false)
			{
				if (!count($wishlists))
					$id_wishlist = false;
				else
				{
					$id_wishlist = (int)$wishlists[0]['id_wishlist'];
					$this->context->cookie->id_wishlist = (int)$id_wishlist;
				}
			}
			else
				$id_wishlist = $this->context->cookie->id_wishlist;

			Media::addJsDef(array(
				'wishlistProductsIdsobject' => WishListClass::getProductByIdCustomer($this->context->customer->id, $this->context->language->id, null, true),
				'mywishlist_url'	=> $this->context->link->getModuleLink('poswishlist', 'mywishlist', array(), true),
				'added_to_wishlist' => $this->l('was successfully added to your wishlist.'),
				'wishlist_text' => $this->l('View my wishlists'),
				'isLoggedWishlist' => true,
				'isLogged' => true,
				'wishlistProductsIds' => array(),
				'wishlist_url' => $this->context->link->getModuleLink('poswishlist', 'mywishlist'),
			));

			$this->smarty->assign(
				array(
					'id_wishlist' => $id_wishlist,
					'isLogged' => true,
					'wishlists' => $wishlists,
					'ptoken' => Tools::getToken(false),
				)
			);
		}
		else{
			Media::addJsDef(array(
				'isLoggedWishlist' => false,
				'isLogged' => false,
				'wishlistProductsIdsobject' => array(),
				'loggin_required' => $this->l('You have to login to use wishlist'),
				'loggin_url' => $this->context->link->getPageLink('my-account', true),
				'loggin_text' => $this->l('Login'),
			));

			$this->smarty->assign(array('wishlist_products' => false, 'wishlists' => false));
		}

		Media::addJsDef(array(
			'baseDir' => __PS_BASE_URI__,
			'static_token' => Tools::getToken(false)
		));
	}
	
	public function hookdisplayNav($params)
	{

	  $useSSL = ((isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED')) || Tools::usingSecureMode()) ? true : false;
        $protocol_content = ($useSSL) ? 'https://' : 'http://';
		if ($this->context->customer->isLogged())
		{
			$wishlists = WishListClass::getByIdCustomer($this->context->customer->id);
			if (empty($this->context->cookie->id_wishlist) === true ||
				WishListClass::exists($this->context->cookie->id_wishlist, $this->context->customer->id) === false)
			{
				if (!count($wishlists))
					$id_wishlist = false;
				else
				{
					$id_wishlist = (int)$wishlists[0]['id_wishlist'];
					$this->context->cookie->id_wishlist = (int)$id_wishlist;
				}
			}
			else
				$id_wishlist = $this->context->cookie->id_wishlist;

			$this->smarty->assign(
				array(
					'id_wishlist' => $id_wishlist,
					'isLogged' => true,
					'wishlist_products' => ($id_wishlist == false ? false : WishListClass::getProductByIdCustomer($id_wishlist,
						$this->context->customer->id, $this->context->language->id, null, true)),
					'wishlists' => $wishlists,
					'ptoken' => Tools::getToken(false),
					'icon' => isset($params['icon']) ? $params['icon'] : '',
				)
			);
		}
		else
			$this->smarty->assign(array('wishlist_products' => false, 'wishlists' => false));
        $this->context->smarty->assign(
            array(
                'content_dir'=> $protocol_content.Tools::getHttpHost().__PS_BASE_URI__,
                'isLogged' => $this->context->customer->logged,
                'count_product' => (int)Db::getInstance()->getValue('SELECT count(id_wishlist_product) FROM '._DB_PREFIX_.'poswishlist w, '._DB_PREFIX_.'poswishlist_product wp where w.id_wishlist = wp.id_wishlist and w.id_customer='.(int)$this->context->customer->id),
                'icon' => isset($params['icon']) ? $params['icon'] : '',
            )
        );
		return  $this->display(__FILE__, 'poswishlist_top.tpl');
	}
	public function hookdisplayTop($params){
		return $this->hookdisplayNav($params);
	}
	public function hookdisplayMegamenuMobileTop($params){
		return $this->hookdisplayNav($params);
	}
	public function hookdisplayMegamenuMobileBottom($params){
		return $this->hookdisplayNav($params);
	}
	public function hookDisplayProductActions($params)
	{
		$cookie = $params['cookie'];

		$this->smarty->assign(array(
			'id_product' => (int)Tools::getValue('id_product'),
		));

		if (isset($cookie->id_customer))
			$this->smarty->assign(array(
				'wishlists' => WishListClass::getByIdCustomer($cookie->id_customer),
			));

		return $this->fetch('module:poswishlist/views/templates/hook/poswishlist-extra.tpl');
	}
	public function hookDisplayAfterButtonCart($params){
		return $this->hookDisplayProductActions($params);
	}

	public function hookCustomerAccount($params)
	{
		return $this->fetch('module:poswishlist/views/templates/hook/my-account.tpl');
	}

	public function hookDisplayMyAccountBlock($params)
	{
		return $this->hookCustomerAccount($params);
	}

	private function _displayProducts($id_wishlist)
	{
		include_once(dirname(__FILE__).'/classes/PosWishList.php');

		$wishlist = new WishListClass($id_wishlist);
		$products = WishListClass::getProductByIdCustomer($wishlist->id_customer, $this->context->language->id);
		$nb_products = count($products);
		for ($i = 0; $i < $nb_products; ++$i)
		{
			$obj = new Product((int)$products[$i]['id_product'], false, $this->context->language->id);
			if (!Validate::isLoadedObject($obj))
				continue;
			else
			{
				$images = $obj->getImages($this->context->language->id);
				foreach ($images as $image)
				{
					if ($image['cover'])
					{
						$products[$i]['cover'] = $obj->id.'-'.$image['id_image'];
						break;
					}
				}
				if (!isset($products[$i]['cover']))
					$products[$i]['cover'] = $this->context->language->iso_code.'-default';
			}
		}

		foreach ($products as $product)
		{
			$this->html .= '<div style="height:98px; width: 50%; float: left; overflow:hidden; margin-bottom: 10px;">';
			$this->html .= '<img src="'.$this->context->link->getImageLink($product['link_rewrite'], $product['cover'],
							'small_default').'" alt="'.htmlentities($product['name'], ENT_COMPAT, 'UTF-8').'" style="float:left; margin-right: 10px;" />
						';
			$this->html .= '<p>'. $product['name'] .'</p>';
			if (isset($product['attributes_small']))
				$this->html .= '<i>'.htmlentities($product['attributes_small'], ENT_COMPAT, 'UTF-8').'</i>';
			$this->html .= '</div>';
			
		}
	}

	public function hookDisplayAdminCustomers($params)
	{
		$customer = new Customer((int)$params['id_customer']);
		if (!Validate::isLoadedObject($customer))
			die (Tools::displayError());

		$this->html = '<div class="col">';
		$this->html .= '<h3 class="card-header">'. $this->l('Wishlist') .'<h3>';
		$this->html .= '<div class="card-body">';
		$wishlists = WishListClass::getByIdCustomer((int)$customer->id);
		if (!count($wishlists))
			$this->html .= $customer->lastname.' '.$customer->firstname.' '.$this->l('No wishlist.');
		else
		{
			
			$id_wishlist = (int)Tools::getValue('id_wishlist');
			if (!$id_wishlist)
				$id_wishlist = $wishlists[0]['id_wishlist'];

			$this->_displayProducts((int)$id_wishlist);

		}

		$this->html .= '</div></div>';
		return $this->html;
	}

}
