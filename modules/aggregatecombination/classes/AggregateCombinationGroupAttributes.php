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

    public static $definition = array(
        'table' => 'ag_group_attribute',
        'primary' => 'id_ag_group_attribute',
        'multilang' => false,
        'fields' => array(
            'id_ag_group' => array('type' => self::TYPE_INT),
            'id_attribute' => array('type' => self::TYPE_INT),
            'id_value' => array('type' => self::TYPE_INT),

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
        $query = "SELECT * FROM " . _DB_PREFIX_ . "ag_group_attribute WHERE id_ag_group = '$group_id'";
        return Db::getInstance()->executeS($query);
    }
}