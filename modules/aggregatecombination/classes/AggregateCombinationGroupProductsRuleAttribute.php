<?php
/**
 * Created by PhpStorm.
 * User: ciro
 * Date: 26/01/19
 * Time: 10.37
 */

class AggregateCombinationGroupProductsRuleAttribute extends ObjectModel
{
    public $id_ag_group_products_rule;
    public $id_attribute;
    public $id_attribute_value;

    public static $definition = array(
        'table' => 'ag_group_products_rule_attribute',
        'primary' => 'id_ag_group_products_rule_attribute',
        'multilang' => false,
        'fields' => array(
            'id_ag_group_products_rule' => array('type' => self::TYPE_INT),
            'id_attribute' => array('type' => self::TYPE_INT),
            'id_attribute_value' => array('type' => self::TYPE_INT),
        )
    );

    public static function getAll()
    {
        $groups = new PrestaShopCollection('AggregateCombinationGroupProductsRuleAttribute');
        return  $groups->getAll()->getResults();
        //return DB::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."ag_group`");
    }

    public static function deleteByRuleID($id_ag_group_products_rule)
    {
        return DB::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."ag_group_products_rule_attribute` WHERE id_ag_group_products_rule = {$id_ag_group_products_rule}");
    }


    public static function getByRuleId($id_ag_group_products_rule)
    {
        return DB::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."ag_group_products_rule_attribute` WHERE id_ag_group_products_rule = {$id_ag_group_products_rule}");
    }


}