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

/**
 * A PrestaShop WYSIWYG (TinyMCE) editor control.
 *
 * @param string $default     A default value
 *                            Default empty
 *
 * @since 1.0.0
 */
class ControlWysiwyg extends ControlBase
{
    public function getType()
    {
        return 'wysiwyg';
    }

    public function enqueue()
    {
        $suffix = _PS_MODE_DEV_ ? '' : '.min';
        $baseAdminDir = __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) . '/';

        wp_enqueue_style('material-icons', _CE_ASSETS_URL_ . 'lib/material-icons/material-icons.css', array(), '1.011');
        wp_enqueue_style('tinymce-theme', _CE_ASSETS_URL_ . "lib/tinymce/ps-theme{$suffix}.css", array(), _CE_VERSION_);

        wp_register_script('tinymce', _PS_JS_DIR_ . 'tiny_mce/tinymce.min.js', array('jquery'), false, true);
        wp_register_script('tinymce-inc', _CE_ASSETS_URL_ . 'lib/tinymce/tinymce.inc.js', array('tinymce'), _CE_VERSION_, true);

        wp_localize_script('tinymce', 'baseAdminDir', $baseAdminDir);
        wp_localize_script('tinymce', 'iso_user', \Context::getContext()->language->iso_code);

        if (_CE_PS16_) {
            wp_enqueue_style('tinymce-skin', _CE_ASSETS_URL_ . 'lib/tinymce/skins/prestashop/skin.min.css');

            wp_enqueue_script('tinymce-align', _CE_ASSETS_URL_ . 'lib/tinymce/plugins/align/plugin.min.js', array('tinymce'), '09.09.16', true);
        }
        wp_enqueue_script('tinymce-inc');
    }

    public function contentTemplate()
    {
        ?>
        <label>
            <span class="elementor-control-title">{{{ data.label }}}</span>
            <textarea data-setting="{{ data.name }}"></textarea>
        </label>
        <?php
    }
}
