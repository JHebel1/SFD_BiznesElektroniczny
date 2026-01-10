<?php
/**
 * package   Pos Search
 *
 * @version 2.0.0
 * @author    Posthemes Website: posthemes.com
 * @copyright (c) 2021 YouTech Company. All Rights Reserved.
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

if (!defined('_PS_VERSION_'))
	exit;

require_once _PS_MODULE_DIR_ . 'possearchproducts/src/PosSearchCore.php';
require_once _PS_MODULE_DIR_ . 'possearchproducts/src/PosSearchProvider.php';

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use Posthemes\Module\Adapter\Search\PosSearchProvider;

class Possearchproducts extends Module implements WidgetInterface
{
	protected $_html = '';
	private $templateFile;

	public static $level = array(
        1 => array('id' =>1 , 'name' => '2'),
        2 => array('id' =>2 , 'name' => '3'),
        3 => array('id' =>3 , 'name' => '4'),
        4 => array('id' =>4 , 'name' => '5'),

    );
	public function __construct()
	{
		$this->name = 'possearchproducts';
		$this->tab = 'Search and filter';
		$this->version = '2.0.0';
		$this->author = 'Posthemes';
		$this->need_instance = 0;
		$this->bootstrap =true ;
		$this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.7','max' => _PS_VERSION_];
		parent::__construct();
		$this->displayName = $this->l('Pos search products by category ');
		$this->description = $this->l('Adds a quick search field categories to your website.');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

		$this->templateFile = 'module:possearchproducts/views/templates/front/possearch.tpl';
	}
	public function install()
	{ 	
		Configuration::updateValue('POSSEARCH_CATE', 0);
        Configuration::updateValue('POSSEARCH_LEVEL', 3);
		Configuration::updateValue('POSSEARCH_NUMBER', 10);

        return parent :: install()
        	&& $this->registerHook('productSearchProvider')
			&& $this->registerHook('displayHeader');
	}

	public function uninstall(){
		Configuration::deleteByName('POSSEARCH_CATE');
		Configuration::deleteByName('POSSEARCH_LEVEL');
		Configuration::deleteByName('POSSEARCH_NUMBER');
		return parent::uninstall();
	}

	public function getContent(){
		$this->html = '';
		if(Tools::isSubmit('submitUpdate')){
			Configuration::UpdateValue('POSSEARCH_CATE',Tools::getValue('POSSEARCH_CATE'));
			Configuration::UpdateValue('POSSEARCH_LEVEL',Tools::getValue('POSSEARCH_LEVEL'));
			Configuration::UpdateValue('POSSEARCH_NUMBER',Tools::getValue('POSSEARCH_NUMBER'));
			$this->html = $this->displayConfirmation($this->l('Settings updated successfully.'));
		}
		$this->html .= $this->renderForm();
		return $this->html;

	}

	public function renderForm(){
	
			$fields_form = array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Settings'),
						'icon' => 'icon-cogs'
					),
					'input' => array(
						array(
							'type'      => 'switch',
							'label'     => $this->l('Enable list categories'),
							'desc'      => $this->l('Would you like show  categories ?'),
							'name'      => 'POSSEARCH_CATE',
							'values'    => array(
								array(
									'id'    => 'active_on',
									'value' => 1,
									'label' => $this->l('Enabled')
								),
								array(
									'id'    => 'active_off',
									'value' => 0,
									'label' => $this->l('Disabled')
								)
							),
						),
						array(
		                    'type' => 'select',
		                    'label' => $this->l('Category depth level'),
		                    'name' => 'POSSEARCH_LEVEL',
		                    'options' => array(
		                        'query' => self::$level,
		                        'id' => 'id',
		                        'name' => 'name',
		                    ),
		                    'validation' => 'isUnsignedInt',
		                ), 
						array(
							'type' => 'text',
							'label' => $this->l('Number products in ajax result'),
							'name' => 'POSSEARCH_NUMBER',
							'class' => 'fixed-width-sm',
							'desc' => $this->l('')
						),
					),
					'submit' => array(
						'title' => $this->l('Save'),
					),
				),
			);
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitUpdate';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'POSSEARCH_CATE' => Tools::getValue('POSSEARCH_CATE', Configuration::get('POSSEARCH_CATE')),
			'POSSEARCH_LEVEL' => Tools::getValue('POSSEARCH_LEVEL', Configuration::get('POSSEARCH_LEVEL')),
			'POSSEARCH_NUMBER' => Tools::getValue('POSSEARCH_NUMBER', Configuration::get('POSSEARCH_NUMBER')),
		);
	}
	public function hookHeader($params)
	{	
		Media::addJsDef(
            array(
                'id_lang' => (int)$this->context->language->id ,
                'possearch_number' => (int)Configuration::get('POSSEARCH_NUMBER'),
             )
    	);
	}

	public function getWidgetVariables($hookName, array $configuration = [])
    {	
        $cate_on = (int)Configuration::get('POSSEARCH_CATE');

        $widgetVariables = array(
        	'show_categories' => $cate_on,
        	'search_query' => (string)Tools::getValue('search_query'),
        	'url_search' => __PS_BASE_URI__ . 'modules/possearchproducts/SearchProducts.php',
            'search_controller_url' =>$this->context->link->getPageLink('search', null, null, null, false, null, true),
            'placeholder'       => isset($configuration['placeholder']) ? $configuration['placeholder'] : '',
            'search_type'       => isset($configuration['search_type']) ? $configuration['search_type'] : '',
            'icon'              => isset($configuration['icon']) ? $configuration['icon'] : '',
            'button_type'       => isset($configuration['button_type']) ? $configuration['button_type'] : '',
            'button_text'       => isset($configuration['button_text']) ? $configuration['button_text'] : '',
            'category_tree' 	=> _PS_MODULE_DIR_. '/possearchproducts/views/templates/front/category-tree-branch.tpl',
            'options' 			=> $this->getCategoryOption(2, false, false, true, 0),
            'selected_cat'		=> Tools::getValue('cat'),
        );
        if (!array_key_exists('search_string', $this->context->smarty->getTemplateVars())) {
            $widgetVariables['search_string'] = '';
        }
        return $widgetVariables;
    }

    public function renderWidget($hookName, array $configuration = [])
    { 
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch($this->templateFile);
    }
    public function getCategoryOption($id_category = 2, $id_lang = false, $id_shop = false, $recursive = true, $depth=0) {
    	$maxdepth = (int)Configuration::get('POSSEARCH_LEVEL') + 2;
    	$depth = $depth + 1;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
		if (is_null($category->id))
			return;
		if ($recursive)
		{
			$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop); // array	
		}
		if($depth <= $maxdepth){
		if (isset($children) && count($children)){
			if($category->id != 1 && $category->id != 2){
				$this->_html .='<li class="search-cat-item cat-level-'. $depth .'" date-depth="'. $depth .'">';
				$this->_html .='<a href="#" class="search-cat-value" data-id="'. $category->id .'">'.$category->name.'</a>';
				$this->_html .='</li>';
			}
		 	foreach ($children as $child){
				$this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'], true, $depth);
			}
		 }else{
			 $this->_html .='<li class="search-cat-item cat-level-'. $depth .'">';
			 $this->_html .='<a href="#" class="search-cat-value" data-id="'. $category->id .'">'.$category->name.'</a>';
			 $this->_html .='</li>';
		 }
		}
		
		
         return $this->_html ;
    }
	

	public function hookProductSearchProvider()
    {
        $controller = Dispatcher::getInstance()->getController();

        if (!empty($this->context->controller->php_self)) {
            $controller = $this->context->controller->php_self;
        }
		
		$controller = Tools::strtolower( $controller );
		
		if( $controller != 'search' ){
			return null;
		}
		
		$search_string = Tools::getValue('s');
		
        if (!$search_string) {
            $search_string = Tools::getValue('search_query');
        }
		
		if( !$search_string ){
			return null;
		}
		
        return new PosSearchProvider(
        	$this->getTranslator(),
			new PosSearchCore()
        );
    }
}

