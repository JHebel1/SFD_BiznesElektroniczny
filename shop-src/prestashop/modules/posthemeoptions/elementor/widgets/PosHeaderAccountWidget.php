<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;

class PosHeaderAccountWidget extends WidgetBase { 

	public function getName() {
		return 'posAccount';
	}
	public function getTitle() {
		return $this->l( 'My account' );
	}

	public function getIcon() {
		return 'fa fa-user';
	}

	public function getCategories() {
		return [ 'posthemes_header' ];
	}

	public function getLinkToOptions()
    {
        return [
            'my-account' => $this->l('My account'),
            'identity' => $this->l('Personal info'),
            'address' => $this->l('New Address'),
            'addresses' => $this->l('Addresses'),
            'history' => $this->l('Order history'),
            'order-slip' => $this->l('Credit slip'),
            'discount' => $this->l('My vouchers'),
            'logout' => $this->l('Sign out'),
            'custom' => $this->l('Custom URL'),
        ];
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
					'label' => $this->l( 'Button layout'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon_text',
					'options' => [
						'icon' => $this->l( 'Icon'),
						'text' => $this->l( 'Text'),
						'icon_text' => $this->l( 'Icon & Text'),
					],
					'prefix_class' => 'button-layout-'
				]
			);
			$this->addControl(
				'account_icon',
				[
					'label' => $this->l( 'Account icon'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon-rt-user',
					'options' => [
						'icon-rt-user' => $this->l( 'Icon 1'),
						'icon-rt-person-circle-outline' => $this->l( 'Icon 2'),
						'icon-rt-person-outline' => $this->l( 'Icon 3')
					],
					'condition' => array(
	                    'button_layout!' => 'text',
	                ),
				]	
			);

            $this->addControl(
            'heading_usermenu',
	            [
	                'label' => $this->l('Usermenu'),
	                'type' => ControlsManager::HEADING,
	                'separator' => 'before',
	            ]
	        );
            $this->addControl(
            'account_links',
	            [
	                'label' => '',
	                'type' => ControlsManager::REPEATER,
	                'default' => [
	                    [
							'link_to' => 'my-account',
							'link'   => '',
							'text'   => '',
						],
						[
							'link_to' => 'history',
							'link'   => '',
							'text'   => '',
						],
						[
							'link_to' => 'logout',
							'link'   => '',
							'text'   => '',
						],
	                ],
	                'fields' => [
			            [
			                'name' => 'link_to',
			                'label' => $this->l('Link'),
			                'type' => ControlsManager::SELECT,
			                'default' => 'identity',
			                'options' => $this->getLinkToOptions()
			            ],
			            [
			            	'name' => 'link',
			                'label_block' => true,
			                'type' => ControlsManager::URL,
			                'placeholder' => $this->l('http://your-link.com'),
			                'classes' => 'ce-hide-link-options',
			                'condition' => [
			                    'link_to' => 'custom',
			                ]
			            ],
			            [
			                'name' => 'text',
			                'label' => $this->l('Text'),
			                'type' => ControlsManager::TEXT,
			                'description' => $this->l('Leave empty to get default text')
			            ]
	                ],
	                'title_field' => '{{{ text || link_to }}}',
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
	                    '{{WRAPPER}} .pos-account i' => 'font-size: {{SIZE}}{{UNIT}}',
	                ],
	                'condition' => [
	                    'button_layout!' => 'text' 
	                ]
	            ]
	        );
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'text_typo',
					'selector' 		=> '{{WRAPPER}} .pos-account > a',
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
	                    '{{WRAPPER}} .pos-account > a' => 'color: {{VALUE}};',
	                )
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => $this->l('Background Color'),
	                'type' => ControlsManager::COLOR,	                
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-account > a' => 'background-color: {{VALUE}};',
	                )
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
	                    '{{WRAPPER}} .pos-account > a:hover' => 'color: {{VALUE}};',
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
	                    '{{WRAPPER}} .pos-account > a:hover' => 'background-color: {{VALUE}};',
	                )
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
	                    '{{WRAPPER}} .pos-account > a:hover' => 'border-color: {{VALUE}};',
	                )
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
	                'selector' => '{{WRAPPER}} .pos-account > a'
	            )
	        );

	        $this->addControl(
	            'border_radius',
	            array(
	                'label' => $this->l('Border Radius'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-account > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	                    '{{WRAPPER}} .pos-account > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );
	        $this->addGroupControl(
	            GroupControlBoxShadow::getType(),
	            array(
	                'name' => 'button_box_shadow',
	                'selector' => '{{WRAPPER}} .pos-account > a'
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
							'max' => 200, 
						],
					],
					'default' => [
						'size' => 130,
						'unit' => 'px',
					], 
	                'selectors' => [
	                    '{{WRAPPER}} .pos-account .pos-dropdown-menu' => 'width: {{SIZE}}{{UNIT}}',
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
	                    '{{WRAPPER}} .pos-account .pos-dropdown-menu' => 'top: {{SIZE}}{{UNIT}}', 
	                ],
	            ]
	        );
		$this->endControlsSection();
	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	protected function render() {
		$settings = $this->getSettings();
		
		$context = Context::getContext();
		$logged = $context->customer->isLogged();
		?>
		<div class="pos-account pos-header-element <?php if($logged): ?>js-dropdown<?php endif; ?>">
			<a href="<?= $context->link->getPageLink('my-account', true) ?>" class="account-login" <?php if($logged): ?>data-toggle="dropdown"<?php endif; ?>>
				<i class="<?= $settings['account_icon'] ?>"></i>
				<?php if($logged): ?>
					<span><?= $this->l('Your account') ?></span>
				<?php else: ?>
					<span><?= $this->l('Sign in') ?></span>
				<?php endif; ?>
			</a>
			<?php if($logged): ?>
			<ul class="dropdown-menu pos-dropdown-menu">
				<?php foreach($settings['account_links'] as $account_link): ?>
					<?php switch ($account_link['link_to']) {
						case 'my-account':
							$url = $context->link->getPageLink('my-account', true);
							$default_text = $this->l('Your account');
							break;
						case 'identity':
							$url = $context->link->getPageLink('identity', true);
							$default_text = $this->l('Personal info');
							break;
						case 'address':
							$url = $context->link->getPageLink('address', true);
							$default_text = $this->l('New Address');
							break;
						case 'addresses':
							$url = $context->link->getPageLink('addresses', true);
							$default_text = $this->l('Addresses');
							break;
						case 'history':
							$url = $context->link->getPageLink('history', true);
							$default_text = $this->l('Order history');
							break;
						case 'order-slip':
							$url = $context->link->getPageLink('order-slip', true);
							$default_text = $this->l('Credit slip');
							break;
						case 'discount':
							$url = $context->link->getPageLink('discount', true);
							$default_text = $this->l('Vouchers');
							break;
						case 'logout':
							$url = $context->link->getPageLink('index', true, null, 'mylogout');
							$default_text = $this->l('Logout');
							break;
						case 'custom':
							$url = $account_link['link']['url'];
							break;
					}
					?>	
					<a href="<?= $url ?>">
						<?php 
						if($account_link['text']) echo $account_link['text'];
						else echo $default_text;
						?>
						
					</a>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
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