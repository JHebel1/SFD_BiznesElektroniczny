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

abstract class ControlsStack
{
    const RESPONSIVE_DESKTOP = 'desktop';
    const RESPONSIVE_TABLET = 'tablet';
    const RESPONSIVE_MOBILE = 'mobile';

    private $_id;
    private $_settings;
    private $_data;
    private $_config;

    /**
     * Holds the current section while render a set of controls sections
     *
     * @var null|array
     */
    private $_current_section = null;

    /**
     * Holds the current tab while render a set of controls tabs
     *
     * @var null|array
     */
    protected $_current_tab = null;

    public function getId()
    {
        return $this->_id;
    }

    abstract public function getName();

    public static function getType()
    {
        return 'stack';
    }

    /**
     * @param array $haystack
     * @param string $needle
     *
     * @return mixed the whole haystack or the
     * needle from the haystack when requested
     */
    private static function _getItems(array $haystack, $needle = null)
    {
        if ($needle) {
            return isset($haystack[$needle]) ? $haystack[$needle] : null;
        }

        return $haystack;
    }

    public function getControls($control_id = null)
    {
        $stack = Plugin::$instance->controls_manager->getElementStack($this);

        if (null === $stack) {
            $this->_initControls();

            return $this->getControls();
        }

        return self::_getItems($stack['controls'], $control_id);
    }

    public function getActiveControls()
    {
        $controls = $this->getControls();

        $settings = $this->getSettings();

        $active_controls = array_reduce(array_keys($controls), function ($active_controls, $control_key) use ($controls, $settings) {
            $control = $controls[$control_key];

            if (${'this'}->isControlVisible($control, $settings)) {
                $active_controls[$control_key] = $control;
            }

            return $active_controls;
        }, array());

        return $active_controls;
    }

    public function addControl($id, array $args, $overwrite = false)
    {
        if (empty($args['type']) || !in_array($args['type'], array(ControlsManager::SECTION, ControlsManager::PS_WIDGET))) {
            if (null !== $this->_current_section) {
                if (!empty($args['section']) || !empty($args['tab'])) {
                    _doing_it_wrong(__CLASS__ . '::' . __FUNCTION__, 'Cannot redeclare control with `tab` or `section` args inside section. - ' . $id, '1.0.0');
                }
                $args = array_merge($args, $this->_current_section);

                if (null !== $this->_current_tab) {
                    $args = array_merge($args, $this->_current_tab);
                }
            } elseif (empty($args['section'])) {
                die(__CLASS__ . '::' . __FUNCTION__ . ': Cannot add a control outside a section (use `start_controls_section`).');
            }
        }

        return Plugin::$instance->controls_manager->addControlToStack($this, $id, $args, $overwrite);
    }

    public function removeControl($control_id)
    {
        return Plugin::$instance->controls_manager->removeControlFromStack($this->getName(), $control_id);
    }

    public function updateControl($control_id, array $args)
    {
        return Plugin::$instance->controls_manager->updateControlInStack($this, $control_id, $args);
    }

    final public function addGroupControl($group_name, array $args = array())
    {
        $group = Plugin::$instance->controls_manager->getControlGroups($group_name);

        if (!$group) {
            die(__CLASS__ . '::' . __FUNCTION__ . ': Group `' . $group_name . '` not found.');
        }

        $group->addControls($this, $args);
    }

    final public function getSchemeControls()
    {
        $enabled_schemes = SchemesManager::getEnabledSchemes();

        return array_filter($this->getControls(), function ($control) use ($enabled_schemes) {
            return (!empty($control['scheme']) && in_array($control['scheme']['type'], $enabled_schemes));
        });
    }

    final public function getStyleControls($controls = null)
    {
        if (null === $controls) {
            $controls = $this->getActiveControls();
        }

        $style_controls = array();

        foreach ($controls as $control_name => $control) {
            if (ControlsManager::REPEATER === $control['type']) {
                $control['style_fields'] = $this->getStyleControls($control['fields']);
            }

            if (!empty($control['style_fields']) || !empty($control['selectors'])) {
                $style_controls[$control_name] = $control;
            }
        }

        return $style_controls;
    }

    final public function getClassControls()
    {
        return array_filter($this->getActiveControls(), function ($control) {
            return (isset($control['prefix_class']));
        });
    }

    final public function getTabsControls()
    {
        $stack = Plugin::$instance->controls_manager->getElementStack($this);

        return $stack['tabs'];
    }

    final public function addResponsiveControl($id, array $args, $overwrite = false)
    {
        $devices = array(
            self::RESPONSIVE_DESKTOP,
            self::RESPONSIVE_TABLET,
            self::RESPONSIVE_MOBILE,
        );

        if (isset($args['default'])) {
            $args['desktop_default'] = $args['default'];

            unset($args['default']);
        }

        foreach ($devices as $device_name) {
            $control_args = $args;

            if (!empty($args['prefix_class'])) {
                $device_to_replace = self::RESPONSIVE_DESKTOP === $device_name ? '' : '-' . $device_name;

                $control_args['prefix_class'] = sprintf($args['prefix_class'], $device_to_replace);
            }

            $control_args['responsive'] = array('max' => $device_name);

            if (isset($control_args[$device_name . '_default'])) {
                $control_args['default'] = $control_args[$device_name . '_default'];
            }

            unset($control_args['desktop_default']);
            unset($control_args['tablet_default']);
            unset($control_args['mobile_default']);

            $id_suffix = self::RESPONSIVE_DESKTOP === $device_name ? '' : '_' . $device_name;

            $this->addControl($id . $id_suffix, $control_args, $overwrite);
        }
    }

    final public function updateResponsiveControl($id, array $args)
    {
        $this->addResponsiveControl($id, $args, true);
    }

    final public function removeResponsiveControl($id)
    {
        $devices = array(
            self::RESPONSIVE_DESKTOP,
            self::RESPONSIVE_TABLET,
            self::RESPONSIVE_MOBILE,
        );

        foreach ($devices as $device_name) {
            $id_suffix = self::RESPONSIVE_DESKTOP === $device_name ? '' : '_' . $device_name;

            $this->removeControl($id . $id_suffix);
        }
    }

    final public function getClassName()
    {
        return get_called_class();
    }

    final public function getConfig()
    {
        if (null === $this->_config) {
            $this->_config = $this->_getInitialConfig();
        }

        return $this->_config;
    }

    public function getData($item = null)
    {
        return self::_getItems($this->_data, $item);
    }

    public function getSettings($setting = null)
    {
        return self::_getItems($this->_settings, $setting);
    }

    public function getActiveSettings()
    {
        $settings = $this->getSettings();

        $active_settings = array_intersect_key($settings, $this->getActiveControls());

        $settings_mask = array_fill_keys(array_keys($settings), null);

        return array_merge($settings_mask, $active_settings);
    }

    public function isControlVisible($control, $values = null)
    {
        if (null === $values) {
            $values = $this->getSettings();
        }

        // Repeater fields
        if (!empty($control['conditions'])) {
            return Conditions::check($control['conditions'], $values);
        }

        if (empty($control['condition'])) {
            return true;
        }

        foreach ($control['condition'] as $condition_key => $condition_value) {
            preg_match('/([a-z_0-9]+)(?:\[([a-z_]+)])?(!?)$/i', $condition_key, $condition_key_parts);

            $pure_condition_key = $condition_key_parts[1];
            $condition_sub_key = $condition_key_parts[2];
            $is_negative_condition = !!$condition_key_parts[3];

            $instance_value = $values[$pure_condition_key];

            if (null === $instance_value) {
                return false;
            }

            if ($condition_sub_key) {
                if (!isset($instance_value[$condition_sub_key])) {
                    return false;
                }

                $instance_value = $instance_value[$condition_sub_key];
            }

            /**
             * If the $condition_value is a non empty array - check if the $condition_value contains the $instance_value,
             * If the $instance_value is a non empty array - check if the $instance_value contains the $condition_value
             * otherwise check if they are equal. ( and give the ability to check if the value is an empty array )
             **/
            if (is_array($condition_value) && !empty($condition_value)) {
                $is_contains = in_array($instance_value, $condition_value);
            } elseif (is_array($instance_value) && !empty($instance_value)) {
                $is_contains = in_array($condition_value, $instance_value);
            } else {
                $is_contains = $instance_value === $condition_value;
            }

            if ($is_negative_condition && $is_contains || !$is_negative_condition && !$is_contains) {
                return false;
            }
        }

        return true;
    }

    public function startControlsSection($section_id, array $args)
    {
        do_action('elementor/element/before_section_start', $this, $section_id, $args);
        do_action('elementor/element/' . $this->getName() . '/' . $section_id . '/before_section_start', $this, $args);

        $args['type'] = ControlsManager::SECTION;

        $this->addControl($section_id, $args);

        if (null !== $this->_current_section) {
            die(sprintf('Elementor: You can\'t start a section before the end of the previous section: `%s`', $this->_current_section['section']));
        }

        $this->_current_section = $this->getSectionArgs($section_id);

        do_action('elementor/element/after_section_start', $this, $section_id, $args);
        do_action('elementor/element/' . $this->getName() . '/' . $section_id . '/after_section_start', $this, $args);
    }

    public function endControlsSection()
    {
        // Save the current section for the action
        $current_section = $this->_current_section;
        $section_id = $current_section['section'];
        $args = array('tab' => $current_section['tab']);

        do_action('elementor/element/before_section_end', $this, $section_id, $args);
        do_action('elementor/element/' . $this->getName() . '/' . $section_id . '/before_section_end', $this, $args);

        $this->_current_section = null;

        do_action('elementor/element/after_section_end', $this, $section_id, $args);
        do_action('elementor/element/' . $this->getName() . '/' . $section_id . '/after_section_end', $this, $args);
    }

    public function startControlsTabs($tabs_id)
    {
        if (null !== $this->_current_tab) {
            die(sprintf('Elementor: You can\'t start tabs before the end of the previous tabs: `%s`', $this->_current_tab['tabs_wrapper']));
        }

        $this->addControl(
            $tabs_id,
            array(
                'type' => ControlsManager::TABS,
            )
        );

        $this->_current_tab = array(
            'tabs_wrapper' => $tabs_id,
        );
    }

    public function endControlsTabs()
    {
        $this->_current_tab = null;
    }

    public function startControlsTab($tab_id, $args)
    {
        if (!empty($this->_current_tab['inner_tab'])) {
            die(sprintf('Elementor: You can\'t start a tab before the end of the previous tab: `%s`', $this->_current_tab['inner_tab']));
        }

        $args['type'] = ControlsManager::TAB;
        $args['tabs_wrapper'] = $this->_current_tab['tabs_wrapper'];

        $this->addControl($tab_id, $args);

        $this->_current_tab['inner_tab'] = $tab_id;
    }

    public function endControlsTab()
    {
        unset($this->_current_tab['inner_tab']);
    }

    final public function setSettings($key, $value = null)
    {
        if (null === $value) {
            $this->_settings = $key;
        } else {
            $this->_settings[$key] = $value;
        }
    }

    protected function _registerControls()
    {
    }

    protected function getDefaultData()
    {
        return array(
            'id' => 0,
            'settings' => array(),
        );
    }

    protected function _getParsedSettings()
    {
        $settings = $this->_data['settings'];

        foreach ($this->getControls() as $control) {
            $control_obj = Plugin::$instance->controls_manager->getControl($control['type']);

            $control = array_merge($control, $control_obj->getSettings());

            $settings[$control['name']] = $control_obj->getValue($control, $settings);
        }

        return $settings;
    }

    protected function _getInitialConfig()
    {
        return array(
            'controls' => $this->getControls(),
            'tabs_controls' => $this->getTabsControls(),
        );
    }

    protected function getSectionArgs($section_id)
    {
        return array(
            'section' => $section_id,
            'tab' => $this->getControls($section_id)['tab'],
        );
    }

    private function _initControls()
    {
        Plugin::$instance->controls_manager->openStack($this);

        $this->_registerControls();
    }

    protected function _init($data)
    {
        $this->_data = array_merge($this->getDefaultData(), $data);

        $this->_id = $data['id'];

        $this->_settings = $this->_getParsedSettings();
    }

    public function __construct(array $data = array())
    {
        if ($data) {
            $this->_init($data);
        }
    }
}
