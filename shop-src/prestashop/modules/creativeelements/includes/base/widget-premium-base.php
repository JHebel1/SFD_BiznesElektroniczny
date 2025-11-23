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

abstract class WidgetPremiumBase extends WidgetBase
{
	protected $buyLicenseLink = 'https://addons.prestashop.com/administrative-tools/44064-creative-elements-elementor-based-pagebuilder.html';

	abstract public function getDemoLink();

    public function getCategories()
    {
        return array('premium');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_premium',
            array(
                'label' => $this->getTitle(),
            )
        );

        $this->addControl(
            'notification',
            array(
                'raw' =>
                    '<div class="premium-notification">' .
                        '<svg width="40" viewBox="0 0 259.559 259.559" fill="currentColor">' .
                            '<polygon points="186.811,106.547 129.803,218.647 73.273,106.547"/><polygon points="78.548,94.614 129.779,43.382 181.011,94.614"/>' .
                            '<polygon points="144.183,40.912 213.507,40.912 193.941,90.67"/><polygon points="66.375,89.912 50.044,40.912 115.375,40.912"/>' .
                            '<polygon points="59.913,106.547 109.546,204.977 3.288,106.547"/><polygon points="200.2,106.547 256.271,106.547 150.258,204.75"/>' .
                            '<polygon points="205.213,94.614 223.907,47.082 259.559,94.614"/><polygon points="38.331,43.507 55.373,94.614 0,94.614"/>' .
                        '</svg>' .
                        '<div class="premium-notification-title">' . __('Premium Widget') . '</div>' .
                        '<p style="line-height: 1.3em">' .
                        	sprintf(__('Use %s widget and dozens more premium features to extend your toolbox and build sites faster and better.'), $this->getTitle()) .
                        '</p>' .
                    '</div>',
                'type' => ControlsManager::RAW_HTML,
            )
        );

        $this->addControl(
            'buttons',
            array(
            	'classes' => 'premium-buttons',
                'raw' =>
                	'<a href="' . esc_attr($this->getDemoLink()) . '" target="_blank" class="elementor-button elementor-button-success">' . __('See it in action') . '</a>' .
                	'<a href="' . esc_attr($this->buyLicenseLink) . '" target="_blank" class="elementor-button elementor-button-default">' . __('Buy License') . '</a>',
                'type' => ControlsManager::RAW_HTML,
            )
        );

        $this->endControlsSection();
    }

    protected function _contentTemplate()
    {
        echo '<!-- Premium Widget -->';
    }
}
