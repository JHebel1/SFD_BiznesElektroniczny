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

class Frontend
{
    private $google_fonts = array();
    private $registered_fonts = array();
    private $google_early_access_fonts = array();

    private $_is_frontend_mode = false;
    private $_has_elementor_in_page = false;
    private $_is_excerpt = false;

    /**
     * @var Stylesheet
     */
    private $stylesheet;

    public function init()
    {
        if (Plugin::$instance->editor->isEditMode()) {
            return;
        }

        // add_filter('body_class', array($this, 'body_class'));

        // if (Plugin::$instance->preview->isPreviewMode()) {
        //     return;
        // }

        $this->_is_frontend_mode = true;
        $this->_has_elementor_in_page = Plugin::$instance->db->isBuiltWithElementor(get_the_ID());

        add_filter('elementor/frontend/the_content', array('\CE\Helper', 'filterTheContent'));

        // if ($this->_has_elementor_in_page) {
        //     add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        // }

        // add_action('wp_head', array($this, 'print_google_fonts'));
        // add_action('wp_footer', array($this, 'wp_footer'));

        // Add Edit with the Elementor in Admin Bar
        // add_action('admin_bar_menu', array($this, 'add_menu_in_admin_bar'), 200);
    }

    protected function _printElements($elements_data)
    {
        foreach ($elements_data as $element_data) {
            $element = Plugin::$instance->elements_manager->createElementInstance($element_data);

            if (!$element) {
                continue;
            }

            $element->printElement();
        }
    }

    // public function bodyClass($classes = array())
    // {
    //     $classes[] = 'elementor-default';

    //     $id = get_the_ID();

    //     if (is_singular() && 'builder' === Plugin::$instance->db->getEditMode($id)) {
    //         $classes[] = 'elementor-page elementor-page-' . $id;
    //     }

    //     return $classes;
    // }

    public function registerScripts()
    {
        do_action('elementor/frontend/before_register_scripts');

        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        wp_register_script(
            'elementor-waypoints',
            _CE_ASSETS_URL_ . 'lib/waypoints/waypoints' . $suffix . '.js',
            array(
                'jquery',
            ),
            '4.0.2',
            true
        );

        wp_register_script(
            'imagesloaded',
            _CE_ASSETS_URL_ . 'lib/imagesloaded/imagesloaded' . $suffix . '.js',
            array(
                'jquery',
            ),
            '4.1.0',
            true
        );

        wp_register_script(
            'jquery-numerator',
            _CE_ASSETS_URL_ . 'lib/jquery-numerator/jquery-numerator' . $suffix . '.js',
            array(
                'jquery',
            ),
            '0.2.1',
            true
        );

        // wp_register_script(
        //     'jquery-swiper',
        //     _CE_ASSETS_URL_ . 'lib/swiper/swiper.jquery' . $suffix . '.js',
        //     array(
        //         'jquery',
        //     ),
        //     '3.4.1',
        //     true
        // );

        wp_register_script(
            'jquery-slick',
            _CE_ASSETS_URL_ . 'lib/slick/slick' . $suffix . '.js',
            array(
                'jquery',
            ),
            '1.6.3',
            true
        );

        wp_register_script(
            'elementor-dialog',
            _CE_ASSETS_URL_ . 'lib/dialog/dialog' . $suffix . '.js',
            array(
                'jquery-ui-position',
            ),
            '3.1.1',
            true
        );

        wp_register_script(
            'elementor-frontend',
            _CE_ASSETS_URL_ . 'js/frontend' . $suffix . '.js',
            array(
                'elementor-waypoints',
            ),
            _CE_VERSION_,
            true
        );

        do_action('elementor/frontend/after_register_scripts');
    }

    public function registerStyles()
    {
        do_action('elementor/frontend/before_register_styles');

        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        $direction_suffix = is_rtl() ? '-rtl' : '';

        wp_register_style(
            'elementor-icons',
            _CE_ASSETS_URL_ . 'lib/eicons/css/elementor-icons' . $suffix . '.css',
            array(),
            _CE_VERSION_
        );

        wp_register_style(
            'font-awesome',
            _CE_ASSETS_URL_ . 'lib/font-awesome/css/font-awesome' . $suffix . '.css',
            array(),
            '4.7.0'
        );

        // Elementor Animations
        wp_register_style(
            'elementor-animations',
            _CE_ASSETS_URL_ . 'css/animations.min.css',
            array(),
            _CE_VERSION_
        );

        wp_register_style(
            'elementor-frontend',
            _CE_ASSETS_URL_ . 'css/frontend' . $direction_suffix . $suffix . '.css',
            array(),
            _CE_VERSION_
        );

        do_action('elementor/frontend/after_register_styles');
    }

    public function enqueueScripts()
    {
        do_action('elementor/frontend/before_enqueue_scripts');

        wp_enqueue_script('elementor-frontend');

        $elementor_frontend_config = array(
            'isEditMode' => Plugin::$instance->editor->isEditMode(),
            'stretchedSectionContainer' => get_option('elementor_stretched_section_container', ''),
            'is_rtl' => is_rtl(),
            'urls' => array(
                'assets' => _CE_ASSETS_URL_,
            ),
        );

        $elements_manager = Plugin::$instance->elements_manager;

        $elements_frontend_keys = array(
            'section' => $elements_manager->getElementTypes('section')->getFrontendSettingsKeys(),
            'column' => $elements_manager->getElementTypes('column')->getFrontendSettingsKeys(),
        );

        $elements_frontend_keys += Plugin::$instance->widgets_manager->getWidgetsFrontendSettingsKeys();

        if (Plugin::$instance->editor->isEditMode()) {
            $elementor_frontend_config['elements'] = array(
                'data' => (object) array(),
                'keys' => $elements_frontend_keys,
            );
        }

        wp_localize_script('elementor-frontend', 'ceFrontendConfig', $elementor_frontend_config);

        do_action('elementor/frontend/after_enqueue_scripts');
    }

    public function enqueueStyles()
    {
        do_action('elementor/frontend/before_enqueue_styles');

        wp_enqueue_style('elementor-icons');
        wp_enqueue_style('font-awesome');
        wp_enqueue_style('elementor-animations');
        wp_enqueue_style('elementor-frontend');

        if (!Plugin::$instance->preview->isPreviewMode()) {
            $this->parseGlobalCssCode();

            $css_file = new PostCSSFile(get_the_ID());
            $css_file->enqueue();
        }

        do_action('elementor/frontend/after_enqueue_styles');
    }

    /**
     * Handle style that do not printed in header
     */
    public function wpFooter()
    {
        if (!$this->_has_elementor_in_page) {
            return;
        }

        $this->enqueueStyles();
        $this->enqueueScripts();

        $this->printGoogleFonts();
    }

    public function printGoogleFonts()
    {
        if (!apply_filters('elementor/frontend/print_google_fonts', true)) {
            return;
        }

        // Print used fonts
        if (!empty($this->google_fonts)) {
            foreach ($this->google_fonts as &$font) {
                $font = str_replace(' ', '+', $font) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
            }

            $fonts_url = sprintf('https://fonts.googleapis.com/css?family=%s', implode('|', $this->google_fonts));

            $subsets = array(
                'ru' => 'cyrillic',
                'bg' => 'cyrillic',
                'he' => 'hebrew',
                'el' => 'greek',
                'vi' => 'vietnamese',
                'uk' => 'cyrillic',
            );
            $locale = \Context::getContext()->language->iso_code;

            if (isset($subsets[$locale])) {
                $fonts_url .= '&subset=' . $subsets[$locale];
            }

            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$fonts_url\">";
            $this->google_fonts = array();
        }

        if (!empty($this->google_early_access_fonts)) {
            foreach ($this->google_early_access_fonts as $current_font) {
                printf(
                    '<%s rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/earlyaccess/%s.css">',
                    'link',
                    \Tools::strtolower(str_replace(' ', '', $current_font))
                );
            }
            $this->google_early_access_fonts = array();
        }
    }

    public function addEnqueueFont($font)
    {
        $font_type = Fonts::getFontType($font);
        $cache_id = $font_type . $font;

        if (in_array($cache_id, $this->registered_fonts)) {
            return;
        }

        switch ($font_type) {
            case Fonts::GOOGLE:
                if (!in_array($font, $this->google_fonts)) {
                    $this->google_fonts[] = $font;
                }

                break;

            case Fonts::EARLYACCESS:
                if (!in_array($font, $this->google_early_access_fonts)) {
                    $this->google_early_access_fonts[] = $font;
                }

                break;
        }

        $this->registered_fonts[] = $cache_id;
    }

    protected function parseGlobalCssCode()
    {
        $scheme_css_file = new GlobalCSSFile();

        $scheme_css_file->enqueue();
    }

    public function applyBuilderInContent($content)
    {
        if (!$this->_is_frontend_mode) {
            return $content;
        }

        $post_id = get_the_ID();
        $builder_content = $this->getBuilderContent($post_id);

        if (!empty($builder_content)) {
            $content = $builder_content;
        }

        // Add the filter again for other `the_content` calls
        // add_filter('the_content', array($this, 'apply_builder_in_content'));

        return $content;
    }

    public function getBuilderContent($post_id, $with_css = false)
    {
        // if ( post_password_required( $post_id ) ) {
        //     return '';
        // }

        $edit_mode = Plugin::$instance->db->getEditMode($post_id);
        if ('builder' !== $edit_mode) {
            return '';
        }

        $data = Plugin::$instance->db->getPlainEditor($post_id);
        $data = apply_filters('elementor/frontend/builder_content_data', $data, $post_id);

        if (empty($data)) {
            return '';
        }

        if (!$this->_is_excerpt) {
            $css_file = new PostCssFile($post_id);
            $css_file->enqueue();
        }

        // Handle JS and Customizer requests, with css inline
        if (is_customize_preview() || \Tools::getIsset('ajax')) {
            $with_css = true;
        }

        ob_start();
        ?>
        <?php if (!empty($css_file) && $with_css) : ?>
            <style><?php $css_file->getCss();?></style>
        <?php endif?>

        <div class="elementor elementor-<?php echo $post_id; ?>">
            <div class="elementor-inner">
                <div class="elementor-section-wrap">
                    <?php $this->_printElements($data);?>
                </div>
            </div>
        </div>
        <?php

        return apply_filters('elementor/frontend/the_content', ob_get_clean());
    }

    // public function addMenuInAdminBar( \WPAdminBar $wp_admin_bar ) { ... }

    public function getBuilderContentForDisplay($post_id)
    {
        if (!get_post($post_id)) {
            return '';
        }

        // Avoid recursion
        if (get_the_ID() === (int) $post_id) {
            $content = '';
            if (Plugin::$instance->editor->isEditMode()) {
                $content = '<div class="elementor-alert elementor-alert-danger">' . __('Invalid Data: The Template ID cannot be the same as the currently edited template. Please choose a different one.', 'elementor') . '</div>';
            }

            return $content;
        }

        // Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the css inline
        $is_edit_mode = Plugin::$instance->editor->isEditMode();
        Plugin::$instance->editor->setEditMode(false);

        // Change the global post to current library post, so widgets can use `get_the_ID` and other post data
        // if (isset($GLOBALS['post'])) {
        //     $global_post = $GLOBALS['post'];
        // }

        // $GLOBALS['post'] = get_post($post_id);

        $content = $this->getBuilderContent($post_id, $is_edit_mode);

        // Restore global post
        // if (isset($global_post)) {
        //     $GLOBALS['post'] = $global_post;
        // } else {
        //     unset($GLOBALS['post']);
        // }

        // Restore edit mode state
        Plugin::$instance->editor->setEditMode($is_edit_mode);

        return $content;
    }

    // public function startExcerptFlag( $excerpt ) { ... }

    // public function endExcerptFlag( $excerpt ) { ... }

    public function __construct()
    {
        // We don't need this class in admin side, but in AJAX requests
        if (is_admin() && !\Tools::getIsset('ajax')) {
            return;
        }
        $this->init();

        // add_action( 'template_redirect', [ $this, 'init' ] );
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 5);
        add_action('wp_enqueue_scripts', array($this, 'register_styles'), 5);
        add_filter('the_content', array($this, 'apply_builder_in_content'));

        // Hack to avoid enqueue post css wail it's a `the_excerpt` call
        // add_filter( 'get_the_excerpt', [ $this, 'start_excerpt_flag' ], 1 );
        // add_filter( 'get_the_excerpt', [ $this, 'end_excerpt_flag' ], 20 );
    }
}
