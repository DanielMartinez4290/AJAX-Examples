<script>
  var allocatedcount = <?= $allocatedcount ?>;
  var selectionassoc = <?= json_encode($selectionassoc) ?>;
  var dates = <?= json_encode($dates) ?>;
  var lineship = <?= json_encode($lineship) ?>;
  var line_items = <?= json_encode($line_itemsA) ?>;
  var productsavail = <?= json_encode($productsavail) ?>;
  var lineshiparray = <?= json_encode($lineshiparray) ?>;
  var shipments = <?= json_encode($shipments) ?>;
  var authUserRole = <?= json_encode($authUser->role) ?>;
  var formchanged ="false";


  $(document).ready(function(){

	$(".Dispatched").parent().parent().find(".formchangecheck").prop("disabled",true);
	$(".Dispatched").parent().parent().parent().find(".formchangecheck").prop("disabled",true);
	//$(".Dispatched").find(".formchangecheck").prop("disabled",true);

  	$( ".formchangecheck" ).change(function() {
  		formchanged = "true";
	});

	if(authUserRole=='User'){
		$(".formchangecheck").prop("disabled",true);
	}

	$("#dispatchorder").click(function(event) {
  		event.preventDefault();
  		console.log("trippped");
  		console.log(formchanged);
  				
  			if(formchanged=='true'){
  				var r = confirm("There are unsaved changes. Proceed?");
  			}
  		
			if (r ==true) {
    			$("#dispatchorderform").submit();
			}
			if(formchanged =="false"){
				$("#dispatchorderform").submit();	
			}
	 });	

  	$("#addskubutton").click(function(event) {
  		event.preventDefault();
  			var r = 'true';
  			for(i=0;i<line_items.length;i++){
  				
  				if(line_items[i]['sku']==$("#addsku").val()){
  					alert("Error: SKU already in order");
  					var r = 'false';
  				}
  			}

			if (r != 'false') {

    			$("#addskuform").submit();
			}
	 });

  	 $("#cancelorder").click(function(event) {
  		event.preventDefault();
  		
  		
  			var r = confirm("Are You Sure?");
		
			if (r == true) {
    			$("#formcancelorder").submit();
			}
		
	 });

  	 for(i=0;i<shipments.length;i++){
  	 	
  	 		$("#cancelshipment"+i).click(function(event) {
  			event.preventDefault();
  			console.log(event.target.id);
  			
  				var r = confirm("Are You Sure?");
		
				if (r == true) {
					console.log("#form"+event.target.id);

    				$("#form"+event.target.id).submit();

				} 
			
	 	});	
  	 }
	 

 
  	 $('button#checkavailability').click(function(){

  	 	var entry = $("#addsku").val();
  	 	var SkusAdded = [];
  	 	SkusAdded[entry] = $("#addsku").val(); 

		var dataString = $.extend({}, SkusAdded);
		
  	 	
  	 	$.ajax({
	        type: "post",
	        dataType: "json",
	        data : {"Skus": dataString},
	        url:"{{ URL::action('AjaxController@ajaxgetinventory') }}",
	        success: function(data)
	        {   

	        	for(i=0;i<data.length;i++){

		    		$.each(data[i],function(key,value){
		    			if(key=='error'){
		    				$("#addquantityavailable").html('');
	        				$("#addproductname").html('');		
	        				alert("Error: SKU not in database");
		    			}
		    			else{
		    				$("#addquantityavailable").html(value);
	        				$("#addproductname").html(key);		
		    			}
		    			
					});
	    		}

	        }
	    });
  	 });


  	for(i=1;i<(allocatedcount + 1);i++){
  		
  		(function(x){
  	  		$("#quantity_ordered"+x).click(function(){
        		$("#lineitemchecked"+x).prop('checked', true);
    		});
    		$("#allocated"+x).click(function(){
        		$("#lineitemchecked"+x).prop('checked', true);
        		$("#allocated"+x).val('');
    		});
  		})(i);
  	}


  	for(j=0;j<selectionassoc.length;j++){
  		
  		(function(x){
  			var y = x + 1;

  			$('#selectall'+y).change(function(){
  				
  				for(k=0;k<selectionassoc[x].length;k++){
  					
					if($("#selectall"+y).val()=='1'){

	  					$("#lineitemchecked"+selectionassoc[x][k]).prop('checked', true);
	  					
	  					if($("#lineitemchecked"+selectionassoc[x][k])){
	  						var quantityordered = $("#quantity_ordered"+selectionassoc[x][k]).val();
	  						$("#allocated"+selectionassoc[x][k]).val(quantityordered);
	  					}

	  				}
 	  				
 	  				if($("#selectall"+y).prop('checked')==false){
	  					$("#lineitemchecked"+selectionassoc[x][k]).prop('checked', false);
	  						$("#allocated"+selectionassoc[x][k]).val(0);
	  				}
	  			}
	  		});

	  		$('#selectionbox'+y).change(function(){
	  			console.log(selectionassoc[x]);
	  			
	  			var selectlineshiparray = [];
	  			console.log(lineshiparray);
	  			var obj = {};

	  			for (a=0;a<selectionassoc[x].length;a++){	  					
	  					obj[selectionassoc[x][a]] = lineshiparray[x][a].line_item_id;
	  			}

	  			selectlineshiparray.push(obj);


	  			for(a=0;a<selectionassoc[x].length;a++){
	  				$("#lineitemchecked"+selectionassoc[x][a]).prop('checked', false);
					$("#allocated"+selectionassoc[x][a]).val(0);
	  			}

	  			for(n=0;n<selectionassoc[x].length;n++){

	  				if($("#selectionbox"+y).val()=='1'){

	  					for(l=0;l<dates.length;l++){

	  							var dateparsed = Date.parse(dates[l]);
	  							var today = new Date();

	  							if(dateparsed<today){
	  								
	  								for(m=0;m<productsavail.length;m++){
	  									if(dates[l]==productsavail[m][0].available_date){
	  										
	  										
	  										for(p=0;p<line_items.length;p++){
	  											
	  											if(productsavail[m][0].id==line_items[p].product_id){

	  												for(q=0;q<lineshiparray[x].length;q++){
	  													
	  													if(line_items[p].id==lineshiparray[x][q].line_item_id){
	  														
	  														for(r=0;r<selectlineshiparray.length;r++){

	  															if(selectlineshiparray[r][selectionassoc[x][n]]==line_items[p].id){

	  																$("#lineitemchecked"+selectionassoc[x][n]).prop('checked', true);
	  																
	  																if($("#lineitemchecked"+selectionassoc[x][n])){				
													  					var quantityordered = $("#quantity_ordered"+selectionassoc[x][n]).val();
													  					$("#allocated"+selectionassoc[x][n]).val(quantityordered);
													  				}
	  															}
	  														}

	  													}	
	  												}
	  												
	  											}
	  										}
	  									}
	  								}		

	  							}
	  							
	  					}


	  				}else if($("#selectionbox"+y).val()!='0'){	  					

	  					var datepicked = $("#selectionbox"+y).val();
	  					
	  					for(i=0;i<productsavail.length;i++){
	  						if(datepicked==productsavail[i][0].available_date){
	  					
	  							for(j=0;j<line_items.length;j++){
	  					
	  								if(productsavail[i][0].id==line_items[j].product_id){
	  					
	  									for(k=0;k<lineshiparray[x].length;k++){
	  					
	  										if(line_items[j].id==lineshiparray[x][k].line_item_id){
	  					
	  											for(l=0;l<selectlineshiparray.length;l++){

	  												if(selectlineshiparray[l][selectionassoc[x][n]]==line_items[j].id){
	  													
	  													$("#lineitemchecked"+selectionassoc[x][n]).prop('checked', true);
	  																
	  													if($("#lineitemchecked"+selectionassoc[x][n])){				
													  		var quantityordered = $("#quantity_ordered"+selectionassoc[x][n]).val();
													  			$("#allocated"+selectionassoc[x][n]).val(quantityordered);
													  	}
	  												}
	  											}
	  										}
	  									}

	  								}
	  							}

	  						}
	  					}	
	
	  				}
	  			}
	  		});

  		})(j);	
  	}   
	
   });
</script>