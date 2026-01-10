<?php  

namespace CE;

defined('_PS_VERSION_') or die;

use Context;

use Posthemes\Module\Poselements\WidgetHelper;

class PosCountdownWidget extends WidgetBase { 
	public function getName() {
		return 'pos_countdown';
	}

	public function getTitle() {
		return $this->l('Pos Countdown');
	}

	public function getIcon() { 
		return 'fa fa-history';
	}

	public function getCategories() {
		return [ 'posthemes' ];
	}

	protected function _registerControls() { 
		
		//Elements
		$this->startControlsSection(
            'section_elements',
            [
                'label' => $this->l('Content')
            ]
		);

		$this->addControl(
			'end_date',
			[
				'label' => $this->l( 'Select End Date' ),
				'type' => ControlsManager::DATE_TIME,
				'default' => date('Y-m-d H:i', strtotime('+1 day')),
			]
		);
		$this->addControl(
	            'separate',
	            [
	                'label' => $this->l('Separate'),
	                'type' => ControlsManager::SELECT,
	                'options' => [
	                	'none' => 'None',
	                	'colon' => ':',
	                	'slash' => '/',
	                	'minus' => '-',
	                ],					
	                'default' => 'none',
	                'prefix_class' => 'countdown-separate-',
	            ]
	        );

        $this->endControlsSection();

        $this->startControlsSection(
			'section_style',
			[
				'label' 		=> $this->l('General'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);

			$this->addControl(
            'countdown_width', 
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
	                    '{{WRAPPER}} .block-countdown' => 'text-align: {{VALUE}};', 
	                ],
	            ]
	        );
			$this->addControl(
				'countdown_background',
				[
					'label' 		=> $this->l('Countdown background'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown' => 'background: {{VALUE}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' 			=> 'countdown_border',
					'selector' 		=> '{{WRAPPER}} .block-countdown',
				]
			);
			
			$this->addResponsiveControl(
				'countdown_border_radius',
				[
					'label' 		=> $this->l('Border Radius'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'countdown_padding',
				[
					'label' 		=> $this->l('Padding'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'countdown_margin',
				[
					'label' 		=> $this->l('Margin'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			
		$this->endControlsSection();
		$this->startControlsSection(
			'section_style_countdown',
			[
				'label' 		=> $this->l('Section Countdown'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);

			$this->addControl(
	            'countdown_display',
	            [
	                'label' => __('Display'),
	                'type' => ControlsManager::SELECT,
	                'options' => [
	                	'block' => __('Block'),
	                	'inline' => __('Inline'),
	                ],
	                'default' => 'block',
	            ]
	        );
			$this->addControl(
				'countdown_background_section',
				[
					'label' 		=> $this->l('Section background'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown .countdown-section' => 'background: {{VALUE}};',
					],
				]
			);
			
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' 			=> 'countdown_border_section',
					'selector' 		=> '{{WRAPPER}} .block-countdown .countdown-section',
				]
			);
			
			$this->addResponsiveControl(
				'countdown_border_radius_section',
				[
					'label' 		=> $this->l('Border Radius'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown .countdown-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'countdown_padding_section',
				[
					'label' 		=> $this->l('Padding'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown .countdown-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'countdown_spacing_section',
				[
					'label' => $this->l( 'Spacing' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'condition'		=>[
						'separate' =>'none'
					],
					'default' => [
						'unit' => 'px',
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .block-countdown .countdown-section' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
			'section_date_style',
			[
				'label' 		=> $this->l('Date style'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
				'countdown_date_color',
				[
					'label' 		=> $this->l('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown .countdown-section .countdown-amount' => 'color: {{VALUE}};',
					],
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'countdown_date_typo',
					'selector' 		=> '{{WRAPPER}} .block-countdown .countdown-section .countdown-amount',
					'scheme' 		=> SchemeTypography::TYPOGRAPHY_1,
				]
			);
		$this->endControlsSection();

		$this->startControlsSection(
			'section_text_style',
			[
				'label' 		=> $this->l('Text style'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
				'countdown_text_color',
				[
					'label' 		=> $this->l('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown .countdown-section .countdown-period' => 'color: {{VALUE}};',
					],
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'countdown_text_typo',
					'selector' 		=> '{{WRAPPER}} .block-countdown .countdown-section .countdown-period',
					'scheme' 		=> SchemeTypography::TYPOGRAPHY_1,
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
			'section_separate',
			[
				'label' 		=> $this->l('Separate style'),
				'tab' 			=> ControlsManager::TAB_STYLE,
				'condition'		=>[
					'separate!' =>'none'
				]
			]
		);
			$this->addControl(
				'separate_color',
				[
					'label' 		=> $this->l('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .block-countdown .countdown-section:after' => 'color: {{VALUE}};',
					],
				]
			);
			$this->addControl(
            	'separate_size',
	            [
	                'label' => $this->l('Size'),
	                'type' => ControlsManager::SLIDER,
	                'default' => [
	                    'size' => 28,
	                ],
	                'selectors' => [
	                    '{{WRAPPER}} .block-countdown .countdown-section:after' => 'font-size: {{SIZE}}{{UNIT}}',
	                ],
	            ]
	        );
		$this->endControlsSection();
	
	}

	protected function render() {
		$settings = $this->getSettings();
		?>
 		<div class="pos-elements-countdown block-countdown <?= $settings['countdown_display'] ?>-display" data-end-date ="<?= $settings['end_date'] ?>"></div>
 		<?php
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