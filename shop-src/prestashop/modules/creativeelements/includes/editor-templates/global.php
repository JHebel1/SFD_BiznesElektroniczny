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
?>
<script type="text/template" id="tmpl-elementor-empty-preview">
    <div class="elementor-first-add">
        <div class="elementor-icon eicon-plus"></div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-preview">
    <div class="elementor-section-wrap"></div>
    <div id="elementor-add-section" class="elementor-visible-desktop">
        <div id="elementor-add-section-inner">
            <div id="elementor-add-new-section">
                <button id="elementor-add-section-button" class="elementor-button"><?php _e('Add New Section', 'elementor');?></button>
                <button id="elementor-add-template-button" class="elementor-button"><?php _e('Add Template', 'elementor');?></button>
                <div id="elementor-add-section-drag-title"><?php _e('Or drag widget here', 'elementor');?></div>
            </div>
            <div id="elementor-select-preset">
                <div id="elementor-select-preset-close">
                    <i class="fa fa-times"></i>
                </div>
                <div id="elementor-select-preset-title"><?php _e('Select your Structure', 'elementor');?></div>
                <ul id="elementor-select-preset-list">
                    <#
                    var structures = [ 10, 20, 30, 40, 21, 22, 31, 32, 33, 50, 60, 34 ];

                    _.each( structures, function( structure ) {
                        var preset = elementor.presetsFactory.getPresetByStructure( structure ); #>

                        <li class="elementor-preset elementor-column elementor-col-16" data-structure="{{ structure }}">
                            {{{ elementor.presetsFactory.getPresetSVG( preset.preset ).outerHTML }}}
                        </li>
                    <# } ); #>
                </ul>
            </div>
        </div>
    </div>
</script>
