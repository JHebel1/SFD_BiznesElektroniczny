<?php 

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Posthemes\Module\Poselements\WidgetHelper;

class PosBannerWidget extends WidgetHelper {

	public function getName() {
		return 'pos_banner';
	}

	public function getTitle() {
		return 'Pos Banner';
	}

	public function getIcon() { 
		return 'fa fa-file-image-o';
	}

	public function getCategories() {
		return [ 'posthemes' ];
	}
 
	protected function _registerControls() {

		$this->startControlsSection(
			'section_banner',
			[
				'label' 		=> $this->l('Banner'),
			]
		);

		$this->addControl(
			'image',
			[
				'label'   		=> $this->l('Image'),
				'type'    		=> ControlsManager::MEDIA,
				'default' 		=> [
					'url' => Utils::getPlaceholderImageSrc()
				],
			]
		);
		$this->addControl(
			'title',
			[
				'label'   		=> $this->l('Title'),
				'type'    		=> ControlsManager::TEXT,
				'label_block' 	=> true,
			]
		);
		
		$this->addControl(
			'title2',
			[
				'label'   		=> $this->l('Title 2'),
				'type'    		=> ControlsManager::TEXT,
				'label_block' 	=> true,
			]
		);

		$this->addControl(
			'subtitle',
			[
				'label'   		=> $this->l('Subtitle'),
				'type'    		=> ControlsManager::TEXTAREA,
			]
		);

		$this->addControl(
			'link',
			[
				'label'   		=> $this->l('Link'),
				'type'    		=> ControlsManager::URL,
				'placeholder' 	=> $this->l('https://your-link.com'),
			]
		);
		$this->addControl(
			'button_link',
			[
				'label'   		=> $this->l('Button text'),
				'type'    		=> ControlsManager::TEXT,
				'label_block' 	=> true,
				'description'   => $this->l('Leave it empty if you dont want to use button link.'),
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
            'banner_width',
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
			$this->addControl(
				'hor_align',
				[
					'label' => $this->l( 'Horizontal Alignment' ),
					'type' => ControlsManager::CHOOSE,
					'options' => [
						'flex-start' => [
							'title' => $this->l( 'Left' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => $this->l( 'Center' ),
							'icon' => 'fa fa-align-center',
						],
						'flex-end' => [
							'title' => $this->l( 'Right' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'center',
					'toggle' => true,
					'selectors' => [
						'{{WRAPPER}} .banner-content' => 'align-items: {{VALUE}};',
					],
				]
			);
			$this->addControl(
				'ver_align',
				[
					'label' => $this->l( 'Vertical Alignment' ),
					'type' => ControlsManager::CHOOSE,
					'options' => [
						'flex-start' => [
							'title' => $this->l( 'Top' ),
							'icon' => 'fa fa-long-arrow-up',
						],
						'center' => [
							'title' => $this->l( 'Middle' ),
							'icon' => 'fa fa-arrows-h',
						],
						'flex-end' => [
							'title' => $this->l( 'Bottom' ),
							'icon' => 'fa fa-long-arrow-down',
						],
					],
					'default' => 'center',
					'toggle' => true,
					'selectors' => [
						'{{WRAPPER}} .banner-content' => 'justify-content: {{VALUE}};',
					],
				]
			);
			
			$this->addResponsiveControl(
				'padding',
				[
					'label' => $this->l( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);


        $this->endControlsSection();

		$this->startControlsSection(
			'section_title_style',
			[
				'label' 		=> $this->l('Title 1'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);

			$this->addControl(
				'title_color',
				[
					'label' 		=> $this->l('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'title_typo',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-title',
				]
			);
			$this->addResponsiveControl(
				'title_spacing',
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
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .banner-content .banner-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
			'section_title2_style',
			[
				'label' 		=> $this->l('Title 2'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
		
			$this->addControl(
				'title2_color',
				[
					'label' 		=> $this->l('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-title2' => 'color: {{VALUE}};',
					],
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'title2_typo',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-title2',
				]
			);
			$this->addResponsiveControl(
				'title2_spacing',
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
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .banner-content .banner-title2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

        $this->endControlsSection();

		$this->startControlsSection(
			'section_subtitle_style',
			[
				'label' 		=> $this->l('Subtitle'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);

			$this->addControl(
				'subtitle_color',
				[
					'label' 		=> $this->l('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-text' => 'color: {{VALUE}};',
					],
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'subtitle_typo',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-text',
				]
			);
			$this->addResponsiveControl(
				'subtitle_spacing',
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
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .banner-content .banner-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

        $this->endControlsSection();

        $this->startControlsSection(
			'section_button',
			[
				'label' 		=> $this->l('Button link'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'button_typo',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-button',
				]
			);
			$this->addResponsiveControl(
				'button_padding',
				[
					'label' 		=> $this->l('Padding'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'button_border_radius',
				[
					'label' 		=> $this->l('Border Radius'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' 			=> 'button_border',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-button',
				]
			);
			$this->startControlsTabs('tabs_banner_style');
				$this->startControlsTab(
					'tab_button_normal',
					[
						'label' 		=> $this->l('Normal'),
					]
				);
					$this->addControl(
						'button_color',
						[
							'label' 		=> $this->l('Color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button' => 'color: {{VALUE}};',
							],
						]
					);

					$this->addControl(
						'button_background',
						[
							'label' 		=> $this->l('Background color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button' => 'background-color: {{VALUE}};',
							],
						]
					);
					
					
				$this->endControlsTab();
				$this->startControlsTab(
					'tab_hover_normal',
					[
						'label' 		=> $this->l('Hover'),
					]
				);
					$this->addControl(
						'button_hover_color',
						[
							'label' 		=> $this->l('Color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button:hover , {{WRAPPER}} .home-banner .banner-button:focus' => 'color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'button_hover_background',
						[
							'label' 		=> $this->l('Background color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button:hover, {{WRAPPER}} .home-banner .banner-button:focus' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'button_hover_border_color',
						[
							'label' 		=> $this->l('Border color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button:hover, {{WRAPPER}} .home-banner .banner-button:focus' => 'border-color: {{VALUE}};',
							],
						]
					);
					
				$this->endControlsTab();
			$this->endControlsTabs();
        $this->endControlsSection();

        $this->startControlsSection(
			'section_hover',
			[
				'label' 		=> $this->l('Hover'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
				'hover_opacity',
				[
					'label' 		=> $this->l('Opacity'),
					'type' 			=> ControlsManager::SLIDER,
					'range' 		=> [
						'px' => [
							'max' => 1,
							'min' => 0.10,
							'step' => 0.01,
						],
					],
					'selectors' 	=> [
						'body {{WRAPPER}} .home-banner img:hover' => 'opacity: {{SIZE}};',
					],
				]
			);
			$this->addControl(
				'hover_animation',
				[
					'label' => $this->l( 'Hover animation' ),
					'type' => ControlsManager::SELECT,
					'multiple' => false,
					'options' => [
						'' => 'None',
						'animation1'  => $this->l( 'Animation 1' ),
						'animation2' => $this->l( 'Animation 2' ),
						'animation3' => $this->l( 'Animation 3' ),
					],
					'default' =>  'animation1' ,
				]
			);
		
		$this->endControlsSection();

	}

	protected function render() {
		$settings 		= $this->getSettings();

		$title 			= $settings['title'];
		$title2 		= $settings['title2'];
		$subtitle 		= $settings['subtitle'];
        $link 			= $settings['link'];
        $button_link 	= $settings['button_link'];

		$this->addRenderAttribute('banner', 'class', ['home-banner', $settings['hover_animation']]);

		$this->addRenderAttribute('content', 'class', 'banner-content');
		$this->addRenderAttribute('title', 'class', 'banner-title');
		$this->addRenderAttribute('title2', 'class', 'banner-title2');
		$this->addRenderAttribute('subtitle', 'class', 'banner-text'); 
		$html = '';

		$html .= '<figure '.$this->getRenderAttributeString('banner').'>';
			if(! empty($link['url'])) {
				$this->addRenderAttribute('link', 'class', 'rt-banner-link');
				$this->addRenderAttribute('link', 'href', $link['url']);

				if($link['is_external']) {
					$this->addRenderAttribute('link', 'target', '_blank');
				}

				$html .= '<a ' . $this->getRenderAttributeString('link') . '>';
			} 
			$html .= GroupControlImageSize::getAttachmentImageHtml($settings);
			if(! empty($link['url'])) {
				$html .= '</a>';
			}
				$html .= '<figcaption>';
					$html .= '<div '. $this->getRenderAttributeString('content').'>';
						$html .= '<p '. $this->getRenderAttributeString('title') .'>'. $title .'</p>';
						$html .= '<p '. $this->getRenderAttributeString('title2') .'>'. $title2 .'</p>';
						$html .= '<div '. $this->getRenderAttributeString('subtitle') .'>'. $subtitle .'</div>';
						if(!empty($button_link) && !empty($link['url'])) { 
							$html .= '<a class="banner-button" href="'. $link['url'] .'">'. $button_link .'</a>';
						}
					$html .= '</div>';
				$html .= '</figcaption>';
			
		$html .= '</figure>';

	    echo $html;
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