$(document).ready(function(){ 
	$('.pos-sizechart__title p').on('click', function(){
		var html = '<div class="modal fade" id="modal-sizechart">';
			html += '<div class="modal-dialog"><div class="modal-content">';
			html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons close">close</i></button>';
			html += '<div class="modal-body">';
				html += $('.pos-sizechart__content').html();
			html += '</div></div></div>';
			html += '</div>';
		$("body").append(html);
		$('#modal-sizechart').modal('show');
		$('#modal-sizechart').on('hidden.bs.modal', function () {
			$('#modal-sizechart').remove();
		});
	})
});
