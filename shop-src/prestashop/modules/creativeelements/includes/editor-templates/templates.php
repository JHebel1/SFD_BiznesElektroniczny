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
<script type="text/template" id="tmpl-elementor-template-library-header">
    <div id="elementor-template-library-header-logo-area"></div>
    <div id="elementor-template-library-header-menu-area"></div>
    <div id="elementor-template-library-header-items-area">
        <div id="elementor-template-library-header-close-modal" class="elementor-template-library-header-item" title="<?php _e('Close', 'elementor');?>">
            <i class="eicon-close" title="<?php _e('Close', 'elementor');?>"></i>
        </div>
        <div id="elementor-template-library-header-tools2"></div>
        <div id="elementor-template-library-header-tools"></div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-logo">
    <img src="<?php echo _MODULE_DIR_ ?>creativeelements/logo.png" width="20">
    <span><?php _e('Library', 'elementor');?></span>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-save">
    <i class="eicon-save" title="<?php _e('Save Template', 'elementor');?>"></i>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-import">
    <i class="material-icons">cloud_upload</i>
    <input type="file" accept=".json" id="elementor-template-library-header-import-file" title="<?php _e('Import Template', 'elementor');?>">
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-load">
    <i class="eicon-file-download" title="<?php _e('Import Template', 'elementor');?>" style="transform: translate(-50%, -50%) scale(-1);"></i>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-menu">
    <div id="elementor-template-library-menu-pre-made-templates" class="elementor-template-library-menu-item" data-template-source="remote"><?php _e('Predesigned Templates', 'elementor');?></div>
    <div id="elementor-template-library-menu-my-templates" class="elementor-template-library-menu-item" data-template-source="local"><?php _e('Templates list', 'elementor');?></div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-preview">
    <div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-template-library-header-item">
        {{{ elementor.templates.getLayout().getTemplateActionButton( isPro ) }}}
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-back">
    <i class="eicon-"></i><span><?php _e('Back To library', 'elementor');?></span>
</script>

<script type="text/template" id="tmpl-elementor-template-library-loading">
    <div class="elementor-loader-wrapper">
        <div class="elementor-loader">
            <div class="elementor-loader-box"></div>
            <div class="elementor-loader-box"></div>
            <div class="elementor-loader-box"></div>
            <div class="elementor-loader-box"></div>
        </div>
        <div class="elementor-loading-title"><?php _e('Loading', 'elementor')?></div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-templates">
    <div id="elementor-template-library-templates-container"></div>
    <div id="elementor-template-library-footer-banner">
        <i class="eicon-nerd"></i>
        <div class="elementor-excerpt"><?php echo __('Stay tuned! More awesome templates coming real soon.', 'elementor'); ?></div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-template-remote">
    <div class="elementor-template-library-template-body">
        <div class="elementor-template-library-template-screenshot" style="background-image: url('{{ thumbnail }}');"></div>
        <div class="elementor-template-library-template-controls">
            <div class="elementor-template-library-template-preview">
                <i class="fa fa-search-plus"></i>
            </div>
            {{{ elementor.templates.getLayout().getTemplateActionButton( isPro ) }}}
        </div>
    </div>
    <div class="elementor-template-library-template-name">{{{ title }}}</div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-insert-button">
    <button class="elementor-template-library-template-insert elementor-button elementor-button-success" data-action="insert">
        <i class="eicon-file-download"></i><span class="elementor-button-title"><?php _e('Insert', 'elementor'); ?></span>
    </button>
</script>

<script type="text/template" id="tmpl-elementor-template-library-get-pro-button">
    <button class="elementor-template-library-template-insert elementor-button elementor-button-go-pro" data-action="get-pro">
        <i class="fa fa-shopping-cart"></i><span class="elementor-button-title"><?php _e('Buy License', 'elementor'); ?></span>
    </button>
</script>

<script type="text/template" id="tmpl-elementor-template-library-template-local">
    <div class="elementor-template-library-template-icon">
        <i class="fa fa-{{ 'section' === type ? 'columns' : 'file-text-o' }}"></i>
    </div>
    <div class="elementor-template-library-template-name">{{{ title }}}</div>
    <div class="elementor-template-library-template-type">{{{ elementor.translate( type ) }}}</div>
    <div class="elementor-template-library-template-controls">
        <button class="elementor-template-library-template-insert elementor-button elementor-button-success" data-action="insert">
            <i class="eicon-file-download"></i><span class="elementor-button-title"><?php _e('Insert', 'elementor');?></span>
        </button>
        <div class="elementor-template-library-template-export">
            <a href="{{ export_link }}">
                <i class="fa fa-sign-out"></i><span class="elementor-template-library-template-control-title"><?php echo __('Export', 'elementor'); ?></span>
            </a>
        </div>
        <div class="elementor-template-library-template-delete">
            <i class="fa fa-trash-o"></i><span class="elementor-template-library-template-control-title"><?php echo __('Delete', 'elementor'); ?></span>
        </div>
        <div class="elementor-template-library-template-preview">
            <i class="eicon-zoom-in"></i><span class="elementor-template-library-template-control-title"><?php echo __('Preview', 'elementor'); ?></span>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-save-template">
    <div class="elementor-template-library-blank-title">{{{ title }}}</div>
    <div class="elementor-template-library-blank-excerpt">{{{ description }}}</div>
    <form id="elementor-template-library-save-template-form">
        <input id="elementor-template-library-save-template-name" name="title" placeholder="<?php _e('Enter Template Name', 'elementor');?>" required>
        <button id="elementor-template-library-save-template-submit" class="elementor-button elementor-button-success">
            <span class="elementor-state-icon">
                <i class="fa fa-spin fa-circle-o-notch "></i>
            </span>
            <?php _e('Save', 'elementor');?>
        </button>
    </form>
    <div class="elementor-template-library-blank-footer">
        <?php _e('What is Library?', 'elementor');?>
        <a class="elementor-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?php _e('Read our tutorial on using Library templates.', 'elementor');?></a>
    </div>
</script>


<script type="text/template" id="tmpl-elementor-template-library-load-template">
    <div class="elementor-template-library-blank-title">{{{ elementor.translate( 'load_your_template'  ) }}}</div>
    <div class="elementor-template-library-blank-excerpt"><?php _e('Import your .json design file from your local pc', 'elementor');?></div>
    <form id="elementor-template-library-load-template-form">
        <div id="elementor-template-library-load-wrapper">
        <button id="elementor-template-library-load-btn-file"><?php _e('Select template .json file', 'elementor');?></button>
        <input id="elementor-template-library-load-template-file" type="file" name="file" required>
        </div>
        <button id="elementor-template-library-load-template-submit" class="elementor-button elementor-button-success">
            <span class="elementor-state-icon">
                <i class="fa fa-spin fa-circle-o-notch "></i>
            </span>
            <?php _e('load', 'elementor');?>
        </button>
    </form>
</script>

<script type="text/template" id="tmpl-elementor-template-library-templates-empty">
    <div id="elementor-template-library-templates-empty-icon">
        <i class="eicon-nerd"></i>
    </div>
    <div class="elementor-template-library-blank-title"><?php _e('Haven’t Saved Templates Yet?', 'elementor');?></div>
    <div class="elementor-template-library-blank-excerpt"><?php _e('This is where your templates should be. Design it. Save it. Reuse it.', 'elementor');?></div>
    <div class="elementor-template-library-blank-footer">
        <?php _e('What is Library?', 'elementor');?>
        <a class="elementor-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?php _e('Read our tutorial on using Library templates.', 'elementor');?></a>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-preview">
    <?php echo "<\x69frame></\x69frame>"; ?>
</script>
