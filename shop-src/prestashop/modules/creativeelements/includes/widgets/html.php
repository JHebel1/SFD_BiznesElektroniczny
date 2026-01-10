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

class WidgetHtml extends WidgetBase
{
    public function getName()
    {
        return 'html';
    }

    public function getTitle()
    {
        return __('HTML', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-coding';
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
                'label' => __('HTML Code', 'elementor'),
            )
        );

        $this->addControl(
            'html',
            array(
                'label' => '',
                'type' => ControlsManager::CODE,
                'default' => '',
                'placeholder' => __('Enter your embed code here', 'elementor'),
                'show_label' => false,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        echo $this->getSettings('html');
    }

    protected function _contentTemplate()
    {
        ?>
        {{{ settings.html }}}
        <?php
    }
}
