<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;

class PosHeaderWishlistWidget extends WidgetBase { 

	public function getName() {
		return 'posWishlist';
	}
	public function getTitle() {
		return $this->l( 'My wishlist' );
	}

	public function getIcon() {
		return 'fa fa-heart-o';
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
				'button_layout',
				[
					'label' => $this->l( 'Button Layout'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon_text',
					'options' => [
						'icon' => $this->l( 'Icon'),
						'icon_text' => $this->l( 'Icon & Text'),
						'text' => $this->l( 'Text'),
					],
					'prefix_class' => 'button-layout-',
				]
			);
			$this->addControl(
				'wishlist_icon',
				[
					'label' => $this->l( 'Wishlist icon'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon-rt-heart2',
					'options' => [
						'icon-rt-heart2' => $this->l( 'Icon 1'),
						'icon-rt-heart-solid' => $this->l( 'Icon 2'),
						'icon-rt-heart-outline' => $this->l( 'Icon 3'),
						'icon-rt-ios-heart-outline' => $this->l( 'Icon 4'),
						'icon-rt-Heart' => $this->l( 'Icon 5')
					],
					'condition' => array(
	                    'button_layout!' => 'text',
	                ),
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
			$this->addControl(
            	'icon_size',
	            [
	                'label' => $this->l('Icon size'),
	                'type' => ControlsManager::SLIDER,
	                'default' => [
	                    'size' => 14,
	                ],
	                'selectors' => [
	                    '{{WRAPPER}} .wishlist-top i' => 'font-size: {{SIZE}}{{UNIT}}',
	                ],
	                'condition' => [
	                    'button_layout!' => 'text' 
	                ],
	            ]
	        );
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'text_typo',
					'selector' 		=> '{{WRAPPER}} .wishlist-top a',
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
	                    '{{WRAPPER}} .wishlist-top a' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => $this->l('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a' => 'background-color: {{VALUE}};',
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
	                    '{{WRAPPER}} .wishlist-top a:hover' => 'color: {{VALUE}};',
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
	                    '{{WRAPPER}} .wishlist-top a:hover' => 'background-color: {{VALUE}};',
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
	                    '{{WRAPPER}} .wishlist-top a:hover' => 'border-color: {{VALUE}};',
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
	                'selector' => '{{WRAPPER}} .wishlist-top a',
	            )
	        );

	        $this->addControl(
	            'border_radius',
	            array(
	                'label' => $this->l('Border Radius'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	                'separator' => 'none'
	            )
	        );
			$this->addControl(
	            'padding',
	            array(
	                'label' => $this->l('Padding'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', 'em', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );
	        $this->addGroupControl(
	            GroupControlBoxShadow::getType(),
	            array(
	                'name' => 'button_box_shadow',
	                'selector' => '{{WRAPPER}} .wishlist-top a',
	            )
	        );
	    $this->endControlsSection();
		$this->startControlsSection(
			'count_section',
			[
				'label' => $this->l( 'Count' ),
				'tab' => ControlsManager::TAB_STYLE,
				'condition' => [
					'button_layout' => 'icon',
				],
			]
		);    
	        $this->addControl(
	            'heading_cart_count',
	            [
	                'label' => $this->l('Count style'),
	                'type' => ControlsManager::HEADING,
					
	            ]
	        );
	        $this->addControl(
				'count_top',
				[
					'label' => $this->l( 'Count Position Top'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'top: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_right',
				[
					'label' => $this->l( 'Count Position Right'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'right: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_size',
				[
					'label' => $this->l( 'Count Size'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'min-width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_font_size',
				[
					'label' => $this->l( 'Count Font Size'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'font-size: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_text_color',
				[
					'label' => $this->l( 'Count Text Color'),
					'type' => ControlsManager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
					'separator' => 'none'
				]
			);

			$this->addControl(
				'count_background_color',
				[
					'label' => $this->l( 'Count Background Color'),
					'type' => ControlsManager::COLOR,
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'background-color: {{VALUE}};',
					],
					'separator' => 'none'
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

		if( \Module::isEnabled('poswishlist') ) {
			$settings = $this->getSettings();
			$icon = $settings['wishlist_icon'];
			$module = \Module::getInstanceByName( 'poswishlist' );
			echo $module->hookdisplayNav( [ 'icon' => $icon ] );
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