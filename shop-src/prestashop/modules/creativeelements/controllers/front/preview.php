<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

defined('_PS_VERSION_') or die;

class CreativeElementsPreviewModuleFrontController extends ModuleFrontController
{
    protected $uid;

    protected $title;

    public function init()
    {
        if (Tools::getIsset('redirect') && CreativeElements::hasAdminToken('AdminCEEditor')) {
            $cookie = CE\get_post_meta(0, 'cookie', true);
            CE\delete_post_meta(0, 'cookie');

            if (!empty($cookie)) {
                $lifetime = max(1, (int) Configuration::get('PS_COOKIE_LIFETIME_BO')) * 3600 + time();
                $admin = new Cookie('psAdmin', '', $lifetime);

                foreach ($cookie as $key => &$value) {
                    $admin->$key = $value;
                }
                unset($admin->remote_addr);

                $admin->write();
            }
            Tools::redirectAdmin(urldecode(Tools::getValue('redirect')));
        }

        $this->uid = CreativeElements::isPreviewMode();

        if (!$this->uid) {
            Tools::redirect('index.php?controller=404');
        }

        parent::init();
    }

    public function initContent()
    {
        $model = $this->uid->getModel();

        if ('CETemplate' != $model) {
            $this->warning[] = CESmarty::get(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_undefined_position');
        }
        $post = CE\get_post($this->uid);

        $this->title = $post->post_title;
        $this->context->smarty->assign($model::${'definition'}['table'], array(
            'id' => $post->_obj->id,
            'content' => '',
        ));

        parent::initContent();

        $this->title = $post->post_title;
        $this->context->smarty->addTemplateDir(_CE_TEMPLATES_);
        $this->context->smarty->assign(array(
            'HOOK_LEFT_COLUMN' => '',
            'HOOK_RIGHT_COLUMN' => '',
        ));

        if (_CE_PS16_) {
            $this->context->smarty->assign('path', $this->getBreadcrumbPath());
            $template = $this->getOverrideTemplate();
            $this->template = $template ? $template : 'front/preview-1.6.tpl';
        } else {
            $this->context->smarty->assign('breadcrumb', $this->getBreadcrumb());
            $this->template = 'front/preview.tpl';
        }
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = array(
            'links' => array(
                array(
                    'title' => 'Creative Elements',
                    'url' => 'javascript:;',
                ),
                array(
                    'title' => CE\__('Preview', 'elementor'),
                    'url' => 'javascript:;',
                ),
            ),
        );
        if (!empty($this->title)) {
            $breadcrumb['links'][] = array(
                'title' => $this->title,
                'url' => 'javascript:;',
            );
        }
        return $breadcrumb;
    }

    public function getBreadcrumbPath()
    {
        $breadcrumb = $this->getBreadcrumbLinks();

        return CESmarty::capture(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_preview_breadcrumb', array('links' => $breadcrumb['links']));
    }
}

function cefilter(&$str)
{
    echo $str;
}
