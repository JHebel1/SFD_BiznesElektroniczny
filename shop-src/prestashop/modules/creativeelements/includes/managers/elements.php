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

class ElementsManager
{
    /**
     * @var ElementBase[]
     */
    private $_element_types;

    private $_categories;

    public function __construct()
    {
        $this->requireFiles();

        add_action('wp_ajax_elementor_save_builder', array($this, 'ajax_save_builder'));
    }

    /**
     * @param array $element_data
     * @param array $element_args
     * @param ElementBase $element_type
     *
     * @return ElementBase
     */
    public function createElementInstance(array $element_data, array $element_args = array(), ElementBase $element_type = null)
    {
        if (null === $element_type) {
            if ('widget' === $element_data['elType']) {
                $element_type = Plugin::$instance->widgets_manager->getWidgetTypes($element_data['widgetType']);
            } else {
                $element_type = $this->getElementTypes($element_data['elType']);
            }
        }

        if (!$element_type) {
            return null;
        }

        $args = array_merge($element_type->getDefaultArgs(), $element_args);

        $element_class = $element_type->getClassName();

        try {
            $element = new $element_class($element_data, $args);
        } catch (\Exception $e) {
            return null;
        }

        return $element;
    }

    public function getCategories()
    {
        if (null === $this->_categories) {
            $this->initCategories();
        }

        return $this->_categories;
    }

    public function addCategory($category_name, $category_properties, $offset = null)
    {
        if (null === $this->_categories) {
            $this->initCategories();
        }

        if (null === $offset) {
            $this->_categories[$category_name] = $category_properties;
        }

        $this->_categories = array_slice($this->_categories, 0, $offset, true) +
        array($category_name => $category_properties) +
        array_slice($this->_categories, $offset, null, true);
    }

    public function registerElementType(ElementBase $element)
    {
        $this->_element_types[$element->getName()] = $element;

        return true;
    }

    public function unregisterElementType($name)
    {
        if (!isset($this->_element_types[$name])) {
            return false;
        }

        unset($this->_element_types[$name]);

        return true;
    }

    public function getElementTypes($element_name = null)
    {
        if (is_null($this->_element_types)) {
            $this->_initElements();
        }

        if (null !== $element_name) {
            return isset($this->_element_types[$element_name]) ? $this->_element_types[$element_name] : null;
        }

        return $this->_element_types;
    }

    public function getElementTypesConfig()
    {
        $config = array();

        foreach ($this->getElementTypes() as $element) {
            $config[$element->getName()] = $element->getConfig();
        }

        return $config;
    }

    public function renderElementsContent()
    {
        foreach ($this->getElementTypes() as $element_type) {
            $element_type->printTemplate();
        }
    }

    public function ajaxSaveBuilder()
    {
        // if (empty($_POST['_nonce']) || !wp_verify_nonce($_POST['_nonce'], 'elementor-editing')) {
        //     wp_send_json_error('token_expired');
        // }

        if (empty(${'_POST'}['post_id'])) {
            wp_send_json_error('no_post_id');
        }

        if (!User::isCurrentUserCanEdit(${'_POST'}['post_id'])) {
            wp_send_json_error('no_access');
        }

        if (isset(${'_POST'}['status']) && in_array(${'_POST'}['status'], array(DB::STATUS_PUBLISH, DB::STATUS_DRAFT, DB::STATUS_AUTOSAVE))) {
            $status = ${'_POST'}['status'];
        } else {
            $status = DB::STATUS_DRAFT;
        }

        $posted = json_decode(${'_POST'}['data'], true);

        Plugin::$instance->db->saveEditor(${'_POST'}['post_id'], $posted, $status);

        $return_data = array();

        $latest_revision = RevisionsManager::getRevisions(${'_POST'}['post_id'], array(
            'posts_per_page' => 1,
        ));

        $all_revision_ids = RevisionsManager::getRevisions(${'_POST'}['post_id'], array(
            'posts_per_page' => (int) \Configuration::get('elementor_max_revisions'),
            'fields' => 'ids',
        ), false);

        if (!empty($latest_revision)) {
            $return_data['last_revision'] = $latest_revision[0];
            $return_data['revisions_ids'] = $all_revision_ids;
        }

        wp_send_json_success($return_data);
    }

    private function _initElements()
    {
        $this->_element_types = array();

        foreach (array('section', 'column') as $element_name) {
            $class_name = __NAMESPACE__ . '\Element' . $element_name;

            $this->registerElementType(new $class_name());
        }

        do_action('elementor/elements/elements_registered');
    }

    private function initCategories()
    {
        $this->_categories = array(
            'basic' => array(
                'title' => __('Basic', 'elementor'),
                'icon' => 'fa fa-font',
            ),
            'general-elements' => array(
                'title' => __('General Elements', 'elementor'),
                'icon' => 'fa fa-font',
            ),
            'premium' => array(
                'title' => __('Premium', 'elementor'),
                'icon' => 'fa fa-diamond',
            ),
        );

        do_action('elementor/elements/categories_registered', $this);
    }

    private function requireFiles()
    {
        require_once _CE_PATH_ . 'includes/base/element-base.php';

        require _CE_PATH_ . 'includes/elements/column.php';
        require _CE_PATH_ . 'includes/elements/section.php';
        require _CE_PATH_ . 'includes/elements/repeater.php';
    }
}
