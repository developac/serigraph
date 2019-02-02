<!--DA FARE UN HOOK-->
<div class="clearfix product-variants-item">
    <span class="control-label">{$name_group}</span>
        <div class="form-group">
            {foreach $attribute as $value}
                <div class="radio">
                    <label><input type="radio" name="temp" value="{$value['id']}" {$value['checked']}>{$value['name']}</label>
                </div>
            {/foreach}
        </div>
</div>


<div class="ag">
    <div class="agGroup" id=""style="background-color: lightgray;padding:20px">
        <span class="control-label">Formato e allestimento</span>
        {*<div class="clearfix product-variants-item">*}
            {*<select*}
                    {*class="form-control form-control-select"*}
                    {*id="group_$id_attribute_group"*}
                    {*data-product-attribute="$id_attribute_group"*}
                    {*name="group[8]">*}
                {*{foreach from=$group.attributes key=id_attribute item=group_attribute}*}
                    {*<option value="5" title="$group_attribute.name}"*}
                            {*{if $group_attribute.selected} selected="selected"{/if}*}
                    {*>$group_attribute.name*}
                    {*</option>*}
                    {*<option value="6" title="$group_attribute.name}"*}
                            {*{if $group_attribute.selected} selected="selected"{/if}*}
                    {*>$group_attribute.name*}
                    {*</option>*}
                {*{/foreach}*}
            {*</select>*}
        {*</div>*}
        <div class="product-variants">

            <div class="clearfix product-variants-item">
                <span class="control-label">Colore</span>
                <ul id="group_2">
                    <li class="float-xs-left input-container">
                        <label>
                            <input class="input-color" type="radio" data-product-attribute="2" name="group[2]" value="6" checked="checked">
                            <span class="color" style="background-color: #CFC4A6"><span class="sr-only">Talpa</span></span>
                        </label>
                    </li>
                    <li class="float-xs-left input-container">
                        <label>
                            <input class="input-color" type="radio" data-product-attribute="2" name="group[2]" value="13">
                            <span class="color" style="background-color: #F39C11"><span class="sr-only">Arancione</span></span>
                        </label>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
