/*-----------------------------
* Author: Be Duc Tai
* Email: os.solutionvn@gmail.com
-----------------------------*/

if(typeof getUrlParameter !== 'function'){
	var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = window.location.search.substring(1),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;
	
	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');
	
	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
	        }
	    }
	};
}

if(typeof validURL !== 'function'){
	function validURL(str) {
		var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
			'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
			'((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
			'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
			'(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
			'(\\#[-a-z\\d_]*)?$','i'); // fragment locator
		return !!pattern.test(str);
	}
}


jQuery( document ).ready(function($) {
	//console.log('huzhop admin');
	$('button.mark-as-fulfilled').click(function(ev){
		ev.preventDefault();
		var close_svg = '../wp-content/plugins/huzhop/assets/img/close.svg';
		
		var order_line_items_heading = $('table.woocommerce_order_items > thead').html();
		var order_line_items = $('#order_line_items').html();
		
		if($('#hz-overlay').length === 0){
			$('body').append(`<div id="hz-overlay"></div>
				<div id="hz-dialog">
					<div class="heading">
						<h3>Mark as fulfilled</h3>
						<a class="close" href="javascript:void(0)"><img src="${close_svg}" alt="Close" /></a>
					</div>
					<div id="hz-dialog-content">
						<div class="woocommerce_order_items_wrapper wc-order-items-editable">
							<table class="woocommerce_order_items">
								${order_line_items_heading}
								${order_line_items}
							</table>
						</div>
						
						<div class="tracking-info">
							<strong>TRACKING INFORMATION</strong>
							<div class="hz-row">
								<label>Tracking url <span class="opt">(OPTIONAL)</span></label>
								<input type="text" class="hz-input" name="tracking_url" placeholder="Enter tracking url here"  />
								<p class="error tracking-url-error">Please enter validate tracking url!</p>
							</div>
							
							<div class="hz-row last">
								<button id="hz-fulfill-items" class="button button-primary">Fulfill items</div>
							</div>
						</div>
					</div>
				</div>`);
		}else{
			$('#hz-overlay, #hz-dialog').fadeIn('fast');
		}
	});
	
	$(document).on('click', '#hz-overlay, #hz-dialog a.close', function(){
		$('#hz-overlay, #hz-dialog').fadeOut('fast');
	});
	
	var send_loading = false;
	
	$(document).on('click', 'button#hz-fulfill-items', function(){
		var wc_order_id = getUrlParameter('post');
		var tracking_url = $('input[name="tracking_url"]').val();
		
		if(!wc_order_id){
			return;
		}
		
		if(tracking_url !== '' && !validURL(tracking_url)){
			$('.tracking-url-error').fadeIn('fast');
			return;
		}else{
			$('.tracking-url-error').fadeOut('fast');
		}
		
		if(send_loading === true)
			return;
		
		send_loading = true;
			
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				action: 'hz_order_fulfillment',
				order_id: wc_order_id,
				tracking_url: tracking_url
			},
			dataType: 'json',
			
			success: function(res){
				send_loading = false;
				
				if(res.status === 'success'){
					setTimeout(function(){
						location.reload();
					}, 1000);
				}
			}
		});
	});
	
	
	$(document).on('click', 'a.edit-tracking-url', function(){
		$('#hz_tracking_edit').slideToggle();
	});
	
	$(document).on('click', '#hz_tracking_edit button', function(ev){
		ev.preventDefault();
		
		var wc_order_id = getUrlParameter('post');
		
		var tracking_url = $('#hz_tracking_url').val();
		
		if(!wc_order_id){
			return;
		}
		
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				action: 'hz_save_change_tracking_url',
				order_id: wc_order_id,
				tracking_url: tracking_url
			},
			dataType: 'json',
			
			success: function(res){
				var _tr_url = $('._tracking_url');
				_tr_url.html(tracking_url).attr('href', tracking_url);
				$('#hz_tracking_edit').slideToggle();
			}
		})
	});
});