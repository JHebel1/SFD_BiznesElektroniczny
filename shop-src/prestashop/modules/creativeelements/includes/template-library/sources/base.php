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

abstract class TemplateLibrarySourceBase
{
    abstract public function getId();

    abstract public function getTitle();

    // abstract public function registerData();

    abstract public function getItems($args = array());

    abstract public function getItem($item_id);

    abstract public function getContent($item_id);

    abstract public function deleteTemplate($item_id);

    abstract public function saveItem($template_data);

    abstract public function updateItem($new_data);

    abstract public function exportTemplate($item_id);

    // public function __construct() { $this->registerData(); }

    protected function replaceElementsIds($data)
    {
        return Plugin::$instance->db->iterateData($data, function ($element) {
            $element['id'] = Utils::generateRandomString();

            return $element;
        });
    }

    /**
     * @param array $data a set of elements
     * @param string $method (onExport|onImport)
     *
     * @return mixed
     */
    protected function processExportImportData($data, $method)
    {
        return Plugin::$instance->db->iterateData($data, function ($element_data) use ($method) {
            $element = Plugin::$instance->elements_manager->createElementInstance($element_data);

            // If the widget/element isn't exist, like a plugin that creates a widget but deactivated
            if (!$element) {
                return null;
            }

            if (method_exists($element, $method)) {
                // TODO: Use the internal element data without parameters
                $element_data = $element->{$method}($element->getData());
            }

            foreach ($element->getControls() as $control) {
                $control_class = Plugin::$instance->controls_manager->getControl($control['type']);

                // If the control isn't exist, like a plugin that creates the control but deactivated
                if (!$control_class) {
                    return $element_data;
                }

                if (method_exists($control_class, $method)) {
                    $element_data['settings'][$control['name']] = $control_class->{$method}($element->getSettings($control['name']));
                }
            }

            return $element_data;
        });
    }
}
