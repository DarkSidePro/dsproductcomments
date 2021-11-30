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

use Gumlet\ImageResize;

require 'modules/dsproductcomments/classes/DSProductComment.php';
class DsproductcommentsnewcommentModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $guestAllowed = false;
    public $auth = true;

    public function initContent()
    {
        $this->ajax = true;
        parent::initContent();
    }

    public function postProcess()
    {
    }

    public function displayAjaxNewrabatAction()
    {
        if (Tools::isSubmit('ratingComment')) {
            $stars = (int) Tools::getValue('ratingStar');
            $title = Tools::getValue('ratingTitle');
            $content = Tools::getValue('ratingContent');
            $lang_id = (int) Tools::getValue('ratingLang');
            $id_product = (int)Tools::getValue('ratingComment');

            $image = null;

            if (isset($_FILES['ratingImage']) && $_FILES['ratingImage']['tmp_name'] != null) {
                $image = $_FILES['ratingImage'];
            }

            $customer_id = (int) Context::getContext()->customer->id;

            $id = $this->createComment($title, $stars, $image, $content, $customer_id, $lang_id, $id_product);

            header('Content-Type: application/json; charset=utf-8');
            if (is_int($id)) {
                echo json_encode(['msg' => 'Success']);
                die();
            } else {
                echo json_encode(['msg' => 'something gone wrong']);
                die();
            }
        }
    }

    protected function createComment(
        string $title, 
        int $stars, 
        $image, 
        string $content, 
        int $customer_id, 
        int $lang_id, 
        int $id_product
    ): int
    {
        $commentObject = new DSProductComment();
        if ($image != null) {
            $imageName = $this->convertImage($image);
            $commentObject->photo = $imageName;
        }

        $date = new DateTime();
        $stringDate = $date->format('Y-m-d H:i:s');
        
        $commentObject->title = htmlspecialchars($title);
        $commentObject->content = htmlspecialchars($content);
        $commentObject->stars = $stars;
        $commentObject->status = Configuration::get('DSPRODUCTCOMMENTS_ACCEPT_ALL');
        $commentObject->id_product = $id_product;
        $commentObject->created_at = $stringDate;
        $commentObject->customer_id = $customer_id;
        $commentObject->id_lang = $lang_id;
        $commentObject->add();

        return $commentObject->id;
    }

    protected function convertImage($image): string
    {
        $size = getimagesize($image['tmp_name']);

        if ($size === false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['msg' => 'Invalid image']);
            die();
        }

        if ($size[0] < 500 && $size[1] < 500) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['msg' => 'Image is too small']);
            die();
        }
        
        $date = new DateTime();
        $stringDate = $date->format('Y-m-d H:i:s');
        $name = md5($stringDate).'.png';

        $currentDir = dirname(__FILE__);
        
        $currentDir = realpath($currentDir . '/../../');
        $preoutput = $currentDir . '/uploads/preoutput.png';
        
        $file = $image['name'];
        $ext = substr($file, strrpos($file, '.') + 1);

        if ($ext == 'bmp') {
            $pre = imagepng(imagecreatefrombmp($image['tmp_name']), $preoutput);
        } elseif ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG') {
            $pre = imagepng(imagecreatefromjpeg($image['tmp_name']), $preoutput);
        } elseif ($ext == 'gif') {
            $pre = imagepng(imagecreatefromgif($image['tmp_name']), $preoutput);
        } else {
            if ($ext != 'png') {
                echo json_encode(['msg' => 'Please select correct image file.']); 
                die();
            } else {
                $pre = imagepng(imagecreatefrompng($image['tmp_name']), $preoutput);
            }
        }

        $imageResize = new ImageResize($preoutput);
        $imageResize->resizeToWidth(500);
        $imageResize->save($currentDir.'/uploads/'.$name, IMAGETYPE_PNG, 100);

        return $name;
    }   
}