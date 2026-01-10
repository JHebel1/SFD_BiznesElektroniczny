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

class ControlsManager
{
    const TAB_CONTENT = 'content';
    const TAB_STYLE = 'style';
    const TAB_ADVANCED = 'advanced';
    const TAB_RESPONSIVE = 'responsive';
    const TAB_LAYOUT = 'layout';
    const TAB_SETTINGS = 'settings';

    const TEXT = 'text';
    const NUMBER = 'number';
    const TEXTAREA = 'textarea';
    const SELECT = 'select';
    const CHECKBOX = 'checkbox';
    const SWITCHER = 'switcher';

    const HIDDEN = 'hidden';
    const HEADING = 'heading';
    const RAW_HTML = 'raw_html';
    const SECTION = 'section';
    const TAB = 'tab';
    const TABS = 'tabs';
    const DIVIDER = 'divider';

    const COLOR = 'color';
    const MEDIA = 'media';
    const SLIDER = 'slider';
    const DIMENSIONS = 'dimensions';
    const CHOOSE = 'choose';
    const WYSIWYG = 'wysiwyg';
    const CODE = 'code';
    const FONT = 'font';
    const IMAGE_DIMENSIONS = 'image_dimensions';

    const PS_WIDGET = 'ps_widget';

    const URL = 'url';
    const REPEATER = 'repeater';
    const ICON = 'icon';
    // const GALLERY = 'gallery';
    const STRUCTURE = 'structure';
    const SELECT2 = 'select2';
    const DATE_TIME = 'date_time';
    const BOX_SHADOW = 'box_shadow';
    const ANIMATION = 'animation';
    const HOVER_ANIMATION = 'hover_animation';
    const ORDER = 'order';

    /**
     * @var ControlBase[]
     */
    private $_controls = null;

    /**
     * @var GroupControlBase[]
     */
    private $_control_groups = array();

    private $_controls_stack = array();

    private static $_available_tabs_controls;

    private static function _getAvailableTabsControls()
    {
        if (!self::$_available_tabs_controls) {
            self::$_available_tabs_controls = array(
                self::TAB_CONTENT => __('Content', 'elementor'),
                self::TAB_STYLE => __('Style', 'elementor'),
                self::TAB_ADVANCED => __('Advanced', 'elementor'),
                self::TAB_RESPONSIVE => __('Responsive', 'elementor'),
                self::TAB_LAYOUT => __('Layout', 'elementor'),
                self::TAB_SETTINGS => __('Settings', 'elementor'),
            );
        }

        return self::$_available_tabs_controls;
    }

    /**
     * @since 1.0.0
     */
    public function registerControls()
    {
        $this->_controls = array();

        $available_controls = array(
            self::TEXT,
            self::NUMBER,
            self::TEXTAREA,
            self::SELECT,
            self::CHECKBOX,
            self::SWITCHER,

            self::HIDDEN,
            self::HEADING,
            self::RAW_HTML,
            self::SECTION,
            self::TAB,
            self::TABS,
            self::DIVIDER,

            self::COLOR,
            self::MEDIA,
            self::SLIDER,
            self::DIMENSIONS,
            self::CHOOSE,
            self::WYSIWYG,
            self::CODE,
            self::FONT,
            self::IMAGE_DIMENSIONS,

            self::PS_WIDGET,

            self::URL,
            self::REPEATER,
            self::ICON,
            // self::GALLERY,
            self::STRUCTURE,
            self::SELECT2,
            self::DATE_TIME,
            self::BOX_SHADOW,
            self::ANIMATION,
            self::HOVER_ANIMATION,
            self::ORDER,
        );

        foreach ($available_controls as $control_id) {
            $control_filename = str_replace('_', '-', $control_id);

            $control_filename = _CE_PATH_ . "includes/controls/{$control_filename}.php";

            require $control_filename;

            $class_name = __NAMESPACE__ . '\Control' . str_replace('_', '', $control_id);

            $this->registerControl($control_id, new $class_name());
        }

        // Group Controls
        require _CE_PATH_ . 'includes/controls/groups/background.php';
        require _CE_PATH_ . 'includes/controls/groups/border.php';
        require _CE_PATH_ . 'includes/controls/groups/typography.php';
        require _CE_PATH_ . 'includes/controls/groups/image-size.php';
        require _CE_PATH_ . 'includes/controls/groups/box-shadow.php';

        $this->_control_groups['background'] = new GroupControlBackground();
        $this->_control_groups['border'] = new GroupControlBorder();
        $this->_control_groups['typography'] = new GroupControlTypography();
        $this->_control_groups['image-size'] = new GroupControlImageSize();
        $this->_control_groups['box-shadow'] = new GroupControlBoxShadow();

        do_action('elementor/controls/controls_registered', $this);
    }

    /**
     * @since 1.0.0
     *
     * @param $control_id
     * @param ControlBase $control_instance
     */
    public function registerControl($control_id, ControlBase $control_instance)
    {
        $this->_controls[$control_id] = $control_instance;
    }

    /**
     * @param $control_id
     *
     * @since 1.0.0
     * @return bool
     */
    public function unregisterControl($control_id)
    {
        if (!isset($this->_controls[$control_id])) {
            return false;
        }

        unset($this->_controls[$control_id]);

        return true;
    }

    /**
     * @since 1.0.0
     * @return ControlBase[]
     */
    public function getControls()
    {
        if (null === $this->_controls) {
            $this->registerControls();
        }

        return $this->_controls;
    }

    /**
     * @since 1.0.0
     * @param $control_id
     *
     * @return bool|\CE\ControlBase
     */
    public function getControl($control_id)
    {
        $controls = $this->getControls();

        return isset($controls[$control_id]) ? $controls[$control_id] : false;
    }

    /**
     * @since 1.0.0
     * @return array
     */
    public function getControlsData()
    {
        $controls_data = array();

        foreach ($this->getControls() as $name => $control) {
            $controls_data[$name] = $control->getSettings();
            $controls_data[$name]['default_value'] = $control->getDefaultValue();
        }

        return $controls_data;
    }

    /**
     * @since 1.0.0
     * @return void
     */
    public function renderControls()
    {
        foreach ($this->getControls() as $control) {
            $control->printTemplate();
        }
    }

    /**
     * @since 1.0.0
     *
     * @param string $id
     *
     * @return Group_Control_Base|Group_Control_Base[]
     */
    public function getControlGroups($id = null)
    {
        if ($id) {
            return isset($this->_control_groups[$id]) ? $this->_control_groups[$id] : null;
        }

        return $this->_control_groups;
    }

    /**
     * @since 1.0.0
     *
     * @param $id
     * @param $instance
     *
     * @return Group_Control_Base[]
     */
    public function addGroupControl($id, $instance)
    {
        $this->_control_groups[$id] = $instance;

        return $instance;
    }

    /**
     * @since 1.0.0
     * @return void
     */
    public function enqueueControlScripts()
    {
        foreach ($this->getControls() as $control) {
            $control->enqueue();
        }
    }

    public function openStack(ControlsStack $element)
    {
        $stack_id = $element->getName();

        $this->_controls_stack[$stack_id] = array(
            'tabs' => array(),
            'controls' => array(),
        );
    }

    public function addControlToStack(ControlsStack $element, $control_id, $control_data, $overwrite = false)
    {
        $default_args = array(
            'default' => '',
            'type' => self::TEXT,
            'tab' => self::TAB_CONTENT,
        );

        $control_data['name'] = $control_id;

        $control_data = array_merge($default_args, $control_data);

        $control_type_instance = $this->getControl($control_data['type']);

        if (!$control_type_instance) {
            _doing_it_wrong(__CLASS__ . '::' . __FUNCTION__, 'Control type `' . $control_data['type'] . '` not found`', '1.0.0');
            return false;
        }

        $control_default_value = $control_type_instance->getDefaultValue();

        if (is_array($control_default_value)) {
            $control_data['default'] = isset($control_data['default']) ? array_merge($control_default_value, (array) $control_data['default']) : $control_default_value;
        } else {
            $control_data['default'] = isset($control_data['default']) ? $control_data['default'] : $control_default_value;
        }

        $stack_id = $element->getName();

        if (!$overwrite && isset($this->_controls_stack[$stack_id]['controls'][$control_id])) {
            _doing_it_wrong(__CLASS__ . '::' . __FUNCTION__, 'Cannot redeclare control with same name. - ' . $control_id, '1.0.0');
            return false;
        }

        $available_tabs = self::_getAvailableTabsControls();

        if (!isset($available_tabs[$control_data['tab']])) {
            $control_data['tab'] = $default_args['tab'];
        }

        $this->_controls_stack[$stack_id]['tabs'][$control_data['tab']] = $available_tabs[$control_data['tab']];

        $this->_controls_stack[$stack_id]['controls'][$control_id] = $control_data;

        return true;
    }

    public function removeControlFromStack($stack_id, $control_id)
    {
        if (is_array($control_id)) {
            foreach ($control_id as $id) {
                $this->removeControlFromStack($stack_id, $id);
            }

            return true;
        }

        if (empty($this->_controls_stack[$stack_id]['controls'][$control_id])) {
            return new \PrestaShopException('Cannot remove not-exists control.');
        }

        unset($this->_controls_stack[$stack_id]['controls'][$control_id]);

        return true;
    }

    /**
     * @param string $stack_id
     * @param string $control_id
     *
     * @return array|\PrestaShopException
     */
    public function getControlFromStack($stack_id, $control_id)
    {
        if (empty($this->_controls_stack[$stack_id]['controls'][$control_id])) {
            return new \PrestaShopException('Cannot get a not-exists control.');
        }

        return $this->_controls_stack[$stack_id]['controls'][$control_id];
    }

    public function updateControlInStack(ControlsStack $element, $control_id, $control_data)
    {
        $old_control_data = $this->getControlFromStack($element->getName(), $control_id);
        if (is_wp_error($old_control_data)) {
            return false;
        }

        $control_data = array_merge($old_control_data, $control_data);

        return $this->addControlToStack($element, $control_id, $control_data, true);
    }

    public function getElementStack(ControlsStack $element)
    {
        $stack_id = $element->getName();

        if (!isset($this->_controls_stack[$stack_id])) {
            return null;
        }

        $stack = $this->_controls_stack[$stack_id];

        if ('widget' === $element->getType() && 'common' !== $stack_id) {
            $common_widget = Plugin::$instance->widgets_manager->getWidgetTypes('common');

            $stack['controls'] = array_merge($stack['controls'], $common_widget->getControls());

            $stack['tabs'] = array_merge($stack['tabs'], $common_widget->getTabsControls());
        }

        return $stack;
    }

    /**
     * @param $element ElementBase
     */
    public function addCustomCssControls($element)
    {
        $element->startControlsSection(
            'section_custom_css',
            array(
                'label' => __('Custom CSS', 'elementor'),
                'tab' => ControlsManager::TAB_ADVANCED,
            )
        );

        $element->addControl(
            'custom_css_pro',
            [
                'type' => ControlsManager::RAW_HTML,
                'raw' => '<div class="elementor-panel-nerd-box">
                    <i class="elementor-panel-nerd-box-icon eicon-hypster"></i>
                    <div class="elementor-panel-nerd-box-title">' .
                        __('Meet Our Custom CSS', 'elementor') .
                    '</div>
                    <div class="elementor-panel-nerd-box-message">' .
                        __('Custom CSS lets you add CSS code to any widget, and see it render live right in the editor.', 'elementor') .
                    '</div>
                    <div class="elementor-panel-nerd-box-message">' .
                        __('This is a Premium feature.', 'elementor') .
                    '</div>
                    <a class="elementor-panel-nerd-box-link elementor-button elementor-button-default elementor-go-pro" href="https://addons.prestashop.com/administrative-tools/44064-creative-elements-elementor-based-pagebuilder.html" target="_blank">' .
                        __('Buy License', 'elementor') .
                    '</a>
                </div>',
            ]
        );

        $element->endControlsSection();
    }

    private function requireFiles()
    {
        // TODO: Move includes in later version (v1.2.x)
        require _CE_PATH_ . 'includes/controls/base.php';
        require _CE_PATH_ . 'includes/controls/base-multiple.php';
        require _CE_PATH_ . 'includes/controls/base-units.php';

        // Group Controls
        require _CE_PATH_ . 'includes/interfaces/group-control.php';
        require _CE_PATH_ . 'includes/controls/groups/base.php';
    }

    public function __construct()
    {
        $this->requireFiles();
    }
}
