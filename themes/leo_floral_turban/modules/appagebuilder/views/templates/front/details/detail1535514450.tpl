{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}

<section id="main" class="product-detail detail-1535514450 product-image-thumbs product-thumbs-left" itemscope itemtype="https://schema.org/Product">
  <meta itemprop="url" content="{$product.url}"><div class="row"><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 col-sp-12">
{block name='page_content_container'}
  <section class="page-content" id="content" data-templateview="left" data-numberimage="5" data-numberimage1200="4" data-numberimage992="6" data-numberimage768="5" data-numberimage576="4" data-numberimage480="2" data-numberimage360="2" data-templatemodal="1" data-templatezoomtype="in" data-zoomposition="right" data-zoomwindowwidth="400" data-zoomwindowheight="400">
    {block name='page_content'}
      <div class="images-container">
        {block name='product_cover_thumbnails'}

          {block name='product_cover'}
            <div class="product-cover">
              {block name='product_flags'}
                <ul class="product-flags">
                  {foreach from=$product.flags item=flag}
                    <li class="product-flag {$flag.type}">{$flag.label}</li>
                  {/foreach}
                </ul>
              {/block}
              <img id="zoom_product" data-type-zoom="" class="js-qv-product-cover img-fluid" src="{$product.cover.bySize.large_default.url}" alt="{$product.cover.legend}" title="{$product.cover.legend}" itemprop="image">
              <div class="layer hidden-sm-down" data-toggle="modal" data-target="#product-modal">
                <i class="material-icons zoom-in">&#xE8FF;</i>
              </div>
            </div>
          {/block}

          {block name='product_images'}
            <div id="thumb-gallery" class="product-thumb-images">
              {foreach from=$product.images item=image}
                <div class="thumb-container {if $image.id_image == $product.cover.id_image} active {/if}">
                  <a href="javascript:void(0)" data-image="{$image.bySize.large_default.url}" data-zoom-image="{$image.bySize.large_default.url}"> 
                    <img
                      class="thumb js-thumb {if $image.id_image == $product.cover.id_image} selected {/if}"
                      data-image-medium-src="{$image.bySize.medium_default.url}"
                      data-image-large-src="{$image.bySize.large_default.url}"
                      src="{$image.bySize.home_default.url}"
                      alt="{$image.legend}"
                      title="{$image.legend}"
                      itemprop="image"
                    >
                  </a>
                </div>
              {/foreach}
            </div>
            
            {if $product.images|@count > 1}
        			<div class="arrows-product-fake slick-arrows">
        			  <button class="slick-prev slick-arrow" aria-label="Previous" type="button" >{l s='Previous' d='Shop.Theme.Catalog'}</button>
        			  <button class="slick-next slick-arrow" aria-label="Next" type="button">{l s='Next' d='Shop.Theme.Catalog'}</button>
        			</div>
            {/if}
          {/block}

        {/block}
        {hook h='displayAfterProductThumbs'}
      </div>
    {/block}
  </section>
{/block}

{block name='product_images_modal'}
  {include file='catalog/_partials/product-images-modal.tpl'}
{/block}
                            </div><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 col-sp-12">
{block name='page_header_container'}
	{block name='page_header'}
		<h1 class="h1 product-detail-name" itemprop="name">{block name='page_title'}{$product.name}{/block}</h1>
	{/block}
{/block}
{block name='product_additional_info'}
	{include file='catalog/_partials/product-additional-info.tpl'}
{/block}
{hook h='displayLeoProductReviewExtra' product=$product}
{block name='product_prices'}
	{include file='catalog/_partials/product-prices.tpl'}
{/block}

{block name='product_description_short'}
  <div id="product-description-short-{$product.id}" class="description-short" itemprop="description">{$product.description_short nofilter}</div>
{/block}
{if $product.is_customizable && count($product.customizations.fields)}
	{block name='product_customization'}
	 	{include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
	{/block}
{/if}
<div class="product-actions">
  {block name='product_buy'}
    <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
      <input type="hidden" name="token" value="{$static_token}">
      <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
      <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">

      {block name='product_variants'}
        {include file='catalog/_partials/product-variants.tpl'}
      {/block}

      {block name='product_pack'}
        {if $packItems}
          <section class="product-pack">
            <h3 class="h4">{l s='This pack contains' d='Shop.Theme.Catalog'}</h3>
            {foreach from=$packItems item="product_pack"}
              {block name='product_miniature'}
                {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack}
              {/block}
            {/foreach}
        </section>
        {/if}
      {/block}

      {block name='product_discounts'}
        {include file='catalog/_partials/product-discounts.tpl'}
      {/block}

      {block name='product_add_to_cart'}
        {include file='catalog/_partials/product-add-to-cart.tpl'}
      {/block}

      {block name='product_refresh'}
        <input class="product-refresh ps-hidden-by-js" name="refresh" type="submit" value="{l s='Refresh' d='Shop.Theme.Actions'}">
      {/block}
    </form>
  {/block}
</div>
                            </div><div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12 col-sp-12">
{block name='hook_display_reassurance'}
  {hook h='displayReassurance'}
{/block}
{include file="sub/product_info/tab.tpl"}
{block name='product_accessories'}
      {if $accessories}
        <section class="product-accessories clearfix">
          <h3 class="h5 products-section-title">{l s='You might also like' d='Shop.Theme.Global'}</h3>

          <div class="products"> 
            <div class="owl-row {if isset($productClassWidget)} {$productClassWidget}{/if}">
              <div id="category-products2">
                {foreach from=$accessories item="product_accessory"}
                  <div class="item{if $smarty.foreach.mypLoop.index == 0} first{/if}">
                    {block name='product_miniature'}
                      {if isset($productProfileDefault) && $productProfileDefault}
                        
                        {hook h='displayLeoProfileProduct' product=$product_accessory profile=$productProfileDefault}
                      {else}
                        {include file='catalog/_partials/miniatures/product.tpl' product=$product_accessory}
                      {/if}
                    {/block}
                  </div>
                {/foreach}
              </div>
            </div>
          </div>
        </section>
      {/if}
    {/block}

    <script type="text/javascript">
      products_list_functions.push(
        function(){
          $('#category-products2').owlCarousel({
            {if isset($IS_RTL) && $IS_RTL}
              direction:'rtl',
            {else}
              direction:'ltr',
            {/if}
            items : 4,
            itemsCustom : false,
            itemsDesktop : [1200, 4],
            itemsDesktopSmall : [992, 3],
            itemsTablet : [768, 2],
            itemsTabletSmall : false,
            itemsMobile : [480, 1],
            singleItem : false,         // true : show only 1 item
            itemsScaleUp : false,
            slideSpeed : 200,  //  change speed when drag and drop a item
            paginationSpeed :800, // change speed when go next page

            autoPlay : false,   // time to show each item
            stopOnHover : false,
            navigation : true,
            navigationText : ["&lsaquo;", "&rsaquo;"],

            scrollPerPage :true,
            responsive :true,
            
            pagination : false,
            paginationNumbers : false,
            
            addClassActive : true,
            
            mouseDrag : true,
            touchDrag : true,

          });
        }
      ); 
    </script>
{block name='product_footer'}
	{hook h='displayFooterProduct' product=$product category=$category}
{/block}
                            </div></div>
{block name='page_footer_container'}
	  <footer class="page-footer">
	    {block name='page_footer'}
	    	<!-- Footer content -->
	    {/block}
	  </footer>
	{/block}
</section>

