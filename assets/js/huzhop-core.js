/*-----------------------------
* Variants Core
* Author: Be Duc Tai
* Email: os.solutionvn@gmail.com
-----------------------------*/

jQuery( document ).ready(function($) {
	
	var hz_variants = async function(){
		if(!$('body').hasClass('single-product'))
			return;
		var hzAjazUrl = hz_vars.ajaxUrl;
		var product_id = $('input[name="product_id"]').val();
		
		if(product_id === undefined)
			return;
		
		var image_attributes = ['color'];
		
		if(typeof hz_vars.options === 'object' && hz_vars.options.type_ids !== undefined){
			var type_ids = hz_vars.options.type_ids.split(',');
			
			if(type_ids.length > 0){
				type_ids.forEach(function(item){
					if(image_attributes.indexOf(item) === -1){
						image_attributes.push(item);
					}
				});
			}
		}
		
		const wc_variants = await $.ajax({
			url: hzAjazUrl,
			method: 'POST',
			dataType: 'json',
			data: {
				_token: hz_vars._token,
				action: 'hz_get_wc_variants',
				pid: product_id
			}
		});
		
		var hz_form = $('form.variations_form');
		var variations = hz_form.find('table.variations > tbody > tr');
		
		$.each(variations, function(index, elem){
			var label_tags = '<div class="hz-tags">';
			$.each($(elem).find('td.value select > option'), function(idx, opt){
				var text = $(opt).text();
				var value = $(opt).val();

				if(value !== '' && image_attributes.indexOf($(opt).parent().attr('id')) > -1){
					var i_v = $.grep(wc_variants, function(item){
						return item.attributes.attribute_color === value;
					});
					
					if(i_v.length > 0 && typeof(i_v[0].image) === 'object'){
						label_tags += `<span class="skuVal sku-img" value="${value}">
							<img src="${i_v[0].image.thumb_src}" alt="${text}" />
							<span class="tooltip">${text}</span>
						</span>`;
					}else{
						label_tags += `<span class="skuVal" value="${value}">${text}</span>`;
					}
				}else if(value !== ''){
					label_tags += `<span class="skuVal" value="${value}">${text}</span>`;
				}
			});
			label_tags += '</div>';
			
			$(elem).find('td.value select').after(label_tags);
		});
		
		
		$(document).on('click', '.hz-tags span.skuVal', function(){
			var attr_val = $(this).attr('value');
	
			$(this).parent().find('span.skuVal').removeClass('selected');
			$(this).addClass('selected');
			$(this).parent().prev().val(attr_val).trigger('change');
		});
		
		$('a.reset_variations').click(function(){
			$('.hz-tags > span.skuVal').removeClass('selected');
		})
	}
	
	hz_variants();
});