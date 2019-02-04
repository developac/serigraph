<?php

if (!defined('_PS_VERSION_')) {
    exit;
}
//require_once(_PS_MODULE_DIR_ . 'aggregatecombination/library/models/AgGroup.php');
//require_once(_PS_MODULE_DIR_ . 'aggregatecombination/classes/AggregateCombinationGroup.php');

require_once _PS_MODULE_DIR_."aggregatecombination/classes/AggregateCombinationGroup.php";
require_once _PS_MODULE_DIR_ ."aggregatecombination/classes/AggregateCombinationGroupAttributes.php";
require_once _PS_MODULE_DIR_."aggregatecombination/classes/AggregateCombinationGroupProducts.php";
require_once _PS_MODULE_DIR_."aggregatecombination/classes/AgAttributeTemp.php";

class AggregateCombination extends Module{

    public function __construct()
    {
        $this->name = 'aggregatecombination';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'SEOChef';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Aggregate Combination');
        $this->description = $this->l('This module create group of combination and set automatically combination of product');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('AGGREGATE_COMBINATION')) {
            $this->warning = $this->l('No name provided');
        }

    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install() ||
            //!$this->registerHook('leftColumn') ||
            ! $this->registerHook('actionAdminControllerSetMedia') ||
            !$this->registerHook('displayAdminProductsExtra') ||
            !$this->registerHook('displayAttributeTemp') ||
            !$this->registerHook('actionBeforeCartUpdateQty') ||
            !$this->registerHook('actionCartSave') ||
            !$this->registerHook('displayOverrideTemplate') ||
            !Configuration::updateValue('AGGREGATE_COMBINATION', 'aggregate combination')
        ) {
            return false;
        }


        //Create an admin tab
        $lang = Language::getLanguages();
        $tab = new Tab();
        $tab->class_name = 'AdminAggregateCombination';
        $tab->module = 'aggregatecombination';
        $tab->id_parent = 2;
        $tab->position = 6;
        foreach ($lang as $l) {
            $tab->name[$l['id_lang']] = $this->l('Aggregate Combination');
        }

        $tab->save();


        //Load file Install sql
      /*  $sqlFile = dirname(__FILE__).'/install/install.sql';
        if(!$sqlFile)
            return false;

        if(!$this->loadSQLFile($sqlFile))
            return false;


        if(!$this->installTab('AdminParentOrders', 'AdminOrderReservation','Order Reservation'))
            return false;*/

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('AGGREGATE_COMBINATION')
        ) {
            return false;
        }

        //Remove Admin Tab
        // Uninstall Tabs
        $tab = new Tab((int)Tab::getIdFromClassName('AdminAggregateCombination'));
        $tab->delete();

        //Load file uninstall sql
        /*$sqlFile = dirname(__FILE__).'/install/uninstall.sql';
        if(!$sqlFile)
            return false;

        if(!$this->loadSQLFile($sqlFile))
            return false;

        if(!$this->uninstallTab('AdminOrderReservation'))
            return false;*/

        return true;
    }

    public function hookDisplayAdminProductsExtra($params)
    {

        if (Validate::isLoadedObject($product = new Product((int)$params['id_product']))) {// && !$product->hasAttributes()) {

            // validate module
            //unset($product);

            $attributes = Attribute::getAttributes($this->context->language->id, $not_null = false);
            $columnHeader = [];
            $attributeOption = [];
            $attributeValueTemp = [];


            foreach ($attributes as $key => $attribute){

                //Valore di tipo temporale
                if (strpos($attribute["name"], '*P') === 0) {
                    // It starts with 'http'
                    $attribute["name"] = substr($attribute["name"], 1);
                    $date = new DateTime('now');
                    $date->add(new DateInterval($attribute["name"]));
                    $attribute["name"] =  $date->format('d/m/Y');
                    $attributeValueTemp[$attribute["id_attribute"]] = $attribute["name"];
                }
                else{
                    $columnHeader[$attribute["id_attribute_group"]] = $attribute["public_name"];

                    if(!isset($attributeOption[$attribute["id_attribute_group"]])){
                        $attributeOption[$attribute["id_attribute_group"]] = [];
                    }

                    $attributeOption[$attribute["id_attribute_group"]][$attribute["id_attribute"]] = $attribute["name"];
                }

            }

            $query = "
                SELECT DISTINCT(gl.name),g.id_ag_group,h.id_product 
                FROM `"._DB_PREFIX_."ag_group` g 
                LEFT JOIN (
                  SELECT a.id_ag_group,al.name,ap.id_product 
                  FROM `"._DB_PREFIX_."ag_group` a 
                  LEFT JOIN `"._DB_PREFIX_."ag_group_products` ap 
                  ON a.id_ag_group = ap.id_ag_group 
                  LEFT JOIN `"._DB_PREFIX_."ag_group_lang` al 
                  ON al.id_ag_group = a.id_ag_group
                  WHERE id_product = '{$params['id_product']}'
                ) h 
                ON h.id_ag_group = g.id_ag_group 
                LEFT JOIN `"._DB_PREFIX_."ag_group_lang` gl 
                ON gl.id_ag_group = g.id_ag_group
            ";

            $groups = Db::getInstance()->executeS($query);


            $columnHeaderGroup = [];
            $attributeOptionGroup = [];

            foreach ($groups as $key => $group){

                if($group["id_product"]){

                    if(empty($columnHeaderGroup)) {

                        $attributesGroup = AggregateCombinationGroupAttributes::getByGroupId($group['id_ag_group']);

                        foreach ($attributesGroup as  $attribute) {

                            $ag = new AttributeGroup($attribute["id_attribute"]);
                            $av = new Attribute($attribute["id_value"]);

                            $columnHeaderGroup[$attribute["id_attribute"]] = $ag->name[1];

                            if (!isset($attributeOptionGroup[$attribute["id_attribute"]])) {
                                $attributeOptionGroup[$attribute["id_attribute"]] = [];
                            }

                            $attributeOptionGroup[$attribute["id_attribute"]][$attribute["id_value"]] =  $av->name[1];

                        }

                    }

                    $groups[$key]["checked"] = "checked=checked";
                }

            }

            //return false;

            //SELECT attribute temp
            $valueTemp = AgAttributeTemp::getAll((int)$params['id_product']);
            //$valueTemp = AgAttributeTemp::getGroupsByProductId((int)$params['id_product']);

            $outValueTemp = [];
            foreach($valueTemp as $value){

                if($value["id_attribute"] == ''){
                    continue;
                }

                if(!isset($outValueTemp[$value["valore"]][$value["tipologia"]])){
                    $outValueTemp[$value["valore"]][$value["tipologia"]] = [];
                }

                $attributeTemp = new Attribute($value["id_attribute_temp"]);

                $attributeTemp->name[1] = substr($attributeTemp->name[1], 1);
                $date = new DateTime('now');
                $date->add(new DateInterval( $attributeTemp->name[1]));
                $attributeTemp->name[1] =  $date->format('d/m/Y');

                if($value["id_attribute"] == '*'){
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["*"] = [];
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["*"]["attribute_temp"] =  $attributeTemp->name[1];
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["*"]["attribute"] = "Tutti";
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["*"]["valore"] = $value["valore"]."".$value["tipologia"];
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["*"]["values_temp"] = $value["id_attribute_temp"];
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["*"]["values_attribute"] = "*";
                }
                else {

                    $attribute = new Attribute($value["id_attribute"]);
                    $groupAttribute = new AttributeGroup($attribute->id_attribute_group);

                    $name = $groupAttribute->name[1]." : ".$attribute->name[1];

                    if(!isset($outValueTemp[$value["valore"]][$value["tipologia"]]["1"])){
                        $outValueTemp[$value["valore"]][$value["tipologia"]]["1"] = [];
                    }
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["1"]["values_temp"] = $value["id_attribute_temp"];
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["1"]["attribute_temp"] = $attributeTemp->name[1];
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["1"]["attribute"] = $outValueTemp[$value["valore"]][$value["tipologia"]]["1"]["attribute"].$name." ; ";
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["1"]["valore"] = $value["valore"]."".$value["tipologia"];
                    $outValueTemp[$value["valore"]][$value["tipologia"]]["1"]["values_attribute"] = $outValueTemp[$value["valore"]][$value["tipologia"]]["1"]["values_attribute"].",".$value["id_attribute"];

                }
            }


            //$valueRule = AgGroup::getAttributesForRules((int)$params['id_product']);
            $valueRule = AggregateCombinationGroup::getAttributesForRules((int)$params['id_product']);



            $outValueRule = [];
            foreach($valueRule as $key => $value){

                if($value["id_attribute"] == ''){
                    continue;
                }

                if(!isset($outValueRule[$value["id_ag_group_products_rule"]])){
                    $outValueRule[$value["id_ag_group_products_rule"]] = [];
                }

                if($value["id_attribute"] == '*'){
                    $outValueRule[$value["id_ag_group_products_rule"]]["*"] = [];
                    $outValueRule[$value["id_ag_group_products_rule"]]["*"]["name"] =  $value["name"];
                    $outValueRule[$value["id_ag_group_products_rule"]]["*"]["group"] = $value["nome"];
                    $outValueRule[$value["id_ag_group_products_rule"]]["*"]["attribute"] = "Tutti";
                    $outValueRule[$value["id_ag_group_products_rule"]]["*"]["valore"] = $value["valore"]."".$value["tipologia"];
                }
                else {

                    $attrGroup = new AttributeGroup($value["id_attribute"]);
                    $attributeValue = new Attribute($value["id_attribute_value"]);




                    if(!isset($outValueRule[$value["id_ag_group_products_rule"]]["1"])){
                        $outValueRule[$value["id_ag_group_products_rule"]][1] = [];
                        $name = '';
                    }

                    $name .= $attrGroup->name[1].": <strong>".$attributeValue->name[1]."</strong>; ";

                    Tools::dieObject($value, false);

                    /*
                    $outValueRule[$value["id_ag_group_products_rule"]] = array(
                        'name' => $value["name"],
                        'group' => $value["nome"],
                        'attribute' => $outValueRule[$value["id_ag_group_products_rule"]][$key]["attribute"].$name." ; ",
                        'valore' => $value["valore"]."".$value["tipologia"],
                    );
                    */


                    $outValueRule[$value["id_ag_group_products_rule"]][1]["name"] = $value["name"];
                    $outValueRule[$value["id_ag_group_products_rule"]][1]["group"] = $value["nome"];
                    $outValueRule[$value["id_ag_group_products_rule"]][1]["attribute"] = $outValueRule[$value["id_group_rule"]]["1"]["attribute"].$name;
                    $outValueRule[$value["id_ag_group_products_rule"]][1]["valore"] = $value["valore"]."".$value["tipologia"];


                }
            }

            //Tools::dieObject($outValueRule, false);
            //Tools::dieObject($valueRule);

            $this->context->smarty->assign(array(
                    'columnHeader' => $columnHeader,
                    'attributeOption' => $attributeOption,
                    'id_product' => (int)$params['id_product'],
                    'groups' => $groups,
                    'attributeTemp' =>$attributeValueTemp,
                    'tableValueTemp' => $outValueTemp,
                    'columnHeaderGroup' => $columnHeaderGroup,
                    'attributeOptionGroup' => $attributeOptionGroup,
                    'tableValueRule' => $outValueRule
                )
            );

            return $this->display(__FILE__, 'productExtra.tpl');
        }
        /*else{
            return "Questo prodotto ha delle combinazioni predefinite";
        }*/
    }

    public function hookDisplayAttributeTemp($params){

        $idProduct = Tools::getValue('id_product');
        $temp = Tools::getValue('temp', '');

        $query = "SELECT DISTINCT(id_attribute_temp) FROM `"._DB_PREFIX_."attribute_temp` WHERE id_product = '$idProduct'";
        $attribute = Db::getInstance()->executeS($query);

        if(!empty($attribute)) {
            foreach ($attribute as $key => $attr) {

                $a = new Attribute($attr["id_attribute_temp"]);

                $attribute[$key]["id"] = $attr["id_attribute_temp"];

                $a->name[1] = substr($a->name[1], 1);
                $date = new DateTime('now');
                $date->add(new DateInterval($a->name[1]));
                $a->name[1] = $date->format('d/m/Y');

                $attribute[$key]["name"] = $a->name[1];
                $attribute[$key]["checked"] = $temp == $attr["id_attribute_temp"] ? "checked" : "";

            }

            $this->context->smarty->assign(
                array(
                    'name_group' => "Data di Consegna",
                    'attribute' => $attribute
                )
            );

            return $this->display(__FILE__, 'displayAttributeTemp.tpl');
        }


    }

    public function hookActionBeforeCartUpdateQty($params){

        $cart = $params['cart'];

        if($params["id_product_attribute"]){

            $temp  = Tools::getValue('temp', '');
            $group = Tools::getValue('group', []);
            $qty = Tools::getValue('qty', 1);
            $idProduct = Tools::getValue('id_product', 0);

            $product = new Product($idProduct);

            Db::getInstance()->delete('specific_price', "id_product = {$idProduct} AND id_product_attribute = {$params["id_product_attribute"]} and id_cart = {$cart->id}");

            $query = "SELECT * FROM `"._DB_PREFIX_."product_attribute` WHERE id_product_attribute = '{$params["id_product_attribute"]}'";
            $result =  Db::getInstance()->executeS($query);

            if(!empty($result)){

                $price = $result[0]["price"];
                $rate = 22;
                if($rate = $product->tax_rate ?: 22){
                    $price += (($rate *  $price) / 100);
                }

                //Calculation price attribute temp
                if($temp) {
                    $groups = [];
                    foreach($group as $el){
                        $groups[$el] = $el;
                    }

                    $query = "SELECT * FROM " . _DB_PREFIX_ . "ag_attribute_temp WHERE id_product = '$idProduct' and id_attribute_temp = '$temp' and id_attribute != ''";
                    $result = Db::getInstance()->executeS($query);

                    $values= [];
                    if ($result) {

                        foreach($result as $res){
                            if(!isset($values[$res["valore"].$res["tipologia"]])){
                                $values[$res["valore"]."".$res["tipologia"]] = [];
                                $values[$res["valore"]."".$res["tipologia"]]["valore"] = $res["valore"];
                                $values[$res["valore"]."".$res["tipologia"]]["tipologia"] = $res["tipologia"];
                                if($res["id_attribute"] == "*")  $values[$res["valore"]."".$res["tipologia"]]["id_attribute"] = '*';
                            }
                        }

                        foreach($values as $value){

                            $flag = true;

                            if(!isset($value["id_attribute"])){

                                $query = "SELECT * FROM " . _DB_PREFIX_ . "ag_attribute_temp WHERE id_product = '$idProduct' and id_attribute_temp = '$temp' and valore = '{$value["valore"]}' and tipologia = '{$value["tipologia"]}'";
                                $result = Db::getInstance()->executeS($query);

                                foreach($result as $res){
                                    if(!isset($groups[$res["id_attribute"]])) {
                                        $flag = false;
                                        break;
                                    }
                                }
                            }

                            if($flag) {
                                if ($value["tipologia"] == '%') {
                                    $price += ($price * $value["valore"]) / 100;
                                } else {
                                    $price += (int)$value["valore"];
                                }
                            }

                        }

                    }

                    $specific_price = new SpecificPrice();
                    $specific_price->id_product = (int)$idProduct; // choosen product id
                    $specific_price->id_product_attribute = (int)$params["id_product_attribute"];
                    $specific_price->id_cart = (int)$cart->id;
                    $specific_price->id_shop = (int)$cart->id_shop;
                    $specific_price->id_currency = 0;
                    $specific_price->id_country = 0;
                    $specific_price->id_group = 0;
                    $specific_price->id_customer = 0;
                    $specific_price->from_quantity = $qty;
                    $specific_price->price = $price;
                    $specific_price->reduction_type = 'amount';
                    $specific_price->reduction_tax = 1;
                    $specific_price->reduction = ($price * $rate)/100;
                    $specific_price->from = date("Y-m-d") . ' 00:00:01';
                    $specific_price->to = date("2100-01-01") . ' 23:59:59'; // or set date x days from now
                    //$specific_price->id_specific_price_rule = $specific_price_rule->id;
                    $specific_price->add();

                }

            }

        }

    }

    public function hookActionAdminControllerSetMedia($params)
    {
        // Create a link with the good path
        $link = new Link;
        $parameters = array("action" => "action_name");
        $ajax_link = $link->getModuleLink('aggregatecombination','ajax', $parameters);


        Media::addJsDef(array(
            "ajax_link" => $ajax_link,
            'ag_admin_url' => $this->context->link->getAdminLink('AdminAggregateCombination'),
        ));

        $this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/aggregatecombintion.js');
        //$this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/main.js');
        $this->context->controller->addCSS(_PS_MODULE_DIR_.$this->name.'/views/css/main.css');


    }

    public function hookDisplayOverrideTemplate($params)
    {
        if ($params['controller']->php_self == 'product') {

        }
    }

}