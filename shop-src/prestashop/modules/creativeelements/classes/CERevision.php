<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

defined('_PS_VERSION_') or die;

class CERevision extends ObjectModel
{
    public $parent;
    public $id_employee;
    public $title;
    public $content;
    public $active;
    public $date_upd;

    public static $definition = array(
        'table' => 'ce_revision',
        'primary' => 'id_ce_revision',
        'fields' => array(
            'parent' => array('type' => self::TYPE_STRING, 'validate' => 'isIp2Long', 'required' => true),
            'id_employee' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'content' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'size' => 3999999999999),
            'active' => array('type' => self::TYPE_INT, 'validate' => 'isBool'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );
}
