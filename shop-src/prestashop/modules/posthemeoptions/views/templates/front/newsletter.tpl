<div class="pos-newsletter-widget block_newsletter">
<form class="pos-newsletter-form" action="{$urls.current_url}#footer" method="post">
    <input name="email" type="email" value="{$value}" placeholder="{if !empty($settings.placeholder)}{$settings.placeholder}{else}{l s='Your email address' d='Shop.Forms.Labels'}{/if}" required >
    <button class="pos-newsletter-button" name="submitNewsletter" value="1" type="submit">
        <span>
            {l s='Subscribe' d='Shop.Theme.Actions'}
        </span>
    </button>
    <input type="hidden" name="action" value="0">
    <div class="pos-newsletter-response"></div>
    {hook h='displayNewsletterRegistration'}
    {if isset($id_module) && !$settings.disable_psgdpr}
        {hook h='displayGDPRConsent' id_module=$id_module}
    {/if}
</form>
</div>