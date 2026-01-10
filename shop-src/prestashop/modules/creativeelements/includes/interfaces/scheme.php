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

interface SchemeInterface
{
    public static function getType();

    public function getTitle();

    public function getDisabledTitle();

    public function getSchemeTitles();

    public function getDefaultScheme();

    public function printTemplateContent();
}
