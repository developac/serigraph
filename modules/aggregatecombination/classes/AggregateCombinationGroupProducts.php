<?php
/**
 * Created by PhpStorm.
 * User: ciro
 * Date: 26/01/19
 * Time: 10.37
 */

class AggregateCombinationGroupProducts extends ObjectModel
{
    public $id_ag_group;
    public $id_product;

    public static $definition = array(
        'table' => 'ag_group_products',
        'primary' => 'id_ag_group_products',
        'multilang' => false,
        'fields' => array(
            'id_ag_group' => array('type' => self::TYPE_INT),
            'id_product' => array('type' => self::TYPE_INT),

        )
    );

    public static function getAll()
    {
        $groups = new PrestaShopCollection('AggregateCombinationGroupProducts');
        return  $groups->getAll()->getResults();
        //return DB::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."ag_group`");
    }

    public static function getByGroupIdAndProductId($id_ag_group, $id_product)
    {
        $query = "SELECT * FROM " . _DB_PREFIX_ . "ag_group_products WHERE id_ag_group = '$id_ag_group' and id_product = '$id_product'";
        return Db::getInstance()->executeS($query);
    }

    public static function deleteByGroupIdAndProductId($id_ag_group, $id_product)
    {
        //get id_ag_group_products
        $sql = "SELECT id_ag_group_products FROM `"._DB_PREFIX_."ag_group_products` WHERE id_ag_group={$id_ag_group} AND id_product={$id_product}";
        $id_ag_group_products = DB::getInstance()->getValue($sql);

        if (!DB::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."ag_group_products` WHERE id_ag_group={$id_ag_group} AND id_product={$id_product} AND id_ag_group_products={$id_ag_group_products}"))
            return false;

        return true;
    }

}