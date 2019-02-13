<?php
/**
 * 2007-2015 Apollotheme
 *
 * NOTICE OF LICENSE
 *
 * ApPageBuilder is module help you can build content for your shop
 *
 * DISCLAIMER
 *
 *  @author    Apollotheme <apollotheme@gmail.com>
 *  @copyright 2007-2015 Apollotheme
 *  @license   http://apollotheme.com - prestashop template provider
 */

if (!defined('_PS_VERSION_')) {
    # module validation
    exit;
}
require_once(_PS_MODULE_DIR_.'appagebuilder/libs/Helper.php');
require_once(_PS_MODULE_DIR_.'appagebuilder/libs/LeoFrameworkHelper.php');
require_once(_PS_MODULE_DIR_.'appagebuilder/classes/ApPageSetting.php');
require_once(_PS_MODULE_DIR_.'appagebuilder/classes/ApPageBuilderModel.php');
require_once(_PS_MODULE_DIR_.'appagebuilder/classes/ApPageBuilderProfilesModel.php');
require_once(_PS_MODULE_DIR_.'appagebuilder/classes/ApPageBuilderProductsModel.php');
require_once(_PS_MODULE_DIR_.'appagebuilder/classes/ApPageBuilderShortcodeModel.php');

class APPageBuilderOverride extends APPageBuilder
{

    public function hookDisplayLeoProfileProduct($params)
    {
        apPageHelper::setGlobalVariable($this->context);
        $html = '';
        $tpl_file = '';

        
        if (isset($params['ony_global_variable'])) {
            # {hook h='displayLeoProfileProduct' ony_global_variable=true}
            return $html;
        }

        if (!isset($params['product'])) {
            return 'Not exist product to load template';
        } else if (isset($params['profile'])) {
            # {hook h='displayLeoProfileProduct' product=$product profile=$productProfileDefault}
            $tpl_file = apPageHelper::getConfigDir('theme_profiles') . $params['profile'].'.tpl';
        } else if (isset($params['load_file'])) {
            # {hook h='displayLeoProfileProduct' product=$product load_file='templates/catalog/_partials/miniatures/product.tpl'}
            $tpl_file = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/' . $params['load_file'];
        } else if (isset($params['typeProduct'])) {
            //DONGND:: load default product tpl when do not have product profile
            if ($params['product']['productLayout'] != '') {
                $tpl_file = apPageHelper::getConfigDir('theme_details') . $params['product']['productLayout'].'.tpl';
            } else {
                $tpl_file = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/templates/catalog/product.tpl';
            }
        }

        if (empty($tpl_file)) {
            return 'Not exist profile to load template';
        }

        Context::getContext()->smarty->assign(array(
            'product' => $params['product'],
        ));

        $tpl_file_2 = _PS_MODULE_DIR_.'aggregatecombination/views/templates/front/aggregatecombination.tpl';
        $html .= Context::getContext()->smarty->fetch('extends:'.$tpl_file.'|'.$tpl_file_2);
        return $html;
    }
}
