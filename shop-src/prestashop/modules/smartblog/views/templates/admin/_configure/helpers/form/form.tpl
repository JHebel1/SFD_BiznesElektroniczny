{extends file="helpers/form/form.tpl"}

{block name="script"}
	$(document).ready(function() {
		$('.iframe-upload').fancybox({	
			'width'		: 900,
			'height'	: 600,
			'type'		: 'iframe',
			'autoScale' : false,
			'autoDimensions': false,
			'fitToView' : false,
			'autoSize' : false,
			onUpdate : function(){ 
				$('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
				$('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
			},
			afterShow: function(){
				$('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
				$('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
			}
		});
	});
{/block}

{block name="label"}

	{if $input.type == "line_label"}
    <hr>
    {elseif $input.type == "no_label"}

    {elseif $input.type == "block_label"}
        <div class="title-reparator" style="font-size: 14px;font-weight: bold;margin-bottom: 20px;background: #eeeeee;padding: 12px 0;">
        	<div class="col-lg-offset-3">{$input.label}</div>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}

{/block}

{block name="input"}

    {if $input.type == "chose_image"}
        <p> 
        	<input id="{$input.name}" type="text" name="{$input.name}" value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"> 
        </p>
        <a href="filemanager/dialog.php?type=1&field_id={$input.name}" class="btn btn-default iframe-upload"  data-input-name="{$input.name}" type="button">
        	{l s='Select image' mod='smartblog'} <i class="icon-angle-right"></i>
        </a>
    {elseif $input['type'] == "chose_style"}
        <div class="image-select">
            {foreach $input.values AS $option }
                <input id="{$input.name|escape:'html':'utf-8'}-{$option.value}" type="radio"
               name="{$input.name|escape:'html':'utf-8'}"
               class='hidden'
               value="{$option.value}" {if $fields_value[$input.name] == ''}{if $option@index eq 0} checked{/if}{/if} {if $option.value == $fields_value[$input.name]}checked{/if} />
                <div class="image-option">
                    <label for="{$input.name|escape:'html':'utf-8'}-{$option.value}">
                		<h4>{$option.name}</h4>
						<img src="{$option.img}" alt="{$option.name}" class="img-responsive"/>
                    </label>
                </div>
            {/foreach}
        </div>
    {else}
        {$smarty.block.parent}
    {/if}

{/block}

