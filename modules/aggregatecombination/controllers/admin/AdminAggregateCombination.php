<?php
/**
 * Created by PhpStorm.
 * User: Utente
 * Date: 15/01/2019
 * Time: 09:05
 */


require_once _PS_MODULE_DIR_."aggregatecombination/classes/AggregateCombinationGroup.php";
require_once _PS_MODULE_DIR_."aggregatecombination/classes/AggregateCombinationProductSettings.php";
require_once _PS_MODULE_DIR_."aggregatecombination/classes/AggregateCombinationGroupProductsRule.php";
require_once _PS_MODULE_DIR_."aggregatecombination/classes/AggregateCombinationGroupProductsRuleAttribute.php";
require_once _PS_MODULE_DIR_."aggregatecombination/classes/AggregateCombinationGroupAttributes.php";

class AdminAggregateCombinationController extends ModuleAdminController
{
    private $tpl_dir;

    public function __construct()
    {
        $this->table = 'ag_group';
        $this->className = 'AggregateCombinationGroup';
        $this->lang = true;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));
        $this->context = Context::getContext();
        // définition de l'upload, chemin par défaut _PS_IMG_DIR_
        $this->ajax = true;

        $this->bootstrap = true;
        parent::__construct();

        $this->tpl_dir = _PS_MODULE_DIR_."aggregatecombination/views/templates/";
    }

    /**
     * Function used to render the list to display for this controller
     */
    public function renderList()
    {
        $this->table = 'ag_group';
        $this->list_id = 'ag_group';
        $this->identifier = 'id_ag_group';
        $this->className = 'AggregateCombinationGroup';
        $this->_defaultOrderBy = 'position';

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->addRowAction('details');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected'),
                'confirm' => $this->trans('Delete selected items?')
            )
        );
        $this->fields_list = array(
            'id_ag_group' => array(
                'title' => $this->trans('ID'),
                'align' => 'center',
                'width' => 25
            ),
            'name' => array(
                'title' => $this->trans('Name'),
                'width' => 'auto',
            ),
            'position' => array(
                'title' => $this->trans('Position'),
                'width' => 70,
                'align' => 'center',
                'position' => 'position',
                'filter_key' => 'a!position',
            ),
            'active' => array(
                'title' => $this->trans('Active'),
                'width' => 'auto',
            ),
        );

        $lists = parent::renderList();
        parent::initToolbar();
        return $lists;
    }



    public function renderForm()
    {
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->trans('Combination Group'),
                'image' => '../img/admin/cog.gif'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'lang' => true,
                    'label' => $this->trans('Name:'),
                    'name' => 'name',
                    'size' => 40
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Active'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', array(), 'Admin.Global'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('No', array(), 'Admin.Global'),
                        ),
                    ),
                )
            ),
            'submit' => array(
                'title' => $this->trans('Save'),
            )
        );
        if (!($obj = $this->loadObject(true))) {
            return;
        }
        /* Thumbnail
         * @todo Error, deletion of the image
        */

        return parent::renderForm();
    }


    public function initContent()
    {
        return parent::initContent(); // TODO: Change the autogenerated stub
    }

    public function init()
    {
        parent::init();
    }

    public function setMedia()
    {
        parent::setMedia();

        // Create a link with the good path
        $link = new Link;
        $parameters = array("action" => "action_name");
        $ajax_link = $link->getModuleLink('aggregatecombination','ajax', $parameters);


        Media::addJsDef(array(
            "ajax_link" => $ajax_link,
            'ag_admin_url' => $this->context->link->getAdminLink('AdminAggregateCombination'),
            'ag_token' => Tools::getAdminTokenLite('AdminAggregateCombination'),
            'file' => __FILE__
        ));

        $this->context->controller->addJS(_PS_MODULE_DIR_.'aggregatecombination/views/js/aggregatecombintion.js');
        //$this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/main.js');
        $this->context->controller->addCSS(_PS_MODULE_DIR_.'aggregatecombination/views/css/main.css');
    }

    public function ajaxProcessSaveGroup()
    {
        $idProduct = Tools::getValue('product', 0);
        $nome = Tools::getValue('group', $this->trans("Empty Group"));
        $arrayCombination = Tools::getValue('element', []);
        $id_ag_group = Tools::getValue('id_ag_group', 0);
        $id_ag_group_product = Tools::getValue('id_ag_group_product', []);
        $quantity_min = Tools::getValue('quantity_min', 0);
        $quantity_increment = Tools::getValue('quantity_increment', 0);
        $attributes_to_remove = Tools::getValue('to_remove', []);

        print "<pre>";
        foreach ($attributes_to_remove as $attribute) {
            foreach ($attribute as $value) {
                $ag_product_attribute = AggregateCombinationGroupAttributes::getByGroupIdAndProductIdAndValueId($id_ag_group, $idProduct, $value);
                //print_r($ag_product_attribute);
                $agPA = new AggregateCombinationGroupAttributes($ag_product_attribute[0]['id_ag_group_attribute']);
                $agPA->delete();
            }
        }


        //print_r(ToolsCore::getAllValues());
        //die();

        $acGP = new AggregateCombinationGroupProducts($id_ag_group_product);
        $acGP->id_ag_group = $id_ag_group;
        $acGP->id_product = $idProduct;


        $acPS = new AggregateCombinationProductSettings();
        $acPS->id_product = $idProduct;
        $acPS->quantity_min = $quantity_min;
        $acPS->quantity_increment = $quantity_increment;
        $acPS->save();

        //if ($id_ag_group && $acGP->save()) {
        if (!$id_ag_group && ($acGP->save() || isset($acGP->id))) {



            $outArrayCombination = [];
            foreach ($arrayCombination as $key => $arr) {
                if (is_array($arr)) {
                    $outArrayCombination[$key] = $arr;
                }
            }

            $errors = 0;
            foreach ($outArrayCombination as $id_attribue => $attributes) {
                foreach ($attributes as $id_value) {

                    $acGA = new AggregateCombinationGroupAttributes();
                    $acGA->id_ag_group = $id_ag_group;
                    $acGA->id_attribute = $id_attribue;
                    $acGA->id_value = $id_value;
                    $acGA->id_product_attribute = 0;
                    $acGA->id_ag_group_products = $acGP->id;

                    if (!$acGA->save())
                        $errors++;
                }
            }

            if (!$errors) {

                $this->context->smarty->assign(array(
                    'value' => ['name' => $nome, 'id_ag_group' => $id_ag_group]
                ));

                $output = $this->context->smarty->fetch($this->tpl_dir."hook/groupRow.tpl");

                die(json_encode(array('status' => true, 'message' => "Group saved successfully", 'id_ag_group' => $id_ag_group, 'nome' => $nome, 'combinations' => $outArrayCombination, 'method' => "SaveGroup", 'html' => $output)));
            }
            else
                die(json_encode(array('status' => false, 'message' => "An error occourred during group saving", 'method' => "SaveGroup")));
        } else {
            $outArrayCombination = [];
            foreach ($arrayCombination as $key => $arr) {
                if (is_array($arr)) {
                    $outArrayCombination[$key] = $arr;
                }
            }

            $sql = "SELECT id_ag_group_products FROM `"._DB_PREFIX_."ag_group_products` WHERE id_ag_group={$id_ag_group} AND id_product={$idProduct}";
            $id_ag_group_products = DB::getInstance()->getValue($sql);

            $errors = 0;
            foreach ($outArrayCombination as $id_attribue => $attributes) {
                foreach ($attributes as $id_value) {

                    //check if value exists
                    if (AggregateCombinationGroupAttributes::getByGroupIdAndProductIdAndValueId($id_ag_group, $idProduct, $id_value))
                        continue;

                    $acGA = new AggregateCombinationGroupAttributes();
                    $acGA->id_ag_group = $id_ag_group;
                    $acGA->id_attribute = $id_attribue;
                    $acGA->id_value = $id_value;
                    $acGA->id_product_attribute = 0;
                    $acGA->id_ag_group_products = $id_ag_group_products;

                    if (!$acGA->save())
                        $errors++;
                }
            }

            if (!$errors) {

                $this->context->smarty->assign(array(
                    'value' => ['name' => $nome, 'id_ag_group' => $id_ag_group]
                ));

                $output = $this->context->smarty->fetch($this->tpl_dir."hook/groupRow.tpl");

                die(json_encode(array('status' => true, 'message' => "Group saved successfully", 'id_ag_group' => $id_ag_group, 'nome' => $nome, 'combinations' => $outArrayCombination, 'method' => "SaveGroup", 'html' => $output)));
            }
            else
                die(json_encode(array('status' => false, 'message' => "An error occourred during group saving", 'method' => "SaveGroup")));
        }
    }

    public function ajaxProcessGenerateCombinations()
    {

        //Tools::dieObject(Tools::getValue('group', []));

        $idProduct = Tools::getValue('product', 0);
        $arrayGroup = Tools::getValue('group', []);

        $query = "SELECT * FROM " . _DB_PREFIX_ . "ag_group_attribute where id_ag_group IN (" . implode($arrayGroup, ',') . ") AND id_ag_group_products IN (SELECT id_ag_group_products FROM `ps_ag_group_products` WHERE id_product = {$idProduct}) ORDER BY group_position ASC, position ASC";
        $results = Db::getInstance()->executeS($query);

        //$this->jsonLog($query);
        //$this->jsonLog($results, false);

        //die();

        $combination = [];
        $outCombination = [];
        while ($results) {
            foreach ($results as $res) {
                if (!isset($combination[$res["id_attribute"]])) {
                    $combination[$res["id_attribute"]] = [];
                }
                $combination[$res["id_attribute"]][] = $res["id_value"];
            }
            $index = 1;
            $k = 1;
            foreach ($combination as $key => $arr) {
                $outCombination[$index] = [];
                foreach ($arr as $value) {
                    $outCombination[$index][$k] = $value;
                    $k++;
                }
                $index++;
            }
            break;
        }


        $outCombination = $this->get_combinations($outCombination);
        //clear combinations array
        //$outCombination = array_map('array_values', $outCombination);


        $errors = 0;
        if ($this->createCombination($idProduct, $outCombination, $arrayGroup)) {
            foreach ($arrayGroup as $group) {
                //ToolsCore::dieObject($group);
            }
        }

        if (!$errors)
            die(json_encode(array('status' => true, 'message' => $this->l('Combinations generated successfully'), 'query' => $query)));
        else
            die(json_encode(array('status' => false, 'message' => $this->l('An error occourred during combinations generation'), 'query' => $query)));
    }

    public function ajaxProcessDeleteGroup()
    {

        $idProduct = Tools::getValue('product', 0);
        $idGroup = Tools::getValue('idGroup', 0);

        //get id_ag_group_products


        if (AggregateCombinationGroupAttributes::deleteByGroupId($idGroup) && AggregateCombinationGroupProducts::deleteByGroupIdAndProductId($idGroup, $idProduct))
            die(json_encode(array('status' => true, 'message' => "Valore eliminato correttamente")));
        else
            die(json_encode(array('status' => false, 'message' => "Si è verificato un errore durante la cancellazione del gruppo")));
    }

    private function get_combinations($arrays)
    {
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_key => $property_value) {
                    $tmp[] = $result_item + array($property_key => $property_value);
                }
            }
            $result = $tmp;
        }
        return $result;
    }


    private function createCombination($idProduct, $outArrayCombination, $group)
    {
        if ($idProduct) {

            $product_setting = AggregateCombinationProductSettings::getByProductId($idProduct);
            $agPS = new AggregateCombinationProductSettings($product_setting);

            $product = new Product($idProduct, true, 1);

            //ini_set("memory_limit", "512M");

            foreach ($outArrayCombination as &$combinations) {


                if (!count($combinations))
                    continue;

                //Tools::dieObject($combinations);

                if (!$product->productAttributeExists($combinations)) {


                    //continue;

                    $price = 0;
                    $weight = 0;
                    $ecotax = 0;
                    $unit_price_impact = "";
                    $quantity = 1000000000;
                    $reference = "";
                    $supplier_reference = "";
                    $ean13 = "";
                    $default = false;
                    $minimal_quantity = $agPS->quantity_min;

                    $idProductAttribute = $product->addProductAttribute((float)$price, (float)$weight, $unit_price_impact, (float)$ecotax, (int)$quantity, "", strval($reference), strval($supplier_reference), strval($ean13), $default, NULL, NULL, $minimal_quantity, NULL);
                    $product->addAttributeCombinaison($idProductAttribute, $combinations);

                    $id = DB::getInstance()->getValue("SELECT MAX(id_product_attribute) as id_product_attribute FROM `"._DB_PREFIX_."product_attribute`");

                    //echo(json_encode(array('combinations' => $combinations, 'exists' => 'not exists', 'id_attr_comb' => $id))."\n");

                    foreach ($combinations as $value) {
                        $query = "UPDATE `"._DB_PREFIX_."ag_group_attribute` SET id_product_attribute = {$id} WHERE id_value = {$value} AND id_ag_group IN (".implode(",", $group).") AND id_ag_group_products IN (SELECT id_ag_group_products FROM `"._DB_PREFIX_."ag_group_products` WHERE id_product = {$idProduct})";
                        DB::getInstance()->execute($query);
                    }

                    //$agA = new AggregateCombinationGroupAttributes()

                    //echo(json_encode(array('combinations' => $combinations, 'exists' => 'not exists', 'id_attr_comb' => $id))."\n");
                    /*
                     * TODO: slvare anche l'id della combinazione in modo da poterlo usare nelle regole
                     */
                } else {

                    //get combination id and store it into group_attribute table


                    foreach ($combinations as $combination) {
                        $query = "SELECT pa.id_product_attribute FROM `"._DB_PREFIX_."product_attribute_combination` pac
                        LEFT JOIN `"._DB_PREFIX_."product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
                        WHERE pa.id_product = {$idProduct} AND pac.id_attribute = {$combination}";

                        $id_product_attribute = DB::getInstance()->getValue($query);

                        //die($id_product_attribute);

                        //$query = "UPDATE `"._DB_PREFIX_."ag_group_attribute` SET id_product_attribute={$id_product_attribute} WHERE id_ag_group IN (".implode(",", $group).") AND id_value={$combination}";
                        $query = "UPDATE `"._DB_PREFIX_."ag_group_attribute` SET id_product_attribute = {$id_product_attribute} WHERE id_value = {$combination} AND id_ag_group IN (".implode(",", $group).") AND id_ag_group_products IN (SELECT id_ag_group_products FROM `"._DB_PREFIX_."ag_group_products` WHERE id_product = {$idProduct})";
                        print($query)."\n";
                        DB::getInstance()->execute($query);


                        //echo json_encode(array('combinations' => $combination, 'exists' => 'already exists', 'test' => $id_product_attribute))."\n";
                    }




                    //echo json_encode(array('combinations' => $combinations, 'exists' => 'already exists', 'test' => $res))."\n";
                }

            }


            //ini_set("memory_limit", "128M");

        }

        return true;
    }


    public function ajaxProcessSaveRule()
    {
        /*
         * TODO: fix salvataggio, non salva le regole separate per lo stesso gruppo
         */
        $idProduct = Tools::getValue('product', 0);
        $combinationAttributeRules = Tools::getValue('element', []);
        $rules = Tools::getValue('rule', []);
        $array = [];

        if (!empty($rules)) {
            foreach ($rules as $rule) {

                //print_r($rule);
                //die();

                $id_group = $rule['id_group'];

                //$ruleProduct = AgGroup::getLinkProduct($rule["id_group"],$idProduct);
                $ruleProduct = AggregateCombinationGroupProducts::getByGroupIdAndProductId($id_group, $idProduct);

                //echo json_encode(array('rule' => $ruleProduct));

                //continue;

                $agPR = new AggregateCombinationGroupProductsRule();
                $agPR->id_ag_group_products = $ruleProduct[0]['id_ag_group_products'];
                $agPR->name = $rule["rule"];
                $agPR->value = floatval($rule['value']);
                $agPR->type = $rule['type'];


                if ($agPR->save()) {

                    //$idRule = Db::getInstance()->Insert_ID();

                    $idRule = $agPR->id;



                    //continue;

                    if (!empty($combinationAttributeRules)) {




                        $array[$idRule] = [];

                        $saved = false;

                        foreach ($combinationAttributeRules as $combination) {
                            //$this->jsonLog($combination);
                            //continue;

                            //echo json_encode(array('combination' => $combination))."\n";

                            $this->getIdProductAttributeByAttributeId($combination);

                            $attr = new Attribute($combination);
                            $groupAttribute = new AttributeGroup($attr->id_attribute_group);

                            //Tools::dieObject($groupAttribute, false);

                            $agPRA = new AggregateCombinationGroupProductsRuleAttribute();
                            $agPRA->id_ag_group_products_rule = $idRule;
                            $agPRA->id_attribute = $attr->id_attribute_group;
                            $agPRA->id_attribute_value = $combination;

                            if (!$agPRA->save())
                                return false;

                            $saved = true;


                            //AgGroup::linkRuleAttributes($idRule,$attr->id_attribute_group,$combination,$rule["value"],$rule["type"]);

                            $name = $groupAttribute->name[1] . " : " . $attr->name[1];

                            //$array[$idRule]["id"] = $attribute["id_attribute_temp"];
                            //$array[$idRule]["temp"] =  $attributeTemp->name[1];
                            //$array[$idRule]["comb"] = !isset($array[$attribute["id_attribute_temp"]]["comb"]) ? $name." ;"  : $array[$attribute["id_attribute_temp"]]["comb"].$name." ; ";
                            //$array[$idRule]["value"] = $attribute['value']."".$attribute['type'];
                            //$array[$idRule]["query"] = !isset($array[$attribute["id_attribute_temp"]]["query"]) ? ",".$combination : $array[$attribute["id_attribute_temp"]]["query"].",".$combination;

                        }

                        if ($saved) {
                            //get product_attribute
                            $query = "SELECT id_product_attribute FROM `"._DB_PREFIX_."ag_group_attribute` WHERE id_ag_group={$id_group} AND id_value IN (".implode(", ", $combinationAttributeRules).")";
                            $id_product_attribute = DB::getInstance()->executeS($query);

                            //echo json_encode(array('id' => $id_product_attribute));

                            //return false;

                            $product = new Product($idProduct);

                            $price = $product->price;

                            if ($rule['type'] == '%')
                                $additional_price = ($price * $rule['value']) / 100;
                            else
                                $additional_price = $rule['value'];

                            $comb = new CombinationCore($id_product_attribute);

                            $comb->price = $additional_price;
                            $comb->save();
                        }

                    } else {

                        $agPRA = new AggregateCombinationGroupProductsRuleAttribute();
                        $agPRA->id_ag_group_products_rule = $idRule;
                        $agPRA->id_attribute = '*';
                        $agPRA->id_attribute_value = '*';
                        $agPRA->valore = $rule['value'];
                        $agPRA->tipologia = $rule['type'];
                        $agPRA->save();

                        //AgGroup::linkRuleAttributes($idRule,'*','*',$rule["value"],$rule["type"]);

//                                    $array[$attribute["id_attribute_temp"]] = [];
//                                    $array[$attribute["id_attribute_temp"]]["id"] = $attribute["id_attribute_temp"];
//                                    $array[$attribute["id_attribute_temp"]]["temp"] =  $attributeTemp->name[1];
//                                    $array[$attribute["id_attribute_temp"]]["comb"] = "Tutti";
//                                    $array[$attribute["id_attribute_temp"]]["value"] = $attribute['value']."".$attribute['type'];
//                                    $array[$attribute["id_attribute_temp"]]["query"] = '*';

                    }

                }

            }


            die(json_encode(array('array' => $array, 'id_rule' => $idRule)));
        } else {
            die(json_encode((array('message' => "Nessun valore temporale impostato"))));
        }
    }

    public function ajaxProcessEditRule()
    {
        $idRule = Tools::getValue("idRule");

        $agPR = new AggregateCombinationGroupProductsRule($idRule);
        $agPRA = AggregateCombinationGroupProductsRuleAttribute::getByRuleId($idRule);

        $object = [
            'id_rule' => $agPR->id,
            'name' => $agPR->name,
            'type' => $agPR->type,
            'value' => $agPR->value
        ];

        die(json_encode(array('status' => true, 'rule' => $object)));
    }

    public function ajaxProcessDeleteRule()
    {
        $idRule = Tools::getValue("idRule");

        //delete all rule attributes
        $rules_attributes = AggregateCombinationGroupProductsRuleAttribute::deleteByRuleID($idRule);

        /*
         * TODO: azzerare il prezzo della combinazione
         */

        //delete rule
        $acGPR = new AggregateCombinationGroupProductsRule($idRule);
        if ($acGPR->delete())
            die(json_encode(array("status" => true, "message" => "ok")));
        else
            die(json_encode(array("status" => false, "message" => "ok")));
    }

    public function ajaxProcessGetGroupSelectedAttributes()
    {
        $idGroup = Tools::getValue("group");
        $idProduct = Tools::getValue("product");


        if ($attributesGroup = AggregateCombinationGroupAttributes::getByGroupIdAndProductId($idGroup, $idProduct)) {
            $attributes = [];
            foreach ($attributesGroup as $attribute) {

                $attributes[$attribute['id_attribute']] = $attribute['id_attribute'];

                //echo json_encode(array('status' => true, 'attribute' => $attribute));
            }

            die(json_encode(array('status' => true, 'output' => '', 'attributesGroup' => $attributesGroup, 'attributes' => $attributes)));
        }
    }

    public function ajaxProcessGetAttributeGroup()
    {
        $idGroup = Tools::getValue("group");
        $idProduct = Tools::getValue("product");

        $columnHeaderGroup = [];
        $attributeOptionGroup = [];

        if (empty($columnHeaderGroup)) {

            //$attributesGroup = AgGroup::getAttributes($idGroup);
            $attributesGroup = AggregateCombinationGroupAttributes::getByGroupIdAndProductId($idGroup, $idProduct);

            foreach ($attributesGroup as $attribute) {

                $ag = new AttributeGroup($attribute["id_attribute"]);
                $av = new Attribute($attribute["id_value"]);

                $columnHeaderGroup[$attribute["id_attribute"]] = $ag->name[1];

                if (!isset($attributeOptionGroup[$attribute["id_attribute"]])) {
                    $attributeOptionGroup[$attribute["id_attribute"]] = [];
                }

                $attributeOptionGroup[$attribute["id_attribute"]][$attribute["id_value"]] = $av->name[1];
            }
        }

        $path_to_tpl_folder = str_replace('\\', '/', _PS_MODULE_DIR_) . 'aggregatecombination//views/templates/hook/partialAttributeGroup.tpl';

        $this->context->smarty->assign(array(
                'columnHeaderGroup' => $columnHeaderGroup,
                'attributeOptionGroup' => $attributeOptionGroup,
                'id_product' => $idProduct,
            )
        );

        die(json_encode(array('status' => true, 'html' => $this->context->smarty->fetch($path_to_tpl_folder))));
    }

    public function ajaxProcessUpdateGroupPosition()
    {
        $position = Tools::getValue('position');
        $attribute_group = Tools::getValue('attribute_group');
        $id_ag_group = Tools::getValue('group');
        $id_product = Tools::getValue('id_product');

        //get id_ag_group_products
        $query = "SELECT id_ag_group_products FROM `"._DB_PREFIX_."ag_group_products` WHERE id_ag_group={$id_ag_group} AND id_product={$id_product}";
        $id_ag_group_products = DB::getInstance()->getValue($query);

        $query = "UPDATE `"._DB_PREFIX_."ag_group_attribute` SET group_position={$position} WHERE id_ag_group={$id_ag_group} AND id_attribute={$attribute_group} AND id_ag_group_products={$id_ag_group_products}";
        print $query;
        DB::getInstance()->execute($query);
    }

    public function ajaxProcessUpdateAttributePosition()
    {
        $position = Tools::getValue('position');
        $attribute_value = Tools::getValue('attribute_value');
        $id_ag_group = Tools::getValue('group');
        $id_product = Tools::getValue('id_product');

        //get id_ag_group_products
        $query = "SELECT id_ag_group_products FROM `"._DB_PREFIX_."ag_group_products` WHERE id_ag_group={$id_ag_group} AND id_product={$id_product}";
        $id_ag_group_products = DB::getInstance()->getValue($query);

        $query = "UPDATE `"._DB_PREFIX_."ag_group_attribute` SET position={$position} WHERE id_ag_group={$id_ag_group} AND id_value={$attribute_value} AND id_ag_group_products={$id_ag_group_products}";
        print $query;
        DB::getInstance()->execute($query);
    }

    function getIdProductAttributeByAttributeId($id_attribute)
    {
        $query = "SELECT id_product_attribute FROM `"._DB_PREFIX_."product_attribute_combination` WHERE id_attribute={$id_attribute}";
        $res = DB::getInstance()->getValue($query);
        return $res;
        //die(json_encode(array('query' => $query, 'id' => $res)));

    }

    function jsonLog($object, $die = false)
    {
        echo json_encode(array('object' => $object))."<br>";
        if ($die)
            die("END");
    }

    public function jsLog($object)
    {
        $output = "<script>";
        if (is_array($object))
            foreach ($object as $item) {
                $output .= "console.log(" . json_encode($item) . ");";
            }
        else
            $output .= "console.log(" . json_encode($object) . ");";

        echo $output . "</script>";
    }
}