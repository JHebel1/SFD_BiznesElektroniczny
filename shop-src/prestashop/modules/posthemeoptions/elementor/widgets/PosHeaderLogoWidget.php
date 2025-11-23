<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Configuration;

class PosHeaderLogoWidget extends WidgetBase { 

	public function getName() {
		return 'posLogo';
	}
	public function getTitle() {
		return $this->l( 'Logo' );
	}

	public function getIcon() {
		return 'fa fa-eercast';
	}

	public function getCategories() {
		return [ 'posthemes_header' ];
	}

	protected function _registerControls() {
		
		$this->startControlsSection(
			'content_section',
			[
				'label' => $this->l( 'Content' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addResponsiveControl(
            'text_align',
	            [
	                'label' => __('Alignment'),
	                'type' => ControlsManager::CHOOSE,
	                'options' => [
	                    'left' => [
	                        'title' => $this->l('Left'),
	                        'icon' => 'fa fa-align-left',
	                    ],
	                    'center' => [
	                        'title' => $this->l('Center'),
	                        'icon' => 'fa fa-align-center',
	                    ],
	                    'right' => [
	                        'title' => $this->l('Right'),
	                        'icon' => 'fa fa-align-right',
	                    ],
	                ],
	                'selectors' => [
	                    '{{WRAPPER}}.elementor-widget-posLogo' => 'text-align: {{VALUE}};',
	                ],
	            ]
	        );
	        $this->addControl(
            	'logo-maxwidth',
	            [
	                'label' => $this->l('Max width'),
	                'type' => ControlsManager::SLIDER,
	                'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 500,
                        ],
                    ],
	                'selectors' => [
	                    '{{WRAPPER}} .site-logo img' => 'max-width: {{SIZE}}{{UNIT}}',
	                ],
	            ]
	        );
		$this->endControlsSection();

	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	protected function render() {
		$context = Context::getContext();
		$settings = $this->getSettings(); 
		$logo = Configuration::hasKey('PS_LOGO') ? _PS_IMG_ . Configuration::get('PS_LOGO') : '';
		$shop_name = Configuration::get('PS_SHOP_NAME');
		$index_url= $context->link->getPageLink('index', true);
		if($logo){
			?>
			<div id="_desktop_logo">
				<a href="<?= $index_url ?>" class="site-logo">
					<img src="<?= $logo ?>" alt="<?= $shop_name ?>" />
				</a>
			</div>
			<?php
		}else{
			echo 'No logo';
		}
		
	} 
	/**
     * Get translation for a given widget text
     *
     * @access protected
     *
     * @param string $string    String to translate
     *
     * @return string Translation
     */
    protected function l($string)
    {
        return translate($string, 'posthemeoptions', basename(__FILE__, '.php'));
    }
}