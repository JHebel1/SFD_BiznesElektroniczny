<?php

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class PosProductextratab extends Module {
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {

		$this->name                   = 'posproductextratab';
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

		$this->displayName = $this->l( 'Pos Product Extra Tab' );
		$this->description = $this->l( 'Add extra product tab to specific products' );

		$this->confirmUninstall = $this->l( 'Are you sure you want to uninstall?' );
		$this->define_constants();
	}

	/**
	 * Install function runs on installing the module.
	 */
	public function install() {
		$this->insertTables();
		return parent::install()
		&& $this->installTab()
		&& $this->registerHook( 'displayProductExtraContent' )
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

	public function getContent(){
		$token = Tools::getAdminTokenLite('AdminPosProductExtraTabs');
     	$currentIndex='index.php?controller=AdminPosProductExtraTabs&token='.$token;
		Tools::redirectAdmin($currentIndex);
	}

	/**
	 * Define_constants function defines constants.
	 *
	 * @return void
	 */
	private function define_constants() {

		if ( ! defined( 'POS_PEXTRATAB_URL' ) ) {
			define( 'POS_PEXTRATAB_URL', _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . '/modules/' . 'posproductextratab/' );
		}

		if ( ! defined( 'POS_PEXTRATAB_CLASS_DIR' ) ) {
			define( 'POS_PEXTRATAB_CLASS_DIR', _PS_MODULE_DIR_ . 'posproductextratab/classes/' );
		}

		if ( ! defined( 'POS_PEXTRATAB_ASSETS_DIR' ) ) {
			define( 'POS_PEXTRATAB_ASSETS_DIR', _PS_MODULE_DIR_ . 'posproductextratab/assets/' );
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
			`'._DB_PREFIX_.'posproductextratabs`,
			`'._DB_PREFIX_.'posproductextratabs_lang`,
			`'._DB_PREFIX_.'posproductextratabs_shop`');
	}

	public function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminPosProductExtraTabs";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "- Product Extra Tabs";
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('PosModules');
        $tab->module = $this->name;
        return $tab->add();
    }
    public function deleteTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminPosProductExtraTabs');
        $tab = new Tab($id_tab);
        return $tab->delete();
    }

	/**
	 * HookdisplayProductExtraContent hook callback for the hook "displayProductExtraContent"
	 *
	 * @param mixed $params pramaeters for the functions.
	 */
	public function hookdisplayProductExtraContent( $params ) {

		include_once POS_PEXTRATAB_CLASS_DIR . 'pospextratab.php';

		$id_product = Tools::getValue( 'id_product' );

		$posextraobg = new Pospextratab();

		$results = $posextraobg->GetTabContentByProductId( $id_product, 'title' );

		$array = array();
		foreach ( $results as $result ) {
			$content = $result['content'];
			
			$array[] = ( new PrestaShop\PrestaShop\Core\Product\ProductExtraContent() )
				->setTitle( $result['title'] )
				->setContent( $content );
		}
		return $array;
	}

	/**
	 * HookDisplayBackOfficeHeader
	 *
	 * @return void
	 */
	public function hookDisplayBackOfficeHeader() {
		$this->context->controller->addCSS( POS_PEXTRATAB_ASSETS_DIR . '/css/admin.css' );
	}
}