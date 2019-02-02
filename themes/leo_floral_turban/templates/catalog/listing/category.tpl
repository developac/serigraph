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
{extends file='catalog/listing/product-list.tpl'}

{block name='product_list_header'}
    <div class="block-category card card-block">
      {if $category.image.large.url}
      <div class="category-cover">
      <img class="img-fluid" src="{$category.image.large.url}" alt="{if !empty($category.image.legend)}{$category.image.legend}{else}{$category.name}{/if}">
      </div>
      {/if}
      <h1 class="h1 category-name">{$category.name}</h1>
      {if $category.description}
        <div id="category-description" class="text-muted">{$category.description nofilter}</div>
      {/if}
    </div>
     {if isset($LEO_SUBCATEGORY) && $LEO_SUBCATEGORY && isset($subcategories) && count($subcategories) > 0}
    <div id="subcategories">
      <div class="row">
        {foreach from=$subcategories item=subcategory}
          <div class="subcategory-block col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-sp-12">
            <div class="subcategory-image">
              <a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
                <img class="img-fluid" src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'category_default')}" alt="{$subcategory.name|escape:'html':'UTF-8'}"/>
              </a>
            </div>
            <div class="subcategory-meta">
              <h3><a class="subcategory-name" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}">{$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'}</a></h3> 
              <div class="subcategory-description">{$subcategory.description|truncate:120:'...'|escape:'html':'UTF-8' nofilter}</div>   
            </div>
          </div>
        {/foreach}
      </div>
    </div>
  {/if}
{/block}
