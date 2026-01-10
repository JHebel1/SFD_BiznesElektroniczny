<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Module;
use Tools;
use Posthemes\Module\Poselements\WidgetHelper;

class PosNewsletterWidget extends WidgetHelper { 

	public function getName() {
		return 'pos-newsletter';
	}

	public function getTitle() {
		return $this->l( 'Newsletter form' );
	}
	
	public function getCategories() {
		return [ 'posthemes' ];
	}

	public function getIcon() {
		return 'fa fa-envelope-o';
	}

	protected function _registerControls() {
		$this->startControlsSection(
			'section_newsletter',
			[
				'label' => $this->l('Newsletter'),
			]
		);

		$this->addControl(
			'placeholder',
			[
				'label' => $this->l('Input Placeholder'),
				'type' => ControlsManager::TEXT,
				'placeholder' => $this->l('Your email address'),
			]
		);
		$this->addControl(
            'placeholder_color',
            [
                'label' => __('Placeholder Color'),
                'type' => ControlsManager::COLOR, 
                'selectors' => [
                    '{{WRAPPER}} input[name=email]::placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );
		$this->addControl(
			'disable_psgdpr',
			[
				'label'        => $this->l( 'Disable Psgdpr' ),
				'type'         => ControlsManager::SWITCHER,
				'label_on'     => $this->l( 'Yes' ),
				'label_off'    => $this->l( 'No' ),
			]
		);
		$this->endControlsSection();
		$this->startControlsSection(
			'section_input_style',
			[
				'label' => $this->l('Input'),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);

		$this->addControl(
			'input_text_color',
			[
				'label' => $this->l('Text Color'),
				'type' => ControlsManager::COLOR,
				'selectors' => [
					'{{WRAPPER}} input[name=email]' => 'color: {{VALUE}};',
				],
			]
		);

		$this->addResponsiveControl(
			'input_width',
			[
				'label' => $this->l('Width'),
				'type' => ControlsManager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1600,
					],
				],
				'responsive' => true,
				'selectors' => [
					'{{WRAPPER}} input[name=email]' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->addControl(
			'input_height',
			[
				'label' => $this->l('Height'),
				'type' => ControlsManager::SLIDER,
				'size_units' => ['px', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} input[name=email]' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->addResponsiveControl(
			'input_align',
			[
				'label' => $this->l('Alignment'),
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
					'{{WRAPPER}} input[name=email]' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->addGroupControl(
			GroupControlTypography::getType(),
			[
				'name' => 'input_typography',
				'label' => $this->l('Typography'),
				'selector' => '{{WRAPPER}} input[name=email]',
			]
		);

		$this->addControl(
			'input_background_color',
			[
				'label' => $this->l('Background Color'),
				'type' => ControlsManager::COLOR,
				'selectors' => [
					'{{WRAPPER}} input[name=email]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->addGroupControl(
			GroupControlBorder::getType(),
			[
				'name' => 'input_border',
				'label' => $this->l('Border'),
				'selector' => '{{WRAPPER}} input[name=email]',
			]
		);

		$this->addControl(
			'input_border_radius',
			[
				'label' => $this->l('Border Radius'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} input[name=email]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->addControl(
			'input_padding',
			[
				'label' => $this->l('Text Padding'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} input[name=email]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->addResponsiveControl(
			'input_margin',
			[
				'label' => $this->l('Margin'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} input[name=email]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->endControlsSection();

		$this->startControlsSection(
			'section_button_style',
			[
				'label' => $this->l('Button'),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);

		$this->addControl(
			'button_text_color',
			[
				'label' => $this->l('Text Color'),
				'type' => ControlsManager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->addResponsiveControl(
			'button_width',
			[
				'label' => $this->l('Width'),
				'type' => ControlsManager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1600,
					],
				],
				'responsive' => true,
				'selectors' => [
					'{{WRAPPER}} button' => 'max-width: 100%; width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->addControl(
			'button_height',
			[
				'label' => $this->l('Height'),
				'type' => ControlsManager::SLIDER,
				'size_units' => ['px', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->addGroupControl(
			GroupControlTypography::getType(),
			[
				'name' => 'button_typography',
				'label' => $this->l('Typography'),
				'selector' => '{{WRAPPER}} button',
			]
		);

		$this->addControl(
			'button_background_color',
			[
				'label' => $this->l('Background Color'),
				'type' => ControlsManager::COLOR,
				'scheme' => [
					'type' => SchemeColor::getType(),
					'value' => SchemeColor::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->addGroupControl(
			GroupControlBorder::getType(),
			[
				'name' => 'button_border',
				'label' => $this->l('Border'),
				'selector' => '{{WRAPPER}} button',
			]
		);

		$this->addControl(
			'button_border_radius',
			[
				'label' => $this->l('Border Radius'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->addControl(
			'button_padding',
			[
				'label' => $this->l('Text Padding'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->addResponsiveControl(
			'button_margin',
			[
				'label' => $this->l('Margin'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->endControlsSection();

		$this->startControlsSection(
			'section_button_hover',
			[
				'label' => $this->l('Button Hover'),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);

		$this->addControl(
			'button_hover_color',
			[
				'label' => $this->l('Text Color'),
				'type' => ControlsManager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->addControl(
			'button_background_hover_color',
			[
				'label' => $this->l('Background Color'),
				'type' => ControlsManager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->addControl(
			'button_hover_border_color',
			[
				'label' => $this->l('Border Color'),
				'type' => ControlsManager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->endControlsSection();

		$this->startControlsSection(
			'section_psgdpr_style',
			[
				'label' => $this->l('Psgdpr'),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
		
		$this->addResponsiveControl(
			'psgdpr_text_align',
			[
				'label' => $this->l('Alignment'),
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
					'{{WRAPPER}} #gdpr_consent' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->addControl(
			'psgdpr_text_color',
			[
				'label' => $this->l('Text Color'),
				'type' => ControlsManager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .psgdpr_consent_message,{{WRAPPER}} .custom-checkbox input[type="checkbox"] + span .checkbox-checked' => 'color: {{VALUE}};',
					'{{WRAPPER}} .custom-checkbox input[type="checkbox"] + span' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->addGroupControl(
			GroupControlTypography::getType(),
			[
				'name' => 'psgdpr_typography',
				'label' => $this->l('Typography'),
				'selector' => '{{WRAPPER}} .psgdpr_consent_message',
			]
		);
		
        $this->addControl(
            'checkbox_spacing',
            [
                'label' => $this->l('Checkbox Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-checkbox input + span' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->addResponsiveControl(
			'psgdpr_margin',
			[
				'label' => $this->l('Margin'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .psgdpr_consent_message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->endControlsSection();
		
        $this->startControlsSection(
            'section_alert_style',
            [
                'label' => $this->l('Alert'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );
		
		$this->addResponsiveControl(
			'alert_text_align',
			[
				'label' => $this->l('Alignment'),
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
					'{{WRAPPER}} .alert' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'alert_typography',
                'selector' => '{{WRAPPER}} .alert',
            ]
        );

        $this->addControl(
            'heading_style_success',
            [
                'type' => ControlsManager::HEADING,
                'label' => $this->l('Success'),
            ]
        );

        $this->addControl(
            'success_alert_color',
            [
                'label' => $this->l('Text Color'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .alert.alert-success' => 'color: {{COLOR}};',
                ],
            ]
        );

        $this->addControl(
            'heading_style_error',
            [
                'type' => ControlsManager::HEADING,
                'label' => $this->l('Error'),
            ]
        );

        $this->addControl(
            'error_alert_color',
            [
                'label' => $this->l('Text Color'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .alert.alert-danger' => 'color: {{COLOR}};',
                ],
            ]
        );
		
		$this->endControlsSection();
	}


	/**
	 * Render Site Image output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {
		if (is_admin()){
            return print '<div class="ce-remote-render"></div>';
        }
		
        if( \Module::isEnabled( 'ps_emailsubscription' ) ) {
			$module = \Module::getInstanceByName( 'ps_emailsubscription' );
			
			$vars = $module->getWidgetVariables();
			
			$vars['settings'] = $this->getSettings();
			$vars['id_module'] = $module->id;
						
			echo $this->fetch( POS_ELEMENTS_PATH . 'views/templates/front/newsletter.tpl', $vars );
		}
	}

	protected function fetch( $templatePath, $params ) {
		$context = Context::getContext();

        if ( is_object( $context->smarty ) ) {
            $smarty = $context->smarty->createData( $context->smarty );
        }
		
		$smarty->assign( $params );
		
        $template = $context->smarty->createTemplate( $templatePath, null, null, $smarty );

        return $template->fetch();
	}

	protected function l($string)
    {
        return translate($string, 'posthemeoptions', basename(__FILE__, '.php'));
    }

}