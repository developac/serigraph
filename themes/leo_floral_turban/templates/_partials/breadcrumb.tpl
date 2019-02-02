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

<nav data-depth="{$breadcrumb.count}" class="breadcrumb">
  <div class="container">
    {if $page.page_name == 'category' && $category.image.large.url != ''}
      <h1 class="h1 category-name">{$category.name}</h1>
    {/if}
    <ol itemscope itemtype="http://schema.org/BreadcrumbList">
      {block name='breadcrumb'}
      {foreach from=$breadcrumb.links item=path name=breadcrumb}
      {block name='breadcrumb_item'}
        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
          <a itemprop="item" href="{$path.url}">
            <span itemprop="name">{$path.title}</span>
          </a>
          <meta itemprop="position" content="{$smarty.foreach.breadcrumb.iteration}">
        </li>
      {/block}
      {/foreach}
      {/block}
    </ol>
  </div>
  
  {if $page.page_name == 'category' && $category.image.large.url != ''}
    {if $category.image}
      <div class="category-cover hidden-sm-down">
        <img src="{$category.image.large.url}" class="img-fluid" alt="{$category.image.legend}">
      </div>
    {/if}
  {else}
    {if isset($tpl_uri) && $tpl_uri}
      <div class="category-cover hidden-sm-down">
        <img src="{$tpl_uri}/assets/img/bg-breadcrumb.jpg" class="img-fluid" alt="Breadcrumb image">
      </div>
    {else}  
      <div class="image-breadcrumb"></div>
    {/if}
  {/if}
</nav>
