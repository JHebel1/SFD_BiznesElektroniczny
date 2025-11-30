<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

defined('_PS_VERSION_') or die;

require_once _PS_MODULE_DIR_ . 'creativeelements/classes/CETemplate.php';

class AdminCETemplatesController extends ModuleAdminController
{
    public $bootstrap = true;

    public function __construct()
    {
        $this->table = 'ce_template';
        $this->identifier = 'id_ce_template';
        $this->className = 'CETemplate';
        $this->fields_options = array();
        parent::__construct();

        if ($type = Tools::getValue('type')) {
            if ('all' == $type) {
                unset($this->context->cookie->cetemplatesce_templateFilter_type);
            } else {
                $this->context->cookie->cetemplatesce_templateFilter_type = $type;
            }
        }

        $this->fields_options['style_settings'] = array(
            'class' => 'ce-import-panel hide',
            'icon' => '',
            'title' => $this->l('Import Template'),
            'description' => $this->l('Choose a template JSON file, and add it to the list of templates available in your library.'),
            'fields' => array(
                'action' => array(
                    'type' => 'hidden',
                    'value' => 'import_template',
                    'no_multishop_checkbox' => true,
                ),
                'file' => array(
                    'type' => 'file',
                    'title' => $this->l('Template file'),
                    'name' => 'file',
                    'no_multishop_checkbox' => true,
                ),
            ),
            'submit' => array(
                'imgclass' => 'import',
                'title' => $this->l('Import Now'),
            ),
        );

        $this->_orderBy = 'title';
        $this->_use_found_rows = false;

        $this->fields_list = array(
            'id_ce_template' => array(
                'title' => $this->trans('ID', array(), 'Admin.Global'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ),
            'title' => array(
                'title' => $this->trans('Title', array(), 'Admin.Global'),
            ),
            'type' => array(
                'title' => $this->trans('Type', array(), 'Admin.Catalog.Feature'),
                'class' => 'fixed-width-lg',
                'type' => 'select',
                'list' => array(
                    'page' => $this->l('Page'),
                    'section' => $this->l('Section'),
                ),
                'filter_key' => 'type',
            ),
            'date_add' => array(
                'title' => $this->trans('Created on', array(), 'Modules.Facetedsearch.Admin'),
                'class' => 'fixed-width-lg',
                'type' => 'datetime',
            ),
            'date_upd' => array(
                'title' => $this->l('Modified on'),
                'class' => 'fixed-width-lg',
                'type' => 'datetime',
            ),
            'active' => array(
                'title' => $this->trans('Active', array(), 'Admin.Global'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
            ),
            'shortcode' => array(
                'title' => $this->l('Shortcode'),
                'class' => 'ce-shortcode',
                'type' => 'editable',
                'orderby' => false,
                'search' => false,
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Notifications.Info'),
                'icon' => 'fa fa-icon-trash',
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Info')
            ),
        );

        $this->action_link = CESmarty::get(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_action_link');
    }

    public function ajaxProcessMigrate()
    {
        if ($ids = Tools::getValue('ids')) {
            require_once _CE_PATH_ . 'classes/CEMigrate.php';

            $done = array();

            foreach ($ids as $id) {
                CEMigrate::moveTemplate($id) && $done[] = (int) $id;
            }
            $res = CEMigrate::removeIds('template', $done);

            die(json_encode($res));
        }
    }

    protected function processUpdateOptions()
    {
        // Process import template
        $res = CE\Plugin::instance()->templates_manager->importTemplate();

        if ($res instanceof PrestaShopException) {
            $this->errors[] = $res->getMessage();
        } elseif (empty($this->errors)) {
            $id = Tools::substr($res['template_id'], 0, -6);

            Tools::redirectAdmin(
                $this->context->link->getAdminLink('AdminCETemplates') . "&id_ce_template=$id&updatece_template&conf=18"
            );
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
                            if ($tab2['class_name'] == 'AdminCETemplates') {
                                $sub_tabs = &$tab2['sub_tabs'];
                                $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminCETemplates'));
                                $new = Tools::getIsset('addce_template');
                                $href = $link->getAdminLink('AdminCETemplates');
                                $type = $this->context->cookie->cetemplatesce_templateFilter_type;

                                $tab['name'] = $this->l('Show all');
                                $tab['current'] = !$new && !$type && empty($this->object);
                                $tab['href'] = "$href&type=all";
                                $sub_tabs[] = $tab;

                                $tab['name'] = $this->l('Page');
                                $tab['current'] = $new || 'page' == $type || !empty($this->object) && 'page' == $this->object->type;
                                $tab['href'] = "$href&type=page";
                                $sub_tabs[] = $tab;

                                $tab['name'] = $this->l('Section');
                                $tab['current'] = !$new && 'section' == $type || !empty($this->object) && 'section' == $this->object->type;
                                $tab['href'] = "$href&type=section";
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
        $this->toolbar_title[] = $this->l('Templates list');
    }

    public function initPageHeaderToolbar()
    {
        if ('add' != $this->display && 'edit' != $this->display) {
            $this->page_header_toolbar_btn['addce_template'] = array(
                'icon' => 'process-icon-new',
                'desc' => $this->trans('Add new', array(), 'Admin.Actions'),
                'href' => self::$currentIndex . '&addce_template&token=' . $this->token,
            );
            $this->page_header_toolbar_btn['importce_template'] = array(
                'icon' => 'process-icon-import',
                'desc' => $this->trans('Import', array(), 'Admin.Actions'),
                'href' => 'javascript:ceAdmin.onClickImport()',
            );
        }
        parent::initPageHeaderToolbar();
    }

    public function initContent()
    {
        $this->context->smarty->assign('current_tab_level', 3);

        return parent::initContent();
    }

    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        // Translate template types
        if (!empty($this->_list)) {
            $type = &$this->fields_list['type']['list'];

            foreach ($this->_list as &$row) {
                $row['id'] = $row['id_ce_template'];
                $row['type'] = $type[$row['type']];
                $row['shortcode'] = '{hook h="CETemplate" id="' . $row['id'] . '"}';
            }
        }
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('preview');
        $this->addRowAction('export');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function displayPreviewLink($token, $id, $name = null)
    {
        $link = $this->context->link->getModuleLink('creativeelements', 'preview', array(
            'id_employee' => $this->context->employee->id,
            'adtoken' => Tools::getAdminTokenLite('AdminCETemplates'),
            'uid' => "{$id}010000",
        ), null, null, null, true);

        return sprintf($this->action_link, Tools::safeOutput($link), '_blank', 'eye', $this->trans('Preview', array(), 'Admin.Actions'));
    }

    public function displayExportLink($token, $id, $name = null)
    {
        $link = $this->context->link->getAdminLink('AdminCEEditor') . '&' . http_build_query(array(
            'ajax' => 1,
            'action' => 'export_template',
            'source' => 'local',
            'template_id' => "{$id}010000",
        ));

        return sprintf($this->action_link, Tools::safeOutput($link), '_self', 'mail-forward', $this->trans('Export', array(), 'Admin.Actions'));
    }

    protected function getTemplateType()
    {
        $type = !empty($this->object->type) ? $this->object->type : 'page';

        return array(
            array(
                'value' => $type,
                'label' => $this->l(Tools::ucfirst($type)),
            ),
        );
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Template'),
                'icon' => 'fa fa-icon-folder-close',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->trans('Title', array(), 'Admin.Global'),
                    'name' => 'title',
                    'col' => 7,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans('Type', array(), 'Admin.Catalog.Feature'),
                    'name' => 'type',
                    'options' => array(
                        'query' => $this->getTemplateType(),
                        'id' => 'value',
                        'name' => 'label',
                    ),
                    'col' => 3,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content'),
                    'name' => 'content',
                    'col' => 7,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Active', array(), 'Admin.Global'),
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
                    'default_value' => 1,
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
                    'name' => 'submitAddce_templateAndStay',
                    'class' => 'btn btn-default pull-right',
                ),
            ),
        );

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
