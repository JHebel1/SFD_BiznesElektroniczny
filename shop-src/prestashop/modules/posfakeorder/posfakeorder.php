<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class PosFakeOrder extends Module implements WidgetInterface
{

    private $templateFile;

    public function __construct()
    {
        $this->name = 'posfakeorder';
        $this->version = '1.1.0';
        $this->author = 'Posthemes';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Pos Fake Orders');
        $this->description = $this->l('Add fake orders display in homepage');

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:posfakeorder/posfakeorder.tpl';
    }

    public function install()
    {
        Configuration::updateValue($this->name . '_products', '');
        Configuration::updateValue($this->name . '_frame_time', 60);
        Configuration::updateValue($this->name . '_time_first', 3000);
        Configuration::updateValue($this->name . '_time_between', 5000);
        Configuration::updateValue($this->name . '_time_display', 7000);

        return (parent::install() &&
            $this->installTab() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayBeforeBodyClosingTag'));
    }

    public function uninstall()
    {  
        Configuration::deleteByName($this->name . '_products');
        Configuration::deleteByName($this->name . '_frame_time');
        Configuration::deleteByName($this->name . '_time_first');
        Configuration::deleteByName($this->name . '_time_between');
        Configuration::deleteByName($this->name . '_time_display');
        return parent::uninstall() && $this->deleteTab();
    }

    public function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminPosFakeOrder";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "- Fake orders";
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('PosModules');
        $tab->module = $this->name;
        return $tab->add();
    }
    public function deleteTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminPosFakeOrder');
        $tab = new Tab($id_tab);
        return $tab->delete();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitPosFakeOrder')) {
            if ( is_array(Tools::getValue('products'))) {
                Configuration::updateValue($this->name . '_products', implode(',', Tools::getValue('products')));
            }else {
                Configuration::updateValue($this->name . '_products', null);
            }
            Configuration::updateValue($this->name . '_frame_time', Tools::getValue('frame_time'));
            Configuration::updateValue($this->name . '_time_first', Tools::getValue('time_first'));
            Configuration::updateValue($this->name . '_time_between', Tools::getValue('time_between'));
            Configuration::updateValue($this->name . '_time_display', Tools::getValue('time_display'));
            $this->_clearCache($this->templateFile);

            return $this->displayConfirmation($this->trans('The settings have been updated.', array(), 'Admin.Notifications.Success'));
        }

        return '';
    }

    public function getContent()
    {
        $output = '';
        $admin_url =  $this->baseAdminUrl(); 
        $tokenProducts = Tools::getAdminTokenLite('AdminProducts');
        $output .= '<div id="admin_info"  data-admin_url ='.$admin_url.' data-token_product ='.$tokenProducts.'></div>';
        return $this->postProcess().$this->renderForm().$output;
    }
    public function baseAdminUrl(){
        return $this->context->link->getAdminLink('AdminProducts', true);
    }
    public function renderForm()
    {
        $this->context->controller->addJS($this->_path. 'js/admin/admin.js');
        $id_lang = (int)Context::getContext()->language->id;
        $products = array();
        $products_current = Configuration::get($this->name . '_products');
        if(isset($products_current) && $products_current){
            $products_current = explode(',', $products_current);
            foreach($products_current as $product_current){
                $product_name = Product::getProductName($product_current, null, $id_lang);
                $products[] = array(
                    'name' => $product_name,
                    'product_id' => $product_current
                );
            }
        }
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Settings', array(), 'Admin.Global'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'infoheading',
                        'label' => $this->l('Content'),
                        'name'=> 'content'
                    ),
                    array(
                        'type' => 'selectproduct',
                        'label' => 'Select products:',
                        'name' => 'products',
                        'multiple'=> true,
                        'size' => 500
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Frame time'),
                        'name' => 'frame_time',
                        'class' => 'fixed-width-sm',
                        'suffix' => 'minutes',
                        'desc' => $this->l('Time will auto get random in this frame.'),
                    ),
                    array(
                        'type' => 'infoheading',
                        'label' => $this->l('Display settings'),
                        'name'=> 'content'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Time first order popup'),
                        'name' => 'time_first',
                        'class' => 'fixed-width-sm',
                        'suffix' => 'milliseconds',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Time between order popup'),
                        'name' => 'time_between',
                        'class' => 'fixed-width-sm',
                        'suffix' => 'milliseconds',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Time display'),
                        'name' => 'time_display',
                        'class' => 'fixed-width-sm',
                        'suffix' => 'milliseconds',
                        'desc' => $this->l('Time display for each order popup'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions')
                )
            ),
        );

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPosFakeOrder';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'products' => $products,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $fields = array(
            'frame_time'  => Configuration::get($this->name . '_frame_time'),
            'time_first'  => Configuration::get($this->name . '_time_first'),
            'time_between'  => Configuration::get($this->name . '_time_between'),
            'time_display'  => Configuration::get($this->name . '_time_display'),
        );
        return $fields;
    }

    public function renderWidget($hookName, array $params)
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('posfakeorder'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $params));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('posfakeorder'));
    }

    public function getWidgetVariables($hookName, array $params)
    {
        return false;
    }

    public function hookHeader($params){
        $this->context->controller->addCSS($this->_path.'posfakeorder.css');
        $this->context->controller->addJS($this->_path.'js/front/front.js');

        $selected_products = $this->getProducts();
        $products = array();
        foreach($selected_products as $product){
            $products[] = array(
                'id_product' => $product['id_product'],
                'name' => $product['name'],
                'image' => $product['cover']['bySize']['small_default']['url'],
                'url' => $product['url']
            );
        }
        Media::addJsDef(array('pos_fakeorder' => [
            'products' =>  $products,
            'frame_time' => Configuration::get($this->name . '_frame_time') ,
            'time_first' => Configuration::get($this->name . '_time_first') ,
            'time_between' => Configuration::get($this->name . '_time_between') ,
            'time_display' => Configuration::get($this->name . '_time_display') ,
            'content_text' => $this->l('Someone has purchased'),
            'button_text' => $this->l('View product'),
            'ago_text' => $this->l('ago'),
            'minute_text' => $this->l('minute'),
            'minutes_text' => $this->l('minutes'),
            'hour_text' => $this->l('hour'),
            'hours_text' => $this->l('hours'),
        ]));
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
    protected function getProducts()
    {
        $products_current = Configuration::get($this->name . '_products');
        $result = array();
        if($products_current){
            $array_products = explode(',', $products_current);
            foreach($array_products as $product_id){
                $test = $this->getProductByID($product_id);
                $result[] = $test[0];
            }
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
        foreach ($result as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }
        return $products_for_template;
    }
}
