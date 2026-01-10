<?php

if (!defined('_PS_VERSION_'))
	exit;

class WishListClass extends ObjectModel
{
	/** @var integer Wishlist ID */
	public $id;

	/** @var integer Customer ID */
	public $id_customer;

	/** @var integer Token */
	public $token;

	/** @var integer Name */
	public $name;

	/** @var string Object last modification date */
	public $id_shop;

	/** @var string Object last modification date */
	public $id_shop_group;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'poswishlist',
		'primary' => 'id_wishlist',
		'fields' => array(
			'id_customer' =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'token' =>			array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
			'name' =>			array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
			'id_shop' =>		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_shop_group' =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
		)
	);

	public function delete()
	{	
		Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'poswishlist_product` WHERE `id_wishlist` = '.(int)($this->id));
		
		if (isset($this->context->cookie->id_wishlist))
			unset($this->context->cookie->id_wishlist);

		return (parent::delete());
	}

	/**
	 * Return true if wishlist exists else false
	 *
	 *  @return boolean exists
	 */
	public static function exists($id_wishlist, $id_customer, $return = false)
	{

		if (!Validate::isUnsignedId($id_wishlist) OR
			!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
		SELECT `id_wishlist`, `name`, `token`
		  FROM `'._DB_PREFIX_.'poswishlist`
		WHERE `id_wishlist` = '.(int)($id_wishlist).'
		AND `id_customer` = '.(int)($id_customer).'
		AND `id_shop` = '.(int)Context::getContext()->shop->id);
		if (empty($result) === false AND $result != false AND sizeof($result))
		{
			if ($return === false)
				return (true);
			else
				return ($result);
		}
		return (false);
	}

	/**
	* Get Customers having a wishlist
     	*
     	* @return array Results
     	*/
	public static function getCustomers()
	{
		$cache_id = 'WishListClass::getCustomers';
		if (!Cache::isStored($cache_id))
		{
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
				SELECT c.`id_customer`, c.`firstname`, c.`lastname`
				  FROM `'._DB_PREFIX_.'poswishlist` w
				INNER JOIN `'._DB_PREFIX_.'customer` c ON c.`id_customer` = w.`id_customer`
				ORDER BY c.`firstname` ASC'
			);
			Cache::store($cache_id, $result);
		}
		return Cache::retrieve($cache_id);
	}

	/**
	 * Get ID wishlist by Token
	 *
	 * @return array Results
	 */
	public static function getByToken($token)
	{
		if (!Validate::isMessage($token))
			die (Tools::displayError());
		return (Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
		SELECT w.`id_wishlist`, w.`name`, w.`id_customer`, c.`firstname`, c.`lastname`
		  FROM `'._DB_PREFIX_.'poswishlist` w
		INNER JOIN `'._DB_PREFIX_.'customer` c ON c.`id_customer` = w.`id_customer`
		WHERE `token` = \''.pSQL($token).'\''));
	}

	/**
	 * Get Wishlists by Customer ID
	 *
	 * @return array Results
	 */
	public static function getByIdCustomer($id_customer)
	{
		if (!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		if (Shop::getContextShopID())
			$shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
		elseif (Shop::getContextShopGroupID())
			$shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
		else
			$shop_restriction = '';

		$cache_id = 'WishListClass::getByIdCustomer_'.(int)$id_customer.'-'.(int)Shop::getContextShopID().'-'.(int)Shop::getContextShopGroupID();
		if (!Cache::isStored($cache_id))
		{
			$result = Db::getInstance()->executeS('
			SELECT w.`id_wishlist`, w.`name`, w.`token`
			FROM `'._DB_PREFIX_.'poswishlist` w
			WHERE `id_customer` = '.(int)($id_customer).'
			'.$shop_restriction.'
			ORDER BY w.`name` ASC');
			Cache::store($cache_id, $result);
		}
		return Cache::retrieve($cache_id);
	}


	/**
	 * Get Wishlist products by Customer ID
	 *
	 * @return array Results
	 */
	public static function getProductByIdCustomer($id_customer, $id_lang, $id_product = null, $quantity = false)
	{
		if (!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_lang))
			die (Tools::displayError());
		$products = Db::getInstance()->executeS('
		SELECT wp.`id_product`, wp.`quantity`, p.`quantity` AS product_quantity, pl.`name`, wp.`id_product_attribute`, wp.`priority`, pl.link_rewrite, cl.link_rewrite AS category_rewrite
		FROM `'._DB_PREFIX_.'poswishlist_product` wp
		LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = wp.`id_product`
		'.Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product` = wp.`id_product`'.Shop::addSqlRestrictionOnLang('pl').'
		LEFT JOIN `'._DB_PREFIX_.'poswishlist` w ON w.`id_wishlist` = wp.`id_wishlist`
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON cl.`id_category` = product_shop.`id_category_default` AND cl.id_lang='.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
		WHERE w.`id_customer` = '.(int)($id_customer).'
		AND pl.`id_lang` = '.(int)($id_lang).
		(empty($id_product) === false ? ' AND wp.`id_product` = '.(int)($id_product) : '').
		($quantity == true ? ' AND wp.`quantity` != 0': '').'
		GROUP BY p.id_product, wp.id_product_attribute');
		if (empty($products) === true OR !sizeof($products))
			return array();
		for ($i = 0; $i < sizeof($products); ++$i)
		{
			if (isset($products[$i]['id_product_attribute']) AND
				Validate::isUnsignedInt($products[$i]['id_product_attribute']))
			{
				$result = Db::getInstance()->executeS('
				SELECT al.`name` AS attribute_name, sa.`quantity` AS "attribute_quantity"
				FROM `'._DB_PREFIX_.'product_attribute_combination` pac
				LEFT JOIN `'._DB_PREFIX_.'attribute` a ON (a.`id_attribute` = pac.`id_attribute`)
				LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON (ag.`id_attribute_group` = a.`id_attribute_group`)
				LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)($id_lang).')
				LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)($id_lang).')
				LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON (pac.`id_product_attribute` = sa.`id_product_attribute`) 
				WHERE pac.`id_product_attribute` = '.(int)($products[$i]['id_product_attribute']));
				$products[$i]['attributes_small'] = '';

				$products_ids = array();
				if ($result) {
					foreach ($result as $k => $row) {
						$products[$i]['attributes_small'] .= $row['attribute_name'].', ';
					}
				}
				$products[$i]['attributes_small'] = rtrim($products[$i]['attributes_small'], ', ');
				if (isset($result[0]))
					$products[$i]['attribute_quantity'] = $result[0]['attribute_quantity'];
			}
			else
				$products[$i]['attribute_quantity'] = $products[$i]['product_quantity'];
		}

		return ($products);

	}

	/**
	 * Get Wishlists number products by Customer ID
	 *
	 * @return array Results
	 */
	public static function getInfosByIdCustomer($id_customer)
	{
		if (Shop::getContextShopID())
			$shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
		elseif (Shop::getContextShopGroupID())
			$shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
		else
			$shop_restriction = '';

		if (!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		return (Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT SUM(wp.`quantity`) AS nbProducts, wp.`id_wishlist`
		  FROM `'._DB_PREFIX_.'poswishlist_product` wp
		INNER JOIN `'._DB_PREFIX_.'poswishlist` w ON (w.`id_wishlist` = wp.`id_wishlist`)
		WHERE w.`id_customer` = '.(int)($id_customer).'
		'.$shop_restriction.'
		GROUP BY w.`id_wishlist`
		ORDER BY w.`name` ASC'));
	}

	/**
	 * Add product to ID wishlist
	 *
	 * @return boolean succeed
	 */
	//public static function removeProduct($id_customer, $id_product, $id_product_attribute)
	public static function addProduct($id_customer, $id_product, $id_product_attribute, $quantity)
	{
		if (!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_product) OR
			!Validate::isUnsignedId($quantity))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
		SELECT `id_wishlist`
		  FROM `'._DB_PREFIX_.'poswishlist`
			WHERE `id_customer` = '.(int)($id_customer)
		);

		if (empty($result) === false AND sizeof($result))
		{
			$wishlist_products = Db::getInstance()->executeS('
				SELECT `id_product` , `id_product_attribute`
				FROM `'._DB_PREFIX_.'poswishlist_product`  wp
				LEFT JOIN `'._DB_PREFIX_.'poswishlist` w ON w.`id_wishlist` = wp.`id_wishlist`
				WHERE w.`id_customer` = '.(int)($id_customer)
			);

			$products = array();
			$attr_products = array();
			foreach($wishlist_products as $wishlist_product){
				$products[] = $wishlist_product['id_product'];
				$attr_products[] = $wishlist_product['id_product_attribute'];
			}
			if(in_array($id_product, $products) && in_array($id_product_attribute, $attr_products)){
				return;
			}else{
				return (Db::getInstance()->execute('
				INSERT INTO `'._DB_PREFIX_.'poswishlist_product` (`id_wishlist`, `id_product`, `id_product_attribute`, `quantity`, `priority`) VALUES(
				'.(int)$result["id_wishlist"].',
				'.(int)($id_product).',
				'.(int)($id_product_attribute).',
				'.(int)($quantity).', 1)'));
			}
		}
		else{
			return (Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'poswishlist_product` (`id_wishlist`, `id_product`, `id_product_attribute`, `quantity`, `priority`) VALUES(
			'.(int)$result["id_wishlist"].',
			'.(int)($id_product).',
			'.(int)($id_product_attribute).',
			'.(int)($quantity).', 1)'));
		}
	}

	/**
	 * Remove product from wishlist
	 *
	 * @return boolean succeed
	 */
	public static function removeProduct($id_customer, $id_product, $id_product_attribute)
	{	
		if (!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_product))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
			SELECT w.`id_customer`, wp.`id_wishlist_product`
			FROM `'._DB_PREFIX_.'poswishlist` w
			LEFT JOIN `'._DB_PREFIX_.'poswishlist_product` wp ON (wp.`id_wishlist` = w.`id_wishlist`)
			WHERE `id_customer` = '.$id_customer
		);
		$checkers = Db::getInstance()->getRow('
			SELECT wp.`id_product_attribute`
			FROM `'._DB_PREFIX_.'poswishlist_product` wp
			WHERE `id_product` = '.$id_product
		);
		$new_array = array();
		foreach($checkers as $key => $value){
			$new_array[] = $value;
		}

		if(!in_array($id_product_attribute, $new_array)){
			$id_product_attribute = 0; //case product changed from simple product to combination product
		}
		if (empty($result) === true OR
			$result === false OR
			!sizeof($result) OR
			$result['id_customer'] != $id_customer)
			return (false);
		return Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'poswishlist_product`
			WHERE `id_product` = '.(int)($id_product).'
			AND `id_product_attribute` = '.(int)($id_product_attribute)
		);
	}

	


};
