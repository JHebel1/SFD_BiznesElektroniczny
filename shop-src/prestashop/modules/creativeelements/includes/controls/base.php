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
 * A base control for creation of all controls in the panel. All controls accept all the params listed below.
 *
 * @param string $label               The title of the control
 * @param mixed  $default             The default value
 * @param string $separator           Set the position of the control separator.
 *                                    'default' means that the separator will be posited depending on the control type.
 *                                    'before' || 'after' will force the separator position before/after the control.
 *                                    'none' will hide the separator
 *                                    Default: 'default'
 * @param bool   $show_label          Sets whether to show the title
 *                                    Default: true
 * @param bool   $label_block         Sets whether to display the title in a separate line
 *                                    Default: false
 * @param string $title               The title that will appear on mouse hover
 * @param string $placeholder         Available for fields that support placeholder
 * @param string $description         The field description that appears below the field
 *
 * @since 1.0.0
 */
abstract class ControlBase
{
    private $_base_settings = array(
        'label' => '',
        'separator' => 'default',
        'show_label' => true,
        'label_block' => false,
        'title' => '',
        'placeholder' => '',
        'description' => '',
    );

    private $_settings = array();

    abstract public function contentTemplate();

    abstract public function getType();

    public function __construct()
    {
        $this->_settings = array_merge($this->_base_settings, $this->getDefaultSettings());
    }

    public function enqueue()
    {
    }

    public function getDefaultValue()
    {
        return '';
    }

    public function getValue($control, $widget)
    {
        if (!isset($control['default'])) {
            $control['default'] = $this->getDefaultValue();
        }

        if (!isset($widget[$control['name']])) {
            return $control['default'];
        }

        return $widget[$control['name']];
    }

    public function getStyleValue($css_property, $control_value)
    {
        return $control_value;
    }

    /**
     * @param string $setting_key
     *
     * @return array
     * @since 1.0.0
     */
    final public function getSettings($setting_key = null)
    {
        if ($setting_key) {
            if (isset($this->_settings[$setting_key])) {
                return $this->_settings[$setting_key];
            }

            return null;
        }

        return $this->_settings;
    }

    /**
     * @return void
     *
     * @since 1.0.0
     */
    final public function printTemplate()
    {
        ?>
        <script type="text/html" id="tmpl-elementor-control-<?php echo esc_attr($this->getType()); ?>-content">
            <div class="elementor-control-content">
                <?php $this->contentTemplate();?>
            </div>
        </script>
        <?php
    }

    protected function getDefaultSettings()
    {
        return array();
    }
}
