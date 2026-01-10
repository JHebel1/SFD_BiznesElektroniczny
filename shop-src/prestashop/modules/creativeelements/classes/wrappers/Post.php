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

class Post
{
    private $id;
    private $uid;
    private $model;

    public $_obj;

    public $post_author = 0;
    public $post_parent = 0;
    public $post_date = '';
    public $post_modified = '';
    public $post_title = '';
    public $post_excerpt = '';
    public $post_content = '';
    public $template_type;

    private function __construct()
    {
    }

    public function __get($prop)
    {
        switch ($prop) {
            case 'uid':
                $val = $this->uid;
                break;
            case 'ID':
            case 'post_ID':
                $val = $this->id;
                break;
            case 'post_type':
                $val = $this->model;
                break;
            case 'post_status':
                $active = property_exists($this->_obj, 'active') ? 'active' : (
                    property_exists($this->_obj, 'enabled') ? 'enabled' : 'actif'
                );
                $val = $this->_obj->$active ? 'publish' : 'draft';
                break;
            default:
                throw new \RuntimeException('TODO');
                break;
        }
        return $val;
    }

    public function __set($prop, $val)
    {
        switch ($prop) {
            case 'ID':
            case 'post_ID':
                // allow change only when starts with zero
                empty($this->id[0]) && $this->id = "$val";
                break;
            case 'post_type':
                // readonly
                break;
            case 'post_status':
                $active = property_exists($this->_obj, 'active') ? 'active' : 'actif';
                $this->_obj->$active = 'publish' == $val;
                break;
            default:
                throw new \RuntimeException('TODO');
                break;
        }
    }

    public function getLangId()
    {
        return (int) \Tools::substr($this->id, -4, 2);
    }

    public static function getInstance(UId $uid, array &$postarr = null)
    {
        $self = new self();
        $self->id = "$uid";
        $self->uid = $uid;
        $self->model = $uid->getModel();
        $objectModel = '\\' . $self->model;

        if ($postarr) {
            $obj = (object) $postarr;
        } elseif ($uid->id_type <= UId::TEMPLATE) {
            $obj = new $objectModel($uid->id ? $uid->id : null);

            property_exists($obj, 'type') && $self->template_type = &$obj->type;
        } elseif ($uid->id_type === UId::PRODUCT) {
            $obj = new \Product($uid->id, false, $uid->id_lang, $uid->id_shop);
        } else {
            $obj = new $objectModel($uid->id, $uid->id_lang, $uid->id_shop);
        }
        $self->_obj = $obj;

        property_exists($obj, 'id_employee') && $self->post_author = &$obj->id_employee;
        property_exists($obj, 'parent') && $self->post_parent = &$obj->parent;
        property_exists($obj, 'date_add') && $self->post_date = &$obj->date_add;
        property_exists($obj, 'date_upd') && $self->post_modified = &$obj->date_upd;

        if (property_exists($obj, 'title')) {
            $self->post_title = &$obj->title;
        } elseif (property_exists($obj, 'name')) {
            $self->post_title = &$obj->name;
        } elseif (property_exists($obj, 'meta_title')) {
            $self->post_title = &$obj->meta_title;
        }

        if (property_exists($obj, 'content')) {
            $self->post_content = &$obj->content;
        } elseif (property_exists($obj, 'description')) {
            $self->post_content = &$obj->description;
        } elseif (property_exists($obj, 'post_content')) {
            $self->post_content = &$obj->post_content;
        }
        return $self;
    }
}

function get_post($post = null, $output = 'OBJECT', $filter = 'raw')
{
    if (null === $post || 0 === $post) {
        $post = get_the_ID();
    }

    if (false === $post || $post instanceof Post) {
        $_post = $post;
    } elseif ($post instanceof UId) {
        $_post = Post::getInstance($post);
    } elseif (is_numeric($post)) {
        $_post = Post::getInstance(UId::parse($post));
    } else {
        _doing_it_wrong(__CLASS__ . '::' . __FUNCTION__, 'Invalid $post argument!');
    }

    if (!$_post) {
        return null;
    }
    if ('OBJECT' != $output) {
        throw new \RuntimeException('todo');
    }
    return $_post;
}

function wp_update_post($postarr = array(), $wp_error = false)
{
    if (is_array($postarr)) {
        $id_key = isset($postarr['ID']) ? 'ID' : 'post_ID';

        if (empty($postarr[$id_key])) {
            _doing_it_wrong(__FUNCTION__, 'ID is missing!');
        }
        $post = get_post($postarr[$id_key]);

        foreach ($postarr as $key => $value) {
            $post->{$key} = $value;
        }
    } elseif ($postarr instanceof Post) {
        $post = $postarr;
    }

    if (!isset($post) || $wp_error) {
        throw new \RuntimeException('TODO');
    }
    // Fix for required lang properties must be defined on default language
    $id_lang_def = \Configuration::get('PS_LANG_DEFAULT');
    \Configuration::set('PS_LANG_DEFAULT', $post->uid->id_lang);

    try {
        // Fix: category groups would lose after save
        if ($post->_obj instanceof \Category) {
            $post->_obj->groupBox = $post->_obj->getGroups();
        }
        $res = @$post->_obj->update();
    } catch (\Exception $ex) {
        $res = false;
    }
    \Configuration::set('PS_LANG_DEFAULT', $id_lang_def);

    if (!$res) {
        return 0;
    }
    wp_save_post_revision($post);

    return $post->ID;
}

function wp_insert_post(array $postarr, $wp_error = false)
{
    $is_revision = 'CERevision' == $postarr['post_type'];

    if ($wp_error || !$is_revision && 'CETemplate' != $postarr['post_type']) {
        throw new \RuntimeException('TODO');
    }
    $uid = new UId(0, $is_revision ? UId::REVISION : UId::TEMPLATE);
    $post = Post::getInstance($uid);
    $postarr['post_author'] = \Context::getContext()->employee->id;

    foreach ($postarr as $key => &$value) {
        $post->$key = $value;
    }
    if ($post->_obj->add()) {
        $uid->id = $post->_obj->id;
        $post->ID = "$uid";
    } else {
        $post->ID = 0;
    }
    return $post->ID;
}

function wp_delete_post($postid, $force_delete = false)
{
    $post = get_post($postid);

    return $post->_obj->delete() || $force_delete ? $post : false;
}

function get_post_meta($id, $key = '', $single = false)
{
    if (false === $id) {
        return $id;
    }
    $table = _DB_PREFIX_ . 'ce_meta';
    $id = ($uid = UId::parse($id)) ? $uid->toDefault() : preg_replace('/\D+/', '', $id);

    if (!is_numeric($id)) {
        _doing_it_wrong(__FUNCTION__, 'Id must be numeric!');
    }
    if (!$key) {
        $res = array();
        $rows = \Db::getInstance()->executeS("SELECT name, value FROM $table WHERE id = $id");

        if (!empty($rows)) {
            foreach ($rows as &$row) {
                $key = &$row['name'];
                $val = &$row['value'];

                isset($res[$key]) or $res[$key] = array();
                $res[$key][] = isset($val[0]) && ('{' == $val[0] || '[' == $val[0] || '"' == $val[0]) ? json_decode($val, true) : $val;
            }
        }
        return $res;
    }
    $key = preg_replace('/\W+/', '', $key);

    if (!$single) {
        throw new \RuntimeException('TODO');
    }
    $val = \Db::getInstance()->getValue("SELECT value FROM $table WHERE id = $id AND name = '$key'");

    return isset($val[0]) && ('{' == $val[0] || '[' == $val[0] || '"' == $val[0]) ? json_decode($val, true) : $val;
}

function update_post_meta($id, $key, $value, $prev_value = '')
{
    if ($prev_value) {
        throw new \RuntimeException('TODO');
    }
    $db = \Db::getInstance();
    $table = _DB_PREFIX_ . 'ce_meta';
    $res = true;
    $ids = ($uid = UId::parse($id)) ? $uid->getListByShopContext() : (array) $id;
    $data = array(
        'name' => preg_replace('/\W+/', '', $key),
        'value' => $db->escape(is_array($value) || is_object($value) ? json_encode($value) : $value, true),
    );
    foreach ($ids as $id) {
        $data['id'] = preg_replace('/\D+/', '', $id);
        $id_ce_meta = $db->getValue("SELECT id_ce_meta FROM $table WHERE id = {$data['id']} AND name = '{$data['name']}'");

        if ($id_ce_meta) {
            $data['id_ce_meta'] = (int) $id_ce_meta;
            $type = \Db::REPLACE;
        } else {
            unset($data['id_ce_meta']);
            $type = \Db::INSERT;
        }
        $res &= $db->insert($table, $data, false, true, $type, false);
    }
    return $res;
}

function delete_post_meta($id, $key, $value = '')
{
    if ($value) {
        throw new \RuntimeException('TODO');
    }
    $ids = ($uid = UId::parse($id)) ? $uid->getListByShopContext() : (array) $id;

    foreach ($ids as &$id) {
        $id = preg_replace('/\D+/', '', $id);
    }
    if (count($ids) > 1) {
        $in = 'IN';
        $ids = '(' . implode(', ', $ids) . ')';
    } else {
        $in = '=';
        $ids = $ids[0];
    }
    $key = preg_replace('/[^\w\%]+/', '', $key);
    $like = stripos($key, '%') === false ? '=' : 'LIKE';

    return \Db::getInstance()->delete('ce_meta', "id $in $ids AND name $like '$key'");
}

// function update_metadata($type, $id, $key, $value, $prev_value = '')
// {
//     if ('post' === $type) {
//         return update_post_meta($id, $key, $value, $prev_value);
//     }
//     throw new \RuntimeException('TODO');
// }

function get_post_type($post = null)
{
    is_null($post) && $post = get_the_ID();

    if ($post instanceof Post) {
        return UId::parse($post->ID)->getModel();
    } elseif ($post instanceof UId) {
        return $post->getModel();
    } elseif (is_numeric($post)) {
        return UId::parse($post)->getModel();
    }
    return false;
}

function get_post_type_object($post_type)
{
    // todo
    return !$post_type ? null : (object) array(
        'cap' => (object) array(
            'edit_post' => 'edit',
            'publish_posts' => 'edit',
        ),
    );
}

function current_user_can($capability, $post_id = null)
{
    $context = \Context::getContext();

    if (empty($context->employee->id_profile)) {
        return false;
    }

    if (null === $post_id) {
        $post_id = get_the_ID();
    } elseif (is_numeric($post_id)) {
        $post_id = UId::parse($post_id);
    } elseif (!$post_id instanceof UId) {
        _doing_it_wrong(__FUNCTION__, 'Invalid $post_id argument!');
    }
    $controller = $post_id->getAdminController();

    if ('AdminModules' === $controller) {
        $id_module = \Module::getModuleIdByName($post_id->getModule());
        $action = 'view' === $capability ? $capability : 'configure';
        $result = \Module::getPermissionStatic($id_module, $action, $context->employee);
    } else {
        $id_tab = \Tab::getIdFromClassName($controller);
        $access = \Profile::getProfileAccess($context->employee->id_profile, $id_tab);
        $result = '1' === $access[$capability];
    }
    return $result;
}

function wp_create_post_autosave(array $post_data)
{
    $post_id = isset($post_data['ID']) ? $post_data['ID'] : $post_data['post_ID'];

    unset($post_data['ID'], $post_data['post_ID']);

    // Autosave already deleted in saveEditor method
    // $autosave = wp_get_post_autosave($post_id, get_current_user_id());

    // if ($autosave) {
    //     foreach ($post_data as $key => $value) {
    //         $autosave->{$key} = $value;
    //     }
    //     return $autosave->_obj->update() ? $autosave->ID : 0;
    // }
    $post_data['post_type'] = 'CERevision';
    $post_data['post_status'] = 'draft';
    $post_data['post_parent'] = $post_id;

    $autosave_id = wp_insert_post($post_data);

    if ($autosave_id) {
        do_action('_wp_put_post_revision', $autosave_id);

        return $autosave_id;
    }
    return 0;
}

function wp_get_post_autosave(UId $post_id, $user_id)
{
    $table = _DB_PREFIX_ . 'ce_revision';
    $parent = $post_id->toDefault();
    $id_employee = (int) $user_id;

    $id = \Db::getInstance()->getValue(
        "SELECT id_ce_revision FROM $table WHERE parent = $parent AND active = 0 AND id_employee = $id_employee"
    );
    return $id ? Post::getInstance(new UId($id, UId::REVISION)) : false;
}

function wp_get_post_revisions($post_id, $args = null)
{
    if ($post_id instanceof UId) {
        $uid = $post_id;
    } elseif ($post_id instanceof Post) {
        $uid = $post_id->uid;
    } elseif (is_numeric($post_id)) {
        $uid = UId::parse($post_id);
    }
    if (empty($uid)) {
        _doing_it_wrong(__CLASS__ . '::' . __FUNCTION__, 'Invalid $post_id argument!');
    }
    $parent = $uid->toDefault();
    $revisions = array();
    $table = _DB_PREFIX_ . 'ce_revision';
    $fields = !empty($args['fields']) && 'ids' === $args['fields'] ? 'id_ce_revision' : '*';
    $id_employee = (int) \Context::getContext()->employee->id;
    $limit = !empty($args['posts_per_page']) ? 'LIMIT ' . (int) $args['posts_per_page'] : '';

    $rows = \Db::getInstance()->executeS(
        "SELECT $fields FROM $table
        WHERE parent = $parent AND (active = 1 OR id_employee = $id_employee)
        ORDER BY date_upd DESC $limit"
    );
    if (!empty($rows)) {
        foreach ($rows as &$row) {
            $uid = new UId($row['id_ce_revision'], UId::REVISION);

            if ('*' === $fields) {
                $row['id'] = $row['id_ce_revision'];
                $revisions[] = Post::getInstance($uid, $row);
            } else {
                $revisions[] = "$uid";
            }
        }
    }
    return $revisions;
}

function wp_is_post_revision($post)
{
    $revision = get_post($post);

    return !empty($revision->_obj->parent) ? $revision->_obj->parent : false;
}

function wp_save_post_revision(Post $post)
{
    $revisions_to_keep = (int) \Configuration::get('elementor_max_revisions');

    if (!$revisions_to_keep) {
        return;
    }
    $db = \Db::getInstance();
    $table = _DB_PREFIX_ . 'ce_revision';

    $data = get_post_meta($post->uid, '_elementor_data', true);
    $page_settings = get_post_meta($post->uid, PageSettingsManager::META_KEY, true);
    $id_employee = \Context::getContext()->employee->id;

    foreach (array_reverse($post->uid->getListByShopContext(true)) as $parent) {
        $revisions = $db->executeS(
            "SELECT id_ce_revision AS id FROM $table WHERE parent = $parent AND active = 1 ORDER BY date_upd DESC"
        );
        if (isset($revisions[0])) {
            $latest = new UId($revisions[0]['id'], UId::REVISION);

            if (get_post_meta($latest, '_elementor_data', true) === $data &&
                get_post_meta($latest, PageSettingsManager::META_KEY, true) === $page_settings
            ) {
                $return = null;
                continue;
            }
        }
        $return = wp_insert_post(array(
            'post_type' => 'CERevision',
            'post_status' => 'publish',
            'post_author' => $id_employee,
            'post_parent' => "$parent",
            'post_title' => $post->post_title,
            'post_content' => $post->post_content,
        ));
        if (!$return) {
            $return = 0;
            continue;
        }
        do_action('_wp_put_post_revision', $return);

        for ($i = $revisions_to_keep - 1; isset($revisions[$i]); $i++) {
            wp_delete_post_revision(new UId($revisions[$i]['id'], UId::REVISION));
        }
    }
    return $return;
}

function wp_delete_post_revision($revision_id)
{
    $revision = get_post($revision_id);

    if ('CERevision' !== $revision->post_type) {
        return false;
    }
    return $revision->_obj->delete();
}

function get_post_statuses()
{
    return array (
        'draft' => __('Disabled', 'elementor'),
        'publish' => __('Enabled', 'elementor'),
    );
}

function get_page_templates(Post $post = null, $post_type = 'CMS')
{
    $templates = array();
    $post_type = $post ? $post->post_type : $post_type;

    return apply_filters("theme_{$post_type}_templates", $templates);
}

function get_post_types_by_support($feature, $operator = 'and')
{
    if ('elementor' !== $feature) {
        throw new RuntimeException('TODO');
    }
    return array(
        'CETemplate',
        'CMS',
        'CMSCategory',
    );
}
