{block name="add_to_cart"}
    <div class="add">
        <button
                class="btn btn-primary add-to-cart"
                data-button-action="add-to-cart"
                type="submit"
                {if !$product.add_to_cart_url}
                    disabled
                {/if}
        >
            <i class="material-icons shopping-cart">&#xE547;</i>
            {l s='Add to cart' d='Shop.Theme.Actions'}
        </button>
        <div class="leo-compare-wishlist-button">
            {hook h='displayLeoWishlistButton' product=$product}
            {hook h='displayLeoCompareButton' product=$product}
        </div>

    </div>
{/block}