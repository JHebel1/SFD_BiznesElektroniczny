var wishlistProductsIds = [];
$(document).ready(function(){	
	posCopyLink();
});

function posCopyLink() {
    var copyText = $('#posCopyLink'),
	    copied_text = copyText.data('text-copied'),
	    copy_text = copyText.data('text-copy');
	copyText.click(function() {
	    $('.js-to-clipboard').select();
	    document.execCommand('copy');
        copyText.text(copied_text);
        setTimeout(function(){
        	copyText.text(copy_text);
        }, 5000);
	})
}

function WishlistCart(id, action, id_product, id_product_attribute, quantity, id_wishlist, product_name , product_image)
{
	$.ajax({
		type: 'GET',
		url: baseDir + 'modules/poswishlist/cart.php?rand=' + new Date().getTime(),
		headers: { "cache-control": "no-cache" },
		async: true,
		cache: false,
		data: 'action=' + action + '&id_product=' + id_product + '&quantity=' + quantity + '&token=' + static_token + '&id_product_attribute=' + id_product_attribute + '&id_wishlist=' + id_wishlist,
		success: function(data)
		{	
			if (action == 'add')
			{	
				if (isLogged == true) {

                    $('.wishlist-top-count').html(data);
                    $('#qmwishlist-count').html(data);

					var html = '';
					html += '<div class="modal fade" id="wishlistModal">';
					html += '<div class="modal-dialog"><div class="modal-content">';
						html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons close">close</i></button>';
						html += '<div class="modal-body">';
							html += '<img src="' + product_image + '" alt="' + product_name + '" />';
							html += '<h4>' + product_name + '</h4>';
							html += added_to_wishlist;
							html += '<a class="btn-secondary" href="' + wishlist_url + '" class="view_wishlist">' + wishlist_text + '</a>';
						html += '</div>';
					html += '</div></div></div>';
					$("body").append(html);

					if($('.quickview').length > 0){
						$('.quickview').modal('hide')
						$('.quickview').on('hidden.bs.modal', function () {
				        	$('.quickview').remove();
				        	$('#wishlistModal').modal('show');
				      	});	
					}else{
						$('#wishlistModal').modal('show');
					}

					$('#wishlistModal').on('hidden.bs.modal', function () {
			        	$('#wishlistModal').remove();
			      	});
				}else{
					var html = '';
					html += '<div class="modal fade" id="wishlistModal">';
					html += '<div class="modal-dialog"><div class="modal-content">';
						html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons close">close</i></button>';
						html += '<div class="modal-body">';
						html += loggin_required;
						html += '<a class="btn-secondary" href="'+ loggin_url +'" class="login_text">'+ loggin_text +'</a>'
						html += '</div>';
					html += '</div></div></div>';
					$("body").append(html);

					if($('.quickview').length > 0){
						$('.quickview').modal('hide')
						$('.quickview').on('hidden.bs.modal', function () {
				        	$('.quickview').remove();
				        	$('#wishlistModal').modal('show');
				      	});	
					}else{
						$('#wishlistModal').modal('show');
					}
					
					$('#wishlistModal').on('hidden.bs.modal', function () {
			        	$('#wishlistModal').remove();
			      	});
				}
			}
			
			if($('#' + id).length != 0)
			{
				$('#' + id).slideUp('normal');
				document.getElementById(id).innerHTML = data;
				$('#' + id).slideDown('normal');
			}
		}
	});
}

/**
* Delete product wish list
*
* @return void
*/
function deleteProductWishlist(id_product, id_product_attribute)
{
	$.ajax({
		type: 'GET',
		async: true,
		url: baseDir + 'module/poswishlist/mywishlist?rand=' + new Date().getTime() + '&action=delete',
		headers: { "cache-control": "no-cache" },
		data: 'id_product=' + id_product + '&id_product_attribute=' + id_product_attribute,
		cache: false,
		dataType: 'json',
		success: function(data)
		{	
			$('#wlp_' + data.id_product + '_' + data.id_product_attribute).fadeOut('fast');
			$('.wishtlist_top .cart-wishlist-number').html(data.current_number);
		}
	});
}
