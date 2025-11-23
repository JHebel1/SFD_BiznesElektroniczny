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

abstract class ElementBase extends ControlsStack
{
    /**
     * @var ElementBase[]
     */
    private $_children;

    private $_render_attributes = array();

    private $_default_args = array();

    protected static $_edit_tools;

    private $_is_type_instance = true;

    public function getScriptDepends()
    {
        return array();
    }

    final public function enqueueScripts()
    {
        foreach ($this->getScriptDepends() as $script) {
            wp_enqueue_script($script);
        }
    }

    final public static function getEditTools()
    {
        if (null === static::$_edit_tools) {
            self::_initEditTools();
        }

        return static::$_edit_tools;
    }

    final public static function addEditTool($tool_name, $tool_data, $after = null)
    {
        if (null === static::$_edit_tools) {
            self::_initEditTools();
        }

        // Adding the tool at specific position
        // in the tools array if requested
        if ($after) {
            $after_index = array_search($after, array_keys(static::$_edit_tools)) + 1;

            static::$_edit_tools = array_slice(static::$_edit_tools, 0, $after_index, true) +
            array($tool_name => $tool_data) +
            array_slice(static::$_edit_tools, $after_index, null, true);
        } else {
            static::$_edit_tools[$tool_name] = $tool_data;
        }
    }

    public static function getType()
    {
        return 'element';
    }

    protected static function getDefaultEditTools()
    {
        return array();
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

    private static function _initEditTools()
    {
        static::$_edit_tools = static::getDefaultEditTools();
    }

    /**
     * @param array $element_data
     *
     * @return ElementBase
     */
    abstract protected function _getDefaultChildType(array $element_data);

    public function beforeRender()
    {
    }

    public function afterRender()
    {
    }

    public function getTitle()
    {
        return '';
    }

    public function getIcon()
    {
        return 'eicon-columns';
    }

    public function isReloadPreviewRequired()
    {
        return false;
    }

    public function printTemplate()
    {
        ob_start();

        $this->_contentTemplate();

        $content_template = ob_get_clean();

        // $content_template = Utils::applyFiltersDeprecated('elementor/elements/print_template', array($content_template, $this), '1.0.10', 'elementor/element/print_template');

        $content_template = apply_filters('elementor/element/print_template', $content_template, $this);

        if (empty($content_template)) {
            return;
        }
        ?>
        <script type="text/html" id="tmpl-elementor-<?php echo $this->getType(); ?>-<?php echo esc_attr($this->getName()); ?>-content">
            <?php $this->_renderSettings();?>
            <?php echo $content_template; ?>
        </script>
        <?php
    }

    public function getChildren()
    {
        if (null === $this->_children) {
            $this->_initChildren();
        }

        return $this->_children;
    }

    public function getDefaultArgs($item = null)
    {
        return self::_getItems($this->_default_args, $item);
    }

    /**
     * @return Element_Base
     */
    public function getParent()
    {
        return $this->getData('parent');
    }

    /**
     * @param array $child_data
     * @param array $child_args
     *
     * @return Element_Base|false
     */
    public function addChild(array $child_data, array $child_args = array())
    {
        if (null === $this->_children) {
            $this->_initChildren();
        }

        $child_type = $this->_getChildType($child_data);

        if (!$child_type) {
            return false;
        }

        $child = Plugin::$instance->elements_manager->createElementInstance($child_data, $child_args, $child_type);

        if ($child) {
            $this->_children[] = $child;
        }

        return $child;
    }

    public function addRenderAttribute($element, $key = null, $value = null, $overwrite = false)
    {
        if (is_array($element)) {
            foreach ($element as $element_key => $attributes) {
                $this->addRenderAttribute($element_key, $attributes, null, $overwrite);
            }

            return $this;
        }

        if (is_array($key)) {
            foreach ($key as $attribute_key => $attributes) {
                $this->addRenderAttribute($element, $attribute_key, $attributes, $overwrite);
            }

            return $this;
        }

        if (empty($this->_render_attributes[$element][$key])) {
            $this->_render_attributes[$element][$key] = array();
        }

        settype($value, 'array');

        if ($overwrite) {
            $this->_render_attributes[$element][$key] = $value;
        } else {
            $this->_render_attributes[$element][$key] = array_merge($this->_render_attributes[$element][$key], $value);
        }

        return $this;
    }

    public function setRenderAttribute($element, $key = null, $value = null)
    {
        return $this->addRenderAttribute($element, $key, $value, true);
    }

    public function getRenderAttributeString($element)
    {
        if (empty($this->_render_attributes[$element])) {
            return '';
        }

        $render_attributes = $this->_render_attributes[$element];

        $attributes = array();

        foreach ($render_attributes as $attribute_key => $attribute_values) {
            $attributes[] = sprintf('%s="%s"', $attribute_key, esc_attr(implode(' ', $attribute_values)));
        }

        return implode(' ', $attributes);
    }

    public function printElement()
    {
        $this->enqueueScripts();

        do_action('elementor/frontend/' . static::getType() . '/before_render', $this);

        $this->_addRenderAttributes();

        $this->beforeRender();

        $this->_printContent();

        $this->afterRender();

        do_action('elementor/frontend/' . static::getType() . '/after_render', $this);
    }

    public function getRawData($with_html_content = false)
    {
        $data = $this->getData();

        $elements = array();

        foreach ($this->getChildren() as $child) {
            $elements[] = $child->getRawData($with_html_content);
        }

        return array(
            'id' => $this->getId(),
            'elType' => $data['elType'],
            'settings' => $data['settings'],
            'elements' => $elements,
            'isInner' => $data['isInner'],
        );
    }

    public function getUniqueSelector()
    {
        return '.elementor-element-' . $this->getId();
    }

    protected function _contentTemplate()
    {
    }

    protected function _renderSettings()
    {
        ?>
        <div class="elementor-element-overlay">
            <div class="elementor-editor-element-settings elementor-editor-<?php echo esc_attr($this->getType()); ?>-settings elementor-editor-<?php echo esc_attr($this->getName()); ?>-settings">
                <ul class="elementor-editor-element-settings-list">
                    <li class="elementor-editor-element-setting elementor-editor-element-add">
                        <a href="#" title="<?php _e('Add Widget', 'elementor');?>">
                            <span class="elementor-screen-only"><?php _e('Add', 'elementor');?></span>
                            <i class="fa fa-plus"></i>
                        </a>
                    </li>
                    <li class="elementor-editor-element-setting elementor-editor-element-duplicate">
                        <a href="#" title="<?php _e('Duplicate Widget', 'elementor');?>">
                            <span class="elementor-screen-only"><?php _e('Duplicate', 'elementor');?></span>
                            <i class="fa fa-files-o"></i>
                        </a>
                    </li>
                    <li class="elementor-editor-element-setting elementor-editor-element-remove">
                        <a href="#" title="<?php _e('Remove Widget', 'elementor');?>">
                            <span class="elementor-screen-only"><?php _e('Remove', 'elementor');?></span>
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * @return boolean
     */
    public function isTypeInstance()
    {
        return $this->_is_type_instance;
    }

    final public function getFrontendSettingsKeys()
    {
        $controls = array();

        foreach ($this->getControls() as $control) {
            if (!empty($control['frontend_available'])) {
                $controls[] = $control['name'];
            }
        }

        return $controls;
    }

    protected function _addRenderAttributes()
    {
        $id = $this->getId();

        $this->addRenderAttribute('_wrapper', 'data-id', $id);

        $this->addRenderAttribute('_wrapper', 'class', array(
            'elementor-element',
            'elementor-element-' . $id,
        ));

        $settings = $this->getActiveSettings();

        foreach (self::getClassControls() as $control) {
            if (empty($settings[$control['name']])) {
                continue;
            }

            $this->addRenderAttribute('_wrapper', 'class', $control['prefix_class'] . $settings[$control['name']]);
        }

        if (!empty($settings['_element_id'])) {
            $this->addRenderAttribute('_wrapper', 'id', trim($settings['_element_id']));
        }

        if (!Plugin::$instance->editor->isEditMode()) {
            $frontend_settings = array_intersect_key($settings, array_flip($this->getFrontendSettingsKeys()));

            foreach ($frontend_settings as $key => $setting) {
                if (in_array($setting, array(null, ''), true)) {
                    unset($frontend_settings[$key]);
                }
            }

            if ($frontend_settings) {
                $this->addRenderAttribute('_wrapper', 'data-settings', json_encode($frontend_settings));
            }
        }
    }

    protected function render()
    {
    }

    protected function getDefaultData()
    {
        $data = parent::getDefaultData();

        return array_merge($data, array(
            'elements' => array(),
            'isInner' => false,
        ));
    }

    protected function _printContent()
    {
        foreach ($this->getChildren() as $child) {
            $child->printElement();
        }
    }

    protected function _getInitialConfig()
    {
        $config = parent::_getInitialConfig();

        return array_merge($config, array(
            'name' => $this->getName(),
            'elType' => $this->getType(),
            'title' => $this->getTitle(),
            'icon' => $this->getIcon(),
            'reload_preview' => $this->isReloadPreviewRequired(),
        ));
    }

    private function _getChildType($element_data)
    {
        $child_type = $this->_getDefaultChildType($element_data);

        // If it's not a valid widget ( like a deactivated plugin )
        if (!$child_type) {
            return false;
        }

        return apply_filters('elementor/element/get_child_type', $child_type, $element_data, $this);
    }

    private function _initChildren()
    {
        $this->_children = array();

        $children_data = $this->getData('elements');

        if (!$children_data) {
            return;
        }

        foreach ($children_data as $child_data) {
            if (!$child_data) {
                continue;
            }

            $this->addChild($child_data);
        }
    }

    public function __construct(array $data = array(), array $args = null)
    {
        parent::__construct($data);

        if ($data) {
            $this->_is_type_instance = false;
        } elseif ($args) {
            $this->_default_args = $args;
        }
    }
}
