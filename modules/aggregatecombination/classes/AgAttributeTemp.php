<?php
/**
 * Created by PhpStorm.
 * User: ciro
 * Date: 27/01/19
 * Time: 21.05
 */

class AgAttributeTemp extends ObjectModel
{
    public $id_attribute;
    public $id_product;
    public $valore;
    public $tipologia;

    public static $definition = array(
        'table' => 'ag_attribute_temp',
        'primary' => 'id_attribute_temp',
        'multilang' => false,
        'fields' => array(
            'id_attribute' => array('type' => self::TYPE_INT, 'required' => true),
            'id_product' => array('type' => self::TYPE_INT, 'required' => true),
            'valore' => array('type' => self::TYPE_FLOAT, 'required' => false),
            'tipologia' => array('type' => self::TYPE_STRING, 'required' => true),
        ),
    );

    public static function getGroupsByProductId($idProduct){

        $query = "SELECT * FROM " . _DB_PREFIX_ . "ag_attribute_temp WHERE id_product = '$idProduct'";
        $result = Db::getInstance()->executeS($query);
        if ($result) {
            return $result;
        }

        return false;

    }

    public static function getAll($idProduct){

        $query = "SELECT * FROM " . _DB_PREFIX_ . "ag_attribute_temp WHERE id_product = '$idProduct'";
        $result = Db::getInstance()->executeS($query);
        if ($result) {
            return $result;
        }

        return false;

    }
}