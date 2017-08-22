function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
}

/*
document.getElementById('myframe').onload = function() {

    var form = document.getElementById('myframe');

    // get reference to iframe window
	var ifrm = document.getElementById('myframe');
	console.log(ifrm);
	var doc = ifrm.contentDocument? ifrm.contentDocument: ifrm.contentWindow.document;
	console.log(doc);
	document.getElementById("apepat3").searchTerm.val("Hello");
	
    $('#botonAE').onclick = function() {
    	
    }
}*/