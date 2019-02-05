<?php
/**
 * Created by PhpStorm.
 * User: Utente
 * Date: 15/01/2019
 * Time: 09:05
 */


require_once _PS_MODULE_DIR_."aggregatecombination/classes/AggregateCombinationGroup.php";

class AdminAggregateCombinationController extends ModuleAdminController
{
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
    }

    public function ajaxProcessGenerateCombinations()
    {

    }
}