<?php

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/classes/WishListClass.php');
require_once(dirname(__FILE__).'/poswishlist.php');

$context = Context::getContext();
$action = Tools::getValue('action');
$add = (!strcmp($action, 'add') ? 1 : 0);
$delete = (!strcmp($action, 'delete') ? 1 : 0);
$id_wishlist = (int)Tools::getValue('id_wishlist');
$id_product = (int)Tools::getValue('id_product');
$quantity = (int)Tools::getValue('quantity');
$id_product_attribute = (int)Tools::getValue('id_product_attribute');

// Instance of module class for translations
$module = new PosWishList();

if (Configuration::get('PS_TOKEN_ENABLE') == 1 &&
	strcmp(Tools::getToken(false), Tools::getValue('token')) &&
	$context->customer->isLogged() === true
)
	echo $module->l('Invalid token', 'cart');
if ($context->customer->isLogged())
{
	if ($id_wishlist && WishListClass::exists($id_wishlist, $context->customer->id) === true)
		$context->cookie->id_wishlist = (int)$id_wishlist;

	if ((int)$context->cookie->id_wishlist > 0 && !WishListClass::exists($context->cookie->id_wishlist, $context->customer->id))
		$context->cookie->id_wishlist = '';

	if (empty($context->cookie->id_wishlist) === true || $context->cookie->id_wishlist == false)
		$context->smarty->assign('error', true);

	if (($add || $delete) && empty($id_product) === false)
	{
		if (!isset($context->cookie->id_wishlist) || $context->cookie->id_wishlist == '')
		{
			$wishlist = new WishListClass();
			$wishlist->id_shop = $context->shop->id;
			$wishlist->id_shop_group = $context->shop->id_shop_group;
			$wishlist->default = 1;

			$wishlist->name = $module->default_wishlist_name;
			$wishlist->id_customer = (int)$context->customer->id;
			list($us, $s) = explode(' ', microtime());
			srand($s * $us);
			$wishlist->token = strtoupper(substr(sha1(uniqid(rand(), true)._COOKIE_KEY_.$context->customer->id), 0, 16));
			$wishlist->add();
		}

		if ($add && $quantity)
			WishListClass::addProduct($context->customer->id, $id_product, $id_product_attribute, $quantity);
		else if ($delete)
			WishListClass::removeProduct($context->cookie->id_wishlist, $context->customer->id, $id_product, $id_product_attribute);
	}
	echo (int)Db::getInstance()->getValue('SELECT count(id_wishlist_product) FROM '._DB_PREFIX_.'poswishlist w, '._DB_PREFIX_.'poswishlist_product wp where w.id_wishlist = wp.id_wishlist and w.id_customer='.(int)$context->customer->id);
	die();

} else
	echo $module->l('You must be logged in to manage your wishlist.', 'cart');
