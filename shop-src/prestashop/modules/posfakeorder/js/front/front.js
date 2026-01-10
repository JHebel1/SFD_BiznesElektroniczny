$(document).ready(function(){
	setTimeout(function () {
		posFakeOrder.get_product();
	}, pos_fakeorder.time_first);
	$('.close-button').on('click', function(){
		posFakeOrder.disablePopup();
	})
});

var posFakeOrder = {
	intel : 0,
	get_product : function(){
		if(this.intel) return; 

		var content = $('.pos-recent-orders-inner'),
			products = pos_fakeorder.products,
			productNum = products.length;
		if(products.length === 0) return;
		var randomNum = Math.floor((Math.random() * productNum));
		var timeAgo = Math.floor((Math.random() * pos_fakeorder.frame_time) + 1);
		var displayTime;
		if(timeAgo == 1){
			displayTime = timeAgo + '&nbsp;' + pos_fakeorder.minute_text;
		}else if(timeAgo >= 60){
			if(Math.floor(timeAgo/60) == 1){
				displayTime = Math.floor(timeAgo/60) + '&nbsp;' + pos_fakeorder.hour_text;
			}else{
				displayTime = Math.floor(timeAgo/60) + '&nbsp;' + pos_fakeorder.hours_text;
			}
		}else{
			displayTime = timeAgo + '&nbsp;' + pos_fakeorder.minutes_text;
		}
		displayTime = displayTime + '&nbsp;' + pos_fakeorder.ago_text;
		
		content.html('');
		var html = '';
		html += '<p>'+ pos_fakeorder.content_text +'</p>';
		html += '<div class="img-order">';
			html += '<a href="'+ products[randomNum].url +'">';
			html += '<img src="'+ products[randomNum].image +'" />';
			html += '</a>';
		html += '</div>';
		html += '<div class="content-order">';	
			html += '<a href="'+ products[randomNum].url +'">'+ products[randomNum].name +'</a>';
			html += '<p>'+ displayTime +'</p>';
		html += '</div>';
		content.append(html);
		posFakeOrder.openPopup();
	},
	openPopup : function(){
		$('.pos-recent-orders').addClass('fadeInUp').removeClass('fadeOutDown');
		setTimeout(function () {
			posFakeOrder.closePopup();
		}, pos_fakeorder.time_display);
	},
	closePopup : function(){
		$('.pos-recent-orders').removeClass('fadeInUp').addClass('fadeOutDown');
		setTimeout(function(){ 
			posFakeOrder.get_product();
		}, pos_fakeorder.time_between)
	},
	disablePopup : function(){
		$('.pos-recent-orders').removeClass('fadeInUp').addClass('fadeOutDown');
		this.intel = 1;
	},
};