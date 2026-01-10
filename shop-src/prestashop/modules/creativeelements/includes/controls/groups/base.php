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

abstract class GroupControlBase implements GroupControlInterface
{
    private $args = array();

    final public function addControls(ControlsStack $element, array $user_args)
    {
        $this->initArgs($user_args);

        // Filter witch controls to display
        $filtered_fields = $this->filterFields();

        $filtered_fields = $this->prepareFields($filtered_fields);

        foreach ($filtered_fields as $field_id => $field_args) {
            // Add the global group args to the control
            $field_args = $this->addGroupArgsToField($field_id, $field_args);

            // Register the control
            $id = $this->getControlsPrefix() . $field_id;

            if (!empty($field_args['responsive'])) {
                unset($field_args['responsive']);

                $element->addResponsiveControl($id, $field_args);
            } else {
                $element->addControl($id, $field_args);
            }
        }
    }

    final public function getArgs()
    {
        return $this->args;
    }

    final public function getFields()
    {
        // TODO: Temp - compatibility for posts group
        if (method_exists($this, '_get_controls')) {
            return $this->_getControls($this->getArgs());
        }

        if (null === static::$fields) {
            static::$fields = $this->initFields();
        }

        return static::$fields;
    }

    public function getControlsPrefix()
    {
        return $this->args['name'] . '_';
    }

    public function getBaseGroupClasses()
    {
        return 'elementor-group-control-' . static::getType() . ' elementor-group-control';
    }

    // TODO: Temp - Make it abstract
    protected function initFields()
    {
    }

    protected function getChildDefaultArgs()
    {
        return array();
    }

    protected function filterFields()
    {
        $args = $this->getArgs();

        $fields = $this->getFields();

        if (!empty($args['include'])) {
            $fields = array_intersect_key($fields, array_flip($args['include']));
        }

        if (!empty($args['exclude'])) {
            $fields = array_diff_key($fields, array_flip($args['exclude']));
        }

        foreach ($fields as $field_key => $field) {
            if (empty($field['condition'])) {
                continue;
            }

            foreach ($field['condition'] as $condition_key => $condition_value) {
                preg_match('/^\w+/', $condition_key, $matches);

                if (empty($fields[$matches[0]])) {
                    unset($fields[$field_key]);

                    continue 2;
                }
            }
        }

        return $fields;
    }

    protected function addGroupArgsToField($control_id, $field_args)
    {
        $args = $this->getArgs();

        if (!empty($args['tab'])) {
            $field_args['tab'] = $args['tab'];
        }

        if (!empty($args['section'])) {
            $field_args['section'] = $args['section'];
        }

        $field_args['classes'] = $this->getBaseGroupClasses() . ' elementor-group-control-' . $control_id;

        if (!empty($args['condition'])) {
            if (empty($field_args['condition'])) {
                $field_args['condition'] = array();
            }

            $field_args['condition'] += $args['condition'];
        }

        return $field_args;
    }

    protected function prepareFields($fields)
    {
        foreach ($fields as &$field) {
            if (!empty($field['condition'])) {
                $field = $this->addConditionsPrefix($field);
            }

            if (!empty($field['selectors'])) {
                $field['selectors'] = $this->handleSelectors($field['selectors']);
            }
        }

        return $fields;
    }

    private function initArgs($args)
    {
        $this->args = array_merge($this->getDefaultArgs(), $this->getChildDefaultArgs(), $args);
    }

    private function getDefaultArgs()
    {
        return array(
            'default' => '',
            'selector' => '{{WRAPPER}}',
        );
    }

    private function addConditionsPrefix($field)
    {
        $controls_prefix = $this->getControlsPrefix();

        $prefixed_condition_keys = array_map(
            function ($key) use ($controls_prefix) {
                return $controls_prefix . $key;
            },
            array_keys($field['condition'])
        );

        $field['condition'] = array_combine(
            $prefixed_condition_keys,
            $field['condition']
        );

        return $field;
    }

    private function handleSelectors($selectors)
    {
        $args = $this->getArgs();

        $selectors = array_combine(
            array_map(function ($key) use ($args) {
                return str_replace('{{SELECTOR}}', $args['selector'], $key);
            }, array_keys($selectors)),
            $selectors
        );

        if (!$selectors) {
            return $selectors;
        }

        $controls_prefix = $this->getControlsPrefix();

        foreach ($selectors as &$selector) {
            $selector = preg_replace_callback('/(?:\{\{)\K[^.}]+(?=\.[^}]*}})/', function ($matches) use ($controls_prefix) {
                return $controls_prefix . $matches[0];
            }, $selector);
        }

        return $selectors;
    }
}
