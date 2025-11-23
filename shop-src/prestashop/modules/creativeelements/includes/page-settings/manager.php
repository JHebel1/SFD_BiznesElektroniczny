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

class PageSettingsManager
{
    const TEMPLATE_CANVAS = 'elementor_canvas';

    const META_KEY = '_elementor_page_settings';

    public static function savePageSettings()
    {
        if (empty(${'_POST'}['id'])) {
            wp_send_json_error('You must set the post ID');
        }

        $post = get_post(${'_POST'}['id']);

        if (empty($post)) {
            wp_send_json_error('Invalid Post');
        }

        $post->post_title = ${'_POST'}['post_title'];
        $post->post_status = ${'_POST'}['post_status'];

        $saved = wp_update_post($post);

        if (self::isCptCustomTemplatesSupported()) {
            update_post_meta($post->ID, '_wp_page_template', ${'_POST'}['template']);
        }

        $special_settings = array(
            'id',
            'post_title',
            'post_status',
            'template',
        );

        foreach ($special_settings as $special_setting) {
            unset(${'_POST'}[$special_setting]);
        }

        update_post_meta($post->ID, self::META_KEY, ${'_POST'});

        $css_file = new PostCSSFile($post->ID);
        $css_file->update();

        if ($saved) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    public static function templateInclude($template)
    {
        if (self::TEMPLATE_CANVAS === get_post_meta(get_the_ID(), '_wp_page_template', true)) {
            _CE_PS16_
                ? \Context::getContext()->smarty->assign('content_only', true)
                : $template = _CE_TEMPLATES_ . 'front/theme/layouts/layout-canvas.tpl'
            ;
        }
        return $template;
    }

    public static function addPageTemplates($post_templates)
    {
        $post_templates = array(
            self::TEMPLATE_CANVAS => __('Canvas', 'elementor'),
        ) + $post_templates;

        return $post_templates;
    }

    public static function isCptCustomTemplatesSupported()
    {
        // require_once ABSPATH . '/wp-admin/includes/theme.php';

        // return method_exists(wp_get_theme(), 'get_post_templates');
        return true;
    }

    public static function getPage($post_id)
    {
        return new PageSettingsPage(array('id' => $post_id));
    }

    public static function init()
    {
        $post_types = get_post_types_by_support('elementor');

        foreach ($post_types as $post_type) {
            add_filter("theme_{$post_type}_templates", array(__CLASS__, 'add_page_templates'), 10, 4);
        }
    }

    public function __construct()
    {
        require _CE_PATH_ . 'includes/page-settings/page.php';

        if (\Tools::getIsset('ajax')) {
            add_action('wp_ajax_elementor_save_page_settings', array(__CLASS__, 'save_page_settings'));
        }

        add_action('init', array(__CLASS__, 'init'));

        add_filter('template_include', array(__CLASS__, 'template_include'));
    }
}
