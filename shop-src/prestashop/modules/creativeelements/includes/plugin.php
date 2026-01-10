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

require_once _CE_PATH_ . 'classes/wrappers/Helper.php';

/**
 * Main class plugin
 */
class Plugin
{
    /**
     * @var Plugin
     */
    public static $instance = null;

    /**
     * @var DB
     */
    public $db;

    /**
     * @var ControlsManager
     */
    public $controls_manager;

    /**
     * @var SchemesManager
     */
    public $schemes_manager;

    /**
     * @var ElementsManager
     */
    public $elements_manager;

    /**
     * @var WidgetsManager
     */
    public $widgets_manager;

    /**
     * @var RevisionsManager
     */
    public $revisions_manager;

    /**
     * @var PageSettingsManager
     */
    public $page_settings_manager;

    /**
     * @var Editor
     */
    public $editor;

    /**
     * @var Frontend
     */
    public $frontend;

    /**
     * @var Heartbeat
     */
    public $heartbeat;

    /**
     * @var TemplateLibraryManager
     */
    public $templates_manager;

    /**
     * @var SkinsManager
     */
    public $skins_manager;

    /**
     * @var PostsCssManager
     */
    // public $posts_css_manager;

    /**
     * @return string
     */
    public function getVersion()
    {
        return _CE_VERSION_;
    }

    /**
     * Throw error on object clone
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     *
     * @since 1.0.0
     * @return void
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'elementor'), '1.0.0');
    }

    /**
     * Disable unserializing of the class
     *
     * @since 1.0.0
     * @return void
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'elementor'), '1.0.0');
    }

    /**
     * @return Plugin
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::_includes();

            do_action('elementor/loaded');
            do_action('init');
        }

        return self::$instance;
    }

    /**
     * Register the CPTs with our Editor support.
     */
    public function init()
    {
        $this->initComponents();

        // do_action('elementor/init');
        \Hook::exec('actionCreativeElementsInit');
    }

    private static function _includes()
    {
        include _CE_PATH_ . 'includes/api.php';
        include _CE_PATH_ . 'includes/utils.php';
        include _CE_PATH_ . 'includes/user.php';
        include _CE_PATH_ . 'includes/fonts.php';

        include _CE_PATH_ . 'includes/db.php';
        include _CE_PATH_ . 'includes/base/controls-stack.php';
        include _CE_PATH_ . 'includes/managers/controls.php';
        include _CE_PATH_ . 'includes/managers/schemes.php';
        include _CE_PATH_ . 'includes/managers/elements.php';
        include _CE_PATH_ . 'includes/managers/widgets.php';
        include _CE_PATH_ . 'includes/managers/skins.php';
        include _CE_PATH_ . 'includes/settings/settings.php';
        include _CE_PATH_ . 'includes/editor.php';
        include _CE_PATH_ . 'includes/embed.php';
        include _CE_PATH_ . 'includes/frontend.php';
        include _CE_PATH_ . 'includes/heartbeat.php';
        include _CE_PATH_ . 'includes/responsive.php';
        include _CE_PATH_ . 'includes/stylesheet.php';

        include _CE_PATH_ . 'includes/template-library/manager.php';

        // include _CE_PATH_ . 'includes/managers/css-files.php';
        include _CE_PATH_ . 'includes/managers/revisions.php';
        include _CE_PATH_ . 'includes/page-settings/manager.php';
        include _CE_PATH_ . 'includes/css-file/css-file.php';
        include _CE_PATH_ . 'includes/css-file/post-css-file.php';
        include _CE_PATH_ . 'includes/css-file/global-css-file.php';
        include _CE_PATH_ . 'includes/conditions.php';
        include _CE_PATH_ . 'includes/shapes.php';
        // include _CE_PATH_ . 'includes/maintenance-mode.php';
    }

    private function initComponents()
    {
        $this->db = new DB();

        $this->controls_manager = new ControlsManager();
        $this->schemes_manager = new SchemesManager();
        $this->elements_manager = new ElementsManager();
        $this->widgets_manager = new WidgetsManager();
        $this->skins_manager = new SkinsManager();
        // $this->posts_css_manager = new PostsCssManager();
        $this->revisions_manager = new RevisionsManager();
        $this->page_settings_manager = new PageSettingsManager();

        $this->editor = new Editor();
        $this->frontend = new Frontend();

        $this->heartbeat = new Heartbeat();

        $this->templates_manager = new TemplateLibraryManager();
    }

    /**
     * Plugin constructor.
     */
    private function __construct()
    {
        add_action('init', array($this, 'init'), 0);

        // TODO: Declare this fields
        // $this->_includes();
    }
}
