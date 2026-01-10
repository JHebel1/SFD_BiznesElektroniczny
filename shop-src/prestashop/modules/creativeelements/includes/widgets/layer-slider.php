<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

namespace CE;

defined('_PS_VERSION_') or die;

class WidgetLayerSlider extends WidgetBase
{
    protected $module;

    public function getName()
    {
        return 'ps-widget-LayerSlider';
    }

    public function getTitle()
    {
        return __('Creative Slider', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-slideshow';
    }

    public function getCategories()
    {
        return array('premium');
    }

    protected function getSliderOptions()
    {
        if (!$this->module) {
            return false;
        }
        $table = _DB_PREFIX_ . 'layerslider';
        $sliders = \Db::getInstance()->executeS(
            "SELECT id, name FROM $table WHERE flag_hidden = 0 AND flag_deleted = 0 LIMIT 100"
        );
        $opts = array(
            '0' => __('- Select Slider -', 'elementor'),
        );
        if (!empty($sliders)) {
            foreach ($sliders as &$slider) {
                $name = empty($slider['name']) ? 'Unnamed' : $slider['name'];
                $opts[$slider['id']] = "#{$slider['id']} - $name";
            }
        }

        return $opts;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_layerslider',
            array(
                'label' => __('Creative Slider', 'elementor'),
            )
        );

        if ($this->module) {
            $context = \Context::getContext();
            $lsUrl = empty($context->employee) ? '#' : $context->link->getAdminLink('AdminLayerSlider');

            $this->addControl(
                'ls-new',
                array(
                    'raw' => '
                        <button class="elementor-button elementor-button-default" style="width: 100%;">
                            <i class="fa fa-plus"></i> ' . __('Create New Slider', 'elementor') . '
                        </button>
                        <div class="elementor-control-description" style="position: absolute; z-index: 1; left: 50%; background: #fff; padding: 0 5px; transform: translate(-50%, 8px);">
                            ' . __('or', 'elementor') . '
                        </div>
                    ',
                    'type' => ControlsManager::RAW_HTML,
                )
            );

            $this->addControl(
                'slider',
                array(
                    'classes' => 'ls-selector',
                    'label' => __('Slider', 'elementor'),
                    'type' => ControlsManager::SELECT,
                    'options' => $this->getSliderOptions(),
                    'default' => '0',
                )
            );

            $this->addControl(
                'ls-edit',
                array(
                    'raw' =>
                        '<button class="elementor-button elementor-button-default" style="margin-left: 45%; width: 55%;">' .
                            '<i class="fa fa-edit"></i> ' . __('Edit Slider', 'elementor') .
                        '</button>',
                    'type' => ControlsManager::RAW_HTML,
                    'separator' => '',
                    'condition' => array(
                        'slider!' => '0',
                    ),
                )
            );
        } else {
            $this->addControl(
                'ls-alert',
                array(
                    'raw' => '
                        <div style="background: #d1eff8; border: 1px solid #bcdff1; border-radius: 4px; padding: 20px; font-size: 12px; text-align: center; color: #43a2bf;">
                            <svg width="40" viewBox="0 0 259.559 259.559" fill="currentColor">
                                <polygon points="186.811,106.547 129.803,218.647 73.273,106.547"/><polygon points="78.548,94.614 129.779,43.382 181.011,94.614"/>
                                <polygon points="144.183,40.912 213.507,40.912 193.941,90.67"/><polygon points="66.375,89.912 50.044,40.912 115.375,40.912"/>
                                <polygon points="59.913,106.547 109.546,204.977 3.288,106.547"/><polygon points="200.2,106.547 256.271,106.547 150.258,204.75"/>
                                <polygon points="205.213,94.614 223.907,47.082 259.559,94.614"/><polygon points="38.331,43.507 55.373,94.614 0,94.614"/>
                            </svg>
                            <h3 style="margin: 5px 0 13px; font-size: 13px; font-weight: bold;">Do you need an awesome slider?</h3>
                            <p style="line-height: 1.3em">Creative Slider is the perfect choice for you. With this widget you can easily place Creative Slider anywhere.</p>
                        </div>
                    ',
                    'type' => ControlsManager::RAW_HTML,
                )
            );

            $this->addControl(
                'ls-promo',
                array(
                    'raw' => $this->getPromo(),
                    'type' => ControlsManager::RAW_HTML,
                )
            );

            $this->addControl('slider', array('type' => ControlsManager::HIDDEN));
        }

        $this->addControl(
            'view',
            array(
                'label' => __('View', 'elementor'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'traditional',
            )
        );

        $this->endControlsSection();
    }

    protected function getPromo()
    {
        ob_start();
        $iso = \Context::getContext()->language->iso_code;
        $more = "https://addons.prestashop.com/$iso/sliders-galleries/19062-creative-slider-responsive-slideshow.html";
        $demo = 'https://addons.prestashop.com/demo/FO11013.html';
        ?>
        <style>
        #ls-btn-demo, #ls-btn-more {
            display: inline-block;
            width: 48%;
            text-align: center;
        }
        #ls-btn-demo { background: #38b54a; }
        #ls-btn-demo:hover { opacity: 0.85; }
        #ls-btn-more { margin-left: 4%; }
        </style>
        <a href="<?php echo $demo; ?>" target="_blank" id="ls-btn-demo" class="elementor-button elementor-button-default"><?php _e('Live Demo') ?></a
        ><a href="<?php echo $more; ?>" target="_blank" id="ls-btn-more" class="elementor-button elementor-button-default"><?php _e('Read More') ?></a>
        <?php
        return ob_get_clean();
    }

    protected function render()
    {
        if (!$this->module) {
            return;
        }
        if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }
        $id = (int) $this->getSettings('slider');

        if (!empty($id)) {
            $slider = $this->module->generateSlider($id);

            if (\Tools::getValue('render') == 'widget') {
                $this->patchInitScript($slider, $id);
            }
            echo $slider;
        }
    }

    protected function patchInitScript(&$slider, $id)
    {
        $suffix = '_' . time();
        $slider = str_replace("layerslider_$id", "layerslider_$id$suffix", $slider);
        ob_start();
        ?>
        <script>
        var js = $('#layerslider_<?php echo $id . $suffix ?>').prev().html() || '';
        if (js = js.match(/{([^]*)}/)) eval(js[1]);
        </script>
        <?php
        $slider .= ob_get_clean();
    }

    protected function _contentTemplate()
    {
        if ($this->module) {
            return;
        }
        echo "<\x69frame src=\"https://creativeslider.webshopworks.com/promo-slider.html\" " .
            "style=\"width: 100%; height: 66vh; border: none;\"></\x69frame>";
    }

    public function __construct($data = array(), $args = array())
    {
        $ls = \Module::getInstanceByName('layerslider');

        if (!empty($ls->active)) {
            $this->module = $ls;

            $context = \Context::getContext();

            empty($context->employee) or Helper::$body_scripts['ce-layerslider'] = array(
                'l10n' => array(
                    'ls' => array(
                        'url' => $context->link->getAdminLink('AdminLayerSlider'),
                        'NameYourSlider' => __('Name your new slider'),
                        'ChangesYouMadeMayNotBeSaved' => __('Changes you made may not be saved, are you sure you want to close?'),
                    )
                ),
            );
        }
        parent::__construct($data, $args);
    }
}
