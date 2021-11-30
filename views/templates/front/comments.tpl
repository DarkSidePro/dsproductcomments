{*
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
*}
{extends file='page.tpl'}
{block name='page_content_container'}
<div class="container">
    {if empty($comments)}
        <div class='row'>
            <div class="alert alert-primary" role="alert">
                <p>{l s='You don\'t have any comments yet.' mod='dsproductcomments'}</p>
            </div>
        </div>
    {/if}
    {foreach $comments as $comment}
        <div class="row">
            {if $comment.photo != null}
                <div class="col-lg-4 col-md-4 col-sm-12 col-4">
                    <img class='img-responsive' src="{$urls.base_url}/uploads/product_comments/{$comment.photo}.png">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-8">
                    <span class='stars'>
                        {for $foo=1 to $comment.stars}
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-star fa-w-18 fa-2x"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path></svg>
                        {/for}
                    </span>
                    <h4 class='h4'>{$comment.title}</h4>
                    <p>{$comment.content}</p>
                </div>
            {else}
                <div class="col-lg-12 col-md-812 col-sm-12 col-12">
                    <span class='stars'>
                        {for $foo=1 to $comment.stars}
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-star fa-w-18 fa-2x"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path></svg>
                        {/for}
                    </span>
                    <h4 class='h4'>{$comment.title}</h4>
                    <p>{$comment.content}</p>
                </div>
            {/if}
        </div> 
    {/foreach}
</div>
{/block}