<?php
/**
 * Created by PhpStorm.
 * User: ciro
 * Date: 26/01/19
 * Time: 10.37
 */

class AggregateCombinationGroupAttributes extends ObjectModel
{
    public $id_ag_group;
    public $id_attribute;
    public $id_value;
    public $id_product_attribute;
    public $id_ag_group_products;

    public static $definition = array(
        'table' => 'ag_group_attribute',
        'primary' => 'id_ag_group_attribute',
        'multilang' => false,
        'fields' => array(
            'id_ag_group' => array('type' => self::TYPE_INT),
            'id_attribute' => array('type' => self::TYPE_INT),
            'id_value' => array('type' => self::TYPE_INT),
            'id_product_attribute' => array('type' => self::TYPE_INT, 'required' => false),
            'id_ag_group_products' => array('type' => self::TYPE_INT, 'required' => false),
        )
    );

    public static function getAll()
    {
        $groups = new PrestaShopCollection('AggregateCombinationGroupAttributes');
        return  $groups->getAll()->getResults();
        //return DB::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."ag_group`");
    }

    public static function getByGroupId($group_id)
    {
        $query = "SELECT * FROM " . _DB_PREFIX_ . "ag_group_attribute WHERE id_ag_group = '$group_id' ORDER BY group_position ASC";
        return Db::getInstance()->executeS($query);
    }

    public static function getByGroupIdAndProductId($group_id, $id_product)
    {
        $query = "SELECT aga.*, al.name FROM " . _DB_PREFIX_ . "ag_group_attribute aga 
        LEFT JOIN `"._DB_PREFIX_."ag_group_products` agp ON (agp.id_ag_group = aga.id_ag_group AND agp.id_ag_group_products = aga.id_ag_group_products) 
        LEFT JOIN `"._DB_PREFIX_."attribute_lang` al ON al.id_attribute = aga.id_value
        WHERE agp.id_ag_group = {$group_id} AND agp.id_product={$id_product} ORDER BY group_position ASC, position ASC";

        //die($query);

        return Db::getInstance()->executeS($query);
    }

    public static function getByGroupIdAndProductIdAndValueId($group_id, $id_product, $id_value)
    {
        $query = "SELECT aga.*, al.name FROM " . _DB_PREFIX_ . "ag_group_attribute aga 
        LEFT JOIN `"._DB_PREFIX_."ag_group_products` agp ON (agp.id_ag_group = aga.id_ag_group AND agp.id_ag_group_products = aga.id_ag_group_products) 
        LEFT JOIN `"._DB_PREFIX_."attribute_lang` al ON al.id_attribute = aga.id_value
        WHERE agp.id_ag_group = {$group_id} AND agp.id_product={$id_product} AND aga.id_value = {$id_value} ORDER BY group_position ASC, position ASC";

        return Db::getInstance()->executeS($query);
    }

    public static function deleteByGroupId($id_ag_group)
    {
        if (!DB::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."ag_group_attribute` WHERE id_ag_group={$id_ag_group}"))
            return false;

        return true;
    }

    public static function deleteByGroupIdAndProductIdAndValueId($group_id, $id_product, $id_value)
    {
        if (!DB::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."ag_group_attribute` WHERE agp.id_ag_group = {$group_id} AND agp.id_product={$id_product} AND aga.id_value = {$id_value}"))
            return false;

        return true;
    }
}