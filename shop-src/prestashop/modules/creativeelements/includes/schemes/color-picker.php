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

class SchemeColorPicker extends SchemeColor
{
    const COLOR_5 = '5';
    const COLOR_6 = '6';
    const COLOR_7 = '7';
    const COLOR_8 = '8';

    public static function getType()
    {
        return 'color-picker';
    }

    public static function getDescription()
    {
        return __('Choose which colors appear in the editor\'s color picker. This makes accessing the colors you chose for the site much easier.', 'elementor');
    }

    public function getDefaultScheme()
    {
        return array_replace(parent::getDefaultScheme(), array(
            self::COLOR_5 => '#4054b2',
            self::COLOR_6 => '#23a455',
            self::COLOR_7 => '#000',
            self::COLOR_8 => '#fff',
        ));
    }

    public function getSchemeTitles()
    {
        return array();
    }

    protected function _initSystemSchemes()
    {
        $schemes = parent::_initSystemSchemes();

        $additional_schemes = array(
            'joker' => array(
                'items' => array(
                    self::COLOR_5 => '#4b4646',
                    self::COLOR_6 => '#e2e2e2',
                ),
            ),
            'ocean' => array(
                'items' => array(
                    self::COLOR_5 => '#154d80',
                    self::COLOR_6 => '#8c8c8c',
                ),
            ),
            'royal' => array(
                'items' => array(
                    self::COLOR_5 => '#ac8e4d',
                    self::COLOR_6 => '#e2cea1',
                ),
            ),
            'violet' => array(
                'items' => array(
                    self::COLOR_5 => '#9c9ea6',
                    self::COLOR_6 => '#c184d0',
                ),
            ),
            'sweet' => array(
                'items' => array(
                    self::COLOR_5 => '#41aab9',
                    self::COLOR_6 => '#ffc72f',
                ),
            ),
            'urban' => array(
                'items' => array(
                    self::COLOR_5 => '#aa4039',
                    self::COLOR_6 => '#94dbaf',
                ),
            ),
            'earth' => array(
                'items' => array(
                    self::COLOR_5 => '#aa6666',
                    self::COLOR_6 => '#efe5d9',
                ),
            ),
            'river' => array(
                'items' => array(
                    self::COLOR_5 => '#7b8c93',
                    self::COLOR_6 => '#eb6d65',
                ),
            ),
            'pastel' => array(
                'items' => array(
                    self::COLOR_5 => '#f5a46c',
                    self::COLOR_6 => '#6e6f71',
                ),
            ),
        );

        $schemes = array_replace_recursive($schemes, $additional_schemes);

        foreach ($schemes as &$scheme) {
            $scheme['items'] += array(
                self::COLOR_7 => '#000',
                self::COLOR_8 => '#fff',
            );
        }

        return $schemes;
    }

    protected function _getSystemSchemesToPrint()
    {
        $schemes = $this->getSystemSchemes();

        $items_to_print = array(
            self::COLOR_1,
            self::COLOR_5,
            self::COLOR_2,
            self::COLOR_3,
            self::COLOR_6,
            self::COLOR_4,
        );

        $items_to_print = array_flip($items_to_print);

        foreach ($schemes as $scheme_key => $scheme) {
            $schemes[$scheme_key]['items'] = array_replace($items_to_print, array_intersect_key($scheme['items'], $items_to_print));
        }

        return $schemes;
    }

    protected function _getCurrentSchemeTitle()
    {
        return __('Color Picker', 'elementor');
    }
}
