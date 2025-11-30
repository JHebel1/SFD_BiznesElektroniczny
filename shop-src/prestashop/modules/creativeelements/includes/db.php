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

class DB
{
    /**
     * Current DB version of the editor.
     */
    const DB_VERSION = '0.4';

    const STATUS_PUBLISH = 'publish';
    const STATUS_DRAFT = 'draft';
    const STATUS_AUTOSAVE = 'autosave';

    /**
     * Save builder method.
     *
     * @since 1.0.0
     *
     * @param int    $post_id
     * @param array  $posted
     * @param string $status
     *
     * @return void
     */
    public function saveEditor($post_id, $posted, $status = self::STATUS_PUBLISH)
    {
        $post_id = UId::parse($post_id);

        // Change the global post to current library post, so widgets can use `get_the_ID` and other post data
        // if (isset($GLOBALS['post'])) {
        //     $global_post = $GLOBALS['post'];
        // }
        // $GLOBALS['post'] = get_post($post_id);

        $editor_data = $this->_getEditorData($posted);

        // We need the `wp_slash` in order to avoid the unslashing during the `update_post_meta`
        // $json_value = wp_slash(wp_json_encode($editor_data));

        if (self::STATUS_PUBLISH === $status) {
            $this->removeDraft($post_id);

            // Don't use `update_post_meta` that can't handle `revision` post type
            // $is_meta_updated = update_metadata('post', $post_id, '_elementor_data', $json_value);
            $is_meta_updated = update_post_meta($post_id, '_elementor_data', $editor_data);

            if ($is_meta_updated) {
                RevisionsManager::handleRevision();
            }

            $this->_savePlainText($post_id, $editor_data);
        } elseif (self::STATUS_AUTOSAVE === $status) {
            RevisionsManager::handleRevision();

            $old_autosave = wp_get_post_autosave($post_id, get_current_user_id());

            if ($old_autosave) {
                wp_delete_post_revision($old_autosave->ID);
            }

            $autosave_id = wp_create_post_autosave(array(
                'post_ID' => $post_id,
                'post_title' => __('Auto Save', 'elementor') . ' ' . date('Y-m-d H:i'),
                'post_modified' => date('Y-m-d H:i:s'),
            ));

            if ($autosave_id) {
                // update_metadata('post', $autosave_id, '_elementor_data', $json_value);
                update_post_meta($autosave_id, '_elementor_data', $editor_data);
            }
        }

        update_post_meta($post_id, '_elementor_version', self::DB_VERSION);

        // Restore global post
        // if (isset($global_post)) {
        //     $GLOBALS['post'] = $global_post;
        // } else {
        //     unset($GLOBALS['post']);
        // }

        foreach ($post_id->getListByShopContext() as $uid) {
            // Remove Post CSS
            delete_post_meta($uid, PostCSSFile::META_KEY);

            do_action('elementor/editor/after_save', $uid, $editor_data);
        }
    }

    /**
     * Get & Parse the builder from DB.
     *
     * @since 1.0.0
     *
     * @param int $post_id
     * @param string $status
     *
     * @return array
     */
    public function getBuilder($post_id, $status = self::STATUS_PUBLISH)
    {
        $data = $this->getPlainEditor($post_id, $status);

        return $this->_getEditorData($data, true);
    }

    public function getPlainEditor($post_id, $status = self::STATUS_PUBLISH)
    {
        $data = get_post_meta($post_id, '_elementor_data', true);
        if (self::STATUS_DRAFT === $status) {
            $draft_data = get_post_meta($post_id, '_elementor_draft_data', true);

            if (!empty($draft_data)) {
                $data = $draft_data;
            }
        }
        // Don't use empty($data)
        if (false === $data) {
            $data = $this->_getNewEditorFromPsEditor($post_id);
        }
        return $data;
    }

    protected function _getNewEditorFromPsEditor($post_id)
    {
        $post = get_post($post_id);
        if (empty($post) || empty($post->post_content)) {
            return array();
        }

        $text_editor_widget_type = Plugin::$instance->widgets_manager->getWidgetTypes('text-editor');

        // TODO: Better coding to start template for editor
        return array(
            array(
                'id' => Utils::generateRandomString(),
                'elType' => 'section',
                'settings' => array(
                    'gap' => 'no',
                ),
                'elements' => array(
                    array(
                        'id' => Utils::generateRandomString(),
                        'elType' => 'column',
                        'settings' => array(
                            '_column_size' => '100',
                        ),
                        'elements' => array(
                            array(
                                'id' => Utils::generateRandomString(),
                                'elType' => $text_editor_widget_type::getType(),
                                'widgetType' => $text_editor_widget_type->getName(),
                                'settings' => array(
                                    'editor' => $post->post_content,
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
    }

    /**
     * Remove draft data from DB.
     *
     * @since 1.0.0
     *
     * @param $post_id
     *
     * @return void
     */
    public function removeDraft($post_id)
    {
        delete_post_meta($post_id, '_elementor_draft_data');
    }

    /**
     * Get edit mode by Page ID
     *
     * @since 1.0.0
     *
     * @param $post_id
     *
     * @return mixed
     */
    public function getEditMode($post_id)
    {
        return get_post_meta($post_id, '_elementor_edit_mode', true);
    }

    /**
     * Setup the edit mode per Page ID
     *
     * @since 1.0.0
     *
     * @param int $post_id
     * @param string $mode
     *
     * @return void
     */
    public function setEditMode($post_id, $mode = 'builder')
    {
        if ('builder' === $mode) {
            update_post_meta($post_id, '_elementor_edit_mode', $mode);
        } else {
            delete_post_meta($post_id, '_elementor_edit_mode');
        }
    }

    private function _renderElementPlainContent($element_data)
    {
        if ('widget' === $element_data['elType']) {
            /** @var WidgetBase $widget */
            $widget = Plugin::$instance->elements_manager->createElementInstance($element_data);

            if ($widget) {
                $widget->renderPlainContent();
            }
        }

        if (!empty($element_data['elements'])) {
            foreach ($element_data['elements'] as $element) {
                $this->_renderElementPlainContent($element);
            }
        }
    }

    private function _savePlainText($post_id, $data = null)
    {
        ob_start();

        is_null($data) && $data = $this->getPlainEditor($post_id);

        if ($data) {
            foreach ($data as $element_data) {
                $this->_renderElementPlainContent($element_data);
            }
        }

        $plain_text = ob_get_clean();

        // Remove unnecessary tags.
        $plain_text = preg_replace('~</?(?:div|span)[^>]*>~i', '', $plain_text);
        $plain_text = preg_replace('~<(script)[^>]*>(.*?)</\1>~is', '', $plain_text);
        $plain_text = preg_replace('~<[iI] [^>]*></[iI][^>]*>~', '', $plain_text);
        $plain_text = preg_replace('/ class=".*?"/', '', $plain_text);

        // Remove empty lines.
        $plain_text = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $plain_text);

        wp_update_post(
            array(
                'ID' => $post_id,
                'post_content' => $plain_text,
            )
        );
    }

    /**
     * Sanitize posted data.
     *
     * @since 1.0.0
     *
     * @param array $data
     * @param bool $with_html_content
     *
     * @return array
     */
    private function _getEditorData($data, $with_html_content = false)
    {
        $editor_data = array();

        if (!empty($data)) {
            foreach ($data as $element_data) {
                $element = Plugin::$instance->elements_manager->createElementInstance($element_data);

                if (!$element) {
                    continue;
                }

                $editor_data[] = $element->getRawData($with_html_content);
            } // End Section
        }

        return $editor_data;
    }

    public function iterateData($data_container, $callback)
    {
        if (isset($data_container['elType'])) {
            if (!empty($data_container['elements'])) {
                $data_container['elements'] = $this->iterateData($data_container['elements'], $callback);
            }

            return $callback($data_container);
        }

        foreach ($data_container as $element_key => $element_value) {
            $element_data = $this->iterateData($data_container[$element_key], $callback);

            if (null === $element_data) {
                continue;
            }

            $data_container[$element_key] = $element_data;
        }

        return $data_container;
    }

    public function copyElementorMeta($from_post_id, $to_post_id)
    {
        if (!$this->isBuiltWithElementor($from_post_id)) {
            return;
        }

        $from_post_meta = get_post_meta($from_post_id);

        foreach ($from_post_meta as $meta_key => $values) {
            // Copy only meta with the `_elementor` prefix
            if (0 === \Tools::strpos($meta_key, '_elementor')) {
                // $value = $values[0];

                // The elementor JSON needs slashes before saving
                // if ('_elementor_data' === $meta_key) {
                //     $value = wp_slash($value);
                // }

                // Don't use `update_post_meta` that can't handle `revision` post type
                // update_metadata('post', $to_post_id, $meta_key, $value);
                update_post_meta($to_post_id, $meta_key, $values[0]);
            }
        }
    }

    public function isBuiltWithElementor($post_id)
    {
        $data = $this->getPlainEditor($post_id);
        $edit_mode = $this->getEditMode($post_id);

        return (!empty($data) && 'builder' === $edit_mode);
    }

    /**
     * @deprecated 1.4.0
     */
    public function hasElementorInPost($post_id)
    {
        return $this->isBuiltWithElementor($post_id);
    }
}
