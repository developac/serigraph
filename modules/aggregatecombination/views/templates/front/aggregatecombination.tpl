
{block name='product_variants'}{/block}

{block name='product_tabs' prepend}
    {block name="aggregatecombination"}
        {block name='product_variants'}
            {include file="./product-variants.tpl"}
        {/block}
    {/block}
{/block}

