<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\Plugin;
use Posthemes\Module\Poselements\Install;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class PosThemeoptions extends Module implements WidgetInterface
{
    // Equivalent module on PrestaShop 1.6, sharing the same data

    public static $text_transform = array(
        1 => array('id' =>1 , 'name' => 'None'),
        2 => array('id' =>2 , 'name' => 'Capitalize'),
        3 => array('id' =>3 , 'name' => 'UPPERCASE'),
    );
    public static $text_font_weight = array(
        1 => array('id' =>1 , 'name' => '600'),
        2 => array('id' =>2 , 'name' => '400'),
        3 => array('id' =>3 , 'name' => '500'),
        4 => array('id' =>4 , 'name' => '700'),
        5 => array('id' =>5 , 'name' => '800'),
        6 => array('id' =>6 , 'name' => '900'),
    );
    public static $product_row = array(
        1 => array('id' =>1 , 'name' => '3'),
        2 => array('id' =>2 , 'name' => '4'),
        3 => array('id' =>3 , 'name' => '5'),
    );
    public $fields_arr_path = '/fields_array.php';
    private $_html;

    private $templateFile;

    public function __construct()
    {
        $this->name = 'posthemeoptions';
        $this->author = 'Posthemes';
        $this->version = '2.2.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        Shop::addTableAssociation('info', array('type' => 'shop'));

        $this->displayName = $this->trans('Pos Themeoptions', array(), 'Modules.Customtext.Admin');
        $this->description = $this->trans('Theme editor', array(), 'Modules.Customtext.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        //$this->templateFile = 'module:ps_customtext/ps_customtext.tpl';
    }

    public function install()
    {
        //General
        Configuration::updateValue($this->name . 'container_width', '');
        Configuration::updateValue($this->name . 'boxed_width', '');
        Configuration::updateValue($this->name . 'layout', 'wide');
        Configuration::updateValue($this->name . 'sidebar', 'normal');
        Configuration::updateValue($this->name . 'g_main_color', '#4fb68d');
        Configuration::updateValue($this->name . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
        Configuration::updateValue($this->name . 'g_body_gfont_name', '"Open Sans", sans-serif');
        Configuration::updateValue($this->name . 'g_body_font_size', 14);
        Configuration::updateValue($this->name . 'g_body_font_color', '#666666');
        // Configuration::updateValue($this->name . 'g_a_color', '#555555');
        // Configuration::updateValue($this->name . 'g_a_colorh', '#253237');
        Configuration::updateValue($this->name . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
        Configuration::updateValue($this->name . 'g_title_gfont_name', '"Open Sans", sans-serif');
        Configuration::updateValue($this->name . 'g_title_font_size', 24);
        Configuration::updateValue($this->name . 'g_title_font_color', '#253237');
        Configuration::updateValue($this->name . 'g_title_font_transform', 2);
        Configuration::updateValue($this->name . 'g_title_font_size_column', 20);
		Configuration::updateValue($this->name . 'g_title_font_weight', 600);
		//header

		Configuration::updateValue($this->name . 'header_sticky', 1);
		Configuration::updateValue($this->name . 'sticky_background', '#ffffff');
        // Product
        Configuration::updateValue($this->name . 'p_display', 1);
        Configuration::updateValue($this->name . 'p_name_color', '#253237');
        Configuration::updateValue($this->name . 'p_name_colorh', '#4fb68d');
        Configuration::updateValue($this->name . 'p_name_size', 14);
        Configuration::updateValue($this->name . 'p_name_length', 0);
        Configuration::updateValue($this->name . 'p_name_transform', 1);
        Configuration::updateValue($this->name . 'p_price_color', '#555555');
        Configuration::updateValue($this->name . 'p_price_size', 15);
        // Category page
        Configuration::updateValue($this->name . 'cp_subcategories', 0);
        Configuration::updateValue($this->name . 'cp_layout', 1);
        Configuration::updateValue($this->name . 'PS_PRODUCTS_PER_PAGE', 16);
        Configuration::updateValue($this->name . 'cp_perrow', 2);
        // Product page
        Configuration::updateValue($this->name . 'productp_layout', 1);
        Configuration::updateValue($this->name . 'ppl1_thumbnail', 0);
        Configuration::updateValue($this->name . 'ppl1_items', 4);
        Configuration::updateValue($this->name . 'pp_name_color', '#253237');
        Configuration::updateValue($this->name . 'pp_name_size', 24);
        Configuration::updateValue($this->name . 'pp_name_transform', 1);
        Configuration::updateValue($this->name . 'pp_price_color', '#555555');
        Configuration::updateValue($this->name . 'pp_price_size', 22);
        Configuration::updateValue($this->name . 'pp_infortab', 0);

        Configuration::updateValue('poselement_flag', 0);

        return parent::install() && 
        $this->registerHook('displayHeader') &&
        $this->registerHook('actionCreativeElementsInit') && 
        $this->registerHook('actionAdminControllerSetMedia') && 
        $this->registerHook('displayBackOfficeHeader') &&
        $this->_createMenu();
    }

    public function uninstall()
    {
        return parent::uninstall()
               && $this->_deleteMenu();
    }

    protected function _createMenu() {
        $response = true;
        // First check for parent tab
        
        $parentconfigure = Tab::getIdFromClassName('IMPROVE');
        $parentTab = new Tab();
        $parentTab->active = 1;
        $parentTab->name = array();
        $parentTab->class_name = "PosThemeMenu";
        foreach (Language::getLanguages() as $lang) {
            $parentTab->name[$lang['id_lang']] = "POSTHEMES";
        }
        $parentTab->id_parent = 0;
        $response &= $parentTab->add();

        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "PosModules";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "Modules";
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('PosThemeMenu');
        $tab->module = $this->name;
        $tab->icon = 'open_with';
        $response &= $tab->add();

        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminPosThemeoptions";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "- Theme settings";
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('PosModules');
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    protected function _deleteMenu() {
        $parentTabID = Tab::getIdFromClassName('PosThemeMenu');

        // Get the number of tabs inside our parent tab
        $tabCount = Tab::getNbTabs($parentTabID);
        if ($tabCount == 0) {
            $parentTab = new Tab($parentTabID);
            $parentTab->delete();
        }
        
        $id_tab = (int)Tab::getIdFromClassName('AdminPosThemeoptions');
        $tab = new Tab($id_tab);
        $tab->delete();

        return true;
    }

    public function getContent()
    {
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
        $this->context->controller->addCSS($this->_path.'views/css/select2.min.css');
        $this->context->controller->addJS($this->_path.'views/js/select2.min.js');
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        
        $html = '';
        $multiple_arr = array();

        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            $html .= $this->getWarningMultishopHtml();
        }
        // START RENDER FIELDS
        $this->AllFields();
        // END RENDER FIELDS
        if(Tools::isSubmit('save'.$this->name)){
            if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
                $helper = $this->SettingForm();
                $html_form = $helper->generateForm($this->fields_form);
                $html .= $html_form;

                return $html;
            }
            foreach($this->fields_form as $key => $value){
                $multiple_arr = array_merge($multiple_arr,$value['form']['input']);
            }
            $old_categoty = Configuration::get('posthemeoptionscp_layout');
            // START LANG
            $languages = Language::getLanguages(false);
            if(isset($multiple_arr) && !empty($multiple_arr)){
                foreach($multiple_arr as $mvalue){
                    if(isset($mvalue['lang']) && $mvalue['lang'] == true && isset($mvalue['name'])){
                       foreach($languages as $lang){
                        ${$mvalue['name'].'_lang'}[$lang['id_lang']] = Tools::getvalue($mvalue['name'].'_'.$lang['id_lang']);
                       }
                    }
                }
            }
            // END LANG
            if(isset($multiple_arr) && !empty($multiple_arr)){
                //echo '<pre>';print_r($multiple_arr);die;
                foreach($multiple_arr as $mvalue){
                    if(isset($mvalue['lang']) && $mvalue['lang'] == true && isset($mvalue['name'])){
                            Configuration::updateValue($this->name.$mvalue['name'],${$mvalue['name'].'_lang'});
                    }else{
                        if(isset($mvalue['name'])){
                            if($mvalue['name'] == 'PS_PRODUCTS_PER_PAGE'){
                                Configuration::updateValue('PS_PRODUCTS_PER_PAGE',Tools::getvalue($mvalue['name']));
                            }else{
                                Configuration::updateValue($this->name.$mvalue['name'],Tools::getvalue($mvalue['name']));
                            }
                        }
                    }
                }
            }
			
            if(Configuration::get('posthemeoptionscp_layout') != $old_categoty){
                $this->changeCategory(Configuration::get('posthemeoptionscp_layout'));
            }
            $helper = $this->SettingForm();
            $html_form = $helper->generateForm($this->fields_form);
            $html .= $this->displayConfirmation($this->l('Successfully Saved All Fields Values.'));
            $html .= $html_form;
            $this->generateCss();
            $this->generateJs();
            
        }else{
            $helper = $this->SettingForm();
            $html_form = $helper->generateForm($this->fields_form);
            $html .= $html_form;
        }
        return $html;
    }
    public function SettingForm() {
        $languages = Language::getLanguages(false);
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->AllFields();
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        foreach ($languages as $lang)
                $helper->languages[] = array(
                        'id_lang' => $lang['id_lang'],
                        'iso_code' => $lang['iso_code'],
                        'name' => $lang['name'],
                        'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
                );
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save'.$this->name.'token=' . Tools::getAdminTokenLite('AdminModules'),
            )
        );
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'save'.$this->name;
        $multiple_arr = array();

        foreach($this->fields_form as $key => $value) {
            if(empty($multiple_arr)){
                if(isset($value['form']['input']) && !empty($value['form']['input'])){
                    $multiple_arr = $value['form']['input'];
                }
            }else{
                if(isset($value['form']['input']) && !empty($value['form']['input'])){
                    $multiple_arr = array_merge($multiple_arr,$value['form']['input']);
                }
            }
        }
        foreach($multiple_arr as $mvalue){
            if(isset($mvalue['lang']) && $mvalue['lang'] == true && isset($mvalue['name'])){
               foreach($languages as $lang){
                    $helper->fields_value[$mvalue['name']][$lang['id_lang']] = Configuration::get($this->name.$mvalue['name'],$lang['id_lang']);
               }
            }else{
                if(isset($mvalue['name'])){
                    if($mvalue['name'] == 'PS_PRODUCTS_PER_PAGE'){
                        $helper->fields_value[$mvalue['name']] = Configuration::get('PS_PRODUCTS_PER_PAGE');
                    }else{
                        $helper->fields_value[$mvalue['name']] = Configuration::get($this->name.$mvalue['name']);
                    }
                }
            }
        }
        return $helper;
    }

    public function AllFields()
    {
        $posthemeoption_settings = array();
        include_once(dirname(__FILE__).$this->fields_arr_path);
        if(isset($posthemeoption_settings) && !empty($posthemeoption_settings)){
            foreach ($posthemeoption_settings as $posthemeoption_setting) {
                $this->fields_form[]['form'] = $posthemeoption_setting;
            }
        }
        //echo '<pre>'; print_r($this->fields_form);die;
        return $this->fields_form;
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function posUnregisterHook($module){
        $shop_list = array();
        $shop_list[0] = (int)Context::getContext()->shop->id;

        Hook::unregisterHook($module, 'displayNav', $shop_list);
        Hook::unregisterHook($module, 'displayNav1', $shop_list);
        Hook::unregisterHook($module, 'displayNav2', $shop_list);
        Hook::unregisterHook($module, 'displayTop', $shop_list);
		Hook::unregisterHook($module, 'displayBanner', $shop_list);
    }
   
    public function changeCategory($category_layout){
        $faceted_module = Module::getInstanceByName("ps_facetedsearch");
        if($category_layout == '1'){
            Hook::registerHook($faceted_module, 'displayLeftColumn');
            Hook::unregisterHook($faceted_module, 'displayFilterTop');
        }else{
            Hook::registerHook($faceted_module, 'displayFilterTop');
            Hook::unregisterHook($faceted_module, 'displayLeftColumn');
        }
    }
    
    public function convertTransform($value) {
            switch($value) {
                case 2 :
                    $transform_option = 'capitalize';
                    break;
                case 1 :
                    $transform_option = 'none';
                    break;
                default :
                    $transform_option = 'uppercase';
            }
            return  $transform_option;
    }
	   public function convertFontweight($value) {
            switch($value) {
                case 1 :
                    $font_weight_option = '600';
                    break;
                case 2 :
                    $font_weight_option = '400';
                    break;
				case 3 :
                    $font_weight_option = '500';
                    break;
                case 4 :
                    $font_weight_option = '700';
                    break;
				case 5 :
                    $font_weight_option = '800';
                    break;	
				default :
                    $font_weight_option = '900';
                
            }
            return  $font_weight_option;
    }
    public function generateCss()
    {
        $css = '';
		$container_width = Configuration::get($this->name . 'container_width');
        if($container_width){
            $css .='
            @media (min-width: 1200px) {
            .container {  	
                width: '.$container_width.';
                
            }}';
        }
        $boxed_width = Configuration::get($this->name . 'boxed_width');
        if($boxed_width){
            $css .='
            @media (min-width: 1200px) {
            .layout_boxed main{  	 
                width: '.$boxed_width.'; 
                
            }}';
        }
        $main_color = Configuration::get($this->name . 'g_main_color'); 
		$body_bg_color = Configuration::get($this->name . 'g_body_bg_color');
		$body_font_color = Configuration::get($this->name . 'g_body_font_color');
		$title_block_font_weight = $this->convertFontweight(Configuration::get($this->name . 'g_title_font_weight'));
        $css .='
         :root {  
            --hovercolor: '.$main_color.'; 
            --bg_dark: '.$body_bg_color.'; 
            --font-weight: '.$title_block_font_weight.';  
			
        }';
        $body_font_family = Configuration::get($this->name . 'g_body_gfont_name');
        $body_font_size = Configuration::get($this->name . 'g_body_font_size');
        $body_link_color = Configuration::get($this->name . 'g_a_color');
        $body_link_colorh = Configuration::get($this->name . 'g_a_colorh');
        $css .= 'body{
            font-family: '.$body_font_family.';
            font-size: '.$body_font_size.'px;
            color: '.$body_font_color.';
        }';
        $body_bg_image = Configuration::get($this->name . 'g_body_bg_image');
        $body_bg_repeat = Configuration::get($this->name . 'g_body_bg_repeat');
        $body_bg_attachment = Configuration::get($this->name . 'g_body_bg_attachment');
        $body_bg_size = Configuration::get($this->name . 'g_body_bg_size');
        if($body_bg_color || $body_bg_image){
            $css .= 'body{';
                if($body_bg_color){
                    $css .= 'background-color: '.$body_bg_color.';';
                }
                if($body_bg_image){
                    $css .= 'background-image:  url('.$body_bg_image.');';
                }
                if($body_bg_repeat){
                    if($body_bg_repeat == 'x' || $body_bg_repeat == 'y'){
                        $css .= 'background-repeat: repeat-'.$body_bg_repeat.';';
                    }elseif($body_bg_repeat == 'xy'){
                        $css .= 'background-repeat: repeat;';
                    }else{
                        $css .= 'background-repeat: no-repeat;';
                    }
                }
                if($body_bg_attachment){
                    $css .= 'background-attachment: '.$body_bg_attachment.';';
                }
                if($body_bg_size){
                    $css .= 'background-size: '.$body_bg_size.';';
                }
            $css .= '}';
        }
        
        $title_block_font_family = Configuration::get($this->name . 'g_title_gfont_name');
        $title_block_font_size = Configuration::get($this->name . 'g_title_font_size');
        $title_block_font_color = Configuration::get($this->name . 'g_title_font_color');
        $title_block_font_tranform = $this->convertTransform(Configuration::get($this->name . 'g_title_font_transform'));
        $title_block_font_size_column = Configuration::get($this->name . 'g_title_font_size_column');
        $css .='.pos_title h2,.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6{
            font-family: '.$title_block_font_family.';
            color: '.$title_block_font_color.';
            text-transform: '.$title_block_font_tranform.';
			font-weight: '.$title_block_font_weight.';
        }';
		 $css .='.pos_title h2{
            font-size: '.$title_block_font_size.'px;
        }';
		$css .='.pos-title{
            font-family: '.$title_block_font_family.';
        }';
        $css .= '.pos-title-column h4{   
            font-size: '.$title_block_font_size_column.'px;
        }';
        $button_color = Configuration::get($this->name . 'g_button_color');
        $button_colorh = Configuration::get($this->name . 'g_button_colorh');
        $button_bgcolor = Configuration::get($this->name . 'g_button_bgcolor');
        $button_bgcolorh = Configuration::get($this->name . 'g_button_bgcolorh');
        //header
        $sticky_header_bg = Configuration::get($this->name . 'sticky_background');
        $css .= '#header .sticky-inner.scroll-menu{  
            background-color: '.$sticky_header_bg.';   
        }';

        //Page title
        $ptitle_bg_image = Configuration::get($this->name . 'ptitle_bg_image');
        $ptitle_color = Configuration::get($this->name . 'ptitle_color');
        if($ptitle_bg_image){
            $css .= '.page-title-wrapper{  
                background-image: url('.$ptitle_bg_image.');   
            }';
        }
        if($ptitle_color){
            $css .= '.page-header h1,.breadcrumb{  
                color: '.$ptitle_color.';   
            }';
        }
        

        //Product grid
        $pg_name_color = Configuration::get($this->name . 'p_name_color');
        $pg_name_colorh = Configuration::get($this->name . 'p_name_colorh');
        $pg_name_font_size = Configuration::get($this->name . 'p_name_size');
        $pg_name_font_transform = $this->convertTransform(Configuration::get($this->name . 'p_name_transform'));
        $pg_price_color = Configuration::get($this->name . 'p_price_color');
        $pg_price_font_size = Configuration::get($this->name . 'p_price_size');
        $css .= '.js-product-miniature .product_desc .product_name{
            color: '.$pg_name_color.';
            font-size: '.$pg_name_font_size.'px;
            text-transform: '.$pg_name_font_transform.';
        }';
        $css .= '.js-product-miniature .product_desc .product_name:hover{
            color: '.$pg_name_colorh.';
        }';
        $css .= '.product-price-and-shipping .price{
            color:'.$pg_price_color.';
            font-size: '.$pg_price_font_size.'px;
        }';

        $pp_name_color = Configuration::get($this->name . 'pp_name_color');
        $pp_name_font_size = Configuration::get($this->name . 'pp_name_size');
        $pp_name_font_transform = $this->convertTransform(Configuration::get($this->name . 'pp_name_transform'));
        $pp_price_color = Configuration::get($this->name . 'pp_price_color');
        $pp_price_font_size = Configuration::get($this->name . 'pp_price_size');
        $css .= '.h1.namne_details, .product_name_h1{
            color: '.$pp_name_color.';
            font-size: '.$pp_name_font_size.'px;
            text-transform: '.$pp_name_font_transform.';
        }';
        $css .= '.product-prices .price, .product-prices .current-price span:first-child{
            color:'.$pp_price_color.';
            font-size: '.$pp_price_font_size.'px;
        }';
        //details
        $case_bg = Configuration::get($this->name . 'productp_background');
        $css .= '#product.showcase-body #header,.showcase-inner,#product.showcase-body .page-title-wrapper{
            background-color: '.$case_bg.';
        }';
        //Custom CSS
        if(Configuration::get($this->name . 'custom_css')){
            $css .= Configuration::get($this->name . 'custom_css');
        }
        if (Shop::getContext() == Shop::CONTEXT_SHOP)
            $my_file = $this->local_path.'views/css/posthemeoptions_s_'.(int)$this->context->shop->getContextShopID().'.css';
        
        $fh = fopen($my_file, 'w') or die("can't open file");
        fwrite($fh, $css);
        fclose($fh);
    }
    public function generateJs()
    {
        $js = '';
    
        if(Configuration::get($this->name . 'custom_js')){
            $js .= Configuration::get($this->name . 'custom_js');
        }
        if (Shop::getContext() == Shop::CONTEXT_SHOP)
            $my_file = $this->local_path.'views/js/posthemeoptions_s_'.(int)$this->context->shop->getContextShopID().'.js';
        if($js){
            $fh = fopen($my_file, 'w') or die("can't open file");
            fwrite($fh, $js);
            fclose($fh);
        }else{
            if(file_exists($my_file)){
                unlink($my_file);
            }
        } 
        
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        return false;
    }
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        return false;
    }

    public function hookHeader($params)
	{  
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
        Media::addJsDef(array(
            'pdays_text' => $this->l('days'),
            'pday_text' => $this->l('day'),
            'phours_text' => $this->l('hours'),
            'phour_text' => $this->l('hour'),
            'pmins_text' => $this->l('mins'),
            'pmin_text' => $this->l('min'),
            'psecs_text' => $this->l('secs'),
            'psec_text' => $this->l('sec'),
            'pos_subscription' => $this->context->link->getModuleLink($this->name, 'subscription'),
        ));

		if (Shop::getContext() == Shop::CONTEXT_SHOP){
    		$this->context->controller->addCSS(($this->_path).'views/css/posthemeoptions_s_'.(int)$this->context->shop->getContextShopID().'.css', 'all');
            $js_file = ($this->_path).'views/js/posthemeoptions_s_'.(int)$this->context->shop->getContextShopID().'.js';
            $this->context->controller->addJS($js_file);
		}
        $body_font_family = Configuration::get($this->name . 'g_body_gfont_url');
		if($body_font_family) $this->context->controller->registerStylesheet('posthemeoptions-body-fonts', $body_font_family,['server' => 'remote']);
		$title_font_family = Configuration::get($this->name . 'g_title_gfont_url');
		if($body_font_family && $title_font_family != $body_font_family)$this->context->controller->registerStylesheet('posthemeoptions-title-fonts', $title_font_family,['server' => 'remote']);
        $body_class = '';
        if(Module::isInstalled('posquickmenu') && Module::isEnabled('posquickmenu')){
            $body_class  = 'has-quickmenu';
        }

		$smart_vals = array(
            'body_class' => $body_class,
			'body_dark' => Configuration::get($this->name . 'g_dark'),
			'sidebar_width' => Configuration::get($this->name . 'sidebar'), 
			'body_layout' => Configuration::get($this->name . 'layout'), 
            'header_sticky' => Configuration::get($this->name . 'header_sticky'),
            'header_template' => Configuration::get($this->name . 'header_template'),
            'home_template' => Configuration::get($this->name . 'home_template'),
            'footer_template' => Configuration::get($this->name . 'footer_template'),
			'grid_type' => isset($_GET['gt']) ? $_GET['gt'] : Configuration::get($this->name . 'p_display'),
            'grid_border' => Configuration::get($this->name . 'p_border'),
            'grid_padding' => Configuration::get($this->name . 'p_padding'),
			'name_length' => Configuration::get($this->name . 'p_name_length'),
			'cate_layout' => isset($_GET['ft']) ? $_GET['ft'] : Configuration::get($this->name . 'cp_layout'),
            'cate_default_display' => Configuration::get($this->name . 'cp_display'),
			'cate_show_subcategories' => Configuration::get($this->name . 'cp_subcategories'),
			'cate_product_per_row' => isset($_GET['pr']) ? $_GET['pr'] : Configuration::get($this->name . 'cp_perrow'),
			'product_thumbnail' => Configuration::get($this->name . 'pp_thumbnail'),
            'productp_layout' => isset($_GET['pplayout']) ? $_GET['pplayout'] : Configuration::get($this->name . 'productp_layout'),
            'productp_image_position' => isset($_GET['pt']) ? $_GET['pt'] : Configuration::get($this->name . 'ppl1_thumbnail'),
            'productp_thumbnail_item' => Configuration::get($this->name . 'ppl1_items'),
            'productp_thumbnail_item_top' => isset($_GET['pp']) ? $_GET['pp'] : Configuration::get($this->name . 'ppl3_items'),
            'productp_image_gridcolumn' => isset($_GET['pl']) ? $_GET['pl'] : Configuration::get($this->name . 'ppl2_column'), 
            'productp_tab' => isset($_GET['tb']) ? $_GET['tb'] : Configuration::get($this->name . 'pp_infortab'),
            //Page title
            'ptitle_size' => Configuration::get($this->name . 'ptitle_size'),
		);
		$this->context->smarty->assign('postheme', $smart_vals);

		$this->context->smarty->assign('name_length', Configuration::get($this->name . 'p_name_length'));
	}

    public function hookDisplayBackOfficeHeader()
    {
        //$this->context->controller->addJS($this->_path.'/views/js/back.js');
        //$this->context->controller->addCSS($this->_path.'/views/css/back.css');
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Configuration::get('poselement_flag') != 1) {
            include_once(_PS_MODULE_DIR_ .'posthemeoptions/install/install.php');

            $install = new Install();
            
            if ($install->installTemplates()) {
                Configuration::updateValue('poselement_flag', 1);
            }
        }
    }

    public function posEnqueueScripts()
    {
        CE\wp_enqueue_style('ecolife-icon', $this->_path.'/views/css/ecolife-icon.css', array(), '1.0.0');
    }

    public function registerPosWidgets(){
        $poswidgets = glob(_PS_MODULE_DIR_.$this->name.'/elementor/widgets/*.php');
        foreach( $poswidgets as $poswidget ){
            require( $poswidget );
            $classname = 'CE\\'.basename( $poswidget, '.php' );
            if ( class_exists($classname) ){
                CE\Plugin::instance()->widgets_manager->registerWidgetType( new $classname() );
            }
        }
    }
    
    public function hookActionCreativeElementsInit()
    {
        include(_PS_MODULE_DIR_ . $this->name . '/elementor/src/WidgetHelper.php');

        CE\add_action('elementor/widgets/widgets_registered', [$this, 'registerPosWidgets']);

        CE\add_action('elementor/elements/categories_registered', function($manager) {
            $manager->addCategory('posthemes',  ['title' => 'Posthemes']);
            $manager->addCategory('posthemes_header',  ['title' => 'Posthemes header']);
            $manager->addCategory('posthemes_footer',   ['title' => 'Posthemes footer']);
        });
        CE\add_action('elementor/editor/before_enqueue_scripts', array($this, 'posEnqueueScripts'));
    }

	protected function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">' .
            $this->getTranslator()->trans('You cannot manage slides items from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit', array(), 'Modules.Imageslider.Admin') .
            '</p>';
        } else {
            return '';
        }
    }

    public function getAllTemplates(){
        if(Module::isInstalled('creativeelements') && Module::isEnabled('creativeelements')){
            $sql = 'SELECT ct.`id_ce_template`, ct.`title` FROM `' . _DB_PREFIX_ . 'ce_template` ct WHERE ct.`active` = 1';
            $results = Db::getInstance()->executeS($sql);
            foreach($results as &$result){
                $result['title'] = $result['id_ce_template'] .'. '. $result['title'];
            }
            return $results;
        }
        return false;
    }
}
