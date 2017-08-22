var arrayChecked = [];

function assignItem(id){
	var inputs = $('#tbGrados').find('input:checkbox');
	var tableData = $('#tbGrados').bootstrapTable('getData');
	var countCheck = 0;
	$.each(tableData, function(val,i){
		var input = $(this[0]).find('input').attr('id');
		var checked = $('#'+input).is(':checked');
		if(checked == true){
            countCheck++;
		}
	});
	if(countCheck > 0){
		$('.mdl-assign').fadeIn();
		$('#itemsText').html(countCheck+' items seleccionados.');
	} else{
		$('.mdl-assign').fadeOut();
	}
}