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

class RevisionsManager
{
    private static $authors = array();

    public function __construct()
    {
        self::registerActions();
    }

    public static function handleRevision()
    {
        // add_filter('wp_save_post_revision_post_has_changed', '__return_true');
        add_action('_wp_put_post_revision', array(__CLASS__, 'save_revision'));
    }

    public static function getRevisions($post_id = 0, $query_args = array(), $parse_result = true)
    {
        // $post = get_post($post_id);

        if (!$post_id) {
            return array();
        }

        $revisions = array();

        $query_args['meta_key'] = '_elementor_data';

        $posts = wp_get_post_revisions($post_id, $query_args);

        if (!$parse_result) {
            return $posts;
        }

        // $current_time = current_time('timestamp');
        $current_time = time();

        /** @var Post $revision */
        foreach ($posts as $revision) {
            // $date = date_i18n(_x('M j @ H:i', 'revision date format', 'elementor'), strtotime($revision->post_modified));
            $date = \Tools::displayDate($revision->post_modified, null, true);

            $human_time = human_time_diff(strtotime($revision->post_modified), $current_time);

            // if (false !== \Tools::strpos($revision->post_name, 'autosave')) {
            //     $type = 'autosave';
            // } else {
            //     $type = 'revision';
            // }
            $type = $revision->_obj->active ? 'revision' : 'autosave';

            if (!isset(self::$authors[$revision->post_author])) {
                $author = new \Employee($revision->post_author);
                $unknown = empty($author->email);

                self::$authors[$revision->post_author] = array(
                    'avatar' => sprintf(
                        '<img src="https://profile.prestashop.com/%s.jpg" width="22" height="22">',
                        $unknown ? 'unknown' : urlencode($author->email)
                    ),
                    'display_name' => $unknown ? __('Unknown') : "{$author->firstname} {$author->lastname}",
                );
            }

            $revisions[] = array(
                'id' => $revision->ID,
                'author' => self::$authors[$revision->post_author]['display_name'],
                'date' => sprintf(__('%1$s ago (%2$s)', 'elementor'), $human_time, $date),
                'type' => $type,
                'gravatar' => self::$authors[$revision->post_author]['avatar'],
            );
        }

        return $revisions;
    }

    public static function saveRevision($revision_id)
    {
        $parent_id = wp_is_post_revision($revision_id);

        if (!$parent_id) {
            return;
        }

        Plugin::$instance->db->copyElementorMeta($parent_id, $revision_id);
    }

    public static function restoreRevision($parent_id, $revision_id)
    {
        Plugin::$instance->db->copyElementorMeta($revision_id, $parent_id);

        $post_css = new PostCSSFile($parent_id);
        $post_css->update();
    }

    public static function onRevisionDataRequest()
    {
        if (empty(${'_POST'}['id'])) {
            wp_send_json_error('You must set the revision ID');
        }

        $revision = Plugin::$instance->db->getPlainEditor(${'_POST'}['id']);

        if (empty($revision)) {
            wp_send_json_error('Invalid Revision');
        }

        wp_send_json_success($revision);
    }

    public static function onDeleteRevisionRequest()
    {
        if (empty(${'_POST'}['id'])) {
            wp_send_json_error('You must set the id');
        }

        $revision = Plugin::$instance->db->getPlainEditor(${'_POST'}['id']);

        if (empty($revision)) {
            wp_send_json_error(__('Invalid Revision', 'elementor'));
        }

        $deleted = wp_delete_post_revision(${'_POST'}['id']);

        if ($deleted && !is_wp_error($deleted)) {
            wp_send_json_success();
        } else {
            wp_send_json_error(__('Cannot delete this Revision', 'elementor'));
        }
    }

    // public static function addRevisionSupportForAllPostTypes() { ... }

    private static function registerActions()
    {
        add_action('wp_restore_post_revision', array(__CLASS__, 'restore_revision'), 10, 2);
        // add_action('init', array(__CLASS__, 'add_revision_support_for_all_post_types'), 9999);

        if (\Tools::getIsset('ajax')) {
            add_action('wp_ajax_elementor_get_revision_data', array(__CLASS__, 'on_revision_data_request'));
            add_action('wp_ajax_elementor_delete_revision', array(__CLASS__, 'on_delete_revision_request'));
        }
    }
}
