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

abstract class SkinBase
{
    /**
     * @var WidgetBase|null
     */
    protected $parent = null;

    /**
     * Skin_Base constructor.
     *
     * @param WidgetBase $parent
     */
    public function __construct(WidgetBase $parent)
    {
        $this->parent = $parent;

        $this->_registerControlsActions();
    }

    abstract public function getId();

    abstract public function getTitle();

    abstract public function render();

    public function _contentTemplate()
    {
    }

    protected function _registerControlsActions()
    {
    }

    protected function getControlId($control_base_id)
    {
        $skin_id = str_replace('-', '_', $this->getId());
        return $skin_id . '_' . $control_base_id;
    }

    public function getInstanceValue($control_base_id)
    {
        $control_id = $this->getControlId($control_base_id);
        return $this->parent->getSettings($control_id);
    }

    public function startControlsSection($id, $args)
    {
        $args['condition']['_skin'] = $this->getId();
        $this->parent->startControlsSection($this->getControlId($id), $args);
    }

    public function endControlsSection()
    {
        $this->parent->endControlsSection();
    }

    public function addControl($id, $args)
    {
        $args['condition']['_skin'] = $this->getId();
        return $this->parent->addControl($this->getControlId($id), $args);
    }

    public function updateControl($id, $args)
    {
        $this->parent->updateControl($this->getControlId($id), $args);
    }

    public function removeControl($id)
    {
        $this->parent->removeControl($this->getControlId($id));
    }

    public function addResponsiveControl($id, $args)
    {
        $args['condition']['_skin'] = $this->getId();
        $this->parent->addResponsiveControl($this->getControlId($id), $args);
    }

    public function updateResponsiveControl($id, $args)
    {
        $this->parent->updateResponsiveControl($this->getControlId($id), $args);
    }

    public function removeResponsiveControl($id)
    {
        $this->parent->removeResponsiveControl($this->getControlId($id));
    }

    final public function addGroupControl($group_name, $args = array())
    {
        $args['name'] = $this->getControlId($args['name']);
        $args['condition']['_skin'] = $this->getId();
        $this->parent->addGroupControl($group_name, $args);
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }
}
