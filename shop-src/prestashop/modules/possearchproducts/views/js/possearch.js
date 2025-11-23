
function getSearchParams(k){
 var p={};
 location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
 return k?p[k]:p;
}

$(document).ready(function () {
	
	var web_url = window.location.href;
	var tag = getSearchParams('tag');
	if(typeof(tag) !=='undefined') { 
		$('.page-list .js-search-link').removeClass('js-search-link');
	}
	
    var $searchBox = $('#pos_query_top');
    var searchURL     = $('#searchbox').attr('data-search-controller-url');

    $.widget('prestashop.psBlockSearchAutocomplete', $.ui.autocomplete, {
        options: {
			appendTo : $('.autocomplete-suggestions'), 
		},
		_renderItem: function (ul, product) {
            if(possearch_image){
            return $("<li>")               
                .append($("<a href= "+ product.product_link +">")
                    .append($('<img src="'+ product.ajaxsearchimage +'" alt="" />'))
                    .append($("<span>").html(product.pname).addClass("product"))
                ).appendTo(ul);
            }else{
                 return $("<li>")               
                .append($("<a href= "+ product.product_link +">")
                    .append($("<span>").html(product.pname).addClass("product"))
                ).appendTo(ul);
            }
        }
    });

    $searchBox.psBlockSearchAutocomplete({
        source: function (query, response) {
            $.post(
            searchURL, 
            {
                s: query.term,
                resultsPerPage: possearch_number,
                id_lang : id_lang,
                id_category: $('select[name=poscats]').val()
            }, null, 'json')
            .then(function (resp) {
                response(resp.products);
            })
            .fail(response);
        },
        select: function (event, ui) {
            var url = ui.item.url;
            window.location.href = url;
        },
    });
});


