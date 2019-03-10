<?php
/**
 * Created by PhpStorm.
 * User: ciro
 * Date: 03/03/19
 * Time: 18.57
 */

class AggregateCombinationProductSettings extends ObjectModel
{
    public $id_product;
    public $quantity_min;
    public $quantity_increment;

    public static $definition = array(
        'table' => 'ag_group_product_settings',
        'primary' => 'id_ag_group_product_settings',
        'multilang' => false,
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT),
            'quantity_min' => array('type' => self::TYPE_INT),
            'quantity_increment' => array('type' => self::TYPE_INT),
        ),
    );

    public static function getByProductId($id_product)
    {
        $query = "SELECT id_ag_group_product_settings FROM `"._DB_PREFIX_."ag_group_product_settings` WHERE id_product={$id_product}";
        return DB::getInstance()->getValue($query);
    }
}