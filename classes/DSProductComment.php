<?php
/**
* 2007-2015 PrestaShop
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
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class DSProductComment extends ObjectModel
{
    public $id;
    public $title;
    public $content;
    public $photo;
    public $stars;
    public $status;
    public $id_product;
    public $created_at;
    public $customer_id;
    public $id_lang;
    public $show_name;

    public static $definition = array(
        'table' => 'dsproductcomments',
        'primary' => 'id',
        'fields' => array(
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'content' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'photo' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'stars' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'status' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'created_at' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'customer_id' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_lang' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'show_name' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool')
        ) 
    );
}