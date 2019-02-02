<?php
/**
 * Created by PhpStorm.
 * User: ciro
 * Date: 26/01/19
 * Time: 10.37
 */

class AggregateCombinationGroupProductsRule extends ObjectModel
{
    public $id_ag_group_products;
    public $name;

    public static $definition = array(
        'table' => 'ag_group_products_rule',
        'primary' => 'id_ag_group_products_rule',
        'multilang' => false,
        'fields' => array(
            'id_ag_group_products' => array('type' => self::TYPE_INT),
            'name' => array('type' => self::TYPE_STRING),

        )
    );

    public static function getAll()
    {
        $groups = new PrestaShopCollection('AggregateCombinationGroupProductsRule');
        return  $groups->getAll()->getResults();
        //return DB::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."ag_group`");
    }

}