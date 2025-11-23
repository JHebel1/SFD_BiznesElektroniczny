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
 * A private control for native PrestaShop widgets.
 *
 * @since 1.0.0
 */
class ControlPsWidget extends ControlBase
{
    public function getType()
    {
        return 'ps_widget';
    }

    public function contentTemplate()
    {
        ?>
        <form action="" method="post">
            <div class="wp-widget-form-loading">Loading..</div>
        </form>
        <?php
    }
}
