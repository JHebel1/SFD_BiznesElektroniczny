var posCompare = {
    addCompare: function(obj, id, product_name, product_image){
        $.ajax({
            type: 'POST',
            url: baseDir + 'module/poscompare/actions',
            dataType: 'json',
            data: {
                action : 'add',
                id: id,
                ajax : true
            },
            success: function(data)
            {  
                poscompare.nbProducts++;
                $('#poscompare-nb, #qmcompare-count').text(poscompare.nbProducts);
                var html = '';
                    html += '<div class="modal fade" id="compareModal">';
                    html += '<div class="modal-dialog"><div class="modal-content">';
                        html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons close">close</i></button>';
                        html += '<div class="modal-body">';
                            html += '<img src="' + product_image + '" alt="' + product_name + '" />';
                            html += '<h4>' + product_name + '</h4>';
                            html += poscompare.success_text; 
                            html += '<a class="btn-secondary" href="' + poscompare.compare_url + '">' + poscompare.compare_text + '</a>';
                        html += '</div>';
                    html += '</div></div></div>';
                $("body").append(html);
                $('.quickview').modal('hide');
                $('#compareModal').modal('show');
                $('#compareModal').on('hidden.bs.modal', function () {
                    $('#compareModal').remove();
                });
            },
            error: function (jqXHR, status, err) {
                 obj.addClass('cmp_added');
            }
        })
    },
    removeCompare: function(id){
        posCompare.blockUI('#poscompare-table');
        $.ajax({
            type: 'POST',
            url: baseDir + 'module/poscompare/actions',
            dataType: 'json',
            data: {
                action : 'remove',
                id: id,
                ajax : true
            },
            success: function(data)

            {
                $('.js-poscompare-product-' + id).remove();
                poscompare.nbProducts--;
                $('#poscompare-nb, #qmcompare-count').text(poscompare.nbProducts);

                if (poscompare.nbProducts == 0) {
                    $('#poscompare-table').remove();
                    $('#poscompare-warning').removeClass('hidden-xs-up');
                }
                posCompare.unblockUI('#poscompare-table');
            }
        })
    },
    removeAllCompare: function(){
        posCompare.blockUI('#content');
        $.ajax({
            type: 'POST',
            url: baseDir + 'module/poscompare/actions',
            dataType: 'json',
            data: {
                action : 'removeAll',
                ajax : true
            },
            success: function(data)

            {
                $('#poscompare-nb, #qmcompare-count').text(0);
                $('#poscompare-table').remove();
                $('#poscompare-warning').removeClass('hidden-xs-up');
                posCompare.unblockUI('#content');
            }
        })
    },

    checkCompare : function (){
        var target = $('.compare .poscompare-add');
        var compareList = poscompare.IdProducts;
        target.each(function(){
            var $id = $(this).data('id_product');
            var flag = false;
            $.each( compareList, function( key, value ) {
              if($id == value) {
                flag = true;
              };
            });
            if(flag) {
                $(this).addClass('cmp_added');
            }
        })
    },
    blockUI: function(selector){
        $(selector).addClass('ar-blocked');
        $(selector).find('.ar-loading').remove();
        $(selector).append('<div class="ar-loading"><div class="ar-loading-inner"></div></div>');
    },
    unblockUI: function(selector){
        $(selector).find('.ar-loading').remove();
        $(selector).removeClass('ar-blocked');
    },
};

$(document).ready(function () { 
    $('#poscompare-nb, #qmcompare-count').text(poscompare.nbProducts);
    $('.cmp_added').css('background-color','red');
    posCompare.checkCompare();
    $('body').on('click', '.js-poscompare-remove-all', function (event) {
        posCompare.removeAllCompare();
        event.preventDefault();
    });
     $(".compare .poscompare-add").click(function(e) {
        e.preventDefault();
        $(this).addClass('cmp_added');
    }); 
});

