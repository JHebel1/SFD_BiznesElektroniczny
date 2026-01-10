{if isset($posts) AND !empty($posts)}
<div class="pos-latestposts-widget">
  <div class="slick-slider-block {$class}" data-slider_options='{$slick_options}' data-slider_responsive='{$slick_responsive}'>
    {foreach from=$posts item="post"}
	  <div>
      {include file="$design" post=$post}
	  </div>
    {/foreach}
  </div>
</div>
{else}
  <p>{l s='There is no new post.'}</p>
{/if}