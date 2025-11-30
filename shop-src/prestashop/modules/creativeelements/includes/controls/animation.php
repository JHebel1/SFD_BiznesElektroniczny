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
 * An Animation effect select box control.
 *
 * @param string $default     The selected option key
 *                            Default empty
 *
 * @since 1.0.0
 */
class ControlAnimation extends ControlBase
{
    private static $_animations;

    public function getType()
    {
        return 'animation';
    }

    public static function getAnimations()
    {
        if (is_null(self::$_animations)) {
            self::$_animations = array(
                'Fading' => array(
                    'fadeIn' => 'Fade In',
                    'fadeInDown' => 'Fade In Down',
                    'fadeInLeft' => 'Fade In Left',
                    'fadeInRight' => 'Fade In Right',
                    'fadeInUp' => 'Fade In Up',
                ),
                'Zooming' => array(
                    'zoomIn' => 'Zoom In',
                    'zoomInDown' => 'Zoom In Down',
                    'zoomInLeft' => 'Zoom In Left',
                    'zoomInRight' => 'Zoom In Right',
                    'zoomInUp' => 'Zoom In Up',
                ),
                'Bouncing' => array(
                    'bounceIn' => 'Bounce In',
                    'bounceInDown' => 'Bounce In Down',
                    'bounceInLeft' => 'Bounce In Left',
                    'bounceInRight' => 'Bounce In Right',
                    'bounceInUp' => 'Bounce In Up',
                ),
                'Sliding' => array(
                    'slideInDown' => 'Slide In Down',
                    'slideInLeft' => 'Slide In Left',
                    'slideInRight' => 'Slide In Right',
                    'slideInUp' => 'Slide In Up',
                ),
                'Rotating' => array(
                    'rotateIn' => 'Rotate In',
                    'rotateInDownLeft' => 'Rotate In Down Left',
                    'rotateInDownRight' => 'Rotate In Down Right',
                    'rotateInUpLeft' => 'Rotate In Up Left',
                    'rotateInUpRight' => 'Rotate In Up Right',
                ),
                'Attention Seekers' => array(
                    'bounce' => 'Bounce',
                    'flash' => 'Flash',
                    'pulse' => 'Pulse',
                    'rubberBand' => 'Rubber Band',
                    'shake' => 'Shake',
                    'headShake' => 'Head Shake',
                    'swing' => 'Swing',
                    'tada' => 'Tada',
                    'wobble' => 'Wobble',
                    'jello' => 'Jello',
                ),
                'Light Speed' => array(
                    'lightSpeedIn' => 'Light Speed In',
                ),
                'Specials' => array(
                    'rollIn' => 'Roll In',
                ),
            );
        }

        return self::$_animations;
    }

    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <select data-setting="{{ data.name }}">
                    <option value=""><?php _e('None', 'elementor');?></option>
                    <?php foreach (self::getAnimations() as $animations_group_name => $animations_group) : ?>
                        <optgroup label="<?php echo $animations_group_name; ?>">
                            <?php foreach ($animations_group as $animation_name => $animation_title) : ?>
                                <option value="<?php echo $animation_name; ?>"><?php echo $animation_title; ?></option>
                            <?php endforeach;?>
                        </optgroup>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
