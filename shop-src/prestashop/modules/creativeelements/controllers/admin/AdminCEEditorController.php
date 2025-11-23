<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

defined('_PS_VERSION_') or die;

class AdminCEEditorController extends ModuleAdminController
{
    public $name = 'AdminCEEditor';

    public $display_header = false;

    public $content_only = true;

    /** @var CE\UId */
    protected $uid;

    public function initShopContext()
    {
        require_once _PS_MODULE_DIR_ . 'creativeelements/classes/wrappers/UId.php';

        Tools::getIsset('uid') && $this->uid = CE\UId::parse(Tools::getValue('uid'));

        if (!empty($this->uid->id_shop) && $this->uid->id_type > CE\UId::TEMPLATE && Shop::getContext() > 1) {
            ${'_POST'}['setShopContext'] = 's-' . $this->uid->id_shop;
        }
        parent::initShopContext();
    }

    public function init()
    {
        if (isset($this->context->cookie->last_activity)) {
            if ($this->context->cookie->last_activity + 900 < time()) {
                $this->context->employee->logout();
            } else {
                $this->context->cookie->last_activity = time();
            }
        }

        if (!isset($this->context->employee) || !$this->context->employee->isLoggedBack()) {
            if (isset($this->context->employee)) {
                $this->context->employee->logout();
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminLogin') . '&redirect=' . $this->controller_name);
        }

        $this->initProcess();
    }

    public function initCursedPage()
    {
        if ($this->ajax) {
            CE\wp_send_json_error('token_expired');
        }
        parent::initCursedPage();
    }

    public function initProcess()
    {
        header('Cache-Control: no-store, no-cache');

        $this->ajax = Tools::getIsset('ajax');
        $this->action = Tools::getValue('action', '');
        $this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, $this->id);

        if (Shop::isFeatureActive() && $this->uid && !$this->ajax) {
            $domain = Tools::getShopProtocol() === 'http://' ? 'domain' : 'domain_ssl';

            if ($this->context->shop->$domain != $_SERVER['HTTP_HOST'] && $this->viewAccess()) {
                CE\update_post_meta(0, 'cookie', $this->context->cookie->getAll());

                $id_shop = $this->uid->id_shop ? $this->uid->id_shop : $this->uid->getDefaultShopId();

                Tools::redirectAdmin(
                    $this->context->link->getModuleLink('creativeelements', 'preview', array(
                        'id_employee' => $this->context->employee->id,
                        'adtoken' => Tools::getAdminTokenLite('AdminCEEditor'),
                        'redirect' => urlencode($_SERVER['REQUEST_URI']),
                    ), true, $this->uid->id_lang, $id_shop)
                );
            }
        }
        CE\Plugin::instance();
    }

    public function postProcess()
    {
        $process = 'process' . Tools::toCamelCase($this->action, true);

        if ($this->ajax) {
            method_exists($this, "ajax$process") && $this->{"ajax$process"}();

            CE\do_action('wp_ajax_elementor_' . $this->action);
        } elseif ($this->action && method_exists($this, $process)) {
            // Call process
            return $this->$process();
        }

        return false;
    }

    public function initContent()
    {
        $this->viewAccess() or die(CE\Helper::transError('You do not have permission to view this.'));

        empty($this->uid) && Tools::redirectAdmin($this->context->link->getAdminLink('AdminCEContent'));

        CE\add_action('elementor/editor/before_enqueue_scripts', array($this, 'beforeEnqueueScripts'));

        CE\Plugin::instance()->editor->init();
    }

    public function beforeEnqueueScripts()
    {
        // Enqueue CE assets
        CE\wp_enqueue_style('ce-editor', _CE_ASSETS_URL_ . 'css/editor-ce.css', array(), _CE_VERSION_);
        CE\wp_register_script('ce-editor', _CE_ASSETS_URL_ . 'js/editor-ce.js', array(), _CE_VERSION_, true);
        CE\wp_localize_script('ce-editor', 'ce', array(
            'PS16' => _CE_PS16_,
        ));
        CE\wp_localize_script('ce-editor', 'baseDir', __PS_BASE_URI__);
        CE\wp_enqueue_script('ce-editor');
    }

    public function processBackToPsEditor()
    {
        if (CE\current_user_can('edit', $this->uid)) {
            CE\Plugin::instance()->db->setEditMode($this->uid, 'editor');
        }
        Tools::redirectAdmin($_SERVER['HTTP_REFERER']);
    }

    public function processAddFooterProduct()
    {
        if (!$this->uid->id || $this->uid->id_type != CE\UId::PRODUCT) {
            Tools::redirectAdmin($_SERVER['HTTP_REFERER']);
        }

        $content = new CEContent();
        $content->hook = 'displayFooterProduct';
        $content->id_product = $this->uid->id;
        $content->active = true;
        $content->title = array();
        $content->content = array();

        foreach (Language::getLanguages(false) as $lang) {
            $content->title[$lang['id_lang']] = 'Product Footer #' . $this->uid->id;
        }
        $content->save();

        $uid = new CE\UId($content->id, CE\UId::CONTENT, $this->uid->id_lang, $this->uid->id_shop);

        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminCEEditor') . "&uid=$uid&footerProduct={$content->id_product}"
        );
    }

    public function processAddMaintenance()
    {
        if (!$uid = Tools::getValue('uid')) {
            Tools::redirectAdmin($_SERVER['HTTP_REFERER']);
        }

        $content = new CEContent();
        $content->hook = 'displayMaintenance';
        $content->active = true;
        $content->title = array();
        $content->content = array();

        foreach (Language::getLanguages(false) as $lang) {
            $id_lang = $lang['id_lang'];

            $content->title[$id_lang] = 'Maintenance';
            $content->content[$id_lang] = (string) Configuration::get('PS_MAINTENANCE_TEXT', $id_lang);
        }
        $content->save();

        $id_lang = Tools::substr($uid, -4, 2);
        $id_shop = Tools::substr($uid, -2);
        $uid = new CE\UId($content->id, CE\UId::CONTENT, $id_lang, $id_shop);

        Tools::redirectAdmin($this->context->link->getAdminLink('AdminCEEditor') . "&uid=$uid");
    }

    public function ajaxProcessSaveBuilder()
    {
        // Set edit mode to builder only at save
        CE\Plugin::instance()->db->setEditMode(Tools::getValue('post_id'));
    }

    public function ajaxProcessGetLanguageContent()
    {
        if ($data = CE\get_post_meta($this->uid, '_elementor_data', true)) {
            CE\Plugin::instance()->db->iterateData($data, function ($element) {
                $element['id'] = CE\Utils::generateRandomString();

                return $element;
            });
        }
        return is_array($data)
            ? CE\wp_send_json_success($data)
            : CE\wp_send_json_error()
        ;
    }

    public function ajaxProcessHeartbeat()
    {
        $response = array();
        $data = isset(${'_POST'}['data']) ? (array) ${'_POST'}['data'] : array();
        $screen_id = Tools::getValue('screen_id', 'front');

        empty($data) or $response = CE\apply_filters('heartbeat_received', $response, $data, $screen_id);

        $response = CE\apply_filters('heartbeat_send', $response, $screen_id);

        CE\do_action('heartbeat_tick', $response, $screen_id);

        $response['server_time'] = time();

        CE\wp_send_json($response);
    }
}
