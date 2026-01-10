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
 * A Media Chooser control. Based on the Responsive File Manager
 *
 * @param array  $default {
 *         @type string  $url   Default empty
 *         @type integer $id    Default empty
 * }
 *
 * @since 1.0.0
 */
class ControlMedia extends ControlBaseMultiple
{
    public function getType()
    {
        return 'media';
    }

    public function getDefaultValue()
    {
        return array(
            'url' => '',
            'id' => '',
        );
    }

    public function onImport($settings)
    {
        if (!empty($settings['url'])) {
            $settings = Plugin::$instance->templates_manager->getImportImagesInstance()->import($settings);

            if (!$settings) {
                $settings = array(
                    'id' => 0,
                    'url' => Utils::getPlaceholderImageSrc(),
                );
            }
        }

        return $settings;
    }

    public function onExport($settings)
    {
        if (!empty($settings['url'])) {
            $settings['url'] = Helper::getMediaLink($settings['url'], true);
        }

        return $settings;
    }

    public function enqueue()
    {
        wp_enqueue_style('jquery-fancybox', _PS_JS_DIR_ . 'jquery/plugins/fancybox/jquery.fancybox.css');

        wp_enqueue_script('jquery-fancybox', _PS_JS_DIR_ . 'jquery/plugins/fancybox/jquery.fancybox.js', array('jquery'), false, true);
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-units-choices">
                <input type="radio" checked id="elementor-control-media-url-{{ data._cid }}" value="{{ data.controlValue.url }}"
                ><label class="elementor-units-choices-label elementor-control-media-url"><?php _e('Modify URL') ?></label>
            </div>
            <div class="elementor-control-input-wrapper">
                <div class="elementor-control-media">
                    <div class="elementor-control-media-upload-button">
                        <i class="fa fa-plus-circle"></i>
                    </div>
                    <div class="elementor-control-media-image-area<# print( data.seo ? ' elementor-control-media-seo' : '' ) #>">
                        <div class="elementor-control-media-image" style="background-image: url('{{ elementor.imagesManager.getImageUrl( data.controlValue ) }}');"></div>
                        <div class="elementor-control-media-btn elementor-control-media-alt"><?php _e('Alt', 'elementor');?></div>
                        <div class="elementor-control-media-btn elementor-control-media-title"><?php _e('Title', 'elementor');?></div>
                        <div class="elementor-control-media-btn elementor-control-media-delete"><?php _e('Delete', 'elementor');?></div>
                    </div>
                </div>
            </div>
            <# if ( data.description ) { #>
                <div class="elementor-control-field-description">{{{ data.description }}}</div>
            <# } #>
            <input type="hidden" data-setting="{{ data.name }}" />
        </div>
        <?php
    }

    protected function getDefaultSettings()
    {
        return array(
            'label_block' => true,
        );
    }

    public static function getImageTitle($instance)
    {
        return !empty($instance['title']) ? \Tools::safeOutput($instance['title']) : '';
    }

    public static function getImageAlt($instance)
    {
        return !empty($instance['alt']) ? \Tools::safeOutput($instance['alt']) : '';
    }
}
