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

class User
{
    const ADMIN_NOTICES_KEY = 'elementor_admin_notices';
    const INTRODUCTION_KEY = 'elementor_introduction';

    public static function init()
    {
        add_action('wp_ajax_elementor_introduction_viewed', array(__CLASS__, 'set_introduction_viewed'));
        add_action('wp_ajax_elementor_set_admin_notice_viewed', array(__CLASS__, 'ajax_set_admin_notice_viewed'));
    }

    public static function isCurrentUserCanEdit($post_id = 0)
    {
        if (empty($post_id)) {
            $post_id = get_the_ID();
        }

        // if (!Utils::is_post_type_support($post_id)) {
        //     return false;
        // }

        // if ('trash' === get_post_status($post_id)) {
        //     return false;
        // }

        $post_type_object = get_post_type_object(get_post_type($post_id));
        if (empty($post_type_object)) {
            return false;
        }

        if (!isset($post_type_object->cap->edit_post)) {
            return false;
        }

        $edit_cap = $post_type_object->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return false;
        }

        // if ( get_option( 'page_for_posts' ) === $post_id ) {
        //     return false;
        // }

        $user = wp_get_current_user();
        $exclude_roles = get_option('elementor_exclude_user_roles', array());

        $compare_roles = array_intersect($user->roles, $exclude_roles);
        if (!empty($compare_roles)) {
            return false;
        }

        return true;
    }

    private static function _getUserNotices()
    {
        return get_user_meta(get_current_user_id(), self::ADMIN_NOTICES_KEY, true);
    }

    public static function isUserNoticeViewed($notice_id)
    {
        $notices = self::_getUserNotices();
        if (empty($notices) || empty($notices[$notice_id])) {
            return false;
        }

        return true;
    }

    public static function ajaxSetAdminNoticeViewed()
    {
        if (empty(${'_POST'}['notice_id'])) {
            die;
        }

        $notices = self::_getUserNotices();
        if (empty($notices)) {
            $notices = array();
        }

        $notices[${'_POST'}['notice_id']] = 'true';
        update_user_meta(get_current_user_id(), self::ADMIN_NOTICES_KEY, $notices);

        die;
    }

    public static function getIntroduction()
    {
        $introduction = self::getCurrentIntroduction();

        if (empty($introduction['active'])) {
            return false;
        }

        $introduction['is_user_should_view'] = self::isUserShouldViewIntroduction();

        return $introduction;
    }

    public static function setIntroductionViewed()
    {
        $user_introduction_meta = self::getIntroductionMeta();

        if (!$user_introduction_meta) {
            $user_introduction_meta = array();
        }

        $current_introduction = self::getCurrentIntroduction();

        $user_introduction_meta[$current_introduction['version']] = true;

        $user = wp_get_current_user();

        update_user_meta($user->ID, self::INTRODUCTION_KEY, $user_introduction_meta);

        die;
    }

    private static function getIntroductionMeta()
    {
        $user = wp_get_current_user();

        return get_user_meta($user->ID, self::INTRODUCTION_KEY, true);
    }

    public static function isUserShouldViewIntroduction()
    {
        $user_introduction_meta = self::getIntroductionMeta();

        $current_introduction = self::getCurrentIntroduction();

        return empty($user_introduction_meta[$current_introduction['version']]);
    }

    private static function getCurrentIntroduction()
    {
        return array(
            'active' => true,
            'title' => '<div id="elementor-introduction-title">' .
                __('Two Minute Tour Of Elementor', 'elementor') .
                '</div><div id="elementor-introduction-subtitle">' .
                __('Watch this quick tour that gives you a basic understanding of how to use Elementor.') .
                '</div>',
            'content' => '<div class="elementor-video-wrapper">' .
                "<\x69frame src=\"https://www.youtube.com/embed/6u45V2q1s4k?autoplay=1&rel=0&showinfo=0\"" .
                " frameborder=\"0\" allowfullscreen></\x69frame>" .
                '</div>',
            'delay' => 2500,
            'version' => 1,
        );
    }
}

User::init();
