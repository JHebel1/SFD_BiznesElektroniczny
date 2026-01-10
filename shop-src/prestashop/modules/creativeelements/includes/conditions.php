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

class Conditions
{
    public static function compare($left_value, $right_value, $operator)
    {
        switch ($operator) {
            case '==':
                return $left_value == $right_value;
            case '!=':
                return $left_value != $right_value;
            case '!==':
                return $left_value !== $right_value;
            case 'in':
                return in_array($left_value, $right_value);
            case '!in':
                return !in_array($left_value, $right_value);
            case 'contains':
                return !empty($left_value) && in_array($right_value, $left_value);
            case '!contains':
                return empty($left_value) || !in_array($right_value, $left_value);
            case '<':
                return $left_value < $right_value;
            case '<=':
                return $left_value <= $right_value;
            case '>':
                return $left_value > $right_value;
            case '>=':
                return $left_value >= $right_value;
            default:
                return $left_value === $right_value;
        }
    }

    public static function check(array $conditions, array $comparison)
    {
        $is_or_condition = isset($conditions['relation']) && 'or' === $conditions['relation'];

        $condition_succeed = !$is_or_condition;

        foreach ($conditions['terms'] as $term) {
            if (!empty($term['terms'])) {
                $comparison_result = self::check($term, $conditions);
            } else {
                preg_match('/(\w+)(?:\[(\w+)])?/', $term['name'], $parsed_name);

                $value = $comparison[$parsed_name[1]];

                if (!empty($parsed_name[2])) {
                    $value = isset($value[$parsed_name[2]]) ? $value[$parsed_name[2]] : null;
                }

                $comparison_result = self::compare($value, $term['value'], empty($term['operator']) ? '===' : $term['operator']);
            }

            if ($is_or_condition) {
                if ($comparison_result) {
                    return true;
                }
            } elseif (!$comparison_result) {
                return false;
            }
        }

        return $condition_succeed;
    }
}
