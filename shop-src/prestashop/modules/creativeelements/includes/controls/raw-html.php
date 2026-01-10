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
 * A UI only control. Show HTML markup between controls
 *
 * @param string $raw     The HTML markup
 *                        Default empty
 * @param string $classes Additional classes for the HTML wrapper
 *                        Default empty
 *
 * @since 1.0.0
 */
class ControlRawhtml extends ControlBase
{
    public function getType()
    {
        return 'raw_html';
    }

    public function contentTemplate()
    {
        ?>
        <# if ( data.label ) { #>
        <span class="elementor-control-title">{{{ data.label }}}</span>
        <# } #>
        <div class="elementor-control-raw-html {{ data.content_classes }}">{{{ data.raw }}}</div>
        <?php
    }

    public function getDefaultSettings()
    {
        return array(
            'content_classes' => '',
        );
    }
}
