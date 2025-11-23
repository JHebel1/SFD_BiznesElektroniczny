<?php
/**
 *
 *  @author    posthemes.com
 *  @copyright 2018 posthemes.com
 *
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Pospopup extends Module implements WidgetInterface
{

    protected $templateFile;

    public function __construct()
    {
        $this->name = 'pospopup';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Posthemes';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Pos newsletter popup');
        $this->description = $this->l('Show pop-up window with newsletter form');

        $this->config_name = 'pospopup';
        $this->defaults = array(
            'width' => 750,
            'height' => 450,
            'pages' => 1,
            'cookie' => 10,
            'bg_color' => '#ffffff',
            'newsletter' => 0,
            'bg_image' => '',
            'bg_position' => 1,
            'bg_cover' => 0,
            'pop_delay' => 2500,
            'content' => '',
        );

        $this->templateFile = 'module:pospopup/views/templates/hook/pospopup.tpl';
    }

    public function install()
    {
        if (parent::install() &&
            $this->installTab() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayBeforeBodyClosingTag') &&
            $this->registerHook('registerGDPRConsent')
        ) {
            $this->installSamples();
            //$this->generateCss(true);
            return true;
        } else {
            return false;
        }
    }

    public function uninstall()
    {
        foreach ($this->defaults as $default => $value) {
            Configuration::deleteByName($this->config_name . '_' . $default);
        }

        return parent::uninstall() && $this->deleteTab();
    }
    public function installTab()
    {
        if(!Tab::getIdFromClassName('PosModules')) return;
        
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminPosPopup";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "- Popup";
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('PosModules');
        $tab->module = $this->name;
        return $tab->add();
    }
    public function deleteTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminPosPopup');
        $tab = new Tab($id_tab);
        return $tab->delete();
    }

    public function installSamples()
    {
        foreach ($this->defaults as $default => $value) {
            if ($default == 'content') {
                $custom_content = array();
                foreach (Language::getLanguages(false) as $lang) {
                    $custom_content[(int)$lang['id_lang']] = '<div class="title-popup">
					<h2>Sign up For Send Newsletter?</h2>
					<p>Subscribe to our newsletters now and stay up-to-date with new collections, the latest lookbooks and exclusive offers.</p>
					</div>';
                }

                Configuration::updateValue($this->config_name . '_' . $default, $custom_content, true);
            } else {
                Configuration::updateValue($this->config_name . '_' . $default, $value);
            }
        }
    }

    public function getContent()
    {
        $output = '';
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return $this->getWarningMultishopHtml();
        }
        $output .='<p class="alert alert-warning">This module requires Newsletter subscription installed. Please sure that you installed Newsletter subscription module.</p>';
        $base_url = Tools::getHttpHost(true); 
        $base_url = Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') ? $base_url : str_replace('https', 'http', $base_url);

        Media::addJsDef(array(
            'posBaseUrl'  => Tools::safeOutput($base_url)
        ));

        if (Tools::isSubmit('submitPospopupModule')) {
            $this->_postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $output . $this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPospopupModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Popup width'),
                        'name' => 'width',
                        'suffix' => 'px',
                        'desc' => $this->l('Popup window width. Below this width module will be hidden.'),
                        'size' => 20,
                        'class' => ' fixed-width-xl'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Popup height'),
                        'name' => 'height',
                        'suffix' => 'px',
                        'desc' => $this->l('Popup window height. Below this height module will be hidden.'),
                        'size' => 20,
                        'class' => ' fixed-width-xl'
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Custom content of popup module'),
                        'name' => 'content',
                        'lang' => true,
                        'autoload_rte' => true,
                        'cols' => 60,
                        'rows' => 30,
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Popup background color'),
                        'name' => 'bg_color',
                        'size' => 30,
                    ),
                    array(
                        'type' => 'pos_image',
                        'label' => $this->l('Popup background image'),
                        'name' => 'bg_image',
                        'desc' => $this->l('Filename should be without special characters or whitespaces'),
                        'size' => 30,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Popup position'),
                        'name' => 'bg_position',
                        'options' => array(
                            'query' => array(array(
                                'id_option' => 3,
                                'name' => $this->l('left'),
                            ),
                                array(
                                    'id_option' => 2,
                                    'name' => $this->l('center'),
                                ),
                                array(
                                    'id_option' => 1,
                                    'name' => $this->l('right'),
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Background-size: cover'),
                        'name' => 'bg_cover',
                        'is_bool' => true,
                        'desc' => $this->l('If enable image background will cover entire popup'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Popup delay'),
                        'name' => 'pop_delay',
                        'suffix' => 'miliseconds',
                        'desc' => $this->l('Delay show of popup'),
                        'size' => 20,
                        'class' => ' fixed-width-xl'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Cookie time'),
                        'name' => 'cookie',
                        'suffix' => 'days',
                        'desc' => $this->l('Time in days of storing cookie. After that time windows will be showed again'),
                        'size' => 20,
                        'class' => ' fixed-width-xl'
                    ),
                    
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        $var = array();

        foreach ($this->defaults as $default => $value) {
            if ($default == 'content') {
                foreach (Language::getLanguages(false) as $lang) {
                    $var[$default][(int)$lang['id_lang']] = Configuration::get($this->config_name . '_' . $default, (int)$lang['id_lang']);
                }
            }elseif($default == 'bg_image'){
                foreach (Language::getLanguages(false) as $lang) {
                    $var[$default][(int)$lang['id_lang']] = Configuration::get($this->config_name . '_' . $default, (int)$lang['id_lang']);
                }
            } else {
                $var[$default] = Configuration::get($this->config_name . '_' . $default);
            }
        }
        return $var;
    }

    protected function _postProcess()
    {
        foreach ($this->defaults as $default => $value) {
            if ($default == 'content') {
                $custom_content = array();
                foreach ($_POST as $key => $value) {
                    if (preg_match('/content_/i', $key)) {
                        $id_lang = preg_split('/content_/i', $key);
                        $custom_content[(int)$id_lang[1]] = $value;
                    }
                }
                Configuration::updateValue($this->config_name . '_' . $default, $custom_content, true);
            }elseif($default == 'bg_image'){
                $bg_image_ = array();
                foreach ($_POST as $key => $value) {
                    if (preg_match('/bg_image_/i', $key)) {
                        $id_lang = preg_split('/bg_image_/i', $key);
                        $bg_image_[(int)$id_lang[1]] = $value;
                    }
                }
                Configuration::updateValue($this->config_name . '_' . $default, $bg_image_, true);
            } else {
                Configuration::updateValue($this->config_name . '_' . $default, (Tools::getValue($default)));
            }
        }
        $this->_clearCache($this->templateFile);
    }


    public function convertBgRepeat($value)
    {
        switch ($value) {
            case 3:
                $repeat_option = 'center left';
                break;
            case 2:
                $repeat_option = 'center';
                break;
            case 1:
                $repeat_option = 'center right';
        }
        return $repeat_option;
    }

    public function hookDisplayHeader()
    {
        if (Configuration::get($this->config_name . '_pages') && $this->context->controller->php_self != 'index') {
            return;
        }

       // $this->context->controller->registerStylesheet('modules-' . $this->name . '-style', 'modules/' . $this->name . '/views/css/pospopup.css', ['media' => 'all', 'priority' => 150]);
        $this->context->controller->registerJavascript('modules' . $this->name . '-script', 'modules/' . $this->name . '/views/js/pospopup.js', ['position' => 'bottom', 'priority' => 150]);

        if (Shop::getContext() == Shop::CONTEXT_SHOP) {
            $this->context->controller->registerStylesheet('modules-' . $this->name . '-style-custom', 'modules/' . $this->name . '/views/css/custom_s_' . (int)$this->context->shop->getContextShopID() . '.css', ['media' => 'all', 'priority' => 150]);
        }

        $delay = 0;
        $delay = (int)Configuration::get($this->config_name . '_pop_delay');

        Media::addJsDef(array(
            'pospopup' => [
                'time' => (int)Configuration::get($this->config_name . '_cookie'),
                'name' => 'posnewsletterpopup',
                'delay' => $delay
            ]
        ));
        $this->smarty->assign(
            array(
                'pnp_width' => Configuration::get($this->config_name . '_width'),
                'pnp_height' => Configuration::get($this->config_name . '_height'),
                'bg_image' => Configuration::get($this->config_name . '_bg_image',$this->context->language->id),
                'bg_color' => Configuration::get($this->config_name . '_bg_color'),
                'bg_position' => $this->convertBgRepeat(Configuration::get($this->config_name . '_bg_position')),
                'bg_cover' => Configuration::get($this->config_name . '_bg_cover'),
            ));
        return $this->display(__FILE__, 'popup-header.tpl');
    }


    public function renderWidget($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }

        if ($this->context->controller->php_self != 'index') {
            return;
        }

        if (!isset($_COOKIE['posnewsletterpopup'])) {
            if (!$this->isCached($this->templateFile, $this->getCacheId())) {
                $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
            }
            return $this->fetch($this->templateFile, $this->getCacheId());
        }
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {

        return array(
            'txt' => Configuration::get($this->config_name . '_content', $this->context->language->id),
            'id_module' =>  $this->id,
        );
    }

    protected function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">' .
            $this->l('You cannot manage module from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit') .
            '</p>';
        } else {
            return '';
        }
    }
}
