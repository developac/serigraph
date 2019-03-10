<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class AC_Businesscard extends Module
{
    public function __construct()
    {
        $this->name = 'ac_businesscard';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'CuPer';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Shopping cart', array(), 'Modules.Shoppingcart.Admin');
        $this->description = $this->trans('Adds a block containing the customer\'s shopping cart.', array(), 'Modules.Shoppingcart.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
        //$this->controllers = array('ajax');
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('actionBeforeCartUpdateQty')
        )
            return false;

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall())
            return false;

        return true;
    }

    public function hookActionBeforeCartUpdateQty($params)
    {
        /*
        print "<pre>";
        print_r($params['cart']);
        die();
        */
    }
}