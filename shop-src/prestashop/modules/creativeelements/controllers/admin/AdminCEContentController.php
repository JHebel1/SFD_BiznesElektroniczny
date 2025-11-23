<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

defined('_PS_VERSION_') or die;

require_once _PS_MODULE_DIR_ . 'creativeelements/classes/CEContent.php';

class AdminCEContentController extends ModuleAdminController
{
    public $bootstrap = true;

    public function __construct()
    {
        $this->table = 'ce_content';
        $this->identifier = 'id_ce_content';
        $this->className = 'CEContent';
        $this->lang = true;
        parent::__construct();

        if ((Tools::getIsset('updatece_content') || Tools::getIsset('addce_content')) && Shop::getContextShopID() === null) {
            $this->displayWarning(
                $this->trans('You are in a multistore context: any modification will impact all your shops, or each shop of the active group.', array(), 'Admin.Catalog.Notification')
            );
        }

        $table_shop = _DB_PREFIX_ . $this->table . '_shop';
        $this->_select = 'sa.*';
        $this->_join = "LEFT JOIN $table_shop sa ON sa.id_ce_content = a.id_ce_content AND b.id_shop = sa.id_shop";
        $this->_where = "AND sa.id_shop = " . (int) $this->context->shop->id . " AND a.id_product = 0";
        $this->_orderBy = 'title';
        $this->_use_found_rows = false;

        $this->fields_list = array(
            'id_ce_content' => array(
                'title' => $this->trans('ID', array(), 'Admin.Global'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ),
            'title' => array(
                'title' => $this->trans('Title', array(), 'Admin.Global'),
            ),
            'hook' => array(
                'title' => $this->trans('Position', array(), 'Admin.Global'),
                'class' => 'fixed-width-xl',
            ),
            'date_add' => array(
                'title' => $this->trans('Created on', array(), 'Modules.Facetedsearch.Admin'),
                'filter_key' => 'sa!date_add',
                'class' => 'fixed-width-lg',
                'type' => 'datetime',
            ),
            'date_upd' => array(
                'title' => $this->l('Modified on'),
                'filter_key' => 'sa!date_upd',
                'class' => 'fixed-width-lg',
                'type' => 'datetime',
            ),
            'active' => array(
                'title' => $this->trans('Displayed', array(), 'Admin.Global'),
                'filter_key' => 'sa!active',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Notifications.Info'),
                'icon' => 'fa fa-icon-trash',
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Info')
            ),
        );
    }

    public function ajaxProcessHideEditor()
    {
        $id = (int) Tools::getValue('id');
        $id_type = (int) Tools::getValue('idType');

        $uids = CE\UId::getBuiltList($id, $id_type, $this->context->shop->id);
        $res = empty($uids) ? $uids : array_keys($uids[$this->context->shop->id]);

        die(json_encode($res));
    }

    public function ajaxProcessMigrate()
    {
        if ($ids = Tools::getValue('ids')) {
            require_once _CE_PATH_ . 'classes/CEMigrate.php';

            $done = array();

            foreach ($ids as $id) {
                CEMigrate::moveContent($id, $this->module) && $done[] = (int) $id;
            }
            $res = CEMigrate::removeIds('content', $done);

            die(json_encode($res));
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addJquery();
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/lib/select2/js/select2.min.js?ver=4.0.2';
        $this->css_files[_MODULE_DIR_ . 'creativeelements/views/lib/select2/css/select2.min.css?ver=4.0.2'] = 'all';
    }

    public function initHeader()
    {
        parent::initHeader();

        $id_lang = $this->context->language->id;
        $link = $this->context->link;
        $tabs = &$this->context->smarty->tpl_vars['tabs']->value;

        foreach ($tabs as &$tab0) {
            if ($tab0['class_name'] == 'IMPROVE') {
                foreach ($tab0['sub_tabs'] as &$tab1) {
                    if ($tab1['class_name'] == 'AdminParentCEContent') {
                        foreach ($tab1['sub_tabs'] as &$tab2) {
                            if ($tab2['class_name'] == 'AdminCEContent') {
                                $sub_tabs = &$tab2['sub_tabs'];

                                $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminCEContent'));
                                $tab['current'] = true;
                                $tab['href'] = $link->getAdminLink('AdminCEContent');
                                $sub_tabs[] = $tab;

                                $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminCmsContent'));
                                $tab['current'] = '';
                                $tab['href'] = $link->getAdminLink('AdminCmsContent');
                                $sub_tabs[] = $tab;

                                $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminProducts'));
                                $tab['current'] = '';
                                $tab['href'] = $link->getAdminLink('AdminProducts');
                                $sub_tabs[] = $tab;

                                $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminCategories'));
                                $tab['current'] = '';
                                $tab['href'] = $link->getAdminLink('AdminCategories');
                                $sub_tabs[] = $tab;

                                $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminManufacturers'));
                                $tab['current'] = '';
                                $tab['href'] = $link->getAdminLink('AdminManufacturers');
                                $sub_tabs[] = $tab;

                                $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminSuppliers'));
                                $tab['current'] = '';
                                $tab['href'] = $link->getAdminLink('AdminSuppliers');
                                $sub_tabs[] = $tab;
                                break;
                            }
                        }
                        break;
                    }
                }
                break;
            }
        }
    }

    public function initToolBarTitle()
    {
        $this->toolbar_title[] = $this->l('Place Content Anywhere');
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['addce_content'] = array(
                'href' => self::$currentIndex . '&addce_content&token=' . $this->token,
                'desc' => $this->trans('Add new', array(), 'Admin.Actions'),
                'icon' => 'process-icon-new',
            );
        }
        parent::initPageHeaderToolbar();
    }

    public function initContent()
    {
        $this->context->smarty->assign('current_tab_level', 3);

        return parent::initContent();
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $col = count(Language::getLanguages(false, false, true)) > 1 ? 9 : 7;

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Content'),
                'icon' => 'fa fa-icon-folder-close',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->trans('Title', array(), 'Admin.Global'),
                    'name' => 'title',
                    'lang' => true,
                    'col' => $col,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Position', array(), 'Admin.Global'),
                    'name' => 'hook',
                    'required' => true,
                    'col' => 3,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content'),
                    'name' => 'content',
                    'lang' => true,
                    'col' => $col,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Displayed', array(), 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                        ),
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->trans('Save', array(), 'Admin.Actions'),
            ),
            'buttons' => array(
                'save_and_stay' => array(
                    'type' => 'submit',
                    'title' => $this->trans('Save and stay', array(), 'Admin.Actions'),
                    'icon' => 'process-icon-save',
                    'name' => 'submitAddce_contentAndStay',
                    'class' => 'btn btn-default pull-right',
                ),
            ),
        );

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->trans('Shop association', array(), 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            );
        }

        return parent::renderForm();
    }

    protected function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return empty($this->translator) ? $this->l($id) : parent::trans($id, $parameters, $domain, $locale);
    }

    protected function l($string, $module = 'creativeelements', $addslashes = false, $htmlentities = true)
    {
        $str = Translate::getModuleTranslation($module, $string, '', null, $addslashes || !$htmlentities);

        return $htmlentities ? $str : call_user_func('strip' . 'slashes', $str);
    }
}
