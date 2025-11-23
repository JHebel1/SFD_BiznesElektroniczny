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

class Heartbeat
{
    /**
     * Handle the post lock in the editor.
     *
     * @since 1.0.0
     *
     * @param array $response
     * @param array $data
     *
     * @return array
     */
    public function heartbeatReceived($response, $data)
    {
        if (isset($data['elementor_post_lock']['post_ID'])) {
            $post_id = $data['elementor_post_lock']['post_ID'];
            $locked_user = Plugin::instance()->editor->getLockedUser($post_id);

            if (!$locked_user || !empty($data['elementor_force_post_lock'])) {
                Plugin::instance()->editor->lockPost($post_id);
            } else {
                $response['locked_user'] = $locked_user->display_name;
            }

            $response['elementor_nonce'] = 1;
        }
        return $response;
    }

    /**
     * Heartbeat constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_filter('heartbeat_received', array($this, 'heartbeat_received'), 10, 2);
    }
}
