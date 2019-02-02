{*
 *  Leo Prestashop SliderShow for Prestashop 1.6.x
 *
 * @package   leosliderlayer
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<article class="blog-item">
	<div class="blog-image-container">
		{if $config->get('listing_show_title','1')}
			<h4 class="title">
				<a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}">{$blog.title|escape:'html':'UTF-8'}</a>
			</h4>
		{/if}
		<div class="blog-meta">
			{if $config->get('listing_show_author','1')&&!empty($blog.author)}
				<span class="blog-author">
					<i class="material-icons">person</i> <span>{l s='Posted By' d='Shop.Theme.Global'}:</span> 
					<a href="{$blog.author_link|escape:'html':'UTF-8'}" title="{$blog.author|escape:'html':'UTF-8'}">{$blog.author|escape:'html':'UTF-8'}</a> 
				</span>
			{/if}
			
			{if $config->get('listing_show_category','1')}
				<span class="blog-cat"> 
					<i class="material-icons">list</i> <span>{l s='In' d='Shop.Theme.Global'}:</span> 
					<a href="{$blog.category_link|escape:'html':'UTF-8'}" title="{$blog.category_title|escape:'html':'UTF-8'}">{$blog.category_title|escape:'html':'UTF-8'}</a>
				</span>
			{/if}
			
			{if $config->get('listing_show_created','1')}
				<span class="blog-created">
					<i class="material-icons">&#xE192;</i> <span>{l s='On' d='Shop.Theme.Global'}: </span> 
					<time class="date" datetime="{strtotime($blog.date_add)|date_format:"%Y"|escape:'html':'UTF-8'}">
						{l s=strtotime($blog.date_add)|date_format:"%A"|escape:'html':'UTF-8' d='Shop.Theme.Global'},	<!-- day of week -->
						{l s=strtotime($blog.date_add)|date_format:"%B"|escape:'html':'UTF-8' d='Shop.Theme.Global'}		<!-- month-->
						{l s=strtotime($blog.date_add)|date_format:"%e"|escape:'html':'UTF-8' d='Shop.Theme.Global'},	<!-- day of month -->
						{l s=strtotime($blog.date_add)|date_format:"%Y"|escape:'html':'UTF-8' d='Shop.Theme.Global'}		<!-- year -->
					</time>
				</span>
			{/if}
			
			{if isset($blog.comment_count)&&$config->get('listing_show_counter','1')}	
				<span class="blog-ctncomment">
					<i class="material-icons">comment</i> <span>{l s='Comment' d='Shop.Theme.Global'}:</span> 
					{$blog.comment_count|intval}
				</span>
			{/if}

			{if $config->get('listing_show_hit','1')}	
				<span class="blog-hit">
					<i class="material-icons">favorite</i> <span>{l s='Hit' d='Shop.Theme.Global'}:</span> 
					{$blog.hits|intval}
				</span>
			{/if}
		</div>
		{if $blog.image && $config->get('listing_show_image',1)}
		<div class="blog-image">
			<img src="{$blog.preview_url|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" alt="" class="img-fluid" />
		</div>
		{/if}
	</div>
	<div class="blog-info">
		{if $config->get('listing_show_description','1')}
			<div class="blog-shortinfo">
				{$blog.description|strip_tags:'UTF-8'|truncate:160:'...' nofilter}{* HTML form , no escape necessary *}
			</div>
		{/if}
		{if $config->get('listing_show_readmore',1)}
			<p>
				<a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" class="more btn btn-primary">{l s='Read more' d='Shop.Theme.Global'}</a>
			</p>
		{/if}
	</div>
</article>
	
{*
	Translation Day of Week - NOT REMOVE
	{l s='Sunday' d='Shop.Theme.Global'}
	{l s='Monday' d='Shop.Theme.Global'}
	{l s='Tuesday' d='Shop.Theme.Global'}
	{l s='Wednesday' d='Shop.Theme.Global'}
	{l s='Thursday' d='Shop.Theme.Global'}
	{l s='Friday' d='Shop.Theme.Global'}
	{l s='Saturday' d='Shop.Theme.Global'}
*}
{*	
	Translation Month - NOT REMOVE
	{l s='January' d='Shop.Theme.Global'}
	{l s='February' d='Shop.Theme.Global'}
	{l s='March' d='Shop.Theme.Global'}
	{l s='April' d='Shop.Theme.Global'}
	{l s='May' d='Shop.Theme.Global'}
	{l s='June' d='Shop.Theme.Global'}
	{l s='July' d='Shop.Theme.Global'}
	{l s='August' d='Shop.Theme.Global'}
	{l s='September' d='Shop.Theme.Global'}
	{l s='October' d='Shop.Theme.Global'}
	{l s='November' d='Shop.Theme.Global'}
	{l s='December' d='Shop.Theme.Global'}
*}