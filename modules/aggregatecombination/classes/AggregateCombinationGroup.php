<?php
/**
 * Created by PhpStorm.
 * User: ciro
 * Date: 26/01/19
 * Time: 10.37
 */

class AggregateCombinationGroup extends ObjectModel
{
    public $name;
    public $position;
    public $active;

    public static $definition = array(
        'table' => 'ag_group',
        'primary' => 'id_ag_group',
        'multilang' => true,
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'lang' => true, 'required' => true),
            'position' => array('type' => self::TYPE_INT),
            'active' => array('type' => self::TYPE_BOOL),
        )
    );

    public static function getAll()
    {
        $groups = new PrestaShopCollection('AggregateCombinationGroup');
        return  $groups->getAll()->getResults();
        //return DB::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."ag_group`");
    }

    public static function getByProductId($id_product)
    {
        $sql = "SELECT ag.id_ag_group, agl.name, aga.id_attribute, aga.id_value FROM `"._DB_PREFIX_."ag_group` ag
        LEFT JOIN `"._DB_PREFIX_."ag_group_lang` agl ON agl.id_ag_group = ag.id_ag_group 
        LEFT JOIN `"._DB_PREFIX_."ag_group_products` agp ON agp.id_ag_group = ag.id_ag_group
        LEFT JOIN `"._DB_PREFIX_."ag_group_attribute` aga ON (agp.id_ag_group = aga.id_ag_group AND agp.id_ag_group_products = aga.id_ag_group_products)
        WHERE agp.id_product = {$id_product} GROUP BY aga.id_attribute
        ORDER BY aga.group_position ASC";
        return DB::getInstance()->executeS($sql);
    }


    public static function getAttributesForRules($idProduct){

        $query = "SELECT *, gl.name as nome, pr.name as name FROM ps_ag_group_products_rule_attribute pra 
                  LEFT JOIN ps_ag_group_products_rule pr on pr.id_ag_group_products_rule = pra.id_ag_group_products_rule
                  LEFT JOIN ps_ag_group_products gp ON gp.id_ag_group_products = pr.id_ag_group_products 
                  LEFT JOIN ps_ag_group g ON gp.id_ag_group = g.id_ag_group 
                  LEFT JOIN ps_ag_group_lang gl ON gl.id_ag_group = g.id_ag_group 
                  WHERE  gp.id_product = '$idProduct'
        ";

        return Db::getInstance()->executeS($query);
    }


    /*
    public static function getAttributesForRules($idProduct){

        $query = "SELECT * FROM ps_ag_group_products_rule_attribute pra 
                  LEFT JOIN ps_ag_group_products_rule pr on pr.id_ag_group_products_rule = pra.id_ag_group_products_rule
                  LEFT JOIN ps_ag_group_products gp ON gp.id_ag_group_products = pr.id_ag_group_products
                  LEFT JOIN ps_ag_group g ON gp.id_ag_group = g.id_ag_group 
                  WHERE  gp.id_product = '$idProduct'
        ";

        return Db::getInstance()->executeS($query);

    }
    */


}