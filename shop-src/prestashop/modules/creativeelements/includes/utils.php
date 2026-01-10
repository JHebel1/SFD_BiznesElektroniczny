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

class Utils
{
    public static function getPlaceholderImageSrc()
    {
        return apply_filters('elementor/utils/get_placeholder_image_src', basename(_MODULE_DIR_) . '/creativeelements/views/img/placeholder.png');
    }

    public static function generateRandomString($length = 7)
    {
        $salt = 'abcdefghijklmnopqrstuvwxyz';
        return \Tools::substr(str_shuffle(str_repeat($salt, $length)), 0, $length);
    }

    public static function getYoutubeIdFromUrl($url)
    {
        preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?vi?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/', $url, $video_id_parts);

        if (empty($video_id_parts[1])) {
            return false;
        }

        return $video_id_parts[1];
    }

    public static function getTimezoneString()
    {
        return \Configuration::get('PS_TIMEZONE');
    }
}
