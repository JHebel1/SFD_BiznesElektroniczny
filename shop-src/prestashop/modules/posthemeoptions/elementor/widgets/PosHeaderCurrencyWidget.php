<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Posthemes\Module\Poselements\WidgetHelper;

class PosHeaderCurrencyWidget extends WidgetHelper { 

	public function getName() {
		return 'pos_currency';
	}
	public function getTitle() {
		return $this->l( 'Currencies' );
	}

	public function getIcon() {
		return 'fa fa-money';
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
				'language_layout',
				[
					'label' => $this->l( 'Layout'),
					'type' => ControlsManager::SELECT,
					'default' => 'symbol_name',
					'options' => [
						'symbol' => $this->l( 'Symbol'),
						'symbol_name' => $this->l( 'Symbol & name'),
					],
					'prefix_class' => 'currency-layout-',
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
							'max' => 200, 
						],
					],
					'default' => [
						'size' => 130,
						'unit' => 'px',
					],
	                'selectors' => [
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-menu' => 'width: {{SIZE}}{{UNIT}}',
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
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-menu' => 'top: {{SIZE}}{{UNIT}}', 
	                ],
	            ]
	        );
		$this->endControlsSection();
		// Start for style
        $this->startControlsSection(
            'section_general',
            [
                'label' => __('General'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );
            $this->addControl(
            'search_width',
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
			'style_section',
			[
				'label' => $this->l( 'Style' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
	        
	        $this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'text_typo',
					'selector' 		=> '{{WRAPPER}} .pos-currency-widget',
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
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => $this->l('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );
			$this->addControl(
	            'background_border_color',
	            array(
	                'label' => $this->l('Border Color'),
	                'type' => ControlsManager::COLOR,
	                'condition' => array(
	                    'border_border!' => '', 
	                ),
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle' => 'border-color: {{VALUE}};',
	                ),
	            ) 
	        );
	        $this->endControlsTab();

	        $this->startControlsTab(
	            'tab_button_hover',
	            array(
	                'label' => $this->l('Hover'),
	            )
	        );

	        $this->addControl(
	            'hover_color',
	            array(
	                'label' => $this->l('Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle:hover' => 'color: {{VALUE}};',
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
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle:hover' => 'background-color: {{VALUE}};',
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
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle:hover' => 'border-color: {{VALUE}};',
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
	                'selector' => '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle',
	            )
	        );

	        $this->addControl(
	            'border_radius',
	            array(
	                'label' => $this->l('Border Radius'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	                'separator' => 'none'
	            )
	        );

	        $this->addGroupControl(
	            GroupControlBoxShadow::getType(),
	            array(
	                'name' => 'button_box_shadow',
	                'selector' => '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle',
	            )
	        );
	        $this->addControl(
	            'padding',
	            array(
	                'label' => $this->l('Padding'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', 'em', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-currency-widget .pos-dropdown-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );
		$this->endControlsSection();
	}

	/**
	 * Render widget output on the frontend. 
	 */
	protected function render() {		
		$params = $this->getListCurrencies();
		
		if( !$params ){
			return;
		}
		$context = Context::getContext();
		
		echo $context->smarty->fetch( POS_ELEMENTS_PATH . 'views/templates/front/currencies.tpl', $params );
		
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