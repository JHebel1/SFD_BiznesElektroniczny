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

class Settings
{
    const UPDATE_TIME_FIELD = '_elementor_settings_update_time';

    public static function getUrl()
    {
        return is_admin() ? \Context::getContext()->link->getAdminLink('AdminCESettings') : '';
    }
}
