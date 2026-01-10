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

class Repeater extends ElementBase
{
    private static $counter = 0;

    public function __construct()
    {
        self::$counter++;

        parent::__construct();
    }

    public function getName()
    {
        return 'repeater-' . self::$counter;
    }

    public static function getType()
    {
        return 'repeater';
    }

    public function addControl($id, array $args, $overwrite = false)
    {
        if (null !== $this->_current_tab) {
            $args = array_merge($args, $this->_current_tab);
        }

        return Plugin::$instance->controls_manager->addControlToStack($this, $id, $args, $overwrite);
    }

    public function _getDefaultChildType(array $element_data)
    {
        return false;
    }
}
