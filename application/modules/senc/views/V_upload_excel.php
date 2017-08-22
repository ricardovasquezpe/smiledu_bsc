<html>
<head>
    <title>Importar Excel prueba XD</title>
</head>
<body>
        <h1>Import Excel</h1>

        <?php
        
        ?>

        <!-- form action="c_upload_excel/subirExcelEncuesta" method="post" enctype="multipart/form-data" -->
            <input type="file" name="excelFile" id="excelFile"/>
            <input type="button" id="btnImport" name="btnImport" value="Importar Data de un Excel" onclick="subirExcel();"/>
        <!--/form-->
    <script src="<?php echo base_url()?>public/js/libs/jquery/jquery-1.12.1.js"></script>
    <script type="text/javascript">
        function subirExcel(){
             
    	    var inputFileExcel = document.getElementById("excelFile");
    	    var file = inputFileExcel.files[0];
    	    
    	    var formData = new FormData();
    	    formData.append('itFileXLS', file);
    	    $.ajax({
    	        data: formData,
    	        url: "c_upload_excel/subirExcelEncuesta",
    	        cache: false,
    	        contentType: false,
    	        processData: false,
    	        type: 'POST'
    	  	})
    	  	.done(function(data) {
    	  		data = JSON.parse(data);
    			if(data.error_excel == 1) { 
        			
    				//$("#expexcel")[0].submit();
    				//mostrarNotificacion('error',CONFIG.get('CABE_ERR'),CONFIG.get('MSJ_ERR')+' - '+data.msj);
    			}else{
    				if(data.error == 0) { 
        				mostrarNotificacion('success', 'Subió las encuestas con éxito');
        				abrirCerrarModal('btnImport');
    					/*$("#contAumTab").html(null);
    					$("#contAumTab").html(data.tablaAlumno);
    					$('#tb_alumn').bootstrapTable({});
    					generarBtnDownload();
    					initAllEditableAlumno();
    					mostrarNotificacion('success', data.cabecera, data.msj);*/
    				}else{
    					//mostrarNotificacion('error',CONFIG.get('CABE_ERR'),CONFIG.get('MSJ_ERR')+' - '+data.msj);
    				}
    			}
    			//$('#rutaExcel').val(null);
    	  	})
    	  	.fail(function(jqXHR, textStatus, errorThrown) {
    	  		//mostrarNotificacion('error',CONFIG.get('CABE_ERR'),CONFIG.get('MSJ_ERR'));
    	  	})
    	  	.always(function() {
    	  		/*$('#divSubir').html($('#divSubir').html());//resetea el form
    	  		initExcel();*/
    	  	});
    	}
    </script>
</body>
</html>