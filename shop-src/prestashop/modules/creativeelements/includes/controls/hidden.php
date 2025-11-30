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
 * A private control for internal use only.
 *
 * @since 1.0.0
 */
class ControlHidden extends ControlBase
{
    public function getType()
    {
        return 'hidden';
    }

    public function contentTemplate()
    {
        ?>
        <input type="hidden" data-setting="{{{ data.name }}}" />
        <?php
    }
}
