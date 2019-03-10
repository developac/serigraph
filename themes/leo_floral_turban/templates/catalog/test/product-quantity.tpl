{block name='product_quantity'}
    <div class="product-quantity clearfix">
        <div class="row">
            <div class="col-sm-3">
                {l s='Quantity'}
            </div>
            <div class="col-sm-9">
                <div class="qty clearfix">
                    <input
                            type="text"
                            name="qty"
                            id="quantity_wanted"
                            value="{$product.quantity_wanted}"
                            class="input-group"
                            min="{$product.minimal_quantity}"
                            aria-label="{l s='Quantity' d='Shop.Theme.Actions'}"
                    >
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
{/block}