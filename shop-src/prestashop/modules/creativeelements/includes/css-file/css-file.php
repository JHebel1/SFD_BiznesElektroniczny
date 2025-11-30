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

abstract class CssFile
{
    const FILE_BASE_DIR = 'modules/creativeelements/views/css/ce';
    // %s: Base folder; %s: file name
    const FILE_NAME_PATTERN = '%s/%s.css';

    const CSS_STATUS_FILE = 'file';

    const CSS_STATUS_INLINE = 'inline';

    const CSS_STATUS_EMPTY = 'empty';

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $css = '';

    /**
     * @var array
     */
    private $fonts = array();

    /**
     * @var Stylesheet
     */
    protected $stylesheet_obj;

    public function __construct()
    {
        $this->setPathAndUrl();

        $this->initStylesheet();
    }

    public function update()
    {
        $this->parseCss();

        $meta = array(
            'time' => time(),
            'fonts' => array_unique($this->fonts),
        );

        if (empty($this->css)) {
            $this->delete();

            $meta['status'] = self::CSS_STATUS_EMPTY;
            $meta['css'] = '';
        } else {
            $file_created = false;
            $is_external_file = ('internal' !== get_option('elementor_css_print_method'));

            if ($is_external_file && is_writable(dirname($this->path))) {
                $file_created = file_put_contents($this->path, $this->css);
            }

            if ($file_created) {
                $meta['status'] = self::CSS_STATUS_FILE;
            } else {
                $meta['status'] = self::CSS_STATUS_INLINE;
                $meta['css'] = $this->css;
            }
        }

        $this->updateMeta($meta);
    }

    public function delete()
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }
    }

    public function enqueue()
    {
        $meta = $this->getMeta();

        if (self::CSS_STATUS_EMPTY === $meta['status']) {
            return;
        }

        // First time after clear cache and etc.
        if ('' === $meta['status'] || $this->isUpdateRequired()) {
            $this->update();

            $meta = $this->getMeta();
        }

        if (self::CSS_STATUS_INLINE === $meta['status']) {
            $id = '_elementor_global_css' === static::META_KEY ? ' id="elementor-global-css"' : '';
            $css = "<style$id>{$meta['css']}</style>";
        } else {
            $css = "<link rel=\"stylesheet\" href=\"{$this->url}?{$meta['time']}\">";
        }

        Helper::$enqueue_css[] = $css;

        // Handle fonts
        if (!empty($meta['fonts'])) {
            foreach ($meta['fonts'] as $font) {
                Plugin::$instance->frontend->addEnqueueFont($font);
            }
        }
    }

    /**
     * @param array $control
     * @param array $controls_stack
     * @param callable $value_callback
     * @param array $placeholders
     * @param array $replacements
     */
    public function addControlRules(array $control, array $controls_stack, callable $value_callback, array $placeholders, array $replacements)
    {
        $value = call_user_func($value_callback, $control);

        if (null === $value) {
            return;
        }

        foreach ($control['selectors'] as $selector => $css_property) {
            try {
                $output_css_property = preg_replace_callback('/\{\{(?:([^.}]+)\.)?([^}]*)}}/', function ($matches) use ($control, $value_callback, $controls_stack, $value, $css_property) {
                    $parser_control = $control;

                    $value_to_insert = $value;

                    if (!empty($matches[1])) {
                        $parser_control = $controls_stack[$matches[1]];

                        $value_to_insert = call_user_func($value_callback, $parser_control);
                    }

                    if (ControlsManager::FONT === $control['type']) {
                        ${'this'}->fonts[] = $value_to_insert;
                    }

                    $control_obj = Plugin::$instance->controls_manager->getControl($parser_control['type']);

                    $parsed_value = $control_obj->getStyleValue(\Tools::strtolower($matches[2]), $value_to_insert);

                    if ('' === $parsed_value) {
                        throw new \Exception();
                    }

                    return $parsed_value;
                }, $css_property);
            } catch (\Exception $e) {
                return;
            }

            if (!$output_css_property) {
                continue;
            }

            $device_pattern = '/^(?:\([^\)]+\)){1,2}/';

            preg_match($device_pattern, $selector, $device_rules);

            $query = array();

            if ($device_rules) {
                $selector = preg_replace($device_pattern, '', $selector);

                preg_match_all('/\(([^\)]+)\)/', $device_rules[0], $pure_device_rules);

                $pure_device_rules = $pure_device_rules[1];

                foreach ($pure_device_rules as $device_rule) {
                    if (ElementBase::RESPONSIVE_DESKTOP === $device_rule) {
                        continue;
                    }

                    $device = preg_replace('/\+$/', '', $device_rule);

                    $endpoint = $device === $device_rule ? 'max' : 'min';

                    $query[$endpoint] = $device;
                }
            }

            $parsed_selector = str_replace($placeholders, $replacements, $selector);

            if (!$query && !empty($control['responsive'])) {
                $query = $control['responsive'];

                if (!empty($query['max']) && ElementBase::RESPONSIVE_DESKTOP === $query['max']) {
                    unset($query['max']);
                }
            }

            $this->stylesheet_obj->addRules($parsed_selector, $output_css_property, $query);
        }
    }

    /**
     * @return string
     */
    public function getCss()
    {
        if (empty($this->css)) {
            $this->parseCss();
        }

        return $this->css;
    }

    /**
     * @return Stylesheet
     */
    public function getStylesheet()
    {
        return $this->stylesheet_obj;
    }

    public function getMeta($property = null)
    {
        $defaults = array(
            'status' => '',
            'time' => 0,
        );

        $meta = array_merge($defaults, (array) $this->loadMeta());

        if ($property) {
            return isset($meta[$property]) ? $meta[$property] : null;
        }

        return $meta;
    }

    /**
     * @return array
     */
    abstract protected function loadMeta();

    /**
     * @param string $meta
     */
    abstract protected function updateMeta($meta);

    /**
     * @return string
     */
    abstract protected function getFileHandleId();

    abstract protected function renderCss();

    /**
     * @return string
     */
    abstract protected function getFileName();

    /**
     * @return array
     */
    protected function getEnqueueDependencies()
    {
        return array();
    }

    /**
     * @return string
     */
    protected function getInlineDependency()
    {
        return '';
    }

    /**
     * @return bool
     */
    protected function isUpdateRequired()
    {
        return false;
    }

    private function initStylesheet()
    {
        $this->stylesheet_obj = new Stylesheet();

        $breakpoints = Responsive::getBreakpoints();

        $this->stylesheet_obj
            ->addDevice('mobile', 0)
            ->addDevice('tablet', $breakpoints['md'])
            ->addDevice('desktop', $breakpoints['lg']);
    }

    private function setPathAndUrl()
    {
        $relative_path = sprintf(self::FILE_NAME_PATTERN, self::FILE_BASE_DIR, $this->getFileName());

        $this->path = _PS_ROOT_DIR_ . '/' . $relative_path;

        $this->url = __PS_BASE_URI__ . $relative_path;
    }

    private function parseCss()
    {
        $this->renderCss();

        $this->css = $this->stylesheet_obj->__toString();
    }
}
