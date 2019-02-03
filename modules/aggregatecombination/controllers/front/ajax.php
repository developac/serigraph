<?php
/**
 * Created by PhpStorm.
 * User: Utente
 * Date: 15/01/2019
 * Time: 10:54
 */

require_once _PS_MODULE_DIR_ . "aggregatecombination/classes/AggregateCombinationGroup.php";
require_once _PS_MODULE_DIR_ . "aggregatecombination/classes/AggregateCombinationGroupAttributes.php";
require_once _PS_MODULE_DIR_ . "aggregatecombination/classes/AggregateCombinationGroupProducts.php";
require_once _PS_MODULE_DIR_ . "aggregatecombination/classes/AggregateCombinationGroupProductsRule.php";
require_once _PS_MODULE_DIR_ . "aggregatecombination/classes/AggregateCombinationGroupProductsRuleAttribute.php";
require_once _PS_MODULE_DIR_ . "aggregatecombination/classes/AgAttributeTemp.php";

class AggregatecombinationAjaxModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
        $this->ajax = true;
        parent::initContent();
    }

    public function displayAjax()
    {

        $method = Tools::getValue("method");

        switch ($method) {
            case 'save' :
                {

                    $idProduct = Tools::getValue('product', 0);
                    $nome = Tools::getValue('group', $this->trans("Empty Group"));
                    $arrayCombination = Tools::getValue('element', []);
                    $id_ag_group = Tools::getValue('id_ag_group', []);


                    //Tools::dieObject(Tools::getAllValues());

                    if ($id_ag_group) {

                        $outArrayCombination = [];
                        foreach ($arrayCombination as $key => $arr) {
                            if (is_array($arr)) {
                                $outArrayCombination[$key] = $arr;
                            }
                        }

                        foreach ($outArrayCombination as $id_attribue => $attributes) {
                            //Tools::dieObject($id_attribue, false);
                            foreach ($attributes as $id_value) {
                                $acGA = new AggregateCombinationGroupAttributes();
                                $acGA->id_ag_group = $id_ag_group;
                                $acGA->id_attribute = $id_attribue;
                                $acGA->id_value = $id_value;
                                $acGA->save();
                            }
                        }
                        //Tools::dieObject($outArrayCombination);


                        //AgGroup::createAttributes($id_ag_group,$outArrayCombination);

                        die(json_encode(array('id_ag_group' => $id_ag_group, 'nome' => $nome)));

                    }

                    break;
                }
            case 'generate' :
                {

                    $idProduct = Tools::getValue('product', 0);
                    $arrayGroup = Tools::getValue('group', []);

                    $query = "SELECT * FROM " . _DB_PREFIX_ . "ag_group_attribute where id_ag_group IN (" . implode($arrayGroup, ',') . ")";
                    $results = Db::getInstance()->executeS($query);

                    //Tools::dieObject($query, false);
                    //Tools::dieObject($results);

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

                    //Tools::dieObject($outCombination);

                    $outCombination = $this->get_combinations($outCombination);
                    if ($this->createCombination($idProduct, $outCombination)) {
                        foreach ($arrayGroup as $group) {
                            //AggregateCombinationGroupProducts::linkProduct($group, $idProduct);
                            $acGP = new AggregateCombinationGroupProducts();
                            $acGP->id_ag_group = $group;
                            $acGP->id_product = $idProduct;
                            $acGP->save();
                        }
                    }

                    die(Tools::jsonEncode(array('result' => "generate")));
                    break;
                }
            case 'export' :
                {

                    $idProduct = Tools::getValue('product', 0);

                    $query = '
                        SELECT pa.id_product_attribute as \'Id_Attributo\',p.active \'Attivo\', m.name AS \'Marca\', p.id_product AS \'ID\', p.reference AS \'Rif.\', pl.name AS \'Nome\', GROUP_CONCAT(DISTINCT(al.name) SEPARATOR ", ") AS \'Combinazione\', s.quantity AS \'Quantità\', COALESCE(pa.price,p.price) AS \'Prezzo\', IF(pr.reduction_type=\'amount\',pr.reduction,\'\') AS \'Sconto valuta\', IF(pr.reduction_type=\'percentage\',pr.reduction,\'\') AS \'Sconto percento\', pr.from AS \'Sconto da (yyyy-mm-dd)\', pr.to AS \'Sconto a (yyyy-mm-dd)\', p.weight AS \'Peso\', GROUP_CONCAT(DISTINCT(cl.name) SEPARATOR ",") AS \'Categorie\', pl.description_short AS \'Desc. breve\', pl.description AS \'Desc. lunga\'
                        FROM ps_product p
                        LEFT JOIN ps_product_lang pl ON (p.id_product = pl.id_product)
                        LEFT JOIN ps_manufacturer m ON (p.id_manufacturer = m.id_manufacturer)
                        LEFT JOIN ps_category_product cp ON (p.id_product = cp.id_product)
                        LEFT JOIN ps_category_lang cl ON (cp.id_category = cl.id_category)
                        LEFT JOIN ps_category c ON (cp.id_category = c.id_category)
                        LEFT JOIN ps_product_tag pt ON (p.id_product = pt.id_product)
                        LEFT JOIN ps_product_attribute pa ON (p.id_product = pa.id_product)
                        LEFT JOIN ps_specific_price pr ON (p.id_product = pr.id_product)
                        LEFT JOIN ps_stock_available s ON (p.id_product = s.id_product AND pa.id_product_attribute = s.id_product_attribute)
                        LEFT JOIN ps_product_attribute_combination pac ON (pac.id_product_attribute = pa.id_product_attribute)
                        LEFT JOIN ps_attribute_lang al ON (al.id_attribute = pac.id_attribute)
                        WHERE pl.id_lang = 1
                        AND cl.id_lang = 1
                        AND p.id_shop_default = 1
                        AND c.id_shop_default = 1
                        AND p.id_product = ' . $idProduct . '
                        GROUP BY pac.id_product_attribute
                    ';

                    $results = Db::getInstance()->executeS($query);

                    if (!empty($results)) {

                        $delimiter = ";";

                        $filename = "exports_" . date('Y-m-d') . ".csv";

                        header("Content-Type: text/csv");
                        header("Content-Disposition: attachment; filename=$filename");
                        header("Cache-Control: no-cache, no-store, must-revalidate");
                        header("Pragma: no-cache");
                        header("Expires: 0");

                        //create a file pointer
                        $f = fopen("php://output", "w");

                        //set column headers
                        $fields = array('Id_Attributo', 'Attivo', 'Marca', 'ID', 'Rif.', 'Nome', 'Combinazione', 'Quantità', 'Prezzo');
                        fputcsv($f, $fields, $delimiter);

                        //output each row of the data, format line as csv and write to file pointer
                        foreach ($results as $row) {
                            $row["Prezzo"] = number_format($row["Prezzo"], 3);
                            $lineData = array($row['Id_Attributo'], $row['Attivo'], $row['Marca'], $row['ID'], $row['Rif.'], $row['Nome'], $row['Combinazione'], $row['Quantità'], $row['Prezzo']);
                            fputcsv($f, $lineData, $delimiter);
                        }

                        //move back to beginning of file
                        fclose($f);
                        break;

                    } else {
                        die(Tools::jsonEncode(array('error' => "impossibile esportare")));
                    }

                }

            case 'import' :
                {

                    $idProduct = Tools::getValue('product', 0);

                    $product = new Product($idProduct);

                    if ($product->hasAttributes()) {
                        $tmpName = $_FILES['file']['tmp_name'];

                        $csvData = file_get_contents($tmpName);
                        $lines = explode(PHP_EOL, $csvData);
                        $array = array();
                        foreach ($lines as $line) {
                            $array[] = str_getcsv($line, ";");
                        }

                        $header = $array[0];
                        $outArray = [];
                        unset($array[0]);

                        foreach ($array as $k => $arr) {
                            $outArray[$k] = [];
                            foreach ($arr as $key => $item) {
                                $outArray[$k][$header[$key]] = $item;
                            }
                            //check if id_product
//                    if($outArray[$k]['ID'] != $idProduct){
//                        die(Tools::jsonEncode(array('message' => "Errore, file import non valido per questo prodotto")));
//                    }
                        }

                        //TODO PER IL MOMENTO AGGIORNO SOLO IL PREZZO
                        foreach ($outArray as $item) {

                            if ((int)$item['Id_Attributo'] > 0) {
                                $combination = new combination ((int)$item['Id_Attributo']);
                                $combination->price = (double)$item['Prezzo'];
                                $combination->update();
                            }
                        }
                        die(Tools::jsonEncode(array('message' => "Import avvenuto correttamente")));
                    } else {
                        die(Tools::jsonEncode(array('error' => "Impossibile importare. Combinazioni non esistenti per questo prodotto")));
                    }
                    break;

                }

            case 'saveTemp' :
                {
                    $idProduct = Tools::getValue('product', 0);
                    $combinationAttributeTemp = Tools::getValue('element', []);
                    $attributeTemp = Tools::getValue('attributeTemp', []);
                    $array = [];

                    if (!empty($attributeTemp)) {
                        foreach ($attributeTemp as $attribute) {

                            $attributeTemp = new Attribute($attribute["id_attribute_temp"]);
                            $attributeTemp->name[1] = substr($attributeTemp->name[1], 1);
                            $date = new DateTime('now');
                            $date->add(new DateInterval($attributeTemp->name[1]));
                            $attributeTemp->name[1] = $date->format('d/m/Y');

                            if (!empty($combinationAttributeTemp)) {
                                $array[$attribute["id_attribute_temp"]] = [];
                                foreach ($combinationAttributeTemp as $combination) {

                                    AgAttributeTemp::create($idProduct, $attribute["id_attribute_temp"], $combination, $attribute['value'], $attribute['type']);

                                    $attr = new Attribute($combination);
                                    $groupAttribute = new AttributeGroup($attr->id_attribute_group);
                                    $name = $groupAttribute->name[1] . " : " . $attr->name[1];

                                    $array[$attribute["id_attribute_temp"]]["id"] = $attribute["id_attribute_temp"];
                                    $array[$attribute["id_attribute_temp"]]["temp"] = $attributeTemp->name[1];
                                    $array[$attribute["id_attribute_temp"]]["comb"] = !isset($array[$attribute["id_attribute_temp"]]["comb"]) ? $name . " ;" : $array[$attribute["id_attribute_temp"]]["comb"] . $name . " ; ";
                                    $array[$attribute["id_attribute_temp"]]["value"] = $attribute['value'] . "" . $attribute['type'];
                                    $array[$attribute["id_attribute_temp"]]["query"] = !isset($array[$attribute["id_attribute_temp"]]["query"]) ? "," . $combination : $array[$attribute["id_attribute_temp"]]["query"] . "," . $combination;

                                }
                            } else {

                                AgAttributeTemp::create($idProduct, $attribute["id_attribute_temp"], '*', $attribute['value'], $attribute['type']);

                                $array[$attribute["id_attribute_temp"]] = [];
                                $array[$attribute["id_attribute_temp"]]["id"] = $attribute["id_attribute_temp"];
                                $array[$attribute["id_attribute_temp"]]["temp"] = $attributeTemp->name[1];
                                $array[$attribute["id_attribute_temp"]]["comb"] = "Tutti";
                                $array[$attribute["id_attribute_temp"]]["value"] = $attribute['value'] . "" . $attribute['type'];
                                $array[$attribute["id_attribute_temp"]]["query"] = '*';

                            }

                        }
                        die(Tools::jsonEncode(array('array' => $array)));
                    } else {
                        die(Tools::jsonEncode(array('message' => "Nessun valore temporale impostato")));
                    }

                    break;
                }

            case 'deleteTemp' :
                {
                    $idProduct = Tools::getValue('product', 0);
                    $idsAttribute = Tools::getValue('idsAttribute', 0);
                    $idAttributeTemp = Tools::getValue('idAttributeTemp', 0);

                    $idsAttribute = $idsAttribute == '*' ? "'*'" : ltrim($idsAttribute, ',');

                    AgAttributeTemp::delete($idProduct, $idAttributeTemp, $idsAttribute);

                    die(Tools::jsonEncode(array('message' => "Valore cancellato correttamente")));

                    break;

                }

            case 'deleteGroup' :
                {
                    $idProduct = Tools::getValue('product', 0);
                    $idGroup = Tools::getValue('idGroup', 0);

                    if (AggregateCombinationGroupProducts::deleteByGroupIdAndProductId($idGroup, $idProduct))
                        die(json_encode(array('message' => "Valore cancellato correttamente")));
                    else
                        die(json_encode(array('message' => "Si è verificato un errore dutante la cancellazione del gruppo")));

                    break;

                }

            case 'saveRule' :
                {

                    die("SAVE RULE");
                    /*
                     * TODO: fix salvataggio, non salva le regole separate per lo stesso gruppo
                     */
                    $idProduct = Tools::getValue('product', 0);
                    $combinationAttributeRules = Tools::getValue('element', []);
                    $rules = Tools::getValue('rule', []);
                    $array = [];

                    //Tools::dieObject(Tools::getAllValues(), false);
                    $this->jsonLog($rules);

                    return false;

                    if (!empty($rules)) {
                        foreach ($rules as $rule) {

                            //$ruleProduct = AgGroup::getLinkProduct($rule["id_group"],$idProduct);
                            $ruleProduct = AggregateCombinationGroupProducts::getByGroupIdAndProductId($rule["id_group"], $idProduct);

                            $agPR = new AggregateCombinationGroupProductsRule();
                            $agPR->id_ag_group_products = $ruleProduct[0]['id_ag_group_products'];
                            $agPR->name = $rule["rule"];

                            if ($agPR->save()) {

                                $idRule = Db::getInstance()->Insert_ID();

                                //Tools::dieObject($idRule, false);

                                //continue;

                                if (!empty($combinationAttributeRules)) {

                                    $array[$idRule] = [];
                                    foreach ($combinationAttributeRules as $combination) {

                                        //continue;

                                        $attr = new Attribute($combination);
                                        $groupAttribute = new AttributeGroup($attr->id_attribute_group);

                                        //Tools::dieObject($groupAttribute, false);

                                        $agPRA = new AggregateCombinationGroupProductsRuleAttribute();
                                        $agPRA->id_ag_group_products_rule = $idRule;
                                        $agPRA->id_attribute = $attr->id_attribute_group;
                                        $agPRA->id_attribute_value = $combination;
                                        $agPRA->valore = $rule['value'];
                                        $agPRA->tipologia = $rule['type'];
                                        $agPRA->save();

                                        //AgGroup::linkRuleAttributes($idRule,$attr->id_attribute_group,$combination,$rule["value"],$rule["type"]);

                                        $name = $groupAttribute->name[1] . " : " . $attr->name[1];

                                        //$array[$idRule]["id"] = $attribute["id_attribute_temp"];
                                        //$array[$idRule]["temp"] =  $attributeTemp->name[1];
                                        //$array[$idRule]["comb"] = !isset($array[$attribute["id_attribute_temp"]]["comb"]) ? $name." ;"  : $array[$attribute["id_attribute_temp"]]["comb"].$name." ; ";
                                        //$array[$idRule]["value"] = $attribute['value']."".$attribute['type'];
                                        //$array[$idRule]["query"] = !isset($array[$attribute["id_attribute_temp"]]["query"]) ? ",".$combination : $array[$attribute["id_attribute_temp"]]["query"].",".$combination;

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

                    break;
                }

            case 'getAttributeGroup' :
                {

                    $idGroup = Tools::getValue("group");
                    $idProduct = Tools::getValue("product");

                    $columnHeaderGroup = [];
                    $attributeOptionGroup = [];

                    if (empty($columnHeaderGroup)) {

                        //$attributesGroup = AgGroup::getAttributes($idGroup);
                        $attributesGroup = AggregateCombinationGroupAttributes::getByGroupId($idGroup);

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

                    die(Tools::jsonEncode(array('html' => $this->context->smarty->fetch($path_to_tpl_folder))));

                    break;
                }

            case 'deleteRule' :
                {

                    //Tools::dieObject(Tools::getAllValues());

                    $idRule = Tools::getValue("idRule");

                    //delete all rule attributes
                    $rules_attributes = AggregateCombinationGroupProductsRuleAttribute::deleteByRuleID($idRule);

                    //delete rule
                    $acGPR = new AggregateCombinationGroupProductsRule($idRule);
                    if ($acGPR->delete())
                        die(json_encode(array("status" => true, "message" => "ok")));
                    else
                        die(json_encode(array("status" => false, "message" => "ok")));
                    //Tools::dieObject($rules_attributes);

                    //AgGroup::deleteRule($idRule);


                    break;
                }
        }

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


    private function createCombination($idProduct, $outArrayCombination)
    {


        //return false;

        if ($idProduct) {

            $product = new Product($idProduct, true, 1);

            //ini_set("memory_limit", "512M");

            foreach ($outArrayCombination as $combinations) {


                if (!count($combinations))
                    continue;


                //Tools::dieObject($combinations, false);
                $this->jsonLog($combinations);


                //continue;


                if (!$product->productAttributeExists($combinations)) {
                    $price = 1;
                    $weight = 1;
                    $ecotax = 1;
                    $unit_price_impact = "";
                    $quantity = 1;
                    $reference = "";
                    $supplier_reference = "";
                    $ean13 = "";
                    $default = false;

                    $idProductAttribute = $product->addProductAttribute((float)$price, (float)$weight, $unit_price_impact, (float)$ecotax, (int)$quantity, "", strval($reference), strval($supplier_reference), strval($ean13), $default, NULL, NULL, NULL, NULL);
                    $product->addAttributeCombinaison($idProductAttribute, $combinations);
                }
            }

            ini_set("memory_limit", "128M");

        }

        return true;
    }


    function jsonLog($object, $die = false)
    {
        echo json_encode(array('object' => $object));
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