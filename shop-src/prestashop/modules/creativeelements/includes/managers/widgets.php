<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2021 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or die;

class WidgetsManager
{
    /**
     * @var WidgetBase[]
     */
    private $_widget_types = null;

    private function _initWidgets()
    {
        $build_widgets_filename = array(
            'common',
            'heading',
            'image',
            'text-editor',
            'video',
            'button',
            'divider',
            'spacer',
            'google-maps',
            'icon',
            // General
            'image-box',
            'icon-box',
            'image-carousel',
            'icon-list',
            'counter',
            'progress',
            'testimonial',
            'tabs',
            'accordion',
            'toggle',
            'social-icons',
            'alert',
            'shortcode',
            'html',
            'menu-anchor',
            // Premium
            'product-grid',
            'product-carousel',
            'product-box',
            'layer-slider',
            'call-to-action',
            'flip-box',
            'contact-form',
            'email-subscription',
            'image-hotspot',
            'countdown',
            'ajax-search',
            'testimonial-carousel',
            'facebook-page',
            'facebook-button',
            'trustedshops-reviews',
        );

        // skip following widgets on PS 1.6.x
        if (!_CE_PS16_) {
            $build_widgets_filename[] = 'image-slider';
            $build_widgets_filename[] = 'category-tree';
        } elseif (file_exists(_PS_MODULE_DIR_ . 'blockcategories/blockcategories.tpl')) {
            $build_widgets_filename[] = 'category-tree';
        }
        $build_widgets_filename[] = 'module';

        $this->_widget_types = array();

        foreach ($build_widgets_filename as $widget_filename) {
            include _CE_PATH_ . 'includes/widgets/' . $widget_filename . '.php';

            $class_name = __NAMESPACE__ . '\Widget' . str_replace('-', '', $widget_filename);

            $this->registerWidgetType(new $class_name());
        }

        do_action('elementor/widgets/widgets_registered', $this);
    }

    private function _requireFiles()
    {
        require _CE_PATH_ . 'includes/base/widget-base.php';
        require _CE_PATH_ . 'includes/base/widget-premium-base.php';
    }

    public function registerWidgetType(WidgetBase $widget)
    {
        if (is_null($this->_widget_types)) {
            $this->_initWidgets();
        }

        $this->_widget_types[$widget->getName()] = $widget;

        return true;
    }

    public function unregisterWidgetType($name)
    {
        if (!isset($this->_widget_types[$name])) {
            return false;
        }

        unset($this->_widget_types[$name]);

        return true;
    }

    public function getWidgetTypes($widget_name = null)
    {
        if (is_null($this->_widget_types)) {
            $this->_initWidgets();
        }

        if (null !== $widget_name) {
            return isset($this->_widget_types[$widget_name]) ? $this->_widget_types[$widget_name] : null;
        }

        return $this->_widget_types;
    }

    public function getWidgetTypesConfig()
    {
        $config = array();

        foreach ($this->getWidgetTypes() as $widget_key => $widget) {
            if (!$widget->showInPanel()) {
                continue;
            }

            $config[$widget_key] = $widget->getConfig();
        }

        return $config;
    }

    public function ajaxRenderWidget()
    {
        // if (empty($_POST['_nonce']) || !wp_verify_nonce($_POST['_nonce'], 'elementor-editing')) {
        //     wp_send_json_error(new \PrestaShopException('token_expired'));
        // }

        // if (empty(${'_POST'}['post_id'])) {
        //     wp_send_json_error(new \PrestaShopException('no_post_id - No post_id'));
        // }

        // if (!User::isCurrentUserCanEdit(${'_POST'}['post_id'])) {
        //     wp_send_json_error(new \PrestaShopException('no_access'));
        // }

        // Override the global $post for the render
        // $GLOBALS['post'] = get_post((int) $_POST['post_id']);

        $data = json_decode(${'_POST'}['data'], true);

        // Start buffering
        ob_start();

        $widget = Plugin::$instance->elements_manager->createElementInstance($data);

        if (!$widget) {
            wp_send_json_error();
        }

        $widget->renderContent();

        $render_html = ob_get_clean();

        wp_send_json_success(array(
            'render' => $render_html,
        ));
    }

    // public function ajaxGetWpWidgetForm() { ... }

    public function renderWidgetsContent()
    {
        foreach ($this->getWidgetTypes() as $widget) {
            $widget->printTemplate();
        }
    }

    public function getWidgetsFrontendSettingsKeys()
    {
        $keys = array();

        foreach ($this->getWidgetTypes() as $widget_type_name => $widget_type) {
            $widget_type_keys = $widget_type->getFrontendSettingsKeys();

            if ($widget_type_keys) {
                $keys[$widget_type_name] = $widget_type_keys;
            }
        }

        return $keys;
    }

    public function enqueueWidgetsScripts()
    {
        foreach ($this->getWidgetTypes() as $widget) {
            $widget->enqueueScripts();
        }
    }

    public function __construct()
    {
        $this->_requireFiles();

        // add_action('wp_ajax_elementor_render_widget', array($this, 'ajax_render_widget'));
        // add_action('wp_ajax_elementor_editor_get_wp_widget_form', array($this, 'ajax_get_wp_widget_form'));
    }
}
