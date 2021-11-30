<?php
/**
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
class Dsproductcomments extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'dsproductcomments';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Dark-Side.pro';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('DS: Product comments');
        $this->description = $this->l('DS: Product comments');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('DSPRODUCTCOMMENTS_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');
        $this->createTab();
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayCustomerAccount') &&
            $this->registerHook('ModuleRoutes') &&
            $this->registerHook('displayFooterProduct') && 
            $this->registerHook('displayProductActions');
    }

    public function uninstall()
    {
        $this->tabRem();
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitDsproductcommentsModule')) == true) {
            $this->postProcess();
        }

        $newComments = $this->getNotReviewComments();
        $reviewdComments = $this->getReviewedComments();

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('newComments', $newComments);
        $this->context->smarty->assign('reviewdComments', $reviewdComments);
        $this->context->smarty->assign('namemodules', $this->name);
        $this->context->smarty->assign('link', $this->context->link);
        $this->context->smarty->assign('base_url', __PS_BASE_URI__);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitDsproductcommentsModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Accept comments'),
                        'name' => 'DSPRODUCTCOMMENTS_ACCEPT_ALL',
                        'is_bool' => true,
                        'desc' => $this->l('Use this to automaticly accept all comments'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Accept all comments')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Manual review comments')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Accept comments from guest'),
                        'name' => 'DSPRODUCTCOMMENTS_ALLOW_GUESTS',
                        'is_bool' => true,
                        'desc' => $this->l(''),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Accept comments from guest')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Not accept comments from guest')
                            )
                        ),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'DSPRODUCTCOMMENTS_ACCEPT_ALL' => Configuration::get('DSPRODUCTCOMMENTS_ACCEPT_ALL', true),
            'DSPRODUCTCOMMENTS_ALLOW_GUESTS' => Configuration::get('DSPRODUCTCOMMENTS_ALLOW_GUESTS', false)
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $psVersion = (float) _PS_VERSION_;

        if ($psVersion <= 1.7) {
            $this->context->controller->addJS($this->_path.'views/js/front.js');
            $this->context->controller->addCSS($this->_path.'views/css/front.css');
        } else {
            $this->context->controller->registerStylesheet(1, $this->_path.'/views/css/front.css');
            $this->context->controller->registerJavascript(1, $this->_path.'/views/js/front.js');
        }
    }

    public function hookDisplayCustomerAccount()
    {
        if ($this->context->customer->isLogged()) {
            return $this->display(__FILE__, 'displayCustomerAccount.tpl');
        }
    }

    public function hookModuleRoutes()
    {
        return array(
            'module-dsproductcomments-comments' => array(
                'controller' => 'comments',
                'rule' => 'comments',
                'keywords' => array(),
                'params' => array(
                    'module' => 'dsproductcomments',
                    'fc' => 'module',
                )
            )
        );
    }

    public function getNotReviewComments(): array
    {
        /* $sql = new DbQuery();
        $sql->select('dsp.*, c.*', 'pl.*')
            ->from('dsproductcomments', 'dsp')
            ->leftJoin('customer', 'c', 'c.id_customer = dsp.customer_id')
            ->leftJoin('product_lang', 'pl', 'pl.id_product = dsp.id_product')
            ->where('dsp.status = 0')
            ->groupBy('dsp.id')
            ->orderBy('dsp.created_at'); */

        $sql = 'SELECT dsp.*, c.firstname, c.lastname, pl.name as product_name
        FROM '._DB_PREFIX_.'dsproductcomments as dsp
        LEFT JOIN '._DB_PREFIX_.'customer as c ON c.id_customer = dsp.customer_id
        LEFT JOIN '._DB_PREFIX_.'product_lang as pl ON pl.id_product = dsp.id_product
        WHERE dsp.status = 0
        GROUP BY dsp.id
        ORDER BY dsp.created_at
        ';

        return Db::getInstance()->executeS($sql);
    }

    public function getReviewedComments(): array
    {
        $sql = 'SELECT dsp.*, c.firstname, c.lastname, pl.name as product_name
        FROM '._DB_PREFIX_.'dsproductcomments as dsp
        LEFT JOIN '._DB_PREFIX_.'customer as c ON c.id_customer = dsp.customer_id
        LEFT JOIN '._DB_PREFIX_.'product_lang as pl ON pl.id_product = dsp.id_product
        WHERE dsp.status = 1
        GROUP BY dsp.id
        ORDER BY dsp.created_at';
        
        return Db::getInstance()->executeS($sql);
    }

    public function deleteTempComment($id): void
    {
        $comment = new DSProductComment($id);
        $comment->delete();
    }

    protected function getRevievCommentsForProductByLang(int $id_product, int $id_lang): array
    {
        $sql = 'SELECT * 
        FROM '._DB_PREFIX_.'dsproductcomments 
        WHERE id_product = '.$id_product.' AND id_lang = '.$id_lang.' AND status  = 1
        ORDER BY created_at DESC';

        return Db::getInstance()->executeS($sql);    
    }

    protected function getAverageProductRating($id_product): array
    {
        /*$sql = new DbQuery;
        $sql->select("AVG('stars')")
            ->from('dsproductcomments')
            ->where('status = 1 AND id_product ='.$id_product); */

        $sql = 'SELECT AVG(stars) 
        FROM '._DB_PREFIX_.'dsproductcomments 
        WHERE status = 1 AND id_product = '.$id_product;

        return Db::getInstance()->executeS($sql);      
    }

    protected function isUserAddComment(int $id_customer,int $id_product): bool
    {
        $sql = 'SELECT id 
        FROM '._DB_PREFIX_.'dsproductcomments 
        WHERE id_product = .'.$id_product.' AND customer_id = '.$id_customer;

        $result = Db::getInstance()->executeS($sql);   

        if (!empty($result)) {
            return true;
        } 

        return false;
    }

    protected function canCustomerWriteComment($id_product, $id_customer): bool
    {
        $sql = new DbQuery();
        $sql->select('od.id_order_detail')
            ->from('order_detail', 'od')
            ->leftJoin('orders', 'o', 'o.id_order = od.id_order')
            ->where('od.product_id = '.$id_product.' AND o.id_customer = '.$id_customer);
        
        $result = Db::getInstance()->executeS($sql);      

        if (!empty($result)) {
            return true;
        }

        return false;
    }

    public function hookDisplayFooterProduct($params)
    {
        $id_product = $params['product']['id'];
        $id_lang = $this->context->cookie->id_lang;
        $id_customer = $this->context->cookie->id_customer;
        $isCreatedComment = false;
        $logged = $this->context->customer->isLogged();
        
        $comments = $this->getRevievCommentsForProductByLang($id_product, $id_lang);        
        $aveargeRating = $this->getAverageProductRating($id_product);
        $canWrite = false;
        $canCustomerWriteComment = $this->canCustomerWriteComment($id_product, $id_customer);
        $canGuestWriteComment = Configuration::get('DSPRODUCTCOMMENTS_ALLOW_GUESTS');

        $isCreatedComment = $this->isUserAddComment($id_customer, $id_product);

        if ($isCreatedComment != true) {
            if (($canCustomerWriteComment == true) || ($canGuestWriteComment == true)) {
                $canWrite = true;
            }
        }

        $this->context->smarty->assign('comments', $comments);
        $this->context->smarty->assign('averageRating', $aveargeRating);
        $this->context->smarty->assign('isCreatedComment', $isCreatedComment);
        $this->context->smarty->assign('logged', $logged);
        $this->context->smarty->assign('canWrite', $canWrite);
        $this->context->smarty->assign('id_lang', $id_lang);

        

        return $this->context->smarty->fetch('module:dsproductcomments/views/templates/hook/displayFooterProduct.tpl');
    }

    private function createTab()
    {
        $response = true;
        $parentTabID = Tab::getIdFromClassName('AdminDarkSideMenu');
        if ($parentTabID) {
            $parentTab = new Tab($parentTabID);
        } else {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = 'AdminDarkSideMenu';
            foreach (Language::getLanguages() as $lang) {
                $parentTab->name[$lang['id_lang']] = 'Dark-Side.pro';
            }
            $parentTab->id_parent = 0;
            $parentTab->module = '';
            $response &= $parentTab->add();
        }
        $parentTab_2ID = Tab::getIdFromClassName('AdminDarkSideMenuSecond');
        if ($parentTab_2ID) {
            $parentTab_2 = new Tab($parentTab_2ID);
        } else {
            $parentTab_2 = new Tab();
            $parentTab_2->active = 1;
            $parentTab_2->name = array();
            $parentTab_2->class_name = 'AdminDarkSideMenuSecond';
            foreach (Language::getLanguages() as $lang) {
                $parentTab_2->name[$lang['id_lang']] = 'Dark-Side Config';
            }
            $parentTab_2->id_parent = $parentTab->id;
            $parentTab_2->module = '';
            $response &= $parentTab_2->add();
        }
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdministratorDsproductcomments';
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = 'DS: Product comments';
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    private function tabRem()
    {
        $id_tab = Tab::getIdFromClassName('AdministratorDsproductcomments');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
        $parentTab_2ID = Tab::getIdFromClassName('AdminDarkSideMenuSecond');
        if ($parentTab_2ID) {
            $tabCount_2 = Tab::getNbTabs($parentTab_2ID);
            if ($tabCount_2 == 0) {
                $parentTab_2 = new Tab($parentTab_2ID);
                $parentTab_2->delete();
            }
        }
        $parentTabID = Tab::getIdFromClassName('AdminDarkSideMenu');
        if ($parentTabID) {
            $tabCount = Tab::getNbTabs($parentTabID);
            if ($tabCount == 0) {
                $parentTab = new Tab($parentTabID);
                $parentTab->delete();
            }
        }

        return true;
    }

    public function hookDisplayProductActions()
    {
        return $this->context->smarty->fetch('module:dsproductcomments/views/templates/hook/displayProductActions.tpl');
    }
}
