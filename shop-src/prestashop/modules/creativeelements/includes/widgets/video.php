<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2021 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or die;

class WidgetVideo extends WidgetBase
{
    protected $_current_instance = array();

    public function getName()
    {
        return 'video';
    }

    public function getTitle()
    {
        return __('Video', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-youtube';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_video',
            array(
                'label' => __('Video', 'elementor'),
            )
        );

        $this->addControl(
            'video_type',
            array(
                'label' => __('Video Type', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'youtube',
                'options' => array(
                    'youtube' => __('YouTube', 'elementor'),
                    'vimeo' => __('Vimeo', 'elementor'),
                    //'hosted' => __( 'HTML5 Video', 'elementor' ),
                ),
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => __('Link', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your YouTube link', 'elementor'),
                'default' => 'https://www.youtube.com/watch?v=9uOETcuFjbE',
                'label_block' => true,
                'condition' => array(
                    'video_type' => 'youtube',
                ),
            )
        );

        $this->addControl(
            'vimeo_link',
            array(
                'label' => __('Vimeo Link', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your Vimeo link', 'elementor'),
                'default' => 'https://vimeo.com/235215203',
                'label_block' => true,
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'hosted_link',
            array(
                'label' => __('Link', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your video link', 'elementor'),
                'default' => '',
                'label_block' => true,
                'condition' => array(
                    'video_type' => 'hosted',
                ),
            )
        );

        $this->addControl(
            'aspect_ratio',
            array(
                'label' => __('Aspect Ratio', 'elementor'),
                'type' => ControlsManager::SELECT,
                'frontend_available' => true,
                'options' => array(
                    '169' => '16:9',
                    '43' => '4:3',
                    '32' => '3:2',
                ),
                'default' => '169',
                'prefix_class' => 'elementor-aspect-ratio-',
            )
        );

        $this->addControl(
            'heading_youtube',
            array(
                'label' => __('Video Options', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        // YouTube
        $this->addControl(
            'yt_autoplay',
            array(
                'label' => __('Autoplay', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('No', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'condition' => array(
                    'video_type' => 'youtube',
                ),
                'default' => 'no',
            )
        );

        $this->addControl(
            'yt_rel',
            array(
                'label' => __('Suggested Videos', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'default' => 'no',
                'condition' => array(
                    'video_type' => 'youtube',
                ),
            )
        );

        $this->addControl(
            'yt_controls',
            array(
                'label' => __('Player Control', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'youtube',
                ),
            )
        );

        $this->addControl(
            'yt_showinfo',
            array(
                'label' => __('Player Title & Actions', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'youtube',
                ),
            )
        );

        // Vimeo
        $this->addControl(
            'vimeo_autoplay',
            array(
                'label' => __('Autoplay', 'elementor'),
                'type' => ControlsManager::SELECT,
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('No', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'default' => 'no',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_loop',
            array(
                'label' => __('Loop', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('No', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'default' => 'no',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_title',
            array(
                'label' => __('Intro Title', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_portrait',
            array(
                'label' => __('Intro Portrait', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_byline',
            array(
                'label' => __('Intro Byline', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_color',
            array(
                'label' => __('Controls Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'view',
            array(
                'label' => __('View', 'elementor'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'youtube',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_image_overlay',
            array(
                'label' => __('Image Overlay', 'elementor'),
                'type' => ControlsManager::SECTION,
            )
        );

        $this->addControl(
            'show_image_overlay',
            array(
                'label' => __('Image Overlay', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide', 'elementor'),
                'label_on' => __('Show', 'elementor'),
            )
        );

        $this->addControl(
            'image_overlay',
            array(
                'label' => __('Image', 'elementor'),
                'type' => ControlsManager::MEDIA,
                'default' => array(
                    'url' => Utils::getPlaceholderImageSrc(),
                ),
                'condition' => array(
                    'show_image_overlay' => 'yes',
                ),
            )
        );

        $this->addControl(
            'show_play_icon',
            array(
                'label' => __('Play Icon', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'yes',
                'options' => array(
                    'yes' => __('Yes', 'elementor'),
                    'no' => __('No', 'elementor'),
                ),
                'condition' => array(
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                ),
            )
        );

        $this->addControl(
            'lightbox',
            array(
                'label' => __('Lightbox', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'frontend_available' => true,
                'label_off' => __('Off', 'elementor'),
                'label_on' => __('On', 'elementor'),
                'condition' => array(
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'lightbox_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '#elementor-video-modal-{{ID}}' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                    'lightbox' => 'yes',
                ),
            )
        );

        $this->addControl(
            'lightbox_content_width',
            array(
                'label' => __('Content Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'units' => array('%'),
                'default' => array(
                    'unit' => '%',
                ),
                'range' => array(
                    '%' => array(
                        'min' => 50,
                    ),
                ),
                'selectors' => array(
                    '#elementor-video-modal-{{ID}} .dialog-widget-content' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                    'lightbox' => 'yes',
                ),
            )
        );

        $this->addControl(
            'lightbox_content_position',
            array(
                'label' => __('Content Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'center center',
                'frontend_available' => true,
                'options' => array(
                    'center center' => __('Center', 'elementor'),
                    'center top' => __('Top', 'elementor'),
                ),
                'condition' => array(
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                    'lightbox' => 'yes',
                ),
                'render_type' => 'none',
            )
        );

        $this->addControl(
            'lightbox_content_animation',
            array(
                'label' => __('Entrance Animation', 'elementor'),
                'type' => ControlsManager::ANIMATION,
                'default' => '',
                'frontend_available' => true,
                'label_block' => true,
                'condition' => array(
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                    'lightbox' => 'yes',
                ),
                'render_type' => 'none',
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getActiveSettings();

        $video_link = 'youtube' === $settings['video_type'] ? $settings['link'] : $settings['vimeo_link'];

        if (empty($video_link)) {
            return;
        }

        $video_html = Embed::getEmbedHtml($video_link, $this->getEmbedParams(), array('loading' => 'lazy'));

        if (!$video_html) {
            echo $video_link;

            return;
        }

        $this->addRenderAttribute('video-wrapper', 'class', 'elementor-wrapper');

        if (!$settings['lightbox']) {
            $this->addRenderAttribute('video-wrapper', 'class', 'elementor-video-wrapper');
        }

        $this->addRenderAttribute('video-wrapper', 'class', 'elementor-open-' . ($settings['lightbox'] ? 'lightbox' : 'inline'));
        ?>
        <div <?php echo $this->getRenderAttributeString('video-wrapper'); ?>>
            <?php echo $video_html; ?>

            <?php if ($this->hasImageOverlay()) :
                $this->addRenderAttribute('image-overlay', 'class', 'elementor-custom-embed-image-overlay');

                if (!$settings['lightbox']) {
                    $this->addRenderAttribute('image-overlay', 'style', 'background-image: url(' . Helper::getMediaLink($settings['image_overlay']['url']) . ');');
                }
                ?>
                <div <?php echo $this->getRenderAttributeString('image-overlay'); ?>>
                    <?php if ($settings['lightbox']) : ?>
                        <img src="<?php echo Helper::getMediaLink($settings['image_overlay']['url']); ?>">
                    <?php endif;?>
                    <?php if ('yes' === $settings['show_play_icon']) : ?>
                        <div class="elementor-custom-embed-play">
                            <i class="fa fa-play-circle"></i>
                        </div>
                    <?php endif;?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function renderPlainContent()
    {
        $settings = $this->getActiveSettings();

        echo 'youtube' === $settings['video_type'] ? $settings['link'] : $settings['vimeo_link'];
    }

    public function getEmbedParams()
    {
        $settings = $this->getSettings();

        $params = array();

        if ('youtube' === $settings['video_type']) {
            $youtube_options = array('autoplay', 'rel', 'controls', 'showinfo');

            foreach ($youtube_options as $option) {
                if ('autoplay' === $option && $this->hasImageOverlay()) {
                    continue;
                }

                $value = ('yes' === $settings['yt_' . $option]) ? '1' : '0';
                $params[$option] = $value;
            }

            $params['wmode'] = 'opaque';
        }

        if ('vimeo' === $settings['video_type']) {
            $vimeo_options = array('autoplay', 'loop', 'title', 'portrait', 'byline');

            foreach ($vimeo_options as $option) {
                if ('autoplay' === $option && $this->hasImageOverlay()) {
                    continue;
                }

                $value = ('yes' === $settings['vimeo_' . $option]) ? '1' : '0';
                $params[$option] = $value;
            }

            $params['color'] = str_replace('#', '', $settings['vimeo_color']);
        }

        return $params;
    }

    protected function hasImageOverlay()
    {
        $settings = $this->getSettings();
        return !empty($settings['image_overlay']['url']) && 'yes' === $settings['show_image_overlay'];
    }

    public function getScriptDepends()
    {
        return array(
            'elementor-dialog',
        );
    }
}
