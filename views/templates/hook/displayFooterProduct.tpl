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
<div class="modal fade" id="writeCommentModal" tabindex="-1" role="dialog" aria-labelledby="writeCommentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="writeCommentModalLabel">{l s='Write review' mod='dsproductcomments'}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {if $isCreatedComment == false && $canWrite == true} 
            <form class='form card card-body card-block' method="POST" id='productCommentForm'>
                <input type='text' name='ratingPot' class='d-none'>
                <input type='hidden' name='ratingComment' value='{$product.id}'>
                <input type='hidden' name='ratingLang' value='{$id_lang}'>
                <div class='form-group'>
                    <label>{l s='Rating title' mod='dsproductcomments'}</label>
                    <input type='text' name='ratingTitle' class='form-control' required>
                </div>
                <div class='form-group'>
                    {if $logged == true}
                        <label>{l s='Show your real name if you logged in' mod='dsproductcomments'}</label>
                        <select class='form-control' name='ratingShowName'>
                            <option value='0'>{l s='Yes' mod='dsproductcomments'}</option>
                            <option value='1'>{l s='No' mod='dsproductcomments'}</option>
                        </select>
                    {/if}
                </div>
                <div class='form-group'>
                    <label>{l s='Add ratings' mod='dsproductcomments'}</label>
                    <div class="rating d-flex justify-content-center p-1">
                        <fieldset>
                            <span class="star-cb-group">
                                <input type="radio" id="rating-5" name="ratingStar" value="5" /><label for="rating-5">5</label>
                                <input type="radio" id="rating-4" name="ratingStar" value="4" checked="checked" /><label for="rating-4">4</label>
                                <input type="radio" id="rating-3" name="ratingStar" value="3" /><label for="rating-3">3</label>
                                <input type="radio" id="rating-2" name="ratingStar" value="2" /><label for="rating-2">2</label>
                                <input type="radio" id="rating-1" name="ratingStar" value="1" /><label for="rating-1">1</label>
                                <input type="radio" id="rating-0" name="ratingStar" value="0" class="star-cb-clear" /><label for="rating-0">0</label>
                            </span>
                        </fieldset>
                    </div>
                <div class='form-group'>
                    <label for='ratingUploadImage' class='btn btn-primary btn-rating w-100'>{l s='Choose image for rating' mod='dsproductcomments'}</label>
                    <input type='file' id='ratingUploadImage' accept="image/*" class='d-none' name='ratingImage'>
                    <div class='comment-image --preview d-none'>
                        <img src='' class='img-responvie'>
                    </div>
                </div>
                <div class='form-group'>
                    <label>{l s='Raiting content' mod='dsproductcomments'}</label>
                    <textarea class='form-control' name='ratingContent' required></textarea>
                </div>
                
            </form>
        {/if}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type='submit' class='btn btn-success' form='productCommentForm'>{l s='Add rating' mod='dsproductcomments'}</button>
      </div>
    </div>
  </div>
</div>
</div>
{if !empty($comments)}
    <div class='container-fluid'>
        <div class='card card-body p-1 mt-1'>
            {foreach $comments as $comment}
                <div class="row mt-1">
                    {if $comment.photo != null}
                        <div itemprop="review" itemscope itemtype="https://schema.org/Review">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-4" >
                                <img class='img-responsive' src="{$urls.base_url}modules/dsproductcomments/uploads/{$comment.photo}">
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-8">
                                <span class='comment--author'>
                                {l s='Author:' mod='dsproductcomments'} 
                                <span itemprop="author">
                                    {if $comment.show_name == 1}
                                        {l s='Guest' mod='dsproductcomments'}
                                    {else}
                                        {$comment.firstname} {$comment.lastname}
                                    {/if} 
                                </span>
                                {l s='write review on:' mod='dsproductcomments'}
                                <meta itemprop="datePublished" content="{$comment.created_at|date_format:"Y-m-d"}">
                                <small>{$comment.created_at|date_format:"m-d-Y"}</small>
                            </span>
                                <meta itemprop="worstRating" content="1">
                                <meta itemprop='bestRating' content='5'>
                                <meta itemprop='ratingValue' content='{$comment.stars}'>
                                <span class='stars'>
                                    {for $foo=1 to $comment.stars}
                                        <i class='fas fa-star'></i>
                                    {/for}
                                </span>
                                <h4 class='h4' itemprop="name">{$comment.title}</h4>
                                <p itemprop="reviewBody">{$comment.content}</p>
                            </div>
                        </div>
                    {else}
                        <div class="col-lg-12 col-md-812 col-sm-12 col-12" itemprop="review" itemscope itemtype="https://schema.org/Review">
                            <span class='comment--author'>
                                {l s='Author:' mod='dsproductcomments'} 
                                <span itemprop="author">
                                    {if $comment.show_name == 1}
                                        {l s='Guest' mod='dsproductcomments'}
                                    {else}
                                        {$comment.firstname} {$comment.lastname}
                                    {/if} 
                                </span>
                                {l s='write review on:' mod='dsproductcomments'}
                                <meta itemprop="datePublished" content="{$comment.created_at|date_format:"Y-m-d"}">
                                <small>{$comment.created_at|date_format:"m-d-Y"}</small>
                            </span>
                            <span class='stars' itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                <meta itemprop="worstRating" content="1">
                                <meta itemprop='bestRating' content='5'>
                                <meta itemprop='ratingValue' content='{$comment.stars}'>
                                {for $foo=1 to $comment.stars max=5}
                                    <span class='rating--star'>★</span>
                                {/for}
                            </span>
                            <h4 class='h4' itemprop="name">{$comment.title}</h4>
                            <p itemprop="reviewBody">{$comment.content}</p>
                        </div>
                    {/if}
                </div> 
            {/foreach}
        </div>
    </div>
{/if}
<script>
    var url= '{url entity='module' name='dsproductcomments' controller='newcomment' params = [action => 'NewrabatAction']}'; 
</script>
