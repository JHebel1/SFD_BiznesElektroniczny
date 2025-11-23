<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

defined('_PS_VERSION_') or die;

class AdminCESettingsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->className = 'CESettings';
        $this->table = 'configuration';
        $this->fields_options = array();

        parent::__construct();

        $this->fields_options['genear_settings'] = array(
            'icon' => 'icon-cog',
            'title' => $this->l('General Settings'),
            'fields' => array(
                'elementor_frontend_edit' => array(
                    'title' => $this->l('Show Edit Icon on Frontend'),
                    'desc' => $this->l('Displays an edit icon on frontend while employee has active session. By clicking on this icon the live editor will open.'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'type' => 'bool',
                    'default' => '1',
                ),
                'elementor_max_revisions' => array(
                    'title' => $this->l('Limit Revisions'),
                    'desc' => $this->l('Sets the maximum number of revisions per content.'),
                    'validation' => 'isUnsignedInt',
                    'cast' => 'intval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array(
                        array(
                            'value' => 0,
                            'name' => $this->l('Disable Revision History'),
                        ),
                        array('value' => 1, 'name' => 1),
                        array('value' => 2, 'name' => 2),
                        array('value' => 3, 'name' => 3),
                        array('value' => 4, 'name' => 4),
                        array('value' => 5, 'name' => 5),
                        array('value' => 10, 'name' => 10),
                        array('value' => 15, 'name' => 15),
                        array('value' => 20, 'name' => 20),
                        array('value' => 25, 'name' => 25),
                        array('value' => 30, 'name' => 30),
                    ),
                ),
                'elementor_disable_color_schemes' => array(
                    'title' => $this->l('Disable Color Palettes'),
                    'desc' => $this->l('Color Palettes let you change the default colors that appear under the various widgets. If you prefer to inherit the colors from your theme, you can disable this feature.'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'type' => 'bool',
                    'default' => '0',
                ),
                'elementor_disable_typography_schemes' => array(
                    'title' => $this->l('Disable Default Fonts'),
                    'desc' => $this->l('Default Fonts let you change the fonts that appear on Elementor from one place. If you prefer to inherit the fonts from your theme, you can disable this feature here.'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'type' => 'bool',
                    'default' => '0',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save')
            ),
        );

        $this->fields_options['style_settings'] = array(
            'icon' => 'icon-adjust',
            'title' => $this->l('Style Settings'),
            'fields' => array(
                'elementor_default_generic_fonts' => array(
                    'title' => $this->l('Default Generic Fonts'),
                    'desc' => $this->l('The list of fonts used if the chosen font is not available.'),
                    'cast' => 'strval',
                    'type' => 'text',
                    'class' => 'fixed-width-xxl',
                ),
                'elementor_container_width' => array(
                    'title' => $this->l('Content Width'),
                    'desc' => $this->l('Sets the default width of the content area (Default: 1140)'),
                    'suffix' => 'px',
                    'validation' => 'isUnsignedInt',
                    'cast' => 'intval',
                    'type' => 'text',
                    'class' => 'fixed-width-sm',
                ),
                'elementor_space_between_widgets' => array(
                    'title' => $this->l('Widgets Space'),
                    'desc' => $this->l('Sets the default space between widgets (Default: 20)'),
                    'suffix' => 'px',
                    'validation' => 'isUnsignedInt',
                    'cast' => 'intval',
                    'type' => 'text',
                    'class' => 'fixed-width-sm',
                ),
                'elementor_stretched_section_container' => array(
                    'title' => $this->l('Stretched Section Fit To'),
                    'desc' => $this->l('Enter parent element selector to which stretched sections will fit to (e.g. #primary / .wrapper / main etc). Leave blank to fit to page width.'),
                    'cast' => 'strval',
                    'type' => 'text',
                    'class' => 'fixed-width-xxl',
                ),
                'elementor_page_title_selector' => array(
                    'title' => $this->l('Page Title Selector'),
                    'desc' => sprintf(
                        $this->l('You can hide the title at Page Settings. This works for themes that have ’’%s’’ selector. If your theme’s selector is different, please enter it above.'),
                        _CE_PS16_ ? 'h1.page-heading' : 'header.page-header h1'
                    ),
                    'hint' => $this->trans('Required field', array(), 'Shop.Forms.Errors'),
                    'cast' => 'strval',
                    'type' => 'text',
                    'class' => 'fixed-width-xxl',
                ),
                'elementor_page_wrapper_selector' => array(
                    'title' => $this->l('Full Width Selector'),
                    'desc' => sprintf(
                        $this->l('You can force full width layout at Page Settings. This works for themes that have ’’%s’’ selector. If your theme’s selector is different, please enter it above.'),
                        _CE_PS16_ ? '#columns, #columns .container' : '#wrapper, #wrapper .container, #content'
                    ),
                    'cast' => 'strval',
                    'type' => 'text',
                    'class' => 'fixed-width-xxl',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save')
            ),
        );

        $this->fields_options['advanced_settings'] = array(
            'class' => 'ce-adv-settings',
            'icon' => 'icon-cogs',
            'title' => $this->l('Advanced Settings'),
            'info' => CESmarty::sprintf(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_alert', 'warning', $this->l(
                'Do not change these options without experience, incorrect settings might break your site.'
            )),
            'fields' => array(
                'elementor_css_print_method' => array(
                    'title' => $this->l('CSS Print Method'),
                    'desc' => $this->l('Use external CSS files for all generated stylesheets. Choose this setting for better performance (recommended).'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array(
                        array(
                            'value' => 'external',
                            'name' => $this->l('External File'),
                        ),
                        array(
                            'value' => 'internal',
                            'name' => $this->l('Internal Embedding'),
                        ),
                    ),
                ),
                'elementor_load_fontawesome' => array(
                    'title' => $this->l('Load FontAwesome Library'),
                    'desc' => $this->l('FontAwesome gives you scalable vector icons that can instantly be customized - size, color, drop shadow, and anything that can be done with the power of CSS.'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'type' => 'bool',
                    'default' => '1',
                ),
                'elementor_load_waypoints' => array(
                    'title' => $this->l('Load Waypoints Library'),
                    'desc' => $this->l('Waypoints library is the easiest way to trigger a function when you scroll to an element.'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'type' => 'bool',
                    'default' => '1',
                ),
                'elementor_load_slick' => array(
                    'title' => $this->l('Load Slick Library'),
                    'desc' => $this->l('Slick is a jQuery plugin for creating fully customizable, responsive and mobile friendly carousels/sliders that work with any html elements.'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'type' => 'bool',
                    'default' => '1',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save')
            ),
        );
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['regenerate-css'] = array(
            'icon' => 'process-icon-refresh',
            'desc' => $this->l('Regenerate CSS'),
            'js' => '//' . Tools::safeOutput(
                $this->l('Styles set in Creative Elements are saved in CSS files. Recreate those files, according to the most recent settings.')
            ),
        );
        $this->page_header_toolbar_btn['replace-url'] = array(
            'icon' => 'process-icon-refresh',
            'desc' => $this->l('Replace URL'),
            'js' => "$('#modal_replace_url').modal()",
        );

        parent::initPageHeaderToolbar();
    }

    public function initModal()
    {
        $this->modals[] = [
            'modal_id' => 'modal_replace_url',
            'modal_class' => 'modal-md',
            'modal_title' => 'Update Site Address (URL)',
            'modal_content' => '
                <form name="replace_url" class="form-horizontal">
                    <div class="modal-body">
                        <input type="hidden" name="ajax" value="1">
                        <input type="hidden" name="action" value="replaceUrl">
                        <div class="alert alert-warning">It is strongly recommended that you backup your database before using Replace URL.</div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <input type="text" placeholder="http://old-url.com" class="form-control" name="from" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" placeholder="http://new-url.com" class="form-control" name="to" required>
                            </div>
                        </div>
                        <div class="help-block">
                            Enter your old and new URL for your PrestaShop installation, to update all Creative Elements data (Relevant for domain transfers or move to "HTTPS").
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="icon-refresh"></i> Replace URL</button>
                    </div>
                </form>
            ',
        ];
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/js/settings.js?v=' . _CE_VERSION_;
    }

    protected function updateOptionElementorPageTitleSelector($val)
    {
        $val = trim($val);

        if (!empty($val) && Validate::isCleanHtml($val)) {
            Configuration::updateValue('elementor_page_title_selector', $val);
        } else {
            $this->errors[] = $this->trans('Required field', array(), 'Shop.Forms.Errors') . ': ' . $this->l('Page Title Selector');
        }
    }

    public function ajaxProcessRegenerateCss()
    {
        CE\Helper::clearCSS();

        die('{"success":true}');
    }

    public function ajaxProcessReplaceUrl()
    {
        $from = trim(Tools::getValue('from'));
        $to = trim(Tools::getValue('to'));

        $is_valid_urls = filter_var($from, FILTER_VALIDATE_URL) && filter_var($to, FILTER_VALIDATE_URL);

        if (!$is_valid_urls) {
            CE\wp_send_json_error(CE\__("The `from` and `to` URLs must be a valid URL", 'elementor'));
        }

        if ($from === $to) {
            CE\wp_send_json_error(CE\__("The `from` and `to` URLs must be different", 'elementor'));
        }

        $db = Db::getInstance();
        $table = _DB_PREFIX_ . 'ce_meta';

        $result = $db->execute("
            UPDATE $table
            SET `value` = REPLACE(`value`, '" . str_replace('/', '\\\/', $from) . "', '" . str_replace('/', '\\\/', $to) . "')
            WHERE `name` = '_elementor_data' AND `value` LIKE '[%'
        ");

        if (false === $result) {
            CE\wp_send_json_error(CE\__('An error occurred', 'elementor'));
        } else {
            // CE\Plugin::$instance->posts_css_manager->clear_cache();
            CE\wp_send_json_success(sprintf(CE\__('%d Rows Affected', 'elementor'), $db->affected_rows()));
        }
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
