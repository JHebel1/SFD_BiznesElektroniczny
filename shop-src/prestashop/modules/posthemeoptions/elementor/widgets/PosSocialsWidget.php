<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Posthemes\Module\Poselements\WidgetHelper;

class PosSocialsWidget extends WidgetHelper { 

	public function getName() {
		return 'pos_social';
	}
	public function getTitle() {
		return $this->l( 'Social icons' );
	}

	public function getIcon() {
		return 'fa fa-globe';
	}

	public function getCategories() {
		return [ 'posthemes' ];
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
            'content',
	            array(
	                'label' => $this->l('List items'),
	                'type' => ControlsManager::REPEATER,
	                'default' => array(
	                	array(
	                		'name' => 'facebook',
	                		'url' => '#'
	                	),
	                	array(
	                		'name' => 'twitter',
	                		'url' => '#'
	                	),
	                	array(
	                		'name' => 'instagram',
	                		'url' => '#'
	                	),
	                ),
	                'fields' => array(	
	                    array(
	                        'name' => 'name',
	                        'label' => $this->l('Social network'),
	                        'type' => ControlsManager::SELECT2,
	                        'options' => [
	                        	'facebook' => $this->l('Facebook'),
	                        	'twitter' => $this->l('Twitter'),
	                        	'google' => $this->l('Google+'),
	                        	'instagram' => $this->l('Instagram'),
	                        	'telegram' => $this->l('Telegram'),
	                        	'youtube' => $this->l('Youtube'),
	                        	'whatsapp' => $this->l('Whatsapp'),
	                        	'tiktok' => $this->l('Tiktok'),
	                        	'snapchat' => $this->l('Snapchat'),
	                        	'pinterest' => $this->l('Pinterest'),
	                        	'rss' => $this->l('RSS'),
	                        	'vimeo' => $this->l('Vimeo'),
	                        	'linkedIn ' => $this->l('LinkedIn'), 
	                        ],
	                    ),
	                    array(
	                        'name' => 'url',
	                        'label' => $this->l('URL'),
	                        'type' => ControlsManager::TEXT,
	                        'label_block' => true,
	                    ),
	                ),
	                'title_field' => '{{{ name }}}',
	            )
	        );
			     
		$this->endControlsSection();
		
		$this->startControlsSection(
			'general_section',
			[
				'label' => $this->l( 'General' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		); 
			$this->addControl(
            'block_width',
                [
                    'label' => __('Width'),
                    'type' => ControlsManager::SELECT,
                    'default' => 'fullwidth',
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
	                    'flex-start' => [
	                        'title' => $this->l('Left'),
	                        'icon' => 'fa fa-align-left',
	                    ],
	                    'center' => [
	                        'title' => $this->l('Center'),
	                        'icon' => 'fa fa-align-center',
	                    ],
	                    'flex-end' => [
	                        'title' => $this->l('Right'),
	                        'icon' => 'fa fa-align-right',
	                    ],
	                ],
	                'selectors' => [
	                    '{{WRAPPER}} .pos-socials-widget ul' => 'justify-content: {{VALUE}};', 
	                ],
	            ]
	        );
		$this->endControlsSection();
		$this->startControlsSection(
			'icons_section',
			[
				'label' => $this->l( 'Icons' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		); 
			$this->addControl(
            'icon_size',
	            [
	                'label' => __('Icon Size'),
	                'type' => ControlsManager::SLIDER,
	                'selectors' => [
	                    '{{WRAPPER}} .pos-socials-widget ul li a' => 'font-size: {{SIZE}}{{UNIT}}',  
	                ],
	                'default' => [
	                    'size' => 24, 
	                    'unit' => 'px',
	                ],
	                'separator' => 'before',
	            ]
	        );
			$this->addControl(
				'box_icon_size',
				[
					'label' => $this->l( 'Box Icon Size'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 100,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .pos-socials-widget ul li a' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
			$this->addControl(
				'space_icon_size',
				[
					'label' => $this->l( 'Icon Spacing'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 100,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .pos-socials-widget ul li' => 'margin-right: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
			$this->addControl(
				'box_border_radius',
				[
					'label' => $this->l('Box Border Radius'),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => ['px', '%'],
					'selectors' => [
						'{{WRAPPER}} .pos-socials-widget ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
	            GroupControlBorder::getType(),
	            array(
	                'name' => 'border',
	                'label' => $this->l('Border'),
	                'placeholder' => '1px',
	                'default' => '1px',
	                'selector' => '{{WRAPPER}} .pos-socials-widget ul li a'
	            )
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
	                    '{{WRAPPER}} .pos-socials-widget ul li a' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => $this->l('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-socials-widget ul li a' => 'background-color: {{VALUE}};',
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
	                    '{{WRAPPER}} .pos-socials-widget ul li a:hover' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'button_background_hover_color',
	            array(
	                'label' => $this->l('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-socials-widget ul li a:hover' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );
			$this->addControl(
	            'button_border_hover_color',
	            array(
	                'label' => $this->l('Border Color'), 
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-socials-widget ul li a:hover' => 'border-color: {{VALUE}};',
	                ),
	            )
	        );
	        $this->endControlsTab();

	        $this->endControlsTabs();
		$this->endControlsSection();

	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	protected function render() {

		$settings = $this->getSettings(); 
		?>
		<div class="pos-socials-widget">
			<ul>
			<?php
			foreach($settings['content'] as $social){
				?>
					<li>
						<a href="<?= $social['url'] ?>" target="_blank" title="<?= $social['name'] ?>"><i class="ecolife-icon ei-<?= $social['name'] ?>"></i></a>
					</li>
				<?php
			}
			?>
			</ul>
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