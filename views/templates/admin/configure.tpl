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
<div class="container-fluid">
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading">{l s='New comments' mod='dsproductcomments'}</div>
			<div class="panel-body">
				<table class='table table-responsive table-bordered'>
					<thead>
						<tr>
							<th>{l s='#'}</th>
							<td>{l s='Title' mod='dsproductcomments'}</td>
							<td>{l s='Stars' mod='dsproductcomments'}</td>
							<td>{l s='Content' mod='dsproductcomments'}</td>
							<td>{l s='Author' mod='dsproductcomments'}</td>
							<td>{l s='Product name' mod='dsproductcomments'}</td>
							<td>{l s='Image' mod='dsproductcomments'}</td>
							<td>{l s='Actions' mod='dsproductcomments'}</td>
						</tr>
					</thead>
					<tbody>
						{foreach $newComments as $comment}
							
							<tr>
								<th>{$comment.id}</th>
								<td>{$comment.title}</td>
								<td>{$comment.stars}</td>
								<td>
									<p>{$comment.content}</p>
								</td>
								<td>{$comment.firstname} {$comment.lastname}</td>
								<td>{$comment.product_name}</td>
								<td>
									{if $comment.photo != null}
										<button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="top" data-img="{$base_url}/uploads/product_comments/{$comment.photo}.png" rel='popover'>
											{l s='Show image' mod='dsproductcomments'}
										</button>
									{/if}
								</td>
								<td>
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											{l s='Actions' mod='dsproductcomments'}
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="{$link->getAdminLink('AdminModules')|escape:'htmlall':'UTF-8'}&configure={$namemodules}&showComment={$comment.id}">{l s='Show' mod='dsproductcomments'}</a>
											<a class="dropdown-item" href="{$link->getAdminLink('AdminModules')|escape:'htmlall':'UTF-8'}&configure={$namemodules}&hideComment={$comment.id}">{l s='Hide'}</a>
										</div>
									</div>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading">{l s='Aproved comments' mod='dsproductcomments'}</div>
			<div class="panel-body">
				<table class='table table-responsive table-bordered'>
					<thead>
						<th>{l s='#'}</th>
						<td>{l s='Title' mod='dsproductcomments'}</td>
						<td>{l s='Stars' mod='dsproductcomments'}</td>
						<td>{l s='Content' mod='dsproductcomments'}</td>
						<td>{l s='Author' mod='dsproductcomments'}</td>
						<td>{l s='Product name' mod='dsproductcomments'}</td>
						<td>{l s='Image' mod='dsproductcomments'}</td>
						<td>{l s='Actions' mod='dsproductcomments'}</td>
					</thead>
					<tbody>
						{foreach $reviewdComments as $comment}
							
							<tr>
								<th>{$comment.id}</th>
								<td>{$comment.title}</td>
								<td>{$comment.stars}</td>
								<td>
									<p>{$comment.content}</p>
								</td>
								<td>{$comment.firstname} {$comment.lastname}</td>
								<td>{$comment.product_name}</td>
								<td>
									{if $comment.photo != null}
										<button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="top" data-img="{$base_url}/uploads/product_comments/{$comment.photo}.png" rel='popover'>
											{l s='Show image' mod='dsproductcomments'}
										</button>
									{/if}
								</td>
								<td>
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											{l s='Actions' mod='dsproductcomments'}
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="{$link->getAdminLink('AdminModules')|escape:'htmlall':'UTF-8'}&configure={$namemodules}&showComment={$comment.id}">{l s='Show' mod='dsproductcomments'}</a>
											<a class="dropdown-item" href="{$link->getAdminLink('AdminModules')|escape:'htmlall':'UTF-8'}&configure={$namemodules}&hideComment={$comment.id}">{l s='Hide' mod='dsproductcomments'}</a>
										</div>
									</div>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


{literal}
	<script>	
		$('a[rel=popover]').popover({
			html: true,
			trigger: 'hover',
			content: function () {
				return '<img src="'+$(this).data('img') + '" />';
			}
		});
	</script>
{/literal}