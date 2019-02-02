{**
 *  Leo Prestashop Theme Framework for Prestashop 1.5.x
 *
 * @package   leotempcp
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 *
 **}

<!-- Block languages module -->
<div id="leo_block_top" class="pull-right popup-over">
    <a data-target="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="popup-title">
		<i class="fa fa-cog"></i>
		<span class="expand-more">{l s='Setting' d='Shop.Theme.Global'}</span>
        <i class="fa fa-angle-down"></i>
    </a>	    
	<div class="popup-content">
		<div class="language-selector">
			<span>{l s='Language:' d='Shop.Theme.Global'}</span>
			<ul class="link">
				{foreach from=$languages item=language}
			        <li {if $language.id_lang == $current_language.id_lang} class="current" {/if}>
		            	<a href="{url entity='language' id=$language.id_lang}" class="dropdown-item">
		            		<img src="{$img_lang_url|escape:'html':'UTF-8'}{$language.id_lang|escape:'html':'UTF-8'}.jpg" alt="{{$language.name_simple}|escape:'html':'UTF-8'}" width="16" height="11" />
		            	</a>
		          	</li>
		        {/foreach}
			</ul>
		</div>
		<div class="currency-selector">
			<span>{l s='Currency:' d='Shop.Theme.Global'}</span>
			<ul class="link">
				{foreach from=$currencies item=currency}
		        	<li {if $currency.current} class="current" {/if}>
		          		<a title="{$currency.name}" rel="nofollow" href="{$currency.url}" class="dropdown-item">{$currency.iso_code}</a>
		        	</li>
		      	{/foreach}
			</ul>
		</div>
		{if $enable_userinfo == 1}
				<div class="useinfo-selector">
					<ul class="user-info">
					{if $logged}
					<li>
					  <a
						class="account" 
						href="{$my_account_url}"
						title="{l s='View my customer account' d='Shop.Theme.CustomerAccount'}"
						rel="nofollow"
					  >
					  	<i class="material-icons">&#xE853;</i>
						<span>{l s='Hello' d='Shop.Theme.Global'} {$customerName}</span>
					  </a>
					</li>
					<li>
					  <a
						class="logout"
						href="{$logout_url}"
						rel="nofollow"
					  >
					  	<i class="material-icons">&#xE853;</i>
						{l s='Sign out' d='Shop.Theme.Actions'}
					  </a>
					</li>
					{else}
					<li>
					  <a
						class="signin"
						href="{$my_account_url}"
						title="{l s='Log in to your customer account' d='Shop.Theme.CustomerAccount'}"
						rel="nofollow"
					  >
					  	<i class="material-icons">&#xE88D;</i>
						<span>{l s='Sign in' d='Shop.Theme.Actions'}</span>
					  </a>
					</li>
					{/if}
					<li>
					<a
					  class="myacount"
					  href="{$my_account_url}"
					  title="{l s='My account' d='Shop.Theme.Global'}"
					  rel="nofollow"
					>
						<i class="material-icons">&#xE8A6;</i>
					  <span>{l s='My account' d='Shop.Theme.Global'}</span>
					</a>
					</li>
					<li>
					<a
					  class="checkout"
					  href="{url entity='cart' params=['action' => show]}"
					  title="{l s='Checkout' d='Shop.Theme.Global'}"
					  rel="nofollow"
					>
					  <i class="material-icons">&#xE890;</i>
					  <span>{l s='Checkout' d='Shop.Theme.Global'}</span>
					</a>
					</li>
					<li>
			      <a
			        class="ap-btn-wishlist dropdown-item"
			        href="{url entity='module' name='leofeature' controller='mywishlist'}"
			        title="{l s='Wishlist' d='Shop.Theme.Global'}"
			        rel="nofollow"
			      >
			      	<i class="material-icons">&#xE87E;</i>
			        <span>{l s='Wishlist' d='Shop.Theme.Global'}</span>
					<span class="ap-total-wishlist ap-total"></span>
			      </a>
			    </li>
				<li>
			      <a
			        class="ap-btn-compare dropdown-item"
			        href="{url entity='module' name='leofeature' controller='productscompare'}"
			        title="{l s='Compare' d='Shop.Theme.Global'}"
			        rel="nofollow"
			      >
			      	<i class="material-icons">&#xE3B9;</i>
			        <span>{l s='Compare' d='Shop.Theme.Global'}</span>
					<span class="ap-total-compare ap-total"></span>
			      </a>
			    </li>
					</ul>
				</div>
			{/if}
	</div>
</div>

<!-- /Block languages module -->
