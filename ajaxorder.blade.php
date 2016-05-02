<script>
var VariantsSku = <?= json_encode($VariantsSku) ?>;

$('.CollectionIndividualProduct').click(function(){
	$('.CollectionOrderForm').hide();
	$(".CollectionIndividualProductFull").css("height","360px");
	$(this).siblings().slideDown();
	$(this).parent().css("height","990px");

	var idScroll = $(this).parent().find('.CollectionOrderForm').attr('id');

	$('html,body').animate({
         scrollTop: $('#'+idScroll).offset().top
    }, 1000);

	
});

$(".CollectionOrderFormSubmit").click(function(event) {

  	event.preventDefault();
  	var SkusAdded = [];
  	
  	VariantsSku.forEach(function(entry) {
	    if($("#"+entry).val()>0){
	    	SkusAdded[entry] = $("#"+entry).val(); 
		}
	});

	var dataString = $.extend({}, SkusAdded);

	$('html,body').animate({
         scrollTop: $('#hometopgrey').offset().top
    }, 1000);
  	 	
  	$.ajax({
	    type: "post",
	    dataType: "json",
	    data : {"SkusAdded": dataString},
	    url:"{{ URL::action('AjaxController@AddSkuToOrder') }}",
	    success: function(data)
	    {   
	 		$("#SkuAdded").html(data[0]).addClass('alert alert-success').delay(3000).slideUp();
	 		$(".CollectionOrderForm").hide();
	 		$(".CollectionIndividualProductFull").css("height","360px");
	 		$(".CollectionAllProductFull").css("height","360px");
	 		$("#homeordertotal").html(data[1]).wrap('<a href="{{ URL::action('PagesController@cart') }}" />').css("color","white");
	    }
	});
  				
});

$('.CollectionOrderFormX').click(function(){
	$('.CollectionOrderForm').hide();
	$(this).parent().parent().css("height","200px");

	$('html,body').animate({
         scrollTop: $('#hometopgrey').offset().top
    }, 1000);
});

$('.CollectionCheckAvailability').click(function(){

	$(this).find(".CollectionBulkCheckAvailability").hide();
	var sku = $(this).siblings(".CollectionOrderFormInputs").find(".CollectionVariant");
	var dataString = [];
	for(i=0;i<sku.length;i++){
		dataString[i] = sku[i]['id'];
	}
	
	$.ajax({
	    type: "post",
	    dataType: "json",
	    data : {"Skus": dataString},
	    url:"{{ URL::action('AjaxController@ajaxgetinventory') }}",
	    success: function(data)
	    {	
	    	for(i=0;i<data.length;i++){
	    		$.each(data[i],function(key,value){
					$('#CollectionNumAvailable'+dataString[i]).html(value + " in stock");
					$('#CollectionBulkNumAvailable'+dataString[i]).html('<div class="CollectionBulkNum">' + value + '</div>' + "in stock");
				});
	    	}

	    }
	});
});

$('.CollectionVariant').click(function(){
	var changedValue = $(this);
	
	if($(this).val()==0){
		changedValue.val('');
	}
});

</script>