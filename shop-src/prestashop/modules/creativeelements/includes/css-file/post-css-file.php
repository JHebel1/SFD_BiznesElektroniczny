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

class PostCssFile extends CssFile
{
    const META_KEY = '_elementor_css';

    const FILE_PREFIX = '';

    /**
     * @var int
     */
    private $post_id;

    /**
     * PostCssFile constructor.
     *
     * @param int $post_id
     */
    public function __construct($post_id)
    {
        $this->post_id = $post_id;

        parent::__construct();
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param ElementBase $element
     *
     * @return string
     */
    public function getElementUniqueSelector(ElementBase $element)
    {
        return '.elementor-' . $this->post_id . ' .elementor-element' . $element->getUniqueSelector();
    }

    /**
     * @return array
     */
    protected function loadMeta()
    {
        return get_post_meta($this->post_id, self::META_KEY, true);
    }

    /**
     * @param string $meta
     */
    protected function updateMeta($meta)
    {
        update_post_meta($this->post_id, '_elementor_css', $meta);
    }

    protected function renderCss()
    {
        $this->addPageSettingsRules();

        $data = Plugin::$instance->db->getPlainEditor($this->post_id);

        if (!empty($data)) {
            foreach ($data as $element_data) {
                $element = Plugin::$instance->elements_manager->createElementInstance($element_data);

                if (!$element) {
                    continue;
                }

                $this->renderStyles($element);
            }
        }

        do_action('elementor/post-css-file/parse', $this);
    }

    public function enqueue()
    {
        if (!Plugin::$instance->db->isBuiltWithElementor($this->post_id)) {
            return;
        }

        parent::enqueue();
    }

    protected function getEnqueueDependencies()
    {
        return array('elementor-frontend');
    }

    protected function getInlineDependency()
    {
        return 'elementor-frontend';
    }

    protected function getFileHandleId()
    {
        return 'elementor-post-' . $this->post_id;
    }

    protected function getFileName()
    {
        return self::FILE_PREFIX . $this->post_id;
    }

    /**
     * @param ControlsStack  $controls_stack
     * @param array          $controls
     * @param array          $values
     * @param array          $placeholders
     * @param array          $replacements
     */
    private function addElementStyleRules(ControlsStack $controls_stack, array $controls, array $values, array $placeholders, array $replacements)
    {
        foreach ($controls as $control) {
            if (!empty($control['style_fields'])) {
                foreach ($values[$control['name']] as $field_value) {
                    $this->addElementStyleRules(
                        $controls_stack,
                        $control['style_fields'],
                        $field_value,
                        array_merge($placeholders, array('{{CURRENT_ITEM}}')),
                        array_merge($replacements, array('.elementor-repeater-item-' . $field_value['_id']))
                    );
                }
            }

            if (empty($control['selectors'])) {
                continue;
            }

            $this->addControlStyleRules($control, $values, $controls_stack->getControls(), $placeholders, $replacements);
        }

        if ($controls_stack instanceof ElementBase) {
            foreach ($controls_stack->getChildren() as $child_element) {
                $this->renderStyles($child_element);
            }
        }
    }

    /**
     * @param array $control
     * @param array $values
     * @param array $controls_stack
     * @param array $placeholders
     * @param array $replacements
     */
    private function addControlStyleRules(array $control, array $values, array $controls_stack, array $placeholders, array $replacements)
    {
        $this->addControlRules($control, $controls_stack, function ($control) use ($values) {
            return ${'this'}->getStyleControlValue($control, $values);
        }, $placeholders, $replacements);
    }

    /**
     * @param array $control
     * @param array $values
     *
     * @return mixed
     */
    private function getStyleControlValue(array $control, array $values)
    {
        $value = $values[$control['name']];

        // fix for background image
        if (substr_compare($control['name'], '_image', -6) === 0 && !empty($value['url'])) {
            $value['url'] = Helper::getMediaLink($value['url']);
        }

        if (isset($control['selectors_dictionary'][$value])) {
            $value = $control['selectors_dictionary'][$value];
        }

        if (!is_numeric($value) && !is_float($value) && empty($value)) {
            return null;
        }

        return $value;
    }

    /**
     * @param ElementBase $element
     */
    private function renderStyles(ElementBase $element)
    {
        $element_settings = $element->getSettings();

        $this->addElementStyleRules($element, $element->getStyleControls(), $element_settings, array('{{ID}}', '{{WRAPPER}}'), array($element->getId(), $this->getElementUniqueSelector($element)));

        if ('column' === $element->getName()) {
            if (!empty($element_settings['_inline_size'])) {
                $this->stylesheet_obj->addRules($this->getElementUniqueSelector($element), array('width' => $element_settings['_inline_size'] . '%'), array('min' => 'tablet'));
            }
        }

        empty($element_settings['custom_css']) or $this->addPostCss($element, $element_settings['custom_css']);

        do_action('elementor/element/parse_css', $this, $element);
    }

    private function addPageSettingsRules()
    {
        $page_settings_instance = PageSettingsManager::getPage($this->post_id);
        $page_settings = $page_settings_instance->getSettings();

        $this->addElementStyleRules(
            $page_settings_instance,
            $page_settings_instance->getStyleControls(),
            $page_settings,
            array('{{WRAPPER}}'),
            array('body.elementor-page-' . $this->post_id)
        );

        empty($page_settings['custom_css']) or $this->addPageSettingsCss($page_settings['custom_css']);
    }

    private function addPostCss(ElementBase $element, $custom_css)
    {
        $css = trim($custom_css);

        if (empty($css)) {
            return;
        }
        $css = str_replace('selector', $this->getElementUniqueSelector($element), $css);

        // Add a css comment
        $css = sprintf('/* Start custom CSS for %s, class: %s */', $element->getName(), $element->getUniqueSelector()) . $css . '/* End custom CSS */';

        $this->stylesheet_obj->addRawCss($css);
    }

    public function addPageSettingsCss($custom_css)
    {
        $custom_css = trim($custom_css);

        if (empty($custom_css)) {
            return;
        }

        $custom_css = str_replace('selector', 'body.elementor-page-' . $this->post_id, $custom_css);

        // Add a css comment
        $custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

        $this->stylesheet_obj->addRawCss($custom_css);
    }
}
