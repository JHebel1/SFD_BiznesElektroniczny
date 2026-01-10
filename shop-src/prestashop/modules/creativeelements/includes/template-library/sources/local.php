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

class TemplateLibrarySourceLocal extends TemplateLibrarySourceBase
{
    const CPT = 'CETemplate';

    // const TAXONOMY_TYPE_SLUG = 'elementor_library_type';

    // const TYPE_META_KEY = '_elementor_template_type';

    private static $_template_types = array('page', 'section');

    public static function getTemplateType($template_id)
    {
        // return get_post_meta($template_id, self::TYPE_META_KEY, true);
        return get_post($template_id)->_obj->type;
    }

    // public static function isBaseTemplatesScreen() { ... }

    public static function addTemplateType($type)
    {
        self::$_template_types[] = $type;
    }

    public function getId()
    {
        return 'local';
    }

    public function getTitle()
    {
        return __('Local', 'elementor');
    }

    // public function registerData() { ... }

    // public function registerAdminMenu() { ... }

    public function getItems($args = array())
    {
        $templates = array();
        $table = _DB_PREFIX_ . 'ce_template';
        $rows = \Db::getInstance()->executeS(
            "SELECT id_ce_template, id_employee, title, type, date_add FROM $table WHERE active = 1 ORDER BY title ASC"
        );
        if ($rows) {
            foreach ($rows as &$row) {
                $post = new \stdClass();
                $post->ID = new UId($row['id_ce_template'], UId::TEMPLATE);
                $post->post_author = $row['id_employee'];
                $post->post_date = $row['date_add'];
                $post->post_title = $row['title'];
                $post->template_type = $row['type'];

                $templates[] = $this->getItem($post);
            }
        }

        // if (!empty($args)) {
        //     $templates = wp_list_filter($templates, $args);
        // }

        return $templates;
    }

    public function saveItem($template_data)
    {
        if (!in_array($template_data['type'], self::$_template_types)) {
            return new \PrestaShopException("save_error - Invalid template type `{$template_data['type']}`");
        }

        $template_id = wp_insert_post(array(
            'post_title' => !empty($template_data['title']) ? $template_data['title'] : __('(no title)', 'elementor'),
            'post_status' => 'publish',
            'post_type' => self::CPT,
            'template_type' => $template_data['type'],
        ));

        if (is_wp_error($template_id)) {
            return $template_id;
        }

        Plugin::$instance->db->setEditMode($template_id);

        Plugin::$instance->db->saveEditor($template_id, $template_data['data']);

        // $this->saveItemType($template_id, $template_data['type']);

        do_action('elementor/template-library/after_save_template', $template_id, $template_data);

        do_action('elementor/template-library/after_update_template', $template_id, $template_data);

        return $template_id;
    }

    public function updateItem($new_data)
    {
        Plugin::$instance->db->saveEditor($new_data['id'], $new_data['data']);

        return true;
    }

    /**
     * @param int $item_id
     *
     * @return array
     */
    public function getItem($item)
    {
        $post = is_object($item) ? $item : get_post($item);

        $user = get_user_by('id', $post->post_author);

        $data = array(
            'template_id' => "{$post->ID}",
            'source' => $this->getId(),
            'type' => $post->template_type,
            'title' => $post->post_title,
            // 'thumbnail' => get_the_post_thumbnail_url($post),
            'date' => \Tools::displayDate($post->post_date),
            'author' => $user ? $user->display_name : __('Unknown', 'elementor'),
            'categories' => array(),
            'keywords' => array(),
            'export_link' => $this->_getExportLink($post->ID),
            'url' => get_preview_post_link($post->ID),
        );

        return apply_filters('elementor/template-library/get_template', $data);
    }

    public function getContent($item_id, $context = 'display')
    {
        $db = Plugin::$instance->db;

        // TODO: Validate the data (in JS too!)
        if ('display' === $context) {
            $data = $db->getBuilder($item_id);
        } else {
            $data = $db->getPlainEditor($item_id);
        }

        $data = $this->replaceElementsIds($data);

        return $data;
    }

    public function deleteTemplate($item_id)
    {
        wp_delete_post($item_id, true);
    }

    public function exportTemplate($item_id)
    {
        $template_data = $this->getContent($item_id, 'raw');

        $template_data = $this->processExportImportData($template_data, 'onExport');

        if (empty($template_data)) {
            return new \PrestaShopException('404 - The template does not exist');
        }
        $post = get_post($item_id);

        // TODO: More fields to export?
        $export_data = array(
            'version' => _CE_VERSION_,
            'title' => $post->post_title,
            'type' => $post->template_type,
            'data' => $template_data,
        );

        $filename = 'CreativeElements_' . $post->_obj->id . '_' . date('Y-m-d') . '.json';
        $template_contents = json_encode($export_data);
        $filesize = call_user_func('strlen', $template_contents);

        // Headers to prompt "Save As"
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $filesize);

        // Clear buffering just in case
        @ob_end_clean();

        flush();

        // Output file contents
        echo $template_contents;

        die;
    }

    public function importTemplate()
    {
        $import_file = $_FILES['file']['tmp_name'];

        if (empty($import_file)) {
            return new \PrestaShopException('file_error - Please upload a file to import');
        }

        $data = 'data';
        $is_invalid_file = true;
        $content = json_decode(\Tools::file_get_contents($import_file), true);

        if (!empty($content[$data])) {
            if (is_string($content[$data])) {
                // iqit compatibility
                $content['type'] = 'page';
                $content[$data] = json_decode($content[$data], true);
            }
            if (is_array($content[$data])) {
                $is_invalid_file = false;
            }
        } elseif (!empty($content['content']) && is_array($content['content'])) {
            $is_invalid_file = false;
            $data = 'content';
        }

        if ($is_invalid_file) {
            return new \PrestaShopException('file_error - Invalid File');
        }

        $content_data = $this->processExportImportData($content[$data], 'onImport');

        $item_id = $this->saveItem(array(
            'data' => $content_data,
            'title' => $content['title'],
            'type' => $content['type'],
        ));

        if ($item_id instanceof \PrestaShopException) {
            return $item_id;
        }

        return $this->getItem($item_id);
    }

    // public function postRowActions( $actions, \WP_Post $post ) { ... }

    // public function adminImportTemplateForm() { ... }

    // public function blockTemplateFrontend() { ... }

    // public function isTemplateSupportsExport( $template_id ) { ... }

    private function _getExportLink($item_id)
    {
        return \Context::getContext()->link->getAdminLink('AdminCEEditor') . '&' . http_build_query(array(
            'ajax' => 1,
            'action' => 'export_template',
            'source' => $this->getId(),
            'template_id' => "$item_id",
        ));
    }

    // public function onSavePost( $post_id, $post ) { ... }

    // private function saveItemType( $post_id, $type ) { ... }

    // public function adminQueryFilterTypes( $query ) { ... }

    // private function _addActions() { ... }

    // public function __construct() { ... }
}
