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
 * An Hover Animation effect select box control.
 * @see ControlHoverAnimation::getAnimations() fot all available animations.
 *
 * @param string $default     The selected effect key
 *                            Default empty
 *
 * @since 1.0.0
 */
class ControlHoverAnimation extends ControlBase
{
    private static $_animations;

    public function getType()
    {
        return 'hover_animation';
    }

    public static function getAnimations()
    {
        if (is_null(self::$_animations)) {
            self::$_animations = array(
                'grow' => 'Grow',
                'shrink' => 'Shrink',
                'pulse' => 'Pulse',
                'pulse-grow' => 'Pulse Grow',
                'pulse-shrink' => 'Pulse Shrink',
                'push' => 'Push',
                'pop' => 'Pop',
                'bounce-in' => 'Bounce In',
                'bounce-out' => 'Bounce Out',
                'rotate' => 'Rotate',
                'grow-rotate' => 'Grow Rotate',
                'fade-out-20' => 'Fade-out 20%',
                'float' => 'Float',
                'sink' => 'Sink',
                'bob' => 'Bob',
                'hang' => 'Hang',
                'skew' => 'Skew',
                'skew-forward' => 'Skew Forward',
                'skew-backward' => 'Skew Backward',
                'wobble-vertical' => 'Wobble Vertical',
                'wobble-horizontal' => 'Wobble Horizontal',
                'wobble-to-bottom-right' => 'Wobble To Bottom Right',
                'wobble-to-top-right' => 'Wobble To Top Right',
                'wobble-top' => 'Wobble Top',
                'wobble-bottom' => 'Wobble Bottom',
                'wobble-skew' => 'Wobble Skew',
                'buzz' => 'Buzz',
                'buzz-out' => 'Buzz Out',
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
                    <?php foreach (self::getAnimations() as $animation_name => $animation_title) : ?>
                        <option value="<?php echo $animation_name; ?>"><?php echo $animation_title; ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    protected function getDefaultSettings()
    {
        return array(
            'label_block' => true,
        );
    }
}
