var socket   = null;
var flg_node = false;

var postulantesEspera      = null;
var postulantesPerdioTurno = null;
var postulantesSuTurno     = null;
 
function init(){
    initSocket();
    /*artyom.initialize({
        continuous:true,
        lang:"es-ES",
        debug:true
    });*/
}

function initSocket(){
	if(socket != null){
		socket.disconnect();
	}
	socket = io.connect(nodeServer,{ query: "param=TURNOS"});
    socket.on('notificationturnos', function (data) {
    	if(flg_node) {
    		return;
    	}
    	postulantesEspera      = (data.postulanteespera[0]==null)?null:data.postulanteespera[0].split(',');
    	postulantesPerdioTurno = (data.postulanteperdioturno[0]==null)?null:data.postulanteperdioturno[0].split(',');
    	postulantesSuTurno     = (data.postulantessuturno[0]==null)?null:data.postulantessuturno[0].split(',');
    	
    	//ESPERTA TURNO
    	if(postulantesEspera == null){
    		$('#cont_espera_turno').html(null);
    	}else{
    		$('.espera_turno .mdl-card').each(function(i,e) {
        		id = $(this).attr("id");
        		if(!postulantesEspera.contains(id)){
        			$("#"+id).remove();
        		} else {
     	    		var index = postulantesEspera.indexOf(id);
     	    		if (index > -1) {
     	    			postulantesEspera.splice(index, 1);
     	    		}
     	    	}
        	});
    	}
    	
    	//PERDIO TURNO
    	if(postulantesPerdioTurno == null){
    		$('#cont_turno_perdido').html(null);
    	}else{
    		$('.turno_perdido .mdl-card').each(function(i,e){
        		id = $(this).attr("id");
        		if(!postulantesPerdioTurno.contains(id)){
        			$("#"+id).remove();
        		} else {
     	    		var index = postulantesPerdioTurno.indexOf(id);
     	    		if (index > -1) {
     	    			postulantesPerdioTurno.splice(index, 1);
     	    		}
     	    	}
        	});
    	}
    	
    	//SU TURNO
    	if(postulantesSuTurno == null){
    		$('#cont_turno').html(null);
    	}else{
    		$('.turno .mdl-card').each(function(i,e){
        		id = $(this).attr("id");
        		if(!postulantesSuTurno.contains(id)){
        			$("#"+id).remove();
        		} else {
     	    		var index = postulantesSuTurno.indexOf(id);
     	    		if (index > -1) {
     	    			postulantesSuTurno.splice(index, 1);
     	    		}
     	    	}
        	});
    	}
    	
    	tamanoSuTurno     = (postulantesSuTurno==null)?0:postulantesSuTurno.length;
    	tamanoPerdioTurno = (postulantesPerdioTurno==null)?0:postulantesPerdioTurno.length;
    	tamanoEspera      = (postulantesEspera==null)?0:postulantesEspera.length;
    	
    	if((tamanoSuTurno + tamanoPerdioTurno + tamanoEspera) > 0){
    		getPostulantesRestates();
    	}
    	
    });
}

function getPostulantesRestates(){
	flg_node = true;
	$.ajax({
		data : { suturno     : postulantesSuTurno,
			     perdioturno : postulantesPerdioTurno,
			     espera      : postulantesEspera},
		url  : 'c_espera/postulantesRestantes',
		type : 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.postulantesSuTurno){
			$("#cont_turno").append(data.postulantesSuTurno);
			$('#sonido').trigger("play");
			/*artyom.say("Ya hay alguien el entrevista");*/
		}
		if(data.postulantesPerdioTurno){
			$("#cont_turno_perdido").append(data.postulantesPerdioTurno);
		}
		if(data.postulantesEspera){
			$("#cont_espera_turno").append(data.postulantesEspera);
		}
		flg_node = false;
	});
}

Array.prototype.contains = function ( needle ) {
   for (i in this) {
       if (this[i] === needle) {
    	   return true;
       }
   }
   return false;
}