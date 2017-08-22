function init(){
	var page_number = $.cookie('page_number');
	if( page_number == null )
	{
		page_number = 1;
	}
	$('#tb_indicadores').bootstrapTable({ pageNumber : parseInt(page_number) });
	/*$( "#main_button" ).hover(function() {
		$('#blackModal').modal({
   	        show: true
   	    });	
	});*/
	initSearchTable();
	if($( window ).width() <= 930){
		$('.bandgeActual').css("display","block");
	}else{
		 $('.bandgeActual').css("display","none");
	}
	 
    $( window ).resize(function() {
    	if($( window ).width() <= 930){
    		$('.bandgeActual').css("display","block");
    	}else{
    		$('.bandgeActual').css("display","none");
    	}
	});
	
	generarBotonMenu();
}

function goToIndicadorDetalle(data){
	$.ajax({
		data : { id_indicador  : data},  
		url  : 'c_indicador/goToIndicadorDetalle', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.href = data;
	});
}

function logOutInd() {
	$.ajax({
		url  : 'c_indicador/logOutInd', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.reload();
	});
}

function setDivHeight() {
    var div = $('.container-gauge');
    div.height(div.width() * 0.9);
    div = $('.container-rpm');
    div.height(div.width() * 0.9);
}

function generarBotonMenu(){
	var div = $('#contTabIndicadores .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<div class="btn-group btn-group-sm pull-right">'+
			        '<button type="button" class="btn btn-default-light" data-toggle="dropdown">'+
			             '<span class="md md-more-vert" style="font-size:18px;margin-right:-17px"></span>'+
			        '</button>'+
			        '<ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu" style="background-color:#fafafa">'+
			            '<li><a href="#"><i class="md md-print" style="margin-right:10px"></i>Imprimir</a></li>'+
			            '<li><a href="#"><i class="md md-file-download" style="margin-right:10px"></i>Descargar</a></li>'+
			        '</ul>'+
			    '</div>');
}

$(window).on('load resize', function(){
    setDivHeight();        
});

$(function () {
    $('#tb_indicadores').on('all.bs.table', function (e, name, args) {})
    .on('page-change.bs.table', function (e, size, number) {
    	$.cookie('page_number', size);
    });
});
