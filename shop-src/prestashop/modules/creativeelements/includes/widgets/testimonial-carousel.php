<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

namespace CE;

defined('_PS_VERSION_') or die;

class WidgetTestimonialCarousel extends WidgetPremiumBase
{
    public function getName()
    {
        return 'testimonial-carousel';
    }

    public function getTitle()
    {
        return __('Testimonial Carousel', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-post-slider';
    }

    public function getDemoLink()
    {
        return 'https://pagebuilder.webshopworks.com/content/82-testimonial-carousel';
    }
}
