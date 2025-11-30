<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Posthemes\Module\Poselements\WidgetHelper;

class PosHeaderSettingsWidget extends WidgetHelper { 

	public function getName() {
		return 'pos_settings';
	}
	public function getTitle() {
		return $this->l( 'Settings item' );
	}

	public function getIcon() {
		return 'fa fa-cogs';
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
			$this->addControl(
				'settings_icon',
				[
					'label' => $this->l( 'Settings icon'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon-rt-settings-outline',
					'options' => [
						'icon-rt-settings-outline' => $this->l( 'Icon 1'),
						'icon-rt-settings' => $this->l( 'Icon 2'),
						'icon-rt-bars-solid' => $this->l( 'Icon 3')
					],
				]
				
			);
			$this->addControl(
            'content',
	            array(
	                'label' => $this->l('Settings Items'),
	                'type' => ControlsManager::REPEATER,
	                'default' => array(
	                    array(
	                    	'title' => 'Languages:',
	                        'block' => 'language',
	                    ),
	                    array(
	                    	'title' => 'Currencies:',
	                        'block' => 'currency',
	                    ),
	                    array(
	                    	'title' => 'Account:',
	                        'block' => 'account',
	                    ),
	                ),
	                'fields' => array(
	                	array(
	                        'name' => 'title',
	                        'label' => $this->l('Title'),
	                        'type' => ControlsManager::TEXT,
	                        'label_block' => true,
	                    ),	
	                    array(
	                        'name' => 'block',
	                        'label' => $this->l('Block'),
	                        'type' => ControlsManager::SELECT,
	                        'options' => [
	                        	'language' => $this->l('Language'),
	                        	'currency' => $this->l('Currency'),
	                        	'account' => $this->l('Account'),
	                        ],
	                    ),
	                ),
	                'title_field' => '{{{ title }}}',
	            )
	        );
		$this->endControlsSection();
		$this->startControlsSection(
            'section_general',
            [
                'label' => __('General'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );
            $this->addControl(
            'setting_width',
                [
                    'label' => __('Width'),
                    'type' => ControlsManager::SELECT,
                    'default' => 'inline',
                    'options' => [ 
                        'fullwidth' => __('Full width 100%'),
                        'inline' => __('Inline (auto)')
                    ],
                    'prefix_class' => 'pewidth-',
                    'render_type' => 'template',
                    'frontend_available' => true
                ]
            );
        $this->endControlsSection();
		$this->startControlsSection(
			'button_section',
			[
				'label' => $this->l( 'Button style' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
            	'icon_size',
	            [
	                'label' => $this->l('Icon size'),
	                'type' => ControlsManager::SLIDER,
	                'default' => [
	                    'size' => 24,
	                ],
	                'selectors' => [
	                    '{{WRAPPER}} .pos-settings-widget .pos-dropdown-toggle i' => 'font-size: {{SIZE}}{{UNIT}}',
	                ],
	        
	            ]
	        );
	        $this->startControlsTabs('tabs_button_style');

	        $this->startControlsTab(
	            'tab_button_normal',
	            array(
	                'label' => $this->l('Normal'),
	            )
	        );

	        $this->addControl(
	            'button_text_color',
	            array(
	                'label' => $this->l('Text Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-settings-widget .pos-dropdown-toggle' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => $this->l('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-settings-widget .pos-dropdown-toggle' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->endControlsTab();

	        $this->startControlsTab(
	            'tab_button_hover',
	            array(
	                'label' => $this->l('Hover & Active'),
	            )
	        );

	        $this->addControl(
	            'hover_color',
	            array(
	                'label' => $this->l('Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-settings-widget .pos-dropdown-toggle:hover' => 'color: {{VALUE}};',
	                ),
	                'scheme' => array(
	                    'type' => SchemeColor::getType(),
	                    'value' => SchemeColor::COLOR_1,
	                ),
	            )
	        );

	        $this->addControl(
	            'button_background_hover_color',
	            array(
	                'label' => $this->l('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-settings-widget .pos-dropdown-toggle:hover' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'button_hover_border_color',
	            array(
	                'label' => $this->l('Border Color'),
	                'type' => ControlsManager::COLOR,
	                'condition' => array(
	                    'border_border!' => '',
	                ),
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-settings-widget .pos-dropdown-toggle:hover' => 'border-color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->endControlsTab();

	        $this->endControlsTabs();

	        $this->addGroupControl(
	            GroupControlBorder::getType(),
	            array(
	                'name' => 'border',
	                'label' => $this->l('Border'),
	                'placeholder' => '1px',
	                'default' => '1px',
	                'selector' => '{{WRAPPER}} .pos-settings-widget .pos-dropdown-toggle',
	            )
	        );

	        $this->addControl(
	            'border_radius',
	            array(
	                'label' => $this->l('Border Radius'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-settings-widget .pos-dropdown-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	                'separator' => 'none'
	            )
	        );

	        $this->addGroupControl(
	            GroupControlBoxShadow::getType(),
	            array(
	                'name' => 'button_box_shadow',
	                'selector' => '{{WRAPPER}} .pos-settings-widget .pos-dropdown-toggle',
	            )
	        );
		$this->endControlsSection();
		$this->startControlsSection(
			'dropdown_section',
			[
				'label' => $this->l( 'Dropdown style' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
				'dropdown_position',
				[
					'label' => $this->l( 'Dropdown Position'),
					'type' => ControlsManager::SELECT,
					'default' => 'left',
					'options' => [
						'left' => $this->l( 'Left'),
						'right' => $this->l( 'Right'),
					],
					'prefix_class' => 'pos-dropdown-',
				]
			);
			$this->addControl(
            	'dropdown_width',
	            [
	                'label' => $this->l('Dropdown width'),
	                'type' => ControlsManager::SLIDER,
	                'range' => [
						'px' => [
							'min' => 100,
							'max' => 300, 
						],
					],
					'default' => [
						'size' => 130,
						'unit' => 'px',
					], 
	                'selectors' => [
	                    '{{WRAPPER}} .pos-settings-widget .pos-dropdown-menu' => 'width: {{SIZE}}{{UNIT}}',
	                ],
	            ]
	        );
			$this->addControl(
            	'position_top',
	            [
	                'label' => $this->l('Position top'),
	                'type' => ControlsManager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100, 
						],
					],
	                'selectors' => [
	                    '{{WRAPPER}} .pos-settings-widget .pos-dropdown-menu' => 'top: {{SIZE}}{{UNIT}}', 
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
		$settings_content = array();
		foreach($settings['content'] as $block){

			switch ($block['block']) {
				case 'language':
					$content = $this->getListLanguages();
					$type = 'language';
					break;
				case 'currency':
					$content = $this->getListCurrencies();
					$type = 'currency';
					break;
				case 'account':
					$type = 'account';
					$content = array(
						'logged' => $context->customer->isLogged(),
						'create_account' => $context->link->getPageLink('authentication', true, null, ['create_account' => '1']),
						'account_url' => $context->link->getPageLink('my-account', true),
						'identity_url' => $context->link->getPageLink('identity', true),
						'history' => $context->link->getPageLink('history', true),
						'order_slip' => $context->link->getPageLink('order-slip', true),
						'discount' => $context->link->getPageLink('discount', true),
						'logout' => $context->link->getPageLink('index', true, null, 'mylogout'),
					);
					break;
			}
			$settings_content[] = array(
				'title' => $block['title'],
				'type'  => $type,
				'content' => $content
			);
		}
		$icon = $settings['settings_icon'];

		$context->smarty->assign(
			array(
				'settings_content'      => $settings_content,
				'icon'					=> $icon
			)
		);
		echo $context->smarty->fetch( POS_ELEMENTS_PATH . 'views/templates/front/settings.tpl' );
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