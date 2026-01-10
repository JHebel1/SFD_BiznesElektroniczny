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

class WidgetShortcode extends WidgetBase
{
    public function getName()
    {
        return 'shortcode';
    }

    public function getTitle()
    {
        return __('Shortcode', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-shortcode';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_title',
            array(
                'label' => __('Shortcode', 'elementor'),
            )
        );

        $this->addControl(
            'shortcode',
            array(
                'label' => __('Insert your shortcode here', 'elementor'),
                'type' => ControlsManager::TEXTAREA,
                'placeholder' => "{hook h='displayShortcode'}",
                'default' => '',
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }
        $shortcode = do_shortcode($this->getSettings('shortcode'));
        ?>
        <div class="elementor-shortcode"><?php echo $shortcode; ?></div>
        <?php
    }

    public function renderPlainContent()
    {
        // In plain mode, render without shortcode
        echo $this->getSettings('shortcode');
    }
}
