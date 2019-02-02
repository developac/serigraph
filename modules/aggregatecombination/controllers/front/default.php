<?php
/**
 * Created by PhpStorm.
 * User: Utente
 * Date: 15/01/2019
 * Time: 10:54
 */

class AggregateCombinationDefaultModuleFrontController extends ModuleFrontController
{
    public function initContent(){
        $row = array();
        parent::initContent();
        if(Tools::getValue('ajax')){

        } else
            $json = array(
                'status' => 'error',
                'message' => $this->l('Error when getting product informations.')
            );

        die(Tools::jsonEncode($json));
    }
}