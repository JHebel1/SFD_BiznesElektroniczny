<?php

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class PosSizeChart extends Module implements WidgetInterface{
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {

		$this->name                   = 'possizechart';
		$this->tab                    = 'front_office_features';
		$this->version                = '1.0.0';
		$this->author                 = 'Posthemes';
		$this->need_instance          = 0;
		$this->ps_versions_compliancy = array(
			'min' => '1.7',
			'max' => _PS_VERSION_,
		);
		$this->bootstrap              = true;

		parent::__construct();

		$this->displayName = $this->l( 'Pos size chart' );
		$this->description = $this->l( 'Add size chart to products' );

		$this->confirmUninstall = $this->l( 'Are you sure you want to uninstall?' );
		$this->define_constants();

		$this->templateFile = 'module:possizechart/views/templates/hook/possizechart.tpl';
	}

	/**
	 * Install function runs on installing the module.
	 */
	public function install() {
		$this->insertTables();
		return parent::install()
		&& $this->installTab()
		&& $this->registerHook('displayHeader')
		&& $this->registerHook( 'displaySizeChart' )
		&& $this->registerHook( 'displayBackOfficeHeader' );
	}

	/**
	 * Uninstall function runs on uninstallation of the module.
	 */
	public function uninstall() {
		return ( parent::uninstall()
		&& $this->deleteTables()
		&& $this->deleteTab() );
	}

	public function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminPosSizeChart";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "- Size chart";
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('PosModules');
        $tab->module = $this->name;
        return $tab->add();
    }
    public function deleteTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminPosSizeChart');
        $tab = new Tab($id_tab);
        return $tab->delete();
    }

	public function getContent(){
		$token = Tools::getAdminTokenLite('AdminPosSizeChart');
     	$currentIndex='index.php?controller=AdminPosSizeChart&token='.$token;
		Tools::redirectAdmin($currentIndex);
	}

	/**
	 * Define_constants function defines constants.
	 *
	 * @return void
	 */
	private function define_constants() {

		if ( ! defined( 'POS_SCHART_URL' ) ) {
			define( 'POS_SCHART_URL', _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . '/modules/' . 'possizechart/' );
		}

		if ( ! defined( 'POS_SCHART_CLASS_DIR' ) ) {
			define( 'POS_SCHART_CLASS_DIR', _PS_MODULE_DIR_ . 'possizechart/classes/' );
		}

		if ( ! defined( 'POS_SCHART_ASSETS_DIR' ) ) {
			define( 'POS_SCHART_ASSETS_DIR', _PS_MODULE_DIR_ . 'possizechart/assets/' );
		}

	}

	/**
	 * InsertTables in sertst tables for the module.
	 */
	private function insertTables() {
		$sql = array();
		include_once dirname( __FILE__ ) . '/helpers/install_sql.php';
		if ( is_array( $sql ) && ! empty( $sql ) ) {
			foreach ( $sql as $sq ) :
				if ( ! Db::getInstance()->Execute( $sq ) ) {
					return false;
				}
			endforeach;
		};
		return true;
	}

	private function deleteTables() {
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS
			`'._DB_PREFIX_.'possizecharts`,
			`'._DB_PREFIX_.'possizecharts_lang`,
			`'._DB_PREFIX_.'possizecharts_shop`');
	}

	/**
	 * HookDisplayBackOfficeHeader
	 *
	 * @return void
	 */
	public function hookDisplayBackOfficeHeader() {
		$this->context->controller->addCSS( POS_SCHART_ASSETS_DIR . '/css/admin.css' );
	}
	public function hookHeader($params){
        $this->context->controller->addCSS($this->_path.'assets/css/possizechart.css');
        $this->context->controller->addJS($this->_path.'js/front/front.js');
    }
	public function renderWidget($hookName, array $configuration)
    {
        $id_product = Tools::getValue( 'id_product' );

        $key = 'possizechart-'.$id_product;

        if (!$this->isCached($this->templateFile, $this->getCacheId($key))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId($key));
    }

    public function getWidgetVariables($hookName, array $configuration)
    {

        include_once POS_SCHART_CLASS_DIR . 'posschart.php';

		$id_product = Tools::getValue( 'id_product' );

		$posschart = new Posschart();

		$results = $posschart->GetTabContentByProductId( $id_product, 'title' );

		if(!$results) return;

		$array = array();
		foreach ( $results as $result ) {
			$content = $result['content'];
		}

		return array(
			'content' => $content,
		);
    }
}