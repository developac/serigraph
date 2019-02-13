{**
 *   PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright   PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{extends file=$layout}

{block name='head_seo' prepend}
  <link rel="canonical" href="{$product.canonical_url}">
{/block}

{block name='head' append}
  <meta property="og:type" content="product">
  <meta property="og:url" content="{$urls.current_url}">
  <meta property="og:title" content="{$page.meta.title}">
  <meta property="og:site_name" content="{$shop.name}">
  <meta property="og:description" content="{$page.meta.description}">
  <meta property="og:image" content="{$product.cover.large.url}">
  <meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
  <meta property="product:pretax_price:currency" content="{$currency.iso_code}">
  <meta property="product:price:amount" content="{$product.price_amount}">
  <meta property="product:price:currency" content="{$currency.iso_code}">
  {if isset($product.weight) && ($product.weight != 0)}
  <meta property="product:weight:value" content="{$product.weight}">
  <meta property="product:weight:units" content="{$product.weight_unit}">
  {/if}
{/block}

{block name='content'}

  {if isset($product.productLayout) && $product.productLayout != ''}
    {hook h='displayLeoProfileProduct' product=$product typeProduct='detail'}
  {else}

    <section id="main" class="product-detail" itemscope itemtype="https://schema.org/Product">
      <meta itemprop="url" content="{$product.url}">

      <div class="row">
        <div class="col-md-6">
          {block name='page_content_container'}
            <section class="page-content" id="content">
              {block name='page_content'}
                {block name='product_cover_thumbnails'}
                  {include file='catalog/_partials/product-cover-thumbnails.tpl'}
                {/block}
              {/block}
            </section>
            {block name='product_images_modal'}
              {include file='catalog/_partials/product-images-modal.tpl'}
            {/block}
          {/block}
        </div>

        <div class="col-md-6">
          {block name='page_header_container'}
            {block name='page_header'}
              <h1 class="h1 product-detail-name" itemprop="name">{block name='page_title'}{$product.name}{/block}</h1>
            {/block}
          {/block}

          {hook h='displayProductButtons' product=$product}
          {hook h='displayLeoProductReviewExtra' product=$product}

          {block name='product_prices'}
            {include file='catalog/_partials/product-prices.tpl'}
          {/block}

          <div class="product-information">
            {block name='product_description_short'}
              <div id="product-description-short-{$product.id}" itemprop="description">{$product.description_short nofilter}</div>
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
                        <p class="h4">{l s='This pack contains' d='Shop.Theme.Catalog'}</p>
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

                  {block name='product_additional_info'}
                    {include file='catalog/_partials/product-additional-info.tpl'}
                  {/block}

                  {* Input to refresh product HTML removed, block kept for compatibility with themes *}
                  {block name='product_refresh'}{/block}
                </form>
              {/block}
            </div>

            {block name='hook_display_reassurance'}
              {hook h='displayReassurance'}
            {/block}

          </div>
        </div>
      </div>

      {block name='product_info'}
        {if isset($USE_PTABS) && $USE_PTABS == 'tab'}
          {include file="sub/product_info/tab.tpl"}
        {else if isset($USE_PTABS) && $USE_PTABS == 'accordion'}
          {include file="sub/product_info/accordions.tpl"}
        {else}
          {include file="sub/product_info/default.tpl"}
        {/if}  
      {/block}

    {block name='product_accessories'}
      {if $accessories}
        <section class="product-accessories clearfix">
          <p class="h5 products-section-title">{l s='You might also like' d='Shop.Theme.Global'}</p>

          <div class="products"> 
            <div class="owl-row {if isset($productClassWidget)} {$productClassWidget}{/if}">
              <div id="category-products2">
                {foreach from=$accessories item="product_accessory"}
                  <div class="item{if $smarty.foreach.mypLoop.index == 0} first{/if}">
                    {block name='product_miniature'}
                      {if isset($productProfileDefault) && $productProfileDefault}
                        {* exits THEME_NAME/profiles/profile_name.tpl -> load template*}
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

    {block name='product_images_modal'}
      {include file='catalog/_partials/product-images-modal.tpl'}
    {/block}

      {block name='page_footer_container'}
        <footer class="page-footer">
          {block name='page_footer'}
            <!-- Footer content -->
          {/block}
        </footer>
      {/block}

    </section>
  {/if}
{/block}

