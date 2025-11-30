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

class SchemeColor extends SchemeBase
{
    const COLOR_1 = '1';
    const COLOR_2 = '2';
    const COLOR_3 = '3';
    const COLOR_4 = '4';

    public static function getType()
    {
        return 'color';
    }

    public function getTitle()
    {
        return __('Colors', 'elementor');
    }

    public function getDisabledTitle()
    {
        return __('Color Palettes', 'elementor');
    }

    public function getSchemeTitles()
    {
        return array(
            self::COLOR_1 => __('Primary', 'elementor'),
            self::COLOR_2 => __('Secondary', 'elementor'),
            self::COLOR_3 => __('Text', 'elementor'),
            self::COLOR_4 => __('Accent', 'elementor'),
        );
    }

    public function getDefaultScheme()
    {
        return array(
            self::COLOR_1 => '#6ec1e4',
            self::COLOR_2 => '#54595f',
            self::COLOR_3 => '#7a7a7a',
            self::COLOR_4 => '#61ce70',
        );
    }

    public function printTemplateContent()
    {
        ?>
        <div class="elementor-panel-scheme-content elementor-panel-box">
            <div class="elementor-panel-heading">
                <div class="elementor-panel-heading-title"><?php echo $this->_getCurrentSchemeTitle(); ?></div>
            </div>
            <?php if ($description = static::getDescription()) : ?>
                <div class="elementor-panel-scheme-description elementor-descriptor"><?php echo $description; ?></div>
            <?php endif;?>
            <div class="elementor-panel-scheme-items elementor-panel-box-content"></div>
        </div>
        <div class="elementor-panel-scheme-colors-more-palettes elementor-panel-box">
            <div class="elementor-panel-heading">
                <div class="elementor-panel-heading-title"><?php _e('More Palettes', 'elementor');?></div>
            </div>
            <div class="elementor-panel-box-content">
                <?php foreach ($this->_getSystemSchemesToPrint() as $scheme_name => $scheme) : ?>
                    <div class="elementor-panel-scheme-color-system-scheme" data-scheme-name="<?php echo $scheme_name; ?>">
                        <div class="elementor-panel-scheme-color-system-items">
                            <?php foreach ($scheme['items'] as $color_value) : ?>
                                <div class="elementor-panel-scheme-color-system-item" style="background-color: <?php echo esc_attr($color_value); ?>;"></div>
                            <?php endforeach;?>
                        </div>
                        <div class="elementor-title"><?php echo $scheme['title']; ?></div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <?php
    }

    protected function _initSystemSchemes()
    {
        return array(
            'joker' => array(
                'title' => 'Joker',
                'items' => array(
                    self::COLOR_1 => '#202020',
                    self::COLOR_2 => '#b7b4b4',
                    self::COLOR_3 => '#707070',
                    self::COLOR_4 => '#f6121c',
                ),
            ),
            'ocean' => array(
                'title' => 'Ocean',
                'items' => array(
                    self::COLOR_1 => '#1569ae',
                    self::COLOR_2 => '#b6c9db',
                    self::COLOR_3 => '#545454',
                    self::COLOR_4 => '#fdd247',
                ),
            ),
            'royal' => array(
                'title' => 'Royal',
                'items' => array(
                    self::COLOR_1 => '#d5ba7f',
                    self::COLOR_2 => '#902729',
                    self::COLOR_3 => '#ae4848',
                    self::COLOR_4 => '#302a8c',
                ),
            ),
            'violet' => array(
                'title' => 'Violet',
                'items' => array(
                    self::COLOR_1 => '#747476',
                    self::COLOR_2 => '#ebca41',
                    self::COLOR_3 => '#6f1683',
                    self::COLOR_4 => '#a43cbd',
                ),
            ),
            'sweet' => array(
                'title' => 'Sweet',
                'items' => array(
                    self::COLOR_1 => '#6ccdd9',
                    self::COLOR_2 => '#763572',
                    self::COLOR_3 => '#919ca7',
                    self::COLOR_4 => '#f12184',
                ),
            ),
            'urban' => array(
                'title' => 'Urban',
                'items' => array(
                    self::COLOR_1 => '#db6159',
                    self::COLOR_2 => '#3b3b3b',
                    self::COLOR_3 => '#7a7979',
                    self::COLOR_4 => '#2abf64',
                ),
            ),
            'earth' => array(
                'title' => 'Earth',
                'items' => array(
                    self::COLOR_1 => '#882021',
                    self::COLOR_2 => '#c48e4c',
                    self::COLOR_3 => '#825e24',
                    self::COLOR_4 => '#e8c12f',
                ),
            ),
            'river' => array(
                'title' => 'River',
                'items' => array(
                    self::COLOR_1 => '#8dcfc8',
                    self::COLOR_2 => '#565656',
                    self::COLOR_3 => '#50656e',
                    self::COLOR_4 => '#dc5049',
                ),
            ),
            'pastel' => array(
                'title' => 'Pastel',
                'items' => array(
                    self::COLOR_1 => '#f27f6f',
                    self::COLOR_2 => '#f4cd78',
                    self::COLOR_3 => '#a5b3c1',
                    self::COLOR_4 => '#aac9c3',
                ),
            ),
        );
    }

    protected function _getSystemSchemesToPrint()
    {
        return $this->getSystemSchemes();
    }

    protected function _getCurrentSchemeTitle()
    {
        return __('Color Palette', 'elementor');
    }
}
