{foreach $columnHeaderGroup as $key => $value}
    <div class="card">
        <div class="card-header" id="headingTree">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{$key}" aria-expanded="true" aria-controls="collapseOne">
                    {$value}
                </button>
            </h5>
        </div>

        <div id="collapse{$key}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                {foreach $attributeOptionGroup[$key] as $o => $option}
                    <div class="form-check">
                        <input type="hidden" name="product" value="{$id_product}"/>
                        <input class="form-check-input option3" type="checkbox" value="" data-attribute-group="{$key}" data-name="{$option}" id="{$o}">
                        <label class="form-check-label" for="{$o}">
                            {$option}
                        </label>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
{/foreach}