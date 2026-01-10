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
 * A UI only control. Show a divider between controls
 *
 * @since 1.0.0
 */
class ControlDivider extends ControlBase
{
    public function getType()
    {
        return 'divider';
    }

    public function contentTemplate()
    {
        ?>
        <hr />
        <?php
    }
}
