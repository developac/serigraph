

{block name='product_prices'}{/block}
{block name='product_buy'}{/block}
{block name='product_variants'}{/block}



{block name='product_tabs' prepend}
<div class="product-actions row">
    {block name='product_discounts'}
        {include file='catalog/_partials/product-discounts.tpl'}
    {/block}

    {block name='product_buy'}
        <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
            <input type="hidden" name="token" value="{$static_token}">
            <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
            <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">

            <div class="col-sm-8">
                {block name='product_variants'}
                    {include file="./product-variants.tpl"}
                {/block}
            </div>
            <div class="col-sm-4">
                <div id="aggregatecombination_buy" class="right-block">
                    <div class="item">
                        <div class="item-name">
                            <h4>{l s='Buy'}</h4>
                        </div>
                        {block name='product_prices'}
                            {include file='./product-prices.tpl'}
                            {include file='./product-add-to-cart.tpl'}
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


                        {*block name='product_add_to_cart'}
                            {include file='catalog/_partials/product-add-to-cart.tpl'}
                        {/block*}

                        {block name='product_refresh'}
                            <input class="product-refresh ps-hidden-by-js" name="refresh" type="submit" value="{l s='Refresh' d='Shop.Theme.Actions'}">
                        {/block}
                    </div>
                </div>
            </div>


        </form>
    {/block}
</div>
{/block}
{*block name='product_tabs' prepend}
    <div class="row">
        <div class="col-sm-8">
            {block name="aggregatecombination"}
                {block name='product_variants'}
                    {include file="./product-variants.tpl"}
                {/block}
            {/block}
        </div>
        <div class="col-sm-4">
            {block name='product_prices'}
                {include file='catalog/_partials/product-prices.tpl'}
            {/block}
            {block name='product_add_to_cart'}
                <div class="product-actions">
                    {include file='catalog/_partials/product-add-to-cart.tpl'}
                </div>
            {/block}
        </div>
    </div>
{/block*}