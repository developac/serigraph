{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
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