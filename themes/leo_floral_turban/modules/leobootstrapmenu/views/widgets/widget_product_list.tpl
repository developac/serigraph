{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($products) && !empty($products)}
<div class="widget-products">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="menu-title">
		{$widget_heading}
	</div>
	{/if}
	<div class="widget-inner">
		{if isset($products) AND $products}
			<div class="product-block">
				{assign var='liHeight' value=140}
				{assign var='nbItemsPerLine' value=3}
				{assign var='nbLi' value=$limit}
				{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
				{math equation="nbLines*liHeight" nbLines=$nbLines|ceil liHeight=$liHeight assign=ulHeight}	 

				{$mproducts=array_chunk($products,$limit)}
				 
				{foreach from=$products item=product name=homeFeaturedProducts}
					{math equation="(total%perLine)" total=$smarty.foreach.homeFeaturedProducts.total perLine=$nbItemsPerLine assign=totModulo}
					{if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if} 
					<div class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
					  	<div class="thumbnail-container clearfix">
					    	<div class="product-image">
						      	{block name='product_thumbnail'}
							        <a href="{$product.url}" class="thumbnail product-thumbnail">
							          	<img
								            class="img-fluid"
								            src = "{$product.cover.bySize.small_default.url}"
								            alt = "{$product.cover.legend}"
								            data-full-size-image-url = "{$product.cover.large.url}"
							          	>
							        </a>
						      	{/block}
					    	</div>
						    <div class="product-meta">
						      	<div class="product-description">
							        {block name='product_name'}
							          	<h1 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></h1>
							        {/block}

							        {block name='product_price_and_shipping'}
							          	{if $product.show_price}
								            <div class="product-price-and-shipping">
								            	{hook h='displayProductPriceBlock' product=$product type="before_price"}
								            	<span class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
									                <span itemprop="priceCurrency" content="{$currency.iso_code}"></span><span itemprop="price" content="{$product.price_amount}">{$product.price}</span>
									            </span>


								              	{if $product.has_discount}
								                	{hook h='displayProductPriceBlock' product=$product type="old_price"}
								                	<span class="regular-price">{$product.regular_price}</span>
									                {if $product.discount_type === 'percentage'}
									                  	<span class="discount-percentage">{$product.discount_percentage}</span>
									                {/if}
								              	{/if}
												

								              	

								              	{hook h='displayProductPriceBlock' product=$product type='unit_price'}

								              	{hook h='displayProductPriceBlock' product=$product type='weight'}
								            </div>
							          	{/if}
							        {/block}
						      	</div>
						    </div>
					  	</div>
					</div>			
				{/foreach}
			</div>
		{/if}
	</div>
</div>
{/if}