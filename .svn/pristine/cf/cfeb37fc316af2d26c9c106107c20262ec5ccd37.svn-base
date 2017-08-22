<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Mural | <?php echo NAME_SMILEDU;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SIST_AVA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_SIST_AV;?>" />
             
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap/css/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstraptour/css/bootstrap-tour.min.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>animate.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>logic/mural.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
    
    </head>
    
    <body>    
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>        		
    		<?php echo $menu?>        		
        		<main class='mdl-layout__content'>
            		<div class="row-fluid ">
            		  <section class="mdl-layout__tab-panel is-active" id="<?php echo MURAL_PUBLICO;?>">
        		          <?php echo $publicacionesPublicas;?>
        		      </section>
            		  <section class="mdl-layout__tab-panel" id="<?php echo MURAL_ESTRELLA;?>">
            		      <?php echo $publicacionesEstrella;?>
            		  </section>  
            		  <section class="mdl-layout__tab-panel" id="<?php echo MURAL_DOCENTE;?>">
            		      <?php echo $publicacionesDocente;?>
            		  </section>          		  
		      </main>
    	
    	</div>
    	
        <ul id="menuPubli" class="mfb-component--br mfb-slidein-spring" 
    		data-mfb-toggle="hover" style="z-index: 1">
    		<li class="mfb-component__wrap"><a href="javascript:void(0)"
    			data-toggle="modal" data-target="#modalNuevaPublicacion"
    			class="mfb-component__button--main mdl-color--indigo"
    			id="main_button"> <i
    				class="mfb-component__main-icon--resting material-icons" style="color: white !important ;">edit</i> <i
    				class="mfb-component__main-icon--active material-icons"  style="color: white !important ;">edit</i>
    		</a></li>
    	</ul>
    
    
    	<div class="modal fade backModal" id="modalNuevaPublicacion"
    		tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    		aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			<div class="mdl-card mdl-shadow--2dp p-t-20 " style="border-radius:5px;  ">
            					<div class="mdl-card__title p-r-40 p-l-60 p-t-5">
            						  <img id="foto_perfil" class="img-circle" src="<?php echo $this->session->userdata('foto_usuario');?>" alt="Profile">
            						
            					<!--	 < img id="foto_perfil" class="img-circle" src="<?php echo base_url()?>public/img/header/fotoprofile.jpg" alt="Profile">-->
            						<div class="row-fuid">
            						  <div class="col-xs-12 p-0 m-0 m-b-5">
            						      <h3 class="mdl-card__title-text mdl-typography--body-2 mdl-typography--font-bold p-l-20"><?php echo  ucwords(strtolower($this->session->userdata('nombre_completo'))); ?></h3>
            						      
            						  </div>
            						</div>            						        
            					</div>
            					<div class="mdl-card__supporting-text p-r-30 p-l-20">
            						  <div class="row-fluid p-0 m-0">
                						<div class="col-xs-12 p-b-10 p-0 m-0">
                						  <div class="mdl-textfield mdl-js-textfield ">
                							 <textarea rows=""class="mdl-textfield__input animated form-control" style="max-height: 300px; overflow-y: hidden;"  type="text"id="sample5" cols=""></textarea>  
                							 <label class="mdl-textfield__label" style="top: 45px;" for="sample5">�Tienes algo educativo que compartir?</label>
                							 <span class="mdl-textfield__error">Ingrese informaci�n para compartir</span>
            							 </div>	 
        							    </div>
        							     <div class="col-xs-12 p-0 m-0 text-left">
                							 <button class="mdl-button mdl-js-button p-r-0 p-l-0 p-b-0 " style="border-radius:50%;height:36px;width:36px;min-width:0px;" onclick="elegirImagen()"><i class="material-icons">camera_alt</i></button>
                							 <button class="mdl-button mdl-js-button p-r-0 p-l-0 p-b-0 " style="border-radius:50%;height:36px;width:36px;min-width:0px;"  type="button" data-toggle="modal" data-target="#modallink"><i class="material-icons">link</i></button>
                							 <button class="mdl-button mdl-js-button p-r-0 p-l-0 p-b-0 " style="border-radius:50%;height:36px;width:36px;min-width:0px;"  onclick="elegirDocumento()"><i class="material-icons">attach_file</i></button>
            							 </div>
            						  </div>
            					</div>
            					<div class="mdl-card__actions mdl-card--border p-r-20 p-l-20 p-t-10 p-b-10">
            					       <a class=" mdl-color-text--grey-500 mdl-button mdl-js-button   mdl-typography--display-4 P-0 " data-dismiss="modal" onclick="limpiarinput()">Cancelar</a>    
        						       <a class=" mdl-color-text--indigo mdl-button mdl-js-button   mdl-typography--display-4 P-0" onclick="publicar()" data-dismiss="modal">Publicar</a>
            					</div>
            				<div class="mdl-card__menu">
            				    <button class="mdl-button  mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="publicacion_menu"><i class="material-icons">more_vert</i></button>
            				    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="publicacion_menu">
            				        <li class="mdl-menu__item"> Inhabilitar comemtarios</li>
                        		    <li class="mdl-menu__item">Inhabilitar la opci�n de compartir</li>          				        
            				    </ul>            				
            				</div>	
            		</div>      
    			</div>
    		</div>
    	</div>
    	<div class="modal fade backModal" id="modallink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"aria-hidden="true">
    		<div class="modal-dialog modal-sm">
    		  <div class="modal-content">
    			 
    			<div class="mdl-card mdl-card--shadow-2dp">
    			        <div class="mdl-card__title">
    			             <h3 class="mdl-card__title-text mdl-typography--body-2 mdl-typography--font-bold">A�adir un v�nculo</h3>
    			        </div>
    			        <div class="mdl-card__supporting-text">
        			        <div class="mdl-textfield mdl-js-textfield">
        			             <input class="mdl-textfield__input mdl-color-text--indigo p-0 " type="text" id="link">
        			             <label class="mdl-textfield__label" for="link"></label>
        			        </div>
    			        </div>
    			        <div class="mdl-card__actions">        			                     
    			                         <button class="mdl-button mdl-js-button mdl-js-ripple-effect  m-0 mdl-color-text--grey-500 mdl-typography--display-4" data-dismiss="modal">Cancelar</button>           			                     
    			                         <button class="mdl-button mdl-js-button mdl-js-ripple-effect  m-0 mdl-color-text--indigo" >A�adir</button>    			                     
    			        </div>
    			</div> 
            	
            </div>   
    	</div>
    </div>
    	
    	
    
    
    	<input type="file" id="fotosPublicacion" name="fotosPublicacion[]"
    		style="display: none;" accept="image/*" multiple>
    	<input type="file" id="documentosPublicacion"
    		name="documentosPublicacion[]" style="display: none;"
    		accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                        text/plain, application/pdf"
    		multiple>
    
    	<div class="offcanvas"></div>
    
    
    
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jquery-ui/js/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/autosize/jquery.autosize.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js" defer></script>
    	<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js" charset="UTF-8"></script>
    	<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap/js/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/spin.js/spin.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/autosize/jquery.autosize.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/nanoscroller/jquery.nanoscroller.min.js"></script>
    	<script src="<?php echo RUTA_JS?>jquery.ui.touch-punch.js"></script>
    	<script src="<?php echo RUTA_JS?>jslogic/jsmural.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>urlLive/jquery.urlive.js"></script>
    
    
    
    	<script type="text/javascript">

        
            var flg = '#'+<?php echo $flg_fab?>;
            
            function showFab(){
        		setTimeout(function(){
                	$.each($('.mdl-layout__tab'),function(){
             	 		var active = $(this).hasClass('is-active');
             	 		var activeCurrent = $(this).attr('id');
             	 		if(active == true){
             	 			console.log(activeCurrent);
                 	 		console.log(flg);
                 	 		if(activeCurrent == 'docentefab' && flg == '#docentefab'){
                 	 			$('#menuPubli').css('display','block');
                     	 	} else if(activeCurrent == 'estrellafab' && flg == '#docentefab'){
                     	 		$('#menuPubli').css('display','none');
                         	} else if(activeCurrent == 'publicofab' && flg == '#docentefab'){
                     	 		$('#menuPubli').css('display','block');
                         	} else if(activeCurrent == 'docentefab' && flg == '#docentefab'){
                     	 		$('#menuPubli').css('display','block');
                         	} else if(activeCurrent == 'docentefab' && flg == '#publicofab'){
                     	 		$('#menuPubli').css('display','none');
                         	} else if(activeCurrent == 'publicofab' && flg == '#publicofab'){
                         		$('#menuPubli').css('display','block');
                         	} else if(activeCurrent == 'estrellafab' && flg == '#publicofab'){
                         		$('#menuPubli').css('display','none');
                         	} else if(activeCurrent == 'estrellafab' && flg == '#estrellafab'){
                         		$('#menuPubli').css('display','block');
                         	} else if(activeCurrent == 'publicofab' && flg == '#estrellafab'){
                         		$('#menuPubli').css('display','block');
                         	} else if(activeCurrent == 'docentefab' && flg == '#estrellafab'){
                         		$('#menuPubli').css('display','block');
                         	} 
             	 		}
                    });
        		}, 200);
            }
    	moreText('comentario');
        
    		$(document).ready(function(){
    		    $(this).scrollTop(0);
    		});
    		
    		$(window).scroll(function (){
    		    if($(document).height() <= $(window).scrollTop() + $(window).height()){
    			    loadmore();
    		    }
    	    });
    
    		(function($) {
            $.fn.clickToggle = function(func1, func2) {
                var funcs = [func1, func2];
                this.data('toggleclicked', 0);
                this.click(function() {
                    var data = $(this).data();
                    var tc = data.toggleclicked;
                    $.proxy(funcs[tc], this)();
                    data.toggleclicked = (tc + 1) % 2;
                });
                return this;
              };
            }(jQuery));
    
           var lastScrollTop = 0;
           $(window).scroll(function(event){
             var st = $(this).scrollTop();
              if (st > lastScrollTop){//OCULTAR
        	      $("#menuPubli").fadeOut();
              } else {
        	     if(st + $(window).height() < $(document).height()) {//MOSTRAR
        		   $("#menuPubli").fadeIn();
        	       
    	    	}
             }
             lastScrollTop = st;
            });
           var eve=0;
			function mostrarComentarios(id){
					
		           if(eve==0)
		               {
// 		        	   function viewComments(id){
		              	  $('#'+id).fadeIn();
							eve=1;
// 		                   }
		               }
		           else if(eve==1)
		               {
// 		        	   function closeComents (id){
		              	  $('#'+id).fadeOut(); 
		              	  eve=0;
// 		                    }
		               }
				}

			function darLike(corazon,idPubli) {
				$.ajax({
					data  : {id : idPubli},
				    url   : 'c_mural/like',
				    type  : 'POST',
				    async : false 
				})
				.done(function(data){
					if(!corazon.hasClass('active')) {
						corazon.addClass('active');
						corazon.parent().find('span.span-like').html(parseInt(corazon.parent().find('span.span-like').html())+1);
// 						console.log(corazon.parent().find('span').html());
					} else {
						corazon.removeClass('active');
						corazon.parent().find('span.span-like').html(parseInt(corazon.parent().find('span.span-like').html())-1);
					}
				});
			}
           
          function viewComments(id){
        	  $('#'+id).find('.mdl-card__actions').fadeIn(); 
              }
          function closeComents (id){
        	  $('#'+id).find('.mdl-card__actions').fadeOut(); 
              }

          function like(id){
        	  $('#'+id).find('.mdl-like').addClass('active');
              }
          function dislike(id){
        	  $('#'+id).find('.mdl-like').removeClass('active'); 
              }
          function moreText(clase){
        	  var showChar = 100000000000;
          	  var ellipsestext = "...";
          	  var moretext = "more";
          	  var lesstext = "less";

      	     $('.'+clase).each(function() {
          			var content = $(this).html();
          		    var id = $(this).attr('id');
          			if(content.length > showChar) {
    
          				var c = content.substr(0, showChar);
          				var h = content.substr(showChar-1, content.length - showChar);
    
          				var html = c + '<span class="moreelipses">'+ellipsestext+'</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="seeMoreText('+id+')" class="morelink">'+moretext+'</a></span>';
    
          				$(this).html(html);
          			}
      		    });	      		
              }

          function  seeMoreText(id){
            	if($('#comentario'+id).find(this).hasClass("less")) {
            		$('#comentario'+id).find(this).removeClass("less");
            		$('#comentario'+id).find(this).html("more");
      			} else {
      				$('#comentario'+id).find(this).addClass("less");
      				$('#comentario'+id).find(this).html("less");
      			}
            	$('#comentario'+id).find(this).parent().prev().toggle();
            	$('#comentario'+id).find(this).prev().toggle();
      			return false;
              }
          
        $(function(){
    			$('.animated').autosize();
    	});
        
        function limpiarinput(){
            $('#sample5').val("");
            $('#sample5').html();
            }  

        
    		</script>
    </body>
</html>