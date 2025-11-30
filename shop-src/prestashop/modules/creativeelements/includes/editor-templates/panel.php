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
?>
<script type="text/template" id="tmpl-elementor-panel">
    <div id="elementor-mode-switcher"></div>
    <header id="elementor-panel-header-wrapper"></header>
    <main id="elementor-panel-content-wrapper"></main>
    <footer id="elementor-panel-footer">
        <div class="elementor-panel-container">
        </div>
    </footer>
</script>

<script type="text/template" id="tmpl-elementor-panel-menu-item">
    <div class="elementor-panel-menu-item-icon">
        <i class="{{ icon }}"></i>
    </div>
    <div class="elementor-panel-menu-item-title">{{{ title }}}</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-header">
    <div id="elementor-panel-header-menu-button" class="elementor-header-button">
        <i class="elementor-icon eicon-menu tooltip-target" data-tooltip="<?php esc_attr_e('Menu', 'elementor');?>"></i>
    </div>
    <div id="elementor-panel-header-title"></div>
    <div id="elementor-panel-header-add-button" class="elementor-header-button">
        <i class="elementor-icon eicon-apps tooltip-target" data-tooltip="<?php esc_attr_e('Widgets Panel', 'elementor');?>"></i>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-footer-content">
    <div id="elementor-panel-footer-exit" class="elementor-panel-footer-tool">
        <i class="fa fa-times" title="<?php _e('Exit', 'elementor');?>"></i>
        <div class="elementor-panel-footer-sub-menu-wrapper">
            <div class="elementor-panel-footer-sub-menu">
                <a id="elementor-panel-footer-view-page" class="elementor-panel-footer-sub-menu-item" href="{{ elementor.config.post_permalink }}" target="_blank">
                    <i class="elementor-icon fa fa-external-link"></i>
                    <span class="elementor-title"><?php _e('View Page', 'elementor');?></span>
                </a>
                <a id="elementor-panel-footer-view-edit-page" class="elementor-panel-footer-sub-menu-item" href="{{ elementor.config.edit_post_link }}">
                    <i class="elementor-icon fa fa-arrow-left"></i>
                    <span class="elementor-title"><?php _e('Go to Back-office', 'elementor');?></span>
                </a>
            </div>
        </div>
    </div>
<?php if ((count($langs = \Language::getLanguages(true, false)) > 1 || \Shop::isFeatureActive()) && ($uid = get_the_ID()) && $uid->id_type != UId::TEMPLATE) : ?>
    <div id="elementor-panel-footer-lang" class="elementor-panel-footer-tool">
        <span class="elementor-screen-only"><?php _e('Language', 'elementor');?></span>
        <i class="fa fa-flag" title="<?php esc_attr_e('Language', 'elementor');?>"></i>
        <div class="elementor-panel-footer-sub-menu-wrapper">
        <?php if (\Shop::isFeatureActive() && count($shops = \Shop::getShops()) > 1) : ?>
            <form class="elementor-panel-footer-sub-menu" id="ce-context-wrapper" name="context" method="post">
                <?php
                $active_shop = \Shop::getContextShopID();
                $active_group = \Shop::getContextShopGroupID();

                $shop_ids = $uid->getShopIdList(true);
                $group_ids = array();
                $groups = array();

                foreach (\ShopGroup::getShopGroups() as $group) {
                    $groups[$group->id] = $group->name;
                }
                foreach ($shop_ids as $id_shop) {
                    $id_group = $shops[$id_shop]['id_shop_group'];
                    $group_ids[$id_group] = $id_group;
                }
                $star = ' ★ ';
                $tab1 = '🖿 &nbsp; &nbsp;';
                $tab2 = '&nbsp; ● &nbsp; &nbsp;';
                ?>
                <select name="setShopContext" id="ce-context">
                    <option value=""><?php echo __('All Shops', 'elementor') . (!$active_group ? $star : ''); ?></option>
                    <?php foreach ($group_ids as $id_group) : ?>
                        <?php $active = !$active_shop && $id_group == $active_group ? $star : ''; ?>
                        <option value="g-<?php echo $id_group; ?>" <?php echo $active ? 'selected' : '';?>><?php echo "$tab1{$groups[$id_group]}$active"; ?></option>
                        <?php foreach ($shop_ids as $id_shop) : ?>
                            <?php if ($shops[$id_shop]['id_shop_group'] == $id_group) : ?>
                                <?php $active = $id_shop == $active_shop ? $star : ''; ?>
                                <option value="s-<?php echo $id_shop;?>" <?php echo $active ? 'selected' : '';?>><?php echo "$tab2{$shops[$id_shop]['name']}$active";?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php endif; ?>
            <div class="elementor-panel-footer-sub-menu" id="ce-langs" data-lang="<?php echo $uid->id_lang;?>" data-built='<?php echo json_encode(UId::getBuiltList($uid->id, $uid->id_type));?>'>
                <?php foreach ($langs as &$lang) : ?>
                    <div class="elementor-panel-footer-sub-menu-item ce-lang" data-lang="<?php echo $lang['id_lang'];?>" data-shops='<?php echo json_encode(array_keys($lang['shops']));?>'>
                        <i class="elementor-icon"><?php echo $lang['iso_code'] ?></i>
                        <span class="elementor-title"><?php echo $lang['name']; ?></span>
                        <span class="elementor-description">
                            <button class="elementor-button elementor-button-success">
                                <i class="eicon-file-download"></i>
                                <?php _e('Insert', 'elementor');?>
                            </button>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif;?>
    <div id="elementor-panel-footer-responsive" class="elementor-panel-footer-tool">
        <i class="eicon-device-desktop" title="<?php esc_attr_e('Responsive Mode', 'elementor');?>"></i>
        <div class="elementor-panel-footer-sub-menu-wrapper">
            <div class="elementor-panel-footer-sub-menu">
                <div class="elementor-panel-footer-sub-menu-item" data-device-mode="desktop">
                    <i class="elementor-icon eicon-device-desktop"></i>
                    <span class="elementor-title"><?php _e('Desktop', 'elementor');?></span>
                    <span class="elementor-description"><?php _e('Default Preview', 'elementor');?></span>
                </div>
                <div class="elementor-panel-footer-sub-menu-item" data-device-mode="tablet">
                    <i class="elementor-icon eicon-device-tablet"></i>
                    <span class="elementor-title"><?php _e('Tablet', 'elementor');?></span>
                    <span class="elementor-description"><?php _e('Preview for 768px', 'elementor');?></span>
                </div>
                <div class="elementor-panel-footer-sub-menu-item" data-device-mode="mobile">
                    <i class="elementor-icon eicon-device-mobile"></i>
                    <span class="elementor-title"><?php _e('Mobile', 'elementor');?></span>
                    <span class="elementor-description"><?php _e('Preview for 360px', 'elementor');?></span>
                </div>
            </div>
        </div>
    </div>
    <div id="elementor-panel-footer-help" class="elementor-panel-footer-tool">
        <span class="elementor-screen-only"><?php _e('Help', 'elementor');?></span>
        <i class="fa fa-question-circle" title="<?php esc_attr_e('Help', 'elementor');?>"></i>
        <div class="elementor-panel-footer-sub-menu-wrapper">
            <div id="elementor-panel-footer-help-title"><?php _e('Need help?', 'elementor');?></div>
            <div class="elementor-panel-footer-sub-menu">
                <div id="elementor-panel-footer-watch-tutorial" class="elementor-panel-footer-sub-menu-item">
                    <i class="elementor-icon fa fa-video-camera"></i>
                    <span class="elementor-title"><?php _e('Take a tour', 'elementor');?></span>
                </div>
                <div class="elementor-panel-footer-sub-menu-item">
                    <i class="elementor-icon fa fa-external-link"></i>
                    <a class="elementor-title" href="<?php esc_attr_e('http://docs.webshopworks.com/creative-elements', 'elementor');?>" target="_blank"><?php _e('Go to the Documentation', 'elementor');?></a>
                </div>
            </div>
        </div>
    </div>
    <div id="elementor-panel-footer-templates" class="elementor-panel-footer-tool">
        <span class="elementor-screen-only"><?php _e('Templates', 'elementor');?></span>
        <i class="fa fa-folder" title="<?php esc_attr_e('Templates', 'elementor');?>"></i>
        <div class="elementor-panel-footer-sub-menu-wrapper">
            <div class="elementor-panel-footer-sub-menu">
                <div id="elementor-panel-footer-templates-modal" class="elementor-panel-footer-sub-menu-item">
                    <i class="elementor-icon fa fa-folder"></i>
                    <span class="elementor-title"><?php _e('Templates Library', 'elementor');?></span>
                </div>
                <div id="elementor-panel-footer-save-template" class="elementor-panel-footer-sub-menu-item">
                    <i class="elementor-icon fa fa-save"></i>
                    <span class="elementor-title"><?php _e('Save Template', 'elementor');?></span>
                </div>
            </div>
        </div>
    </div>
    <div id="elementor-panel-footer-save" class="elementor-panel-footer-tool" title="<?php esc_attr_e('Save', 'elementor');?>">
        <button class="elementor-button">
            <span class="elementor-state-icon">
                <i class="fa fa-spin fa-circle-o-notch "></i>
            </span>
            <?php _e('Save', 'elementor');?>
        </button>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-mode-switcher-content">
    <input id="elementor-mode-switcher-preview-input" type="checkbox">
    <label for="elementor-mode-switcher-preview-input" id="elementor-mode-switcher-preview" title="<?php esc_attr_e('Preview', 'elementor');?>">
        <span class="elementor-screen-only"><?php _e('Preview', 'elementor');?></span>
        <i class="fa"></i>
    </label>
</script>

<script type="text/template" id="tmpl-editor-content">
    <div class="elementor-panel-navigation">
        <# _.each( elementData.tabs_controls, function( tabTitle, tabSlug ) { #>
        <div class="elementor-panel-navigation-tab elementor-tab-control-{{ tabSlug }}" data-tab="{{ tabSlug }}">
            <a href="#">{{{ tabTitle }}}</a>
        </div>
        <# } ); #>
    </div>
    <# if ( elementData.reload_preview ) { #>
        <div class="elementor-update-preview">
            <div class="elementor-update-preview-title"><?php echo __('Update changes to page', 'elementor'); ?></div>
            <div class="elementor-update-preview-button-wrapper">
                <button class="elementor-update-preview-button elementor-button elementor-button-success"><?php echo __('Apply', 'elementor'); ?></button>
            </div>
        </div>
    <# } #>
    <div id="elementor-controls"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-schemes-disabled">
    <i class="elementor-panel-nerd-box-icon eicon-nerd"></i>
    <div class="elementor-panel-nerd-box-title">{{{ '<?php echo __('{0} are disabled', 'elementor'); ?>'.replace( '{0}', disabledTitle ) }}}</div>
    <div class="elementor-panel-nerd-box-message"><?php printf(__('You can enable it from the <a href="%s" target="_blank">Elementor settings page</a>.', 'elementor'), Settings::getUrl());?></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-scheme-color-item">
    <div class="elementor-panel-scheme-color-input-wrapper">
        <input type="text" class="elementor-panel-scheme-color-value" value="{{ value }}" data-alpha="true" />
    </div>
    <div class="elementor-panel-scheme-color-title">{{{ title }}}</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-scheme-typography-item">
    <div class="elementor-panel-heading">
        <div class="elementor-panel-heading-toggle">
            <i class="fa"></i>
        </div>
        <div class="elementor-panel-heading-title">{{{ title }}}</div>
    </div>
    <div class="elementor-panel-scheme-typography-items elementor-panel-box-content">
        <?php
        $scheme_fields_keys = GroupControlTypography::getSchemeFieldsKeys();

        $typography_group = Plugin::$instance->controls_manager->getControlGroups('typography');

        $typography_fields = $typography_group->getFields();

        $scheme_fields = array_intersect_key($typography_fields, array_flip($scheme_fields_keys));

        $system_fonts = Fonts::getFontsByGroups(array(Fonts::SYSTEM));

        $google_fonts = Fonts::getFontsByGroups(array(Fonts::GOOGLE, Fonts::EARLYACCESS));
        ?>
        <?php foreach ($scheme_fields as $option_name => $option) : ?>
            <div class="elementor-panel-scheme-typography-item">
                <div class="elementor-panel-scheme-item-title elementor-control-title"><?php echo $option['label']; ?></div>
                <div class="elementor-panel-scheme-typography-item-value">
                    <?php if ('select' === $option['type']) : ?>
                        <select name="<?php echo $option_name; ?>" class="elementor-panel-scheme-typography-item-field">
                            <?php foreach ($option['options'] as $field_key => $field_value) : ?>
                                <option value="<?php echo $field_key; ?>"><?php echo $field_value; ?></option>
                            <?php endforeach;?>
                        </select>
                    <?php elseif ('font' === $option['type']) : ?>
                        <select name="<?php echo $option_name; ?>" class="elementor-panel-scheme-typography-item-field">
                            <option value=""><?php _e('Default', 'elementor');?></option>

                            <optgroup label="<?php _e('System', 'elementor');?>">
                                <?php foreach ($system_fonts as $font_title => $font_type) : ?>
                                    <option value="<?php echo esc_attr($font_title); ?>"><?php echo $font_title; ?></option>
                                <?php endforeach;?>
                            </optgroup>

                            <optgroup label="<?php _e('Google', 'elementor');?>">
                                <?php foreach ($google_fonts as $font_title => $font_type) : ?>
                                    <option value="<?php echo esc_attr($font_title); ?>"><?php echo $font_title; ?></option>
                                <?php endforeach;?>
                            </optgroup>
                        </select>
                    <?php elseif ('text' === $option['type']) : ?>
                        <input name="<?php echo $option_name; ?>" class="elementor-panel-scheme-typography-item-field" />
                    <?php endif;?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-control-responsive-switchers">
    <div class="elementor-control-responsive-switchers">
        <a class="elementor-responsive-switcher elementor-responsive-switcher-desktop" data-device="desktop">
            <i class="eicon-device-desktop"></i>
        </a>
        <a class="elementor-responsive-switcher elementor-responsive-switcher-tablet" data-device="tablet">
            <i class="eicon-device-tablet"></i>
        </a>
        <a class="elementor-responsive-switcher elementor-responsive-switcher-mobile" data-device="mobile">
            <i class="eicon-device-mobile"></i>
        </a>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-revisions">
    <div class="elementor-panel-scheme-buttons">
        <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-discard">
            <button class="elementor-button" disabled>
                <i class="fa fa-times"></i><?php _e('Discard', 'elementor');?>
            </button>
        </div>
        <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-save">
            <button class="elementor-button elementor-button-success" disabled>
                <?php _e('Apply', 'elementor');?>
            </button>
        </div>
    </div>
    <div class="elementor-panel-box">
        <div class="elementor-panel-heading">
            <div class="elementor-panel-heading-title"><?php _e('Revision History', 'elementor');?></div>
        </div>
        <div id="elementor-revisions-list" class="elementor-panel-box-content"></div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-page-settings">
    <div class="elementor-panel-navigation">
        <# _.each( elementor.config.page_settings.tabs, function( tabTitle, tabSlug ) { #>
            <div class="elementor-panel-navigation-tab elementor-tab-control-{{ tabSlug }}" data-tab="{{ tabSlug }}">
                <a href="#">{{{ tabTitle }}}</a>
            </div>
            <# } ); #>
    </div>
    <div id="elementor-panel-page-settings-controls" class="elementor-panel-box"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-revisions-no-revisions">
    <i class="elementor-panel-nerd-box-icon eicon-nerd"></i>
    <div class="elementor-panel-nerd-box-title"><?php _e('No Revisions Saved Yet', 'elementor');?></div>
    <div class="elementor-panel-nerd-box-message">{{{ elementor.translate( elementor.config.revisions_enabled ? 'no_revisions_1' : 'revisions_disabled_1' ) }}}</div>
    <div class="elementor-panel-nerd-box-message">{{{ elementor.translate( elementor.config.revisions_enabled ? 'no_revisions_2' : 'revisions_disabled_2' ) }}}</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-revisions-revision-item">
    <div class="elementor-revision-item__gravatar">{{{ gravatar }}}</div>
    <div class="elementor-revision-item__details">
        <div class="elementor-revision-date">{{{ date }}}</div>
        <div class="elementor-revision-meta">{{{ elementor.translate( type ) }}} <?php _e('By', 'elementor');?> {{{ author }}}</div>
    </div>
    <div class="elementor-revision-item__tools">
        <i class="elementor-revision-item__tools-delete fa fa-times"></i>
        <i class="elementor-revision-item__tools-spinner fa fa-spin fa-circle-o-notch"></i>
    </div>
</script>
