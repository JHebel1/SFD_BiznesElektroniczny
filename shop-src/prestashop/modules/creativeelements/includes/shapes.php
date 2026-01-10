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

class Shapes
{
    const FILTER_EXCLUDE = 'exclude';

    const FILTER_INCLUDE = 'include';

    private static $shapes;

    public static function getShapes($shape = null)
    {
        if (null === self::$shapes) {
            self::initShapes();
        }

        if ($shape) {
            return isset(self::$shapes[$shape]) ? self::$shapes[$shape] : null;
        }

        return self::$shapes;
    }

    public static function filterShapes($by, $filter = self::FILTER_INCLUDE)
    {
        return array_filter(self::getShapes(), function ($shape) use ($by, $filter) {
            return self::FILTER_INCLUDE === $filter xor empty($shape[$by]);
        });
    }

    public static function getShapePath($shape, $is_negative = false)
    {
        $file_name = $shape;

        if ($is_negative) {
            $file_name .= '-negative';
        }

        return _CE_PATH_ . 'views/img/shapes/' . $file_name . '.svg';
    }

    private static function initShapes()
    {
        self::$shapes = array(
            'mountains' => array(
                'title' => _x('Mountains', 'Shapes', 'elementor'),
                'has_flip' => true,
            ),
            'drops' => array(
                'title' => _x('Drops', 'Shapes', 'elementor'),
                'has_negative' => true,
                'has_flip' => true,
                'height_only' => true,
            ),
            'clouds' => array(
                'title' => _x('Clouds', 'Shapes', 'elementor'),
                'has_negative' => true,
                'has_flip' => true,
                'height_only' => true,
            ),
            'zigzag' => array(
                'title' => _x('Zigzag', 'Shapes', 'elementor'),
            ),
            'pyramids' => array(
                'title' => _x('Pyramids', 'Shapes', 'elementor'),
                'has_negative' => true,
                'has_flip' => true,
            ),
            'triangle' => array(
                'title' => _x('Triangle', 'Shapes', 'elementor'),
                'has_negative' => true,
            ),
            'triangle-asymmetrical' => array(
                'title' => _x('Triangle Asymmetrical', 'Shapes', 'elementor'),
                'has_negative' => true,
                'has_flip' => true,
            ),
            'tilt' => array(
                'title' => _x('Tilt', 'Shapes', 'elementor'),
                'has_flip' => true,
                'height_only' => true,
            ),
            'opacity-tilt' => array(
                'title' => _x('Tilt Opacity', 'Shapes', 'elementor'),
                'has_flip' => true,
            ),
            'opacity-fan' => array(
                'title' => _x('Fan Opacity', 'Shapes', 'elementor'),
            ),
            'curve' => array(
                'title' => _x('Curve', 'Shapes', 'elementor'),
                'has_negative' => true,
            ),
            'curve-asymmetrical' => array(
                'title' => _x('Curve Asymmetrical', 'Shapes', 'elementor'),
                'has_negative' => true,
                'has_flip' => true,
            ),
            'waves' => array(
                'title' => _x('Waves', 'Shapes', 'elementor'),
                'has_negative' => true,
                'has_flip' => true,
            ),
            'wave-brush' => array(
                'title' => _x('Waves Brush', 'Shapes', 'elementor'),
                'has_flip' => true,
            ),
            'waves-pattern' => array(
                'title' => _x('Waves Pattern', 'Shapes', 'elementor'),
                'has_flip' => true,
            ),
            'arrow' => array(
                'title' => _x('Arrow', 'Shapes', 'elementor'),
                'has_negative' => true,
            ),
            'split' => array(
                'title' => _x('Split', 'Shapes', 'elementor'),
                'has_negative' => true,
            ),
            'book' => array(
                'title' => _x('Book', 'Shapes', 'elementor'),
                'has_negative' => true,
            ),
        );
    }
}
