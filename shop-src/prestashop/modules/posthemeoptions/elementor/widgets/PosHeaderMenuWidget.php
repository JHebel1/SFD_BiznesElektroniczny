<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;

class PosHeaderMenuWidget extends WidgetBase { 

	public function getName() {
		return 'pos_menu';
	}
	public function getTitle() {
		return $this->l( 'Main menu' );
	}

	public function getIcon() {
		return 'fa fa-map-o';
	}

	public function getCategories() {
		return [ 'posthemes_header' ];
	}

	protected function _registerControls() {
		$is_admin = is_admin();
		$this->startControlsSection(
			'content_section',
			[
				'label' => $this->l( 'Content' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
		$this->addControl(
			'layout',
			[ 
	        	'label' => $this->l('Menu type'),
	            'type' => ControlsManager::SELECT,
	            'default' => 'hmenu',
	            'options' => [
	            	'hmenu' => $this->l( 'Horizontal menu' ),
	            	'vmenu' => $this->l( 'Vertical menu' ),
					'mobilemenu' => $this->l( 'Mobile menu' ),
	            ],
        	]
        );
		$this->addControl(
			'm_vertical',
	        array(
				'label' => $this->l('Show vertical menu in mobile menu'),
				'type' => ControlsManager::SWITCHER,
				'default' => 'yes',
				'condition' => array(
					'layout' => 'mobilemenu'
				),
				'label_on'     => 'Yes',
				'label_off'    => 'No',
			)
		);
		if ($is_admin && \Module::getInstanceByName('posmegamenu')) {
            $this->addControl(
                'hmenu_description',
                [
                    'raw' => sprintf(
                        __("Go to the <a href='%s' target='_blank'>%s module</a> to manage your menu items."),
                        \Context::getContext()->link->getAdminLink('AdminModules') . '&configure=posmegamenu',
                        __('Pos Megamenu')
                    ),
                    'type' => ControlsManager::RAW_HTML,
                    'content_classes' => 'elementor-descriptor',
                    'condition' => [
                        'layout' => 'hmenu',
                    ],
                ]
            );
        } else {
            $this->addControl(
                'hmenu_description',
                [
                    'raw' => sprintf(__('%s module (%s) must be installed!'), __('Pos Megamenu'), 'posmegamenu'),
                    'type' => ControlsManager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'condition' => [
                        'layout' => 'hmenu',
                    ],
                ]
            );
        }
        if ($is_admin && \Module::getInstanceByName('posvegamenu')) {
            $this->addControl(
                'vmenu_description',
                [
                    'raw' => sprintf(
                        __("Go to the <a href='%s' target='_blank'>%s module</a> to manage your menu items."),
                        \Context::getContext()->link->getAdminLink('AdminModules') . '&configure=posvegamenu',
                        __('Vertical Megamenu')
                    ),
                    'type' => ControlsManager::RAW_HTML,
                    'content_classes' => 'elementor-descriptor',
                    'condition' => [
                        'layout' => 'vmenu',
                    ],
                ]
            );
        } else {
            $this->addControl(
                'vmenu_description',
                [
                    'raw' => sprintf(__('%s module (%s) must be installed!'), __('Vertical Megamenu'), 'posvegamenu'),
                    'type' => ControlsManager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'condition' => [
                        'layout' => 'vmenu',
                    ],
                ]
            );
        }
		$this->endControlsSection();
		// Start for style
        $this->startControlsSection(
            'section_general',
            [
                'label' => __('General'),
                'tab' => ControlsManager::TAB_STYLE,
				'condition' => array(
					'layout!' => 'mobilemenu'
				),
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
            'section_menu_icon',
            [
                'label' => __('Menu icon'),
                'tab' => ControlsManager::TAB_STYLE,
				 'condition' => [
                    'layout' => 'mobilemenu',
                ],
            ]
        );
            $this->addControl(
				'icon_size',
				[
					'label' => __('Icon Size'),
					'type' => ControlsManager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'size' => 28,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} #menu-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->addControl(
				'icon_color',
				[
					'label' => $this->l( 'Icon Color' ),
					'type' => ControlsManager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} #menu-icon i' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
				]
			);
			$this->addControl(
				'icon_hover_color',
				[
					'label' => $this->l( 'Icon Hover Color' ),
					'type' => ControlsManager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} #menu-icon i:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
					'scheme' => array(
	                    'type' => SchemeColor::getType(),
	                    'value' => SchemeColor::COLOR_1,
	                ),	
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
					'name' => 'typography',
					'selector' => '{{WRAPPER}} .pos-menu-horizontal .menu-item > a, {{WRAPPER}} .pos-menu-vertical .menu-item > a',
				]
			);


			$this->startControlsTabs( 'tabs_style' );

				$this->startControlsTab(
					'tab_normal',
					[
						'label' => $this->l( 'Normal' ),
					]
				);

					$this->addControl(
						'text_color',
						[
							'label' => $this->l( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .pos-menu-horizontal .menu-item > a, {{WRAPPER}} .pos-menu-vertical .menu-item > a' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->addControl(
						'background_color',
						[
							'label' => $this->l( 'Background Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pos-menu-horizontal .menu-item > a, {{WRAPPER}} .pos-menu-vertical .menu-item > a' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->endControlsTab();

				$this->startControlsTab(
					'tab_hover',
					[
						'label' => $this->l( 'Hover & Active' ),
					]
				);

					$this->addControl(
						'hover_color',
						[
							'label' => $this->l( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pos-menu-horizontal .menu-item:hover > a, {{WRAPPER}} .pos-menu-vertical .menu-item:hover > a,{{WRAPPER}} .pos-menu-horizontal .menu-item.home > a, {{WRAPPER}} .pos-menu-vertical .menu-item.home > a, {{WRAPPER}} .pos-menu-horizontal .menu-item.active > a, {{WRAPPER}} .pos-menu-vertical .menu-item.active > a' => 'color: {{VALUE}};'
							],
						]
					);

					$this->addControl(
						'background_hover_color',
						[
							'label' => $this->l( 'Background Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pos-menu-horizontal .menu-item:hover > a, {{WRAPPER}} .pos-menu-vertical .menu-item:hover > a,{{WRAPPER}} .pos-menu-horizontal .menu-item.home > a, {{WRAPPER}} .pos-menu-vertical .menu-item.home > a, {{WRAPPER}} .pos-menu-horizontal .menu-item.active > a, {{WRAPPER}} .pos-menu-vertical .menu-item.active > a' => 'background-color: {{VALUE}};',
							],
						] 
					);

					$this->addControl(
						'hover_border_color',
						[
							'label' => $this->l( 'Border Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pos-menu-horizontal .menu-item:hover > a, {{WRAPPER}} .pos-menu-vertical .menu-item:hover > a,{{WRAPPER}} .pos-menu-horizontal .menu-item.home > a, {{WRAPPER}} .pos-menu-vertical .menu-item.home > a, {{WRAPPER}} .pos-menu-horizontal .menu-item.active > a, {{WRAPPER}} .pos-menu-vertical .menu-item.active > a' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->endControlsTab();

			$this->endControlsTabs();

			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' => 'border',
					'selector' => '{{WRAPPER}} .pos-menu-horizontal .menu-item > a, {{WRAPPER}} .pos-menu-vertical .menu-item > a',
					'separator' => 'before',
				]
			);

			$this->addControl(
				'border_radius',
				[
					'label' => $this->l( 'Border Radius' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .pos-menu-horizontal .menu-item > a, {{WRAPPER}} .pos-menu-vertical .menu-item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],					
				]
			);

			$this->addGroupControl(
				GroupControlBoxShadow::getType(),
				[
					'name' => 'box_shadow',
					'selector' => '{{WRAPPER}} .pos-menu-horizontal .menu-item > a, {{WRAPPER}} .pos-menu-vertical .menu-item > a',
				]
			);

			$this->addControl(
				'text_padding',
				[
					'label' => $this->l( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .pos-menu-horizontal .menu-item > a, {{WRAPPER}} .pos-menu-vertical .menu-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->addControl(
				'margin',
				[
					'label' => $this->l( 'Margin' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .pos-menu-horizontal .menu-item > a , {{WRAPPER}} .pos-menu-vertical .menu-item > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		
		$this->endControlsSection();
		
		$this->startControlsSection(
			'section_vertical_title',
			[
				'label' => $this->l( 'Vertical Title' ),
				'type' => ControlsManager::SECTION,
				'tab' => ControlsManager::TAB_STYLE,
				'condition'    => [
					'layout' => [ 'vmenu' ],
				],
			]
		);
		
			$this->addControl(
				'title_icon_size',
				[
					'label' => $this->l( 'Icon Size' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .pos-menu-vertical .title_vertical i' => 'font-size: {{SIZE}}{{UNIT}}', 
					],
					'separator' => 'before',
				]
			);		
		
			$this->addControl(
				'title_icon_size_margin',
				[
					'label' => $this->l( 'Icon Margin Right' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .pos-menu-vertical .title_vertical i' => 'margin-right: {{SIZE}}{{UNIT}}',  
					]
				]
			);
			
			$this->addControl(
				'title_space_right_icon',
				[
					'label' => $this->l( 'Space right icon' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .pos-menu-vertical .title_vertical:after' => 'margin-left: {{SIZE}}{{UNIT}}',  
					]
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .pos-menu-vertical .title_vertical',
				]
			);

			$this->startControlsTabs( 'title_tabs_style' );

				$this->startControlsTab(
					'title_tab_normal',
					[
						'label' => $this->l( 'Normal' ),
					]
				);

					$this->addControl(
						'title_text_color',
						[
							'label' => $this->l( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .pos-menu-vertical .title_vertical' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->addControl(
						'title_background_color',
						[
							'label' => $this->l( 'Background Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pos-menu-vertical .title_vertical' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->endControlsTab();

				$this->startControlsTab(
					'title_tab_hover',
					[
						'label' => $this->l( 'Hover & Active' ),
					]
				);

					$this->addControl(
						'title_hover_color',
						[
							'label' => $this->l( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pos-menu-vertical:hover .title_vertical' => 'fill: {{VALUE}}; color: {{VALUE}};'
							],
						]
					);

					$this->addControl(
						'title_background_hover_color',
						[
							'label' => $this->l( 'Background Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pos-menu-vertical:hover .title_vertical' => 'background-color: {{VALUE}};', 
							],
						]
					);

					$this->addControl(
						'title_hover_border_color',
						[
							'label' => $this->l( 'Border Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pos-menu-vertical:hover .title_vertical' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->endControlsTab();

			$this->endControlsTabs();

			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' => 'title_border',
					'selector' => '{{WRAPPER}} .pos-menu-vertical .title_vertical',
					'separator' => 'before',
				]
			);

			$this->addControl(
				'title_border_radius',
				[
					'label' => $this->l( 'Border Radius' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .pos-menu-vertical .title_vertical' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					]
				]
			);

			$this->addGroupControl(
				GroupControlBoxShadow::getType(),
				[
					'name' => 'title_box_shadow',
					'selector' => '{{WRAPPER}} .pos-menu-vertical .title_vertical',
				]
			);

			$this->addControl(
				'title_text_padding',
				[
					'label' => $this->l( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .pos-menu-vertical .title_vertical' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

		$this->endControlsSection();
		
	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	protected function render() {

		if (is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}
		$context = Context::getContext();
		$settings = $this->getSettings();
		if($settings['layout'] == 'hmenu'){
			if( \Module::isEnabled('posmegamenu') ) {
				$module = \Module::getInstanceByName( 'posmegamenu' );
				echo $module->hookDisplayMegamenu();
			}else{
				echo $this->l('Megamenu is not active.');
			}
		}else if($settings['layout'] == 'vmenu'){
			if( \Module::isEnabled('posvegamenu') ) {
				$module = \Module::getInstanceByName( 'posvegamenu' );
				echo $module->hookDisplayVegamenu();
			}else{
				echo $this->l('Vertical menu is not active.');
			}
		}else{
			$hmenu = $vmenu = '';
			if( \Module::isEnabled('posmegamenu') ) {
				$module = \Module::getInstanceByName( 'posmegamenu' );
				$hmenu = $module->hookDisplayMegamenuMobile();
			}
			if( \Module::isEnabled('posvegamenu') && $settings['m_vertical'] ) {
				$module = \Module::getInstanceByName( 'posvegamenu' );
				$vmenu = $module->hookDisplayVegamenuMobile();
			}
			$context->smarty->assign(
				array(
					'hmenu'      => $hmenu,
					'vmenu'      => $vmenu,
				)
			);
			$output = $context->smarty->fetch( POS_ELEMENTS_PATH . '/views/templates/front/menu-mobile.tpl' );
			echo $output;
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