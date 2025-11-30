<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

defined('_PS_VERSION_') or die;

define('_CE_VERSION_', '1.4.10.4.in-stock');
define('_CE_PATH_', _PS_MODULE_DIR_ . 'creativeelements/');
define('_CE_TEMPLATES_', _CE_PATH_ . 'views/templates/');
define('_CE_ASSETS_URL_', _MODULE_DIR_ . 'creativeelements/views/');
define('_CE_PS16_', version_compare(_PS_VERSION_, '1.7', '<'));

require_once _CE_PATH_ . 'classes/CETemplate.php';
require_once _CE_PATH_ . 'classes/CEContent.php';
require_once _CE_PATH_ . 'classes/CESmarty.php';
require_once _CE_PATH_ . 'includes/plugin.php';

class CreativeElements extends Module
{
    const VIEWS = 'modules/creativeelements/views/';

    protected static $controller;

    public $controllers = array(
        'preview',
    );

    protected $overrides = array(
        'Category',
        'CmsCategory',
        'Manufacturer',
        'Supplier',
    );
    protected $tplOverride = false;

    public function __construct($name = null, Context $context = null)
    {
        $this->name = 'creativeelements';
        $this->tab = 'content_management';
        $this->version = '1.4.10.4';
        $this->author = 'WebshopWorks';
        $this->module_key = '7a5ebcc21c1764675f1db5d0f0eacfe5';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '8.9');
        $this->bootstrap = true;
        $this->displayName = $this->l('Creative Elements - Elementor based PageBuilder') . ' [in-stock]';
        $this->description = $this->l('The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.') .
            '<a href="https://addons.prestashop.com/administrative-tools/44064-creative-elements-elementor-based-pagebuilder.html" target="_blank">' .
                ' [' . $this->l('Upgrade to PREMIUM') . ']' .
            '</a>';
        parent::__construct($this->name, null);

        $this->checkThemeChange();

        $this->dir = $this->context->language->is_rtl ? '-rtl' : '';
        $this->min = _PS_MODE_DEV_ ? '' : '.min';

        Shop::addTableAssociation(CEContent::$definition['table'], array('type' => 'shop'));
        Shop::addTableAssociation(CEContent::$definition['table'] . '_lang', array('type' => 'fk_shop'));
    }

    public function install()
    {
        require_once _CE_PATH_ . 'classes/CEDatabase.php';

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        CEDatabase::initConfigs();

        if (!CEDatabase::createTables()) {
            $this->_errors[] = Db::getInstance()->getMsgError();
            return false;
        }
        CEDatabase::addHome();

        if ($res = parent::install() && CEDatabase::updateTabs()) {
            foreach (CEDatabase::getHooks() as $hook) {
                $res = $res && $this->registerHook($hook, null, 1);
            }
        }

        return $res;
    }

    public function uninstall()
    {
        foreach (Tab::getCollectionFromModule($this->name) as $tab) {
            $tab->delete();
        }

        return parent::uninstall();
    }

    public function enable($force_all = false)
    {
        return parent::enable($force_all) && Db::getInstance()->update(
            'tab',
            array('active' => 1),
            "module = 'creativeelements' AND class_name != 'AdminCEEditor'"
        );
    }

    public function disable($force_all = false)
    {
        return Db::getInstance()->update(
            'tab',
            array('active' => 0),
            "module = 'creativeelements'"
        ) && parent::disable($force_all);
    }

    public function addOverride($classname)
    {
        try {
            return parent::addOverride($classname);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminCEContent'));
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        if (_CE_PS16_) {
            $ssl = Tools::usingSecureMode();

            if (!$ssl && Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) {
                // PS 1.6 fix for Enable SSL on all pages
                Tools::redirectAdmin('https://' . ShopUrl::getMainShopDomainSSL() . $_SERVER['REQUEST_URI']);
            }
            if (strcasecmp($domain = $ssl ? ShopUrl::getMainShopDomainSSL() : ShopUrl::getMainShopDomain(), $_SERVER['HTTP_HOST'])) {
                // PS 1.6 fix for cross-origin restrictions
                Tools::redirectAdmin(Tools::getProtocol($ssl) . $domain . $_SERVER['REQUEST_URI']);
            }
        }
        if (!Configuration::get('PS_ALLOW_HTML_IF' . 'RAME')) {
            Configuration::updateValue('PS_ALLOW_HTML_IF' . 'RAME', 1);
        }

        // Handle migrate
        if ((Configuration::getGlobalValue('ce_migrate') || Tools::getIsset('CEMigrate')) &&
            Db::getInstance()->executeS("SHOW TABLES LIKE '%_ce_meta'")
        ) {
            require_once _CE_PATH_ . 'classes/CEMigrate.php';
            CEMigrate::registerJavascripts();
        }

        $footer_product = '';
        preg_match('~/([^/]+)/(\d+)/edit\b~', $_SERVER['REQUEST_URI'], $req);
        $controller = Tools::strtolower(Tools::getValue('controller'));

        switch ($controller) {
            case 'admincetemplates':
                $id_type = CE\UId::TEMPLATE;
                $id = (int) Tools::getValue('id_ce_template');
                break;
            case 'admincecontent':
                $id_type = CE\UId::CONTENT;
                $id = (int) Tools::getValue('id_ce_content');
                break;
            case 'admincmscontent':
                if ($req && $req[1] == 'category' || Tools::getIsset('addcms_category') || Tools::getIsset('updatecms_category')) {
                    $id_type = CE\UId::CMS_CATEGORY;
                    $id = (int) Tools::getValue('id_cms_category', $req ? $req[2] : 0);
                    break;
                }
                $id_type = CE\UId::CMS;
                $id = (int) Tools::getValue('id_cms', $req ? $req[2] : 0);
                break;
            case 'adminproducts':
                $id_type = CE\UId::PRODUCT;
                $id = (int) Tools::getValue('id_product', basename(explode('?', $_SERVER['REQUEST_URI'])[0]));
                $footer_product = new CE\UId(CEContent::getFooterProductId($id), CE\UId::CONTENT, 0, $this->context->shop->id);
                break;
            case 'admincategories':
                $id_type = CE\UId::CATEGORY;
                $id = (int) Tools::getValue('id_category', $req ? $req[2] : 0);
                break;
            case 'adminmanufacturers':
                $id_type = CE\UId::MANUFACTURER;
                $id = (int) Tools::getValue('id_manufacturer', $req ? $req[2] : 0);
                break;
            case 'adminsuppliers':
                $id_type = CE\UId::SUPPLIER;
                $id = (int) Tools::getValue('id_supplier', $req ? $req[2] : 0);
                break;
            case 'adminmaintenance':
                $id_type = CE\UId::CONTENT;
                $id = CEContent::getMaintenanceId();

                $uids = CE\UId::getBuiltList($id, $id_type, $this->context->shop->id);
                $hideEditor = empty($uids) ? $uids : array_keys($uids[$this->context->shop->id]);
                break;
        }

        if (isset($id)) {
            self::$controller = $this->context->controller;
            self::$controller->addJQuery();
            self::$controller->js_files[] = $this->_path . 'views/js/admin.js?v=' . _CE_VERSION_;
            self::$controller->css_files[$this->_path . 'views/css/admin.css?v=' . _CE_VERSION_] = 'all';

            if (_CE_PS16_) {
                self::$controller->css_files[$this->_path . 'views/lib/material-icons/material-icons.css?v=' . _CE_VERSION_] = 'all';
            }
            $uid = new CE\UId($id, $id_type, 0, Shop::getContext() === Shop::CONTEXT_SHOP ? $this->context->shop->id : 0);

            isset($hideEditor) or $hideEditor = $uid->getBuiltLangIdList();

            Media::addJsDef(array(
                'ceAdmin' => array(
                    'uid' => "$uid",
                    'hideEditor' => $hideEditor,
                    'footerProduct' => "$footer_product",
                    'i18n' => array(
                        'save' => str_replace("'", "’", $this->l('Please save the form before editing with Creative Elements')),
                        'error' => str_replace("'", "’", $this->getErrorMsg()),
                    ),
                ),
            ));
            $this->context->smarty->assign('edit_width_ce', $this->context->link->getAdminLink('AdminCEEditor'));
        }
        return $this->context->smarty->fetch(_CE_TEMPLATES_ . 'hook/backoffice_header.tpl');
    }

    protected function getErrorMsg()
    {
        if (!Configuration::get('PS_SHOP_ENABLE', null, null, $this->context->shop->id)) {
            $ips = explode(',', Configuration::get('PS_MAINTENANCE_IP', null, null, $this->context->shop->id));

            if (!in_array(Tools::getRemoteAddr(), $ips)) {
                return $this->l('The shop is in maintenance mode, please whitelist your IP');
            }
        }

        $id_tab = Tab::getIdFromClassName('AdminCEEditor');
        $access = Profile::getProfileAccess($this->context->employee->id_profile, $id_tab);

        if ('1' !== $access['view']) {
            return CE\Helper::transError('You do not have permission to view this.');
        }

        $class = isset(self::$controller->className) ? self::$controller->className : '';

        if (in_array($class, $this->overrides)) {
            $loadObject = new ReflectionMethod(self::$controller, 'loadObject');
            $loadObject->setAccessible(true);

            if (empty($loadObject->invoke(self::$controller, true)->active) && !defined("$class::CE_OVERRIDE")) {
                return $this->l('You can not edit items which are not displayed, because an override file is missing. Please contact us on https://addons.prestashop.com');
            }
        }
        return '';
    }

    public function registerPreviewAssets()
    {
        self::registerStylesheet('ce-editor-preview', self::VIEWS . "css/editor-preview{$this->dir}{$this->min}.css", array('version' => _CE_VERSION_));
        self::registerStylesheet('ce-icons', self::VIEWS . 'lib/eicons/css/elementor-icons.min.css', array('version' => _CE_VERSION_));

        $uid = Tools::getValue('uid');

        if (CE\UId::CONTENT === CE\UId::parse($uid)->id_type) {
            $tab = 'AdminCEContent';
            $id_employee = (int) Tools::getValue('id_employee');

            Media::addJsDef(array(
                'cePreview' => $this->context->link->getModuleLink('creativeelements', 'preview', array(
                    'id_employee' => $id_employee,
                    'adtoken' => Tools::getAdminToken($tab . (int) Tab::getIdFromClassName($tab) . $id_employee),
                    'uid' => $uid,
                ), null, null, null, true),
            ));
            self::registerJavascript('ce-editor-preview', self::VIEWS . 'js/editor-preview.js', array('version' => _CE_VERSION_));
        }
    }

    public function registerStylesheets()
    {
        Configuration::get('elementor_load_fontawesome') && self::registerStylesheet(
            'ce-font-awesome',
            self::VIEWS . 'lib/font-awesome/css/font-awesome.min.css',
            array('version' => '4.7.0')
        );
        self::registerStylesheet('ce-animations', self::VIEWS . 'css/animations.min.css', array('version' => _CE_VERSION_));
        self::registerStylesheet('ce-frontend', self::VIEWS . "css/frontend{$this->dir}{$this->min}.css", array('version' => _CE_VERSION_));
    }

    public function registerJavascripts()
    {
        self::$controller->addJQuery();

        Configuration::get('elementor_load_waypoints') && self::registerJavascript(
            'ce-waypoints',
            self::VIEWS . 'lib/waypoints/waypoints.min.js',
            array('version' => '4.0.2')
        );
        self::registerJavascript(
            'ce-jquery-numerator',
            self::VIEWS . 'lib/jquery-numerator/jquery-numerator.min.js',
            array('version' => '0.2.1')
        );
        Configuration::get('elementor_load_slick') && self::registerJavascript(
            'ce-slick',
            self::VIEWS . "lib/slick/slick{$this->min}.js",
            array('version' => '1.6.3')
        );
        self::registerJavascript('ce-frontend', self::VIEWS . "js/frontend{$this->min}.js", array('version' => _CE_VERSION_));
    }

    public function hookHeader()
    {
        // Compatibility fix for PS 1.7.7.x upgrade
        return $this->hookDisplayHeader();
    }

    public function hookDisplayHeader()
    {
        self::$controller = $this->context->controller;

        $plugin = CE\Plugin::instance();

        if (self::isPreviewMode()) {
            if ('widget' === Tools::getValue('render')) {
                $this->tplOverride = '';

                $plugin->editor->setEditMode(true);
                $plugin->widgets_manager->ajaxRenderWidget();
            }
            header_register_callback(function () {
                header_remove('Content-Security-Policy');
                header_remove('X-Content-Type-Options');
                header_remove('X-Frame-Options');
                header_remove('X-Xss-Protection');
            });
            if (Tools::getValue('ctx') > Shop::CONTEXT_SHOP) {
                self::$controller->warning[] = CESmarty::get(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_warning_multistore');
            }
        }

        if (_CE_PS16_ && !self::$controller instanceof CmsController) {
            $this->hookOverrideLayoutTemplate(null);
        }

        Media::addJsDef(array(
            'ceFrontendConfig' => array(
                'isEditMode' => '',
                'stretchedSectionContainer' => Configuration::get('elementor_stretched_section_container'),
                'is_rtl' => !empty($this->context->language->is_rtl),
            ),
        ));
        self::isPreviewMode() && Tools::getIsset('maintenance') && $this->displayMaintenancePage();
    }

    public function hookOverrideLayoutTemplate($params)
    {
        if (false !== $this->tplOverride || !self::$controller) {
            return $this->tplOverride;
        }
        $this->tplOverride = '';

        $this->registerStylesheets();

        $controller = self::$controller;
        $tpl_vars = &$this->context->smarty->tpl_vars;
        $front = Tools::strtolower(preg_replace('/(ModuleFront)?Controller(Override)?$/i', '', get_class($controller)));
        $uid_preview = self::isPreviewMode();
        // PrestaBlog fix for non-default blog URL
        stripos($front, 'prestablog') === 0 && 'prestablog' . Configuration::get('prestablog_urlblog') === $front && $front = 'prestablogblog';

        switch ($front) {
            case 'creativeelementspreview':
                $model = $uid_preview->getModel();
                $key = $model::${'definition'}['table'];

                if (isset($tpl_vars[$key]->value['id'])) {
                    $id = $tpl_vars[$key]->value['id'];
                    $desc = array('description' => &$tpl_vars[$key]->value['content']);
                }
                break;
            case 'cms':
                $model = class_exists('CMS') ? 'CMS' : 'CMSCategory';
                $key = $model::${'definition'}['table'];

                if (!_CE_PS16_ && isset($tpl_vars[$key]->value['id'])) {
                    $id = $tpl_vars[$key]->value['id'];
                    $desc = array('description' => &$tpl_vars[$key]->value['content']);
                } elseif (isset($controller->cms->id)) {
                    $id = $controller->cms->id;
                    $desc = array('description' => &$controller->cms->content);
                } elseif (!_CE_PS16_ && isset($tpl_vars['cms_category']->value['id'])) {
                    $model = 'CMSCategory';
                    $id = $tpl_vars['cms_category']->value['id'];
                    $desc = &$tpl_vars['cms_category']->value;
                } elseif (isset($controller->cms_category->id)) {
                    $model = 'CMSCategory';
                    $id = $controller->cms_category->id;
                    $desc = array('description' => &$controller->cms_category->description);
                }
                break;
            case 'product':
            case 'category':
            case 'manufacturer':
            case 'supplier':
                $model = $front;

                if (!_CE_PS16_ && isset($tpl_vars[$model]->value['id'])) {
                    $id = $tpl_vars[$model]->value['id'];
                    $desc = &$tpl_vars[$model]->value;
                } elseif (method_exists($controller, "get$model") && Validate::isLoadedObject($obj = $controller->{"get$model"}())) {
                    $id = $obj->id;
                    $desc = array('description' => &$obj->description);
                }
                break;
        }

        if ($uid_preview && Tools::getValue('_')) {
            $this->registerPreviewAssets();

            if (isset($id) && $uid_preview->id === (int) $id && $uid_preview->id_type === CE\UId::getTypeId($model)) {
                CE\UId::$_ID = $uid_preview;

                $this->filterBodyClasses();
                $desc['description'] = $this->context->smarty->fetch(_CE_TEMPLATES_ . 'front/builder_wrapper.tpl');
            }
        } else {
            $this->registerJavascripts();
        }

        if (isset($id) && !CE\UId::$_ID) {
            CE\UId::$_ID = new CE\UId($id, CE\UId::getTypeId($model), $this->context->language->id, $this->context->shop->id);

            $this->filterBodyClasses();
            $desc['description'] = CE\apply_filters('the_content', $desc['description']);
        }
        $this->tplOverride = CE\apply_filters('template_include', $this->tplOverride);

        if ($this->tplOverride && basename($this->tplOverride, '.tpl') === 'layout-canvas') {
            $this->context->smarty->assign('ce_desc', $desc);

            CE\do_action('smarty/before_fetch', $this->context->smarty);
        }
        return $this->tplOverride;
    }

    public function hookDisplayOverrideTemplate($params)
    {
        if (_CE_PS16_ && !Tools::getIsset('ajax')) {
            $this->hookOverrideLayoutTemplate($params);
        }
    }

    protected function filterBodyClasses()
    {
        $tpl_vars = &$this->context->smarty->tpl_vars;

        if (_CE_PS16_) {
            isset($tpl_vars['body_classes']->value) or $this->smarty->assign('body_classes', array());

            $body_classes = &$tpl_vars['body_classes']->value;
            $body_classes[] = 'elementor-page elementor-page-' . CE\get_the_ID();
        } else {
            $body_classes = &$tpl_vars['page']->value['body_classes'];
            $body_classes['elementor-page'] = 1;
            $body_classes['elementor-page-' . CE\get_the_ID()] = 1;
        }
    }

    protected function displayMaintenancePage()
    {
        Configuration::set('PS_SHOP_ENABLE', false);
        Configuration::set('PS_MAINTENANCE_IP', '');

        $displayMaintenance = new ReflectionMethod($this->context->controller, 'displayMaintenancePage');
        $displayMaintenance->setAccessible(true);
        $displayMaintenance->invoke($this->context->controller);
    }

    public function hookDisplayMaintenance($params)
    {
        if (self::isPreviewMode()) {
            http_response_code(200);
            header_remove('Retry-After');
        } else {
            $this->hookDisplayHeader();
        }

        CE\add_filter('the_content', function () {
            $uid = CE\get_the_ID();
            Context::getContext()->smarty->assign('ce_content', new CEContent($uid->id, $uid->id_lang, $uid->id_shop));
        }, 0);

        if (!$maintenance = $this->renderContent('displayMaintenance', $params)) {
            return;
        }
        if (!_CE_PS16_) {
            self::$controller->registerJavascript('jquery', 'js/jquery/jquery-1.11.0.min.js');
        }
        $this->hookOverrideLayoutTemplate($params);

        CE\do_action('smarty/before_fetch', $this->context->smarty);

        if (_CE_PS16_) {
            $this->context->smarty->assign(array(
                'language_code' => $this->context->language->iso_code,
                'css_files' => Configuration::get('PS_CSS_THEME_CACHE')
                    ? Media::cccJs($this->context->controller->css_files)
                    : $this->context->controller->css_files
                ,
                'js_files' => Configuration::get('PS_JS_THEME_CACHE')
                    ? Media::cccJs($this->context->controller->js_files)
                    : array_unique($this->context->controller->js_files)
                ,
                'js_def' => Media::getJsDef(),
                'HOOK_MAINTENANCE' => $maintenance,
            ));
            $this->context->cookie->write();
            $html = $this->context->smarty->fetch(_CE_TEMPLATES_ . 'front/theme-1.6/maintenance.tpl');

            die(trim($html));
        }
        $tpl_dir = $this->context->smarty->getTemplateDir();
        array_unshift($tpl_dir, _CE_TEMPLATES_ . 'front/theme/');

        $this->context->smarty->setTemplateDir($tpl_dir);
        $this->context->smarty->assign(array(
            'iso_code' => $this->context->language->iso_code,
            'favicon' => Configuration::get('PS_FAVICON'),
            'favicon_update_time' => Configuration::get('PS_IMG_UPDATE_TIME'),
            'javascript' => $this->context->controller->getJavascript(),
            'js_custom_vars' => Media::getJsDef(),
        ));
        return $maintenance;
    }

    public function hookDisplayFooterProduct($params)
    {
        return $this->renderContent('displayFooterProduct', $params);
    }

    public function __call($method, $args)
    {
        if (stripos($method, 'hookActionObject') === 0 && stripos($method, 'DeleteAfter') !== false) {
            call_user_func_array(array($this, 'hookActionObjectDeleteAfter'), $args);
        } elseif (stripos($method, 'hook') === 0) {
            // render hook only after Header init or if it's Home
            if (false !== $this->tplOverride || !strcasecmp($method, 'hookDisplayHome')) {
                return $this->renderContent(Tools::substr($method, 4), $args);
            }
        } else {
            throw new Exception('Can not find method: ' . $method);
        }
    }

    public function renderContent($hook_name = null, array $config = array())
    {
        if (!$hook_name) {
            return;
        }
        $out = '';
        $uid = CE\UId::$_ID;
        $rows = CEContent::getIdsByHook(
            $hook_name,
            $id_lang = $this->context->language->id,
            $id_shop = $this->context->shop->id,
            Tools::getValue('id_product', 0),
            $uid_preview = self::isPreviewMode()
        );
        foreach ($rows as $row) {
            CE\UId::$_ID = new CE\UId($row['id'], CE\UId::CONTENT, $id_lang, $id_shop);

            if (CE\UId::$_ID == $uid_preview && Tools::getValue('_')) {
                $out .= $this->context->smarty->fetch(_CE_TEMPLATES_ . 'front/builder_wrapper.tpl');
            } else {
                $out .= CE\apply_filters('the_content', '');
            }
        }
        CE\UId::$_ID = $uid;

        return $out;
    }

    public function registerHook($hook_name, $shop_list = null, $position = null)
    {
        $res = parent::registerHook($hook_name, $shop_list);

        if ($res && is_numeric($position)) {
            $this->updatePosition(Hook::getIdByName($hook_name), 0, $position);
        }
        return $res;
    }

    public function hookCETemplate($params)
    {
        if (empty($params['id']) || !Validate::isLoadedObject($tpl = new CETemplate($params['id'])) || !$tpl->active) {
            return;
        }
        $uid = CE\UId::$_ID;
        CE\UId::$_ID = new CE\UId($params['id'], CE\UId::TEMPLATE);
        $out = CE\apply_filters('the_content', '');
        CE\UId::$_ID = $uid;

        return $out;
    }

    public function hookActionObjectDeleteAfter($params)
    {
        $model = get_class($params['object']);
        $id_type = CE\UId::getTypeId($model);
        $id_half = sprintf('%d%02d', $params['object']->id, $id_type);

        // Delete meta data
        Db::getInstance()->delete('ce_meta', "id LIKE '{$id_half}____'");

        // Delete CSS files
        $css_files = glob(_CE_PATH_ . "views/css/ce/$id_half????.css", GLOB_NOSORT);

        foreach ($css_files as $css_file) {
            Tools::deleteFile($css_file);
        }
    }

    public function hookActionObjectProductDeleteAfter($params)
    {
        $this->hookActionObjectDeleteAfter($params);

        // Delete displayFooterProduct content
        if ($id = CEContent::getFooterProductId($params['object']->id)) {
            $content = new CEContent($id);
            Validate::isLoadedObject($content) && $content->delete();
        }
    }

    public function hookActionProductAdd($params)
    {
        if (isset($params['request']) && $params['request']->attributes->get('action') === 'duplicate') {
            $id_product_duplicate = (int) $params['request']->attributes->get('id');
        } elseif (Tools::getIsset('duplicateproduct')) {
            $id_product_duplicate = (int) Tools::getValue('id_product');
        }

        if (isset($id_product_duplicate, $params['id_product']) &&
            $built_list = CE\UId::getBuiltList($id_product_duplicate, CE\UId::PRODUCT)
        ) {
            $db = CE\Plugin::instance()->db;
            $uid = new CE\UId($params['id_product'], CE\UId::PRODUCT, 0);

            foreach ($built_list as $id_shop => &$langs) {
                foreach ($langs as $id_lang => $uid_from) {
                    $uid->id_lang = $id_lang;
                    $uid->id_shop = $id_shop;

                    $db->copyElementorMeta($uid_from, $uid);
                }
            }
        }
    }

    protected function checkThemeChange()
    {
        if (!empty($this->context->shop->theme)) {
            $theme = $this->context->shop->theme->get('name');
            $ce_theme = Configuration::get('CE_THEME');

            if (empty($ce_theme)) {
                Configuration::updateValue('CE_THEME', $theme);
            } elseif ($ce_theme != $theme) {
                require_once _CE_PATH_ . 'classes/CEDatabase.php';

                // register missing hooks after changing theme
                foreach (CEDatabase::getHooks() as $hook) {
                    $this->registerHook($hook, null, 1);
                }
                Configuration::updateValue('CE_THEME', $theme);
            }
        }
    }

    public static function isPreviewMode()
    {
        static $res = null;

        if (null === $res && ($res = Tools::getIsset('uid')) && $uid = CE\UId::parse(Tools::getValue('uid'))) {
            $admin = $uid->getAdminController();
            $key = 'AdminBlogPosts' === $admin ? 'blogtoken' : 'adtoken';
            $res = self::hasAdminToken($admin, $key) ? $uid : false;
        }
        return $res;
    }

    public static function hasAdminToken($tab, $key = 'adtoken')
    {
        $adtoken = Tools::getAdminToken($tab . (int) Tab::getIdFromClassName($tab) . (int) Tools::getValue('id_employee'));

        return Tools::getValue($key) == $adtoken;
    }

    public static function registerStylesheet($hander, $path, $attrs = array())
    {
        if (_CE_PS16_) {
            $path = __PS_BASE_URI__ . $path . (isset($attrs['version']) ? "?v={$attrs['version']}" : '');
            self::$controller->css_files[$path] = isset($attrs['media']) ? $attrs['media'] : 'all';
        } else {
            if (isset($attrs['version']) && !\Configuration::get('PS_CSS_THEME_CACHE')) {
                $attrs['server'] = 'remote';
                $path = __PS_BASE_URI__ . "$path?v={$attrs['version']}";
            }
            if(Configuration::get('PS_CSS_THEME_CACHE')) $attrs['version'] = '';
            self::$controller->registerStylesheet($hander, $path, $attrs);
        }
    }

    public static function registerJavascript($hander, $path, $attrs = array())
    {
        if (_CE_PS16_) {
            $path = __PS_BASE_URI__ . $path . (isset($attrs['version']) ? "?v={$attrs['version']}" : '');
            self::$controller->js_files[] = $path;
        } else {
            if (isset($attrs['version']) && !\Configuration::get('PS_JS_THEME_CACHE')) {
                $attrs['server'] = 'remote';
                $path = __PS_BASE_URI__ . "$path?v={$attrs['version']}";
            }
            if(Configuration::get('PS_JS_THEME_CACHE')) $attrs['version'] = '';
            self::$controller->registerJavascript($hander, $path, $attrs);
        }
    }
}
