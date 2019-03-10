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
{block name="product-variants"}
    <div id="aggregatecombination" class="product-variants">
        <div class="row row-eq-height">
            {foreach from=$ag_groups key=id_ag_group item=ag_group}

                <div class="ag_group col-sm-12">
                    <div class="item">
                        <div class="item-name">
                            <h4>{$ag_group.name}</h4>
                        </div>

                        {if $ag_group.name == 'Configura qui'}
                            {include file="./product-quantity.tpl"}
                        {/if}

                        <div class="item-attributes">
                            <div class="ag-product-variants">
                                {foreach from=$ag_group.attributes key=id_attribute_group item=group}
                                    {if !empty($group.attributes)}
                                        <div class="clearfix product-variants-item row">
                                            <span class="col-sm-3">{$group.name}</span>
                                            {if $group.group_type == 'select'}
                                                <div class="col-sm-9">
                                                    <select
                                                            class="form-control form-control-select"
                                                            id="group_{$id_attribute_group}"
                                                            data-product-attribute="{$id_attribute_group}"
                                                            name="group[{$id_attribute_group}]">
                                                        {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                                            <option value="{$id_attribute}" title="{$group_attribute.name}"{if $group_attribute.selected} selected="selected"{/if}>{$group_attribute.name}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            {elseif $group.group_type == 'color'}
                                                <div class="col-sm-9">
                                                    <ul id="group_{$id_attribute_group}">
                                                        {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                                            <li class="float-xs-left input-container">
                                                                <label>
                                                                    <input class="input-color" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                                                                    <span
                                                                            {if $group_attribute.html_color_code}class="color" style="background-color: {$group_attribute.html_color_code}" {/if}
                                                                            {if $group_attribute.texture}class="color texture" style="background-image: url({$group_attribute.texture})" {/if}
                ><span class="sr-only">{$group_attribute.name}</span></span>
                                                                </label>
                                                            </li>
                                                        {/foreach}
                                                    </ul>
                                                </div>
                                            {elseif $group.group_type == 'radio'}
                                                <div class="col-sm-9">
                                                    <ul id="group_{$id_attribute_group}">
                                                        {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                                            <li class="input-container float-xs-left">
                                                                <label>
                                                                    <input class="input-radio" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                                                                    <span class="radio-label">{$group_attribute.name}</span>
                                                                </label>
                                                            </li>
                                                        {/foreach}
                                                    </ul>
                                                </div>
                                            {/if}
                                        </div>
                                    {/if}
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>

            {/foreach}
        </div>
    </div>
{/block}
{*block name="product-variantsa"}
    <div class="product-variants">
        {foreach from=$groups key=id_attribute_group item=group}

            {if !empty($group.attributes)}
                <div class="clearfix product-variants-item">
                    <span class="control-label">{$group.name}</span>
                    {if $group.group_type == 'select'}
                        <select
                                class="form-control form-control-select"
                                id="group_{$id_attribute_group}"
                                data-product-attribute="{$id_attribute_group}"
                                name="group[{$id_attribute_group}]">
                            {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                <option value="{$id_attribute}" title="{$group_attribute.name}"{if $group_attribute.selected} selected="selected"{/if}>{$group_attribute.name}</option>
                            {/foreach}
                        </select>
                    {elseif $group.group_type == 'color'}
                        <ul id="group_{$id_attribute_group}">
                            {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                <li class="float-xs-left input-container">
                                    <label>
                                        <input class="input-color" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                                        <span
                                                {if $group_attribute.html_color_code}class="color" style="background-color: {$group_attribute.html_color_code}" {/if}
                                                {if $group_attribute.texture}class="color texture" style="background-image: url({$group_attribute.texture})" {/if}
                ><span class="sr-only">{$group_attribute.name}</span></span>
                                    </label>
                                </li>
                            {/foreach}
                        </ul>
                    {elseif $group.group_type == 'radio'}
                        <ul id="group_{$id_attribute_group}">
                            {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                <li class="input-container float-xs-left">
                                    <label>
                                        <input class="input-radio" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                                        <span class="radio-label">{$group_attribute.name}</span>
                                    </label>
                                </li>
                            {/foreach}
                        </ul>
                    {/if}
                </div>
            {/if}
        {/foreach}
    </div>
{/block*}