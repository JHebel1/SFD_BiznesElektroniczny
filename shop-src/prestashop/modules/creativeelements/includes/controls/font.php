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
 * A font select box control. The list is based on Google Fonts project (@see https://fonts.google.com/)
 *
 * @param string $default   The selected font name
 *                          Default empty
 * @param array $fonts      All available fonts
 *                          Default @see Fonts::getFonts()
 *
 * @since 1.0.0
 */
class ControlFont extends ControlBase
{
    public function getType()
    {
        return 'font';
    }

    protected function getDefaultSettings()
    {
        return array(
            'fonts' => Fonts::getFonts(),
        );
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <select class="elementor-control-font-family" data-setting="{{ data.name }}">
                    <option value=""><?php _e('Default', 'elementor');?></option>
                    <optgroup label="<?php _e('System', 'elementor');?>">
                        <# _.each( getFontsByGroups( 'system' ), function( fontType, fontName ) { #>
                        <option value="{{ fontName }}">{{{ fontName }}}</option>
                        <# } ); #>
                    </optgroup>
                    <?php /*
                    <optgroup label="<?php _e( 'Local', 'elementor' ); ?>">
                    <# _.each( getFontsByGroups( 'local' ), function( fontType, fontName ) { #>
                    <option value="{{ fontName }}">{{{ fontName }}}</option>
                    <# } ); #>
                    </optgroup> */ ?>
                    <optgroup label="<?php _e('Google', 'elementor');?>">
                        <# _.each( getFontsByGroups( [ 'googlefonts', 'earlyaccess' ] ), function( fontType, fontName ) { #>
                        <option value="{{ fontName }}">{{{ fontName }}}</option>
                        <# } ); #>
                    </optgroup>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
