<?php
/**
* Advance Blog
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
* @author    Dark-Side.pro
* @copyright Copyright 2017 © Dark-Side.pro All right reserved
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* @category  FO Module
* @package   dsafillate
*/
require 'vendor/autoload.php';
use Intervention\Image\ImageManager;
class DsproductcommentsCommentsModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        if (!Context::getContext()->customer->isLogged()) {
            Tools::redirect('index.php?controller=authentication&redirect=module&module='.$this->module->name.'&action=comments');
        }

        $customer_id = (int) Context::getContext()->customer->id;
        $lang_id = $this->context->language->id;
        $comments = $this->getCustomerComments($customer_id, $lang_id);

        $this->context->smarty->assign('comments', $comments);
        $this->context->smarty->assign('id_module', $this->module->id);
        $this->context->smarty->assign('link', $this->context->link);
        $this->context->smarty->assign(array('lang_iso' => $this->context->language->iso_code));
        $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
        $this->context->smarty->assign(array('base_dir' => _PS_BASE_URL_.__PS_BASE_URI__,
            'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
            'force_ssl' => $force_ssl));
        $this->setTemplate('module:'.$this->module->name.'/views/templates/front/comments.tpl');
    }

    public function setMedia()
    {
        parent::setMedia();

        $this->addJqueryUi('ui.datepicker');
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();
        $breadcrumb['links'][] = array('title' => 'My account',
            'url' => $this->context->link->getPageLink('my-account'),
        );
        $breadcrumb['links'][] = array('title' => 'My product comments',
            'url' => $this->context->link->getModuleLink($this->module->name, 'comments', array(), true),
        );
        return $breadcrumb;
    }

    protected function getCustomerComments(int $customer_id, int $lang_id): array
    {
        $sql = new DbQuery();
        $sql->select('p.name')
            ->from('dsproductcomments', 'dsc')
            ->leftJoin('product_lang', 'p', 'p.id_product = dsc.id_product')
            ->where('dsc.customer_id = '.$customer_id);
        
        
        return DB::getInstance()->executeS($sql);
    }

    public function displayAjaxNewrabatAction()
    {
        if (Tools::isSubmit('ratingComment')) {
            $stars = (int) Tools::getValue('ratingStar');
            $title = Tools::getValue('ratingTitle');
            $content = Tools::getValue('ratingContent');
            $image = null;

            if (isset($_FILES['ratingImage'])) {
                $image = $_FILES['ratingImage'];
            }

            $customer_id = (int) Context::getContext()->customer->id;

            $this->createComment($title, $stars, $image, $content, $customer_id);
        }
    }

    protected function createComment(string $title, int $stars, array $image, string $content): array
    {
        return [];
    }

    protected function convertImage($image): void
    {
        var_dump($image);
        $size = getimagesize($image);

        if ($size === false) {
            throw new Exception('Invalid image');
        }

        if ($size[0] < 500 && $size[1] < 500) {
            throw new Exception('Image is too small');
        }
        
        $imageLib = new \Intervention\Image\Image;
        $date = new DateTime();
        $stringDate = $date->format('Y-m-d H:i:s');
        $name = md5($stringDate);
        $smallImage = $imageLib->make($image['tmp_name'])->resize(300)->save($this->_path.'/uploads/'.$name);
        $bigImage = $imageLib->save($this->_path.'/uploads/'.$name);
    }    
    
}
