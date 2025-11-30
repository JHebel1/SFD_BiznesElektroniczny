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

class Editor
{
    private $_is_edit_mode;

    private $_editor_templates = array(
        'editor-templates/global.php',
        'editor-templates/panel.php',
        'editor-templates/panel-elements.php',
        'editor-templates/repeater.php',
        'editor-templates/templates.php',
    );

    public function init()
    {
        if (is_admin() && !$this->isEditMode()) {
            return;
        }
        /*
        add_filter('show_admin_bar', '__return_false');

        // Remove all WordPress actions
        remove_all_actions('wp_head');
        remove_all_actions('wp_print_styles');
        remove_all_actions('wp_print_head_scripts');
        remove_all_actions('wp_footer');
        */

        // Handle `wp_head`
        add_action('wp_head', 'wp_enqueue_scripts', 1);
        add_action('wp_head', 'wp_print_styles', 8);
        add_action('wp_head', 'wp_print_head_scripts', 9);
        // add_action('wp_head', 'wp_site_icon');
        add_action('wp_head', array($this, 'editor_head_trigger'), 30);

        // Handle `wp_footer`
        add_action('wp_footer', 'wp_print_footer_scripts', 20);
        // add_action('wp_footer', 'wp_auth_check_html', 30);
        add_action('wp_footer', array($this, 'wp_footer'));

        // Handle `wp_enqueue_scripts`
        // remove_all_actions('wp_enqueue_scripts');

        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 999999);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'), 999999);

        $post_id = get_the_ID();

        // Don't Change mode to Builder immediately
        // Plugin::$instance->db->setEditMode($post_id);

        // Post Lock
        if (!$this->getLockedUser($post_id)) {
            $this->lockPost($post_id);
			$get_type_id = substr($post_id, -6, 2);  
            if($get_type_id == '01'){
                update_post_meta(get_the_ID(), '_wp_page_template', 'elementor_canvas');
            }
        }
	
        // Setup default heartbeat options
        add_filter('heartbeat_settings', function ($settings) {
            $settings['interval'] = 15;
            return $settings;
        });

        // Tell to WP Cache plugins do not cache this request.
        // Utils::do_not_cache();

        // Print the panel
        $this->printPanelHtml();
        die;
    }

    public function isEditMode()
    {
        if (null !== $this->_is_edit_mode) {
            return $this->_is_edit_mode;
        }

        if (!User::isCurrentUserCanEdit()) {
            return false;
        }

        if (\Tools::getValue('controller') == 'AdminCEEditor' && !\Tools::getIsset('ajax')) {
            return true;
        }

        // In some Apache configurations, in the Home page, the $_GET['elementor'] is not set
        // if ( '/?elementor' === $_SERVER['REQUEST_URI'] ) {
        //     return true;
        // }

        // Ajax request as Editor mode
        $actions = array(
            'elementor_render_widget',

            // Templates
            'elementor_get_templates',
            'elementor_save_template',
            'elementor_get_template',
            'elementor_delete_template',
            'elementor_export_template',
            'elementor_import_template',
        );

        if (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $actions)) {
            return true;
        }

        return false;
    }

    /**
     * @param $post_id
     */
    public function lockPost($post_id)
    {
        wp_set_post_lock($post_id);
    }

    /**
     * @param $post_id
     *
     * @return bool|\WP_User
     */
    public function getLockedUser($post_id)
    {
        $locked_user = wp_check_post_lock($post_id);
        if (!$locked_user) {
            return false;
        }

        return get_user_by('id', $locked_user);
    }

    public function printPanelHtml()
    {
        include 'editor-templates/editor-wrapper.php';
    }

    public function enqueueScripts()
    {
        // global $wp_styles, $wp_scripts;

        $post_id = get_the_ID();
        $plugin = Plugin::$instance;

        $editor_data = $plugin->db->getBuilder($post_id, DB::STATUS_PUBLISH);

        // Reset global variable
        // $wp_styles = new \WP_Styles();
        // $wp_scripts = new \WP_Scripts();

        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        // Hack for waypoint with editor mode.
        wp_register_script(
            'elementor-waypoints',
            _CE_ASSETS_URL_ . 'lib/waypoints/waypoints-for-editor.js',
            array(
                'jquery',
            ),
            '4.0.2',
            true
        );

        // Enqueue frontend scripts too
        $plugin->frontend->registerScripts();
        $plugin->frontend->enqueueScripts();

        $plugin->widgets_manager->enqueueWidgetsScripts();

        wp_register_script(
            'backbone-marionette',
            _CE_ASSETS_URL_ . 'lib/backbone/backbone.marionette' . $suffix . '.js',
            array(
                'backbone',
            ),
            '2.4.5',
            true
        );

        wp_register_script(
            'backbone-radio',
            _CE_ASSETS_URL_ . 'lib/backbone/backbone.radio' . $suffix . '.js',
            array(
                'backbone',
            ),
            '1.0.4',
            true
        );

        wp_register_script(
            'perfect-scrollbar',
            _CE_ASSETS_URL_ . 'lib/perfect-scrollbar/perfect-scrollbar.jquery' . $suffix . '.js',
            array(
                'jquery',
            ),
            '0.6.12',
            true
        );

        wp_register_script(
            'nprogress',
            _CE_ASSETS_URL_ . 'lib/nprogress/nprogress' . $suffix . '.js',
            array(),
            '0.2.0',
            true
        );

        wp_register_script(
            'tipsy',
            _CE_ASSETS_URL_ . 'lib/tipsy/tipsy' . $suffix . '.js',
            array(
                'jquery',
            ),
            '1.0.0',
            true
        );

        wp_register_script(
            'heartbeat',
            _CE_ASSETS_URL_ . 'lib/heartbeat/heartbeat' . $suffix . '.js',
            array(
                'jquery',
            ),
            '5.5',
            true
        );
        wp_localize_script(
            'heartbeat',
            'heartbeatSettings',
            apply_filters('heartbeat_settings', array(
                'ajaxurl' => Helper::getAjaxLink(),
            ))
        );

        wp_register_script(
            'jquery-select2',
            _CE_ASSETS_URL_ . 'lib/select2/js/select2' . $suffix . '.js',
            array(
                'jquery',
            ),
            '4.0.2',
            true
        );

        wp_register_script(
            'jquery-simple-dtpicker',
            _CE_ASSETS_URL_ . 'lib/jquery-simple-dtpicker/jquery.simple-dtpicker' . $suffix . '.js',
            array(
                'jquery',
            ),
            '1.12.0',
            true
        );

        wp_register_script(
            'ace',
            'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/ace.js',
            array(),
            '1.2.5',
            true
        );

        wp_register_script(
            'elementor-editor',
            _CE_ASSETS_URL_ . 'js/editor' . $suffix . '.js',
            array(
                // 'wp-auth-check',
                'jquery-ui-sortable',
                'jquery-ui-resizable',
                'backbone-marionette',
                'backbone-radio',
                'perfect-scrollbar',
                // 'jquery-easing',
                'nprogress',
                'tipsy',
                'imagesloaded',
                'heartbeat',
                'jquery-select2',
                'jquery-simple-dtpicker',
                'ace',
            ),
            _CE_VERSION_,
            true
        );

        do_action('elementor/editor/before_enqueue_scripts');

        wp_enqueue_script('elementor-editor');

        // Tweak for WP Admin menu icons
        // wp_print_styles('editor-buttons');

        $locked_user = $this->getLockedUser($post_id);

        if ($locked_user) {
            $locked_user = $locked_user->display_name;
        }

        $context = \Context::getContext();
        $preview_link = get_preview_post_link($post_id);
        $revisions = \Configuration::get('elementor_max_revisions');
        $page_settings_instance = PageSettingsManager::getPage($post_id);

        wp_localize_script(
            'elementor-editor',
            'ElementorConfig',
            array(
                'ajaxurl' => Helper::getAjaxLink(),
                'home_url' => __PS_BASE_URI__,
                'nonce' => 1,
                'preview_link' => $preview_link . '&ctx=' . \Shop::getContext(),
                'elements_categories' => $plugin->elements_manager->getCategories(),
                'controls' => $plugin->controls_manager->getControlsData(),
                'elements' => $plugin->elements_manager->getElementTypesConfig(),
                'widgets' => $plugin->widgets_manager->getWidgetTypesConfig(),
                'schemes' => array(
                    'items' => $plugin->schemes_manager->getRegisteredSchemesData(),
                    'enabled_schemes' => SchemesManager::getEnabledSchemes(),
                ),
                'default_schemes' => $plugin->schemes_manager->getSchemesDefaults(),
                'revisions' => $revisions ? RevisionsManager::getRevisions($post_id) : array(),
                'revisions_enabled' => $revisions,
                'page_settings' => array(
                    'controls' => $page_settings_instance->getControls(),
                    'tabs' => $page_settings_instance->getTabsControls(),
                    'settings' => $page_settings_instance->getSettings(),
                ),
                'system_schemes' => $plugin->schemes_manager->getSystemSchemes(),
                // 'wp_editor' => $this->_getWpEditorConfig(),
                'post_id' => "$post_id",
                'post_permalink' => $preview_link,
                'edit_post_link' => get_edit_post_link($post_id),
                'settings_page_link' => $context->link->getAdminLink('AdminCESettings'),
                'elementor_site' => __('https://addons.prestashop.com/administrative-tools/44064-creative-elements-elementor-based-pagebuilder.html'),
                'help_the_content_url' => __('http://docs.webshopworks.com/creative-elements'),
                'assets_url' => _CE_ASSETS_URL_,
                'data' => $editor_data,
                'locked_user' => $locked_user,
                'is_rtl' => !empty($context->language->is_rtl),
                'locale' => $context->language->iso_code,
                'introduction' => User::getIntroduction(),
                'viewportBreakpoints' => Responsive::getBreakpoints(),
                'rich_editing_enabled' => true,
                'page_title_selector' => \Configuration::get('elementor_page_title_selector'),
                'tinymceHasCustomConfig' => false,
                'i18n' => array(
                    'elementor' => __('Creative Elements', 'elementor'),
                    'dialog_confirm_delete' => __('Are you sure you want to remove this {0}?', 'elementor'),
                    'dialog_user_taken_over' => __('{0} has taken over and is currently editing. Do you want to take over this page editing?', 'elementor'),
                    'delete' => __('Delete', 'elementor'),
                    'cancel' => __('Cancel', 'elementor'),
                    'delete_element' => __('Delete {0}', 'elementor'),
                    'take_over' => __('Take Over', 'elementor'),
                    'go_back' => __('Go Back', 'elementor'),
                    'saved' => __('Saved', 'elementor'),
                    'before_unload_alert' => __('Please note: All unsaved changes will be lost.', 'elementor'),
                    'edit_element' => __('Edit {0}', 'elementor'),
                    'global_colors' => __('Global Colors', 'elementor'),
                    'global_fonts' => __('Global Fonts', 'elementor'),
                    'elementor_settings' => __('Module Settings', 'elementor'),
                    'revision_history' => __('Revision History', 'elementor'),
                    'keyboard_shortcuts' => __('Keyboard Shortcuts', 'elementor'),
                    'save' => __('Save', 'elementor'),
                    'show_hide_panel' => __('Show / Hide Panel', 'elementor'),
                    'templates_library' => __('Templates Library', 'elementor'),
                    'responsive_mode' => __('Responsive Mode', 'elementor'),
                    'about_elementor' => __('About Creative Elements', 'elementor'),
                    'inner_section' => __('Columns', 'elementor'),
                    'insert_media' => __('Insert Media', 'elementor'),
                    'preview_el_not_found_header' => __('Sorry, the content area was not found in this page.', 'elementor'),
                    'preview_el_not_found_message' => __('This position is not supported by your theme, or your site is in Maintenance mode.', 'elementor'),
                    'learn_more' => __('Learn More', 'elementor'),
                    'an_error_occurred' => __('An error occurred', 'elementor'),
                    'templates_request_error' => __('The following error occurred when processing the request:', 'elementor'),
                    'save_your_template' => __('Save Your {0} to Library', 'elementor'),
                    'save_your_template_description' => __('Your designs will be available for export and reuse on any page or website', 'elementor'),
                    'page' => __('Page', 'elementor'),
                    'section' => __('Section', 'elementor'),
                    'delete_template' => __('Delete Template', 'elementor'),
                    'delete_template_confirm' => __('Are you sure you want to delete this template?', 'elementor'),
                    'color_picker' => __('Color Picker', 'elementor'),
                    'clear_page' => __('Delete All Content', 'elementor'),
                    'dialog_confirm_clear_page' => __('Attention! We are going to DELETE ALL CONTENT from this page. Are you sure you want to do that?', 'elementor'),
                    'asc' => __('Ascending order', 'elementor'),
                    'desc' => __('Descending order', 'elementor'),
                    'no_revisions_1' => __('Revision history lets you save your previous versions of your work, and restore them any time.', 'elementor'),
                    'no_revisions_2' => __('Start designing your page and you\'ll be able to see the entire revision history here.', 'elementor'),
                    'revisions_disabled_1' => __('It looks like the revision feature is turned off.', 'elementor'),
                    'revisions_disabled_2' => sprintf(__("You can enable it in the \x3Ca href=\"%s\" target=\"_blank\"\x3ESettings page\x3C/a\x3E", 'elementor'), Settings::getUrl()),
                    'revision' => __('Revision', 'elementor'),
                    'autosave' => __('Autosave', 'elementor'),
                    'preview' => __('Preview', 'elementor'),
                    'page_settings' => __('Page Settings', 'elementor'),
                    'back_to_editor' => __('Back to Editor', 'elementor'),
                    'multistore' => __('You are in a multistore context: any modification will impact all your shops, or each shop of the active group.', 'elementor'),
                ),
            )
        );

        $plugin->controls_manager->enqueueControlScripts();

        do_action('elementor/editor/after_enqueue_scripts');
    }

    public function enqueueStyles()
    {
        do_action('elementor/editor/before_enqueue_styles');

        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        $direction_suffix = \Context::getContext()->language->is_rtl ? '-rtl' : '';

        wp_register_style(
            'font-awesome',
            _CE_ASSETS_URL_ . 'lib/font-awesome/css/font-awesome' . $suffix . '.css',
            array(),
            '4.7.0'
        );

        wp_register_style(
            'select2',
            _CE_ASSETS_URL_ . 'lib/select2/css/select2' . $suffix . '.css',
            array(),
            '4.0.2'
        );

        wp_register_style(
            'elementor-icons',
            _CE_ASSETS_URL_ . 'lib/eicons/css/elementor-icons' . $suffix . '.css',
            array(),
            _CE_VERSION_
        );

        wp_register_style(
            'google-font-roboto',
            'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700',
            array(),
            _CE_VERSION_
        );

        wp_register_style(
            'jquery-simple-dtpicker',
            _CE_ASSETS_URL_ . 'lib/jquery-simple-dtpicker/jquery.simple-dtpicker' . $suffix . '.css',
            array(),
            '1.12.0'
        );

        wp_register_style(
            'elementor-editor',
            _CE_ASSETS_URL_ . 'css/editor' . $direction_suffix . $suffix . '.css',
            array(
                'font-awesome',
                'select2',
                'elementor-icons',
                'wp-auth-check',
                'google-font-roboto',
                'jquery-simple-dtpicker',
            ),
            _CE_VERSION_
        );

        wp_enqueue_style('elementor-editor');

        do_action('elementor/editor/after_enqueue_styles');
    }

    // protected function _getWpEditorConfig() { ... }

    public function editorHeadTrigger()
    {
        do_action('elementor/editor/wp_head');
    }

    public function wpFooter()
    {
        $plugin = Plugin::$instance;

        $plugin->controls_manager->renderControls();
        $plugin->widgets_manager->renderWidgetsContent();
        $plugin->elements_manager->renderElementsContent();

        $plugin->schemes_manager->printSchemesTemplates();

        foreach ($this->_editor_templates as $editor_template) {
            include $editor_template;
        }

        do_action('elementor/editor/footer');
    }

    /**
     * @param bool $edit_mode
     */
    public function setEditMode($edit_mode)
    {
        $this->_is_edit_mode = $edit_mode;
    }

    public function __construct()
    {
        // add_action( 'template_redirect', [ $this, 'init' ] );
    }
}
