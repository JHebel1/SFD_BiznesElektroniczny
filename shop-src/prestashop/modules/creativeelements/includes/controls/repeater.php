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
 * NOTE! THIS CONTROL IS UNDER DEVELOPMENT, USE AT YOUR OWN RISK.
 *
 * Repeater control allows you to build repeatable blocks of fields. You can create for example a set of fields that
 * will contain a checkbox and a textfield. The user will then be able to add “rows”, and each row will contain a
 * checkbox and a textfield.
 *
 * @since 1.0.0
 */
class ControlRepeater extends ControlBase
{
    public function getType()
    {
        return 'repeater';
    }

    protected function getDefaultSettings()
    {
        return array(
            'prevent_empty' => true,
            'is_repeater' => true,
        );
    }

    public function onImport($settings)
    {
        $import_images = Plugin::$instance->templates_manager->getImportImagesInstance();

        if ($settings) {
            foreach ($settings as &$item) {
                // import already handled
                if (!empty($item['_imported'])) {
                    unset($item['_imported']);
                    continue;
                }

                foreach ($item as &$subitem) {
                    // handle MEDIA type
                    if (isset($subitem['id'], $subitem['url'])) {
                        $subitem = $import_images->import($subitem);

                        if (!$subitem) {
                            $subitem = array(
                                'id' => 0,
                                'url' => Utils::getPlaceholderImageSrc(),
                            );
                        }
                    }
                }
            }
        }

        return $settings;
    }

    public function onExport($settings)
    {
        if ($settings) {
            foreach ($settings as &$item) {
                foreach ($item as &$subitem) {
                    // handle MEDIA type
                    if (isset($subitem['id'], $subitem['url'])) {
                        $subitem['url'] = Helper::getMediaLink($subitem['url'], true);
                    }
                }
            }
        }

        return $settings;
    }

    public function getValue($control, $widget)
    {
        $value = parent::getValue($control, $widget);

        if (!empty($value)) {
            foreach ($value as &$item) {
                foreach ($control['fields'] as $field) {
                    $control_obj = Plugin::instance()->controls_manager->getControl($field['type']);
                    if (!$control_obj) {
                        continue;
                    }

                    $item[$field['name']] = $control_obj->getValue($field, $item);
                }
            }
        }
        return $value;
    }

    public function contentTemplate()
    {
        ?>
        <label>
            <span class="elementor-control-title">{{{ data.label }}}</span>
        </label>
        <div class="elementor-repeater-fields"></div>
        <div class="elementor-button-wrapper">
            <button class="elementor-button elementor-button-default elementor-repeater-add"><span class="eicon-plus"></span><?php _e('Add Item', 'elementor');?></button>
        </div>
        <?php
    }
}
