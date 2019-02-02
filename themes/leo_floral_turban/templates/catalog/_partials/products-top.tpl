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
{if !isset($LISTING_GRID_MODE) || !isset($LISTING_PRODUCT_COLUMN) || !isset($LISTING_PRODUCT_COLUMN_MODULE) || !isset($LISTING_PRODUCT_TABLET) || !isset($LISTING_PRODUCT_SMALLDEVICE) || !isset($LISTING_PRODUCT_EXTRASMALLDEVICE) || !isset($LISTING_PRODUCT_MOBILE)}
    {block name="setting"}
      {include file="layouts/setting.tpl"}
    {/block}
{/if}

<div id="js-product-list-top" class="products-selection">
  <div class="row">
    <div class="col-lg-5 col-md-3 hidden-sm-down total-products">     
      {block name='leo_gird_list'}
        <div class="display">
          <div id="grid" class=" leo_grid {if isset($LISTING_GRID_MODE) && $LISTING_GRID_MODE =='grid'}selected{/if}"><a rel="nofollow" href="#" title="{l s='Grid' d='Shop.Theme.Global'}"><i class="fa fa-th" aria-hidden="true"></i></a></div>
          <div id="list" class="leo_list {if isset($LISTING_GRID_MODE) && $LISTING_GRID_MODE =='list'}selected{/if}"><a rel="nofollow" href="#" title="{l s='List' d='Shop.Theme.Global'}"><i class="fa fa-list-ul" aria-hidden="true"></i></a></div>
        </div>
      {/block}
      {if $listing.pagination.total_items > 1}
        <p class="products-counter hidden-md-down">{l s='There are %product_count% products.' d='Shop.Theme.Catalog' sprintf=['%product_count%' => $listing.pagination.total_items]}</p>
      {else if $listing.pagination.total_items > 0}
        <p class="products-counter hidden-md-down">{l s='There is 1 product.' d='Shop.Theme.Catalog'}</p>
      {/if}
    </div>
    <div class="col-lg-7 col-md-9">
      <div class="row sort-by-row">
        {block name='sort_by'}
          {include file='catalog/_partials/sort-orders.tpl' sort_orders=$listing.sort_orders}
        {/block}

        {if !empty($listing.rendered_facets)}
          <div class="col-sm-4 col-xs-4 col-sp-12 hidden-md-up filter-button">
            <button id="search_filter_toggler" class="btn btn-outline">
              {l s='Filter' d='Shop.Theme.Actions'}
            </button>
          </div>
        {/if}
      </div>
    </div>
    <div class="col-sm-12 hidden-lg-up text-xs-center showing">
      {l s='Showing %from%-%to% of %total% item(s)' d='Shop.Theme.Catalog' sprintf=[
      '%from%' => $listing.pagination.items_shown_from ,
      '%to%' => $listing.pagination.items_shown_to,
      '%total%' => $listing.pagination.total_items
      ]}
    </div>
  </div>
</div>
