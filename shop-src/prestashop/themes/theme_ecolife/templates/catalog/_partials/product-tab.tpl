{if $postheme.productp_tab == '0' || $postheme.productp_tab == '1'}
<!-- Tab horizontal or vertical -->
<div class="tabs {if $postheme.productp_tab == '1'}tabs-left{/if}">  
    <ul class="nav nav-tabs" role="tablist">
      {if $product.description}
        <li class="nav-item">
           <a
             class="nav-link{if $product.description} active{/if}"
             data-toggle="tab"
             href="#description"
             role="tab"
             aria-controls="description"
             {if $product.description} aria-selected="true"{/if}>{l s='Description' d='Shop.Theme.Catalog'}</a>
        </li>
      {/if}
      <li class="nav-item">
        <a
          class="nav-link{if !$product.description} active{/if}"
          data-toggle="tab"
          href="#product-details"
          role="tab"
          aria-controls="product-details"
          {if !$product.description} aria-selected="true"{/if}>{l s='Product Details' d='Shop.Theme.Catalog'}</a>
      </li>
      {if $product.attachments}
        <li class="nav-item">
          <a
            class="nav-link"
            data-toggle="tab"
            href="#attachments"
            role="tab"
            aria-controls="attachments">{l s='Attachments' d='Shop.Theme.Catalog'}</a>
        </li>
      {/if}
      {foreach from=$product.extraContent item=extra key=extraKey}
        <li class="nav-item">
          <a
            class="nav-link"
            data-toggle="tab"
            href="#extra-{$extraKey}"
            role="tab"
            aria-controls="extra-{$extraKey}">{$extra.title}</a>
        </li>
      {/foreach}
	   <li class="nav-item">
		{hook h="displayProductTab"}
	   </li>
    </ul>

    <div class="tab-content" id="tab-content">
	 <div class="tab-pane fade in{if $product.description} active{/if}" id="description" role="tabpanel">
       {block name='product_description'}
         <div class="product-description">{$product.description nofilter}</div>
       {/block}
     </div>
     {block name='product_details'}
       {include file='catalog/_partials/product-details.tpl'}
     {/block}
     {block name='product_attachments'}
       {if $product.attachments}
        <div class="tab-pane fade in" id="attachments" role="tabpanel">
           <section class="product-attachments">
             <p class="h5 text-uppercase">{l s='Download' d='Shop.Theme.Actions'}</p>
             {foreach from=$product.attachments item=attachment}
               <div class="attachment">
                 <h4><a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.name}</a></h4>
                 <p>{$attachment.description}</p>
                 <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                   {l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
                 </a>
               </div>
             {/foreach}
           </section>
         </div>
       {/if}
     {/block}
     {foreach from=$product.extraContent item=extra key=extraKey}
     <div class="tab-pane fade in {$extra.attr.class}" id="extra-{$extraKey}" role="tabpanel" {foreach $extra.attr as $key => $val} {$key}="{$val}"{/foreach}>
       {$extra.content nofilter}
     </div>
     {/foreach}
	 {hook h="displayProductTabContent"}
  </div>
</div>
{else}
<!-- Tab accordion -->
<div class="accordion section" id="accordion">
	{if $product.description}
	<div class="card">
		<div class="card-header" id="headingOne">
		   <a class="collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
		    {l s='Description' d='Shop.Theme.Catalog'}				 
			</a>
		</div>
		<div id="collapseOne" class="collapse in" aria-labelledby="headingOne" data-parent="#accordion">
			<div class="card-body">
			   {block name='product_description'}
				<div class="product-description">{$product.description nofilter}</div>
			   {/block}
			</div>
		</div>
	</div>
	{/if}
	<div class="card">
		<div class="card-header" id="headingTwo">
			<a class="collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> 
				{l s='Product Details' d='Shop.Theme.Catalog'}
			</a>
		</div>
		<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
			<div class="card-body">
				 {block name='product_details'}
				   {include file='catalog/_partials/product-details.tpl'}
				 {/block}
			</div>
		</div>
	</div>
	{if $product.attachments}
	<div class="card">
		<div class="card-header" id="headingThree">
			<a class="collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			{l s='Attachments' d='Shop.Theme.Catalog'}
			</a>
		</div>
		<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
			<div class="card-body">
				{block name='product_attachments'}
				{if $product.attachments}
					<section class="product-attachments">
						<p class="h5 text-uppercase">{l s='Download' d='Shop.Theme.Actions'}</p>
						{foreach from=$product.attachments item=attachment}
						<div class="attachment">
						<h4><a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.name}</a></h4>
						<p>{$attachment.description}</p>
						<a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
						{l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
						</a>
						</div>
						{/foreach}
					</section>
				{/if}
				{/block}
			</div>
		</div>
	</div>
	{/if}
	{foreach from=$product.extraContent item=extra key=extraKey}
	<div class="card">
		<div class="card-header" id="headingFour">
			<a class="collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">{$extra.title}</a>
		</div>
		<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
			<div class="card-body">
				{foreach from=$product.extraContent item=extra key=extraKey}
				 <div class="{$extra.attr.class}" id="extra-{$extraKey}" {foreach $extra.attr as $key => $val} {$key}="{$val}"{/foreach}>
				   {$extra.content nofilter}
				 </div>
				 {/foreach}
			</div>
		</div>
	</div>
	{/foreach}
	<div class="card">
		<div class="card-header" id="headingFive">
			{hook h="displayProductTab"}
		</div>
		<div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
			<div class="card-body">
				{hook h="displayProductTabContent"}
			</div>
		</div>
	</div>
</div>  
{/if}