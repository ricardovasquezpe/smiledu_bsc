<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title">
                    <img alt="Logo"
        				src="<?php echo RUTA_IMG?>iconsSistem/icon_encuestas_blanco.png"
        				style="margin-right: 10px; position: absolute; top: -10px; left: -50px;">
                <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-medium" style="float: left;padding-right: 15px;border-right: 1px solid;">Encuesta EFQM</h2>
                <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-light" style="float: right;padding-left: 15px;">Llenado f&iacute;sico</h2>
            </span>
            <div class="mdl-layout-spacer"></div>
        </div>
        <div class="mdl-layout__tab-bar" id="categorias"></div>
    </header>
    <main class="mdl-layout__content p-t-20 is-visible">
        <section>
	        <div class="row-fluid">
	            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 card-width">
				   	<div id="modal-init" style="display: block;">
					   	<div class="cards">
						   	<input id="dribbble" name="card-control" type="radio" checked="checked" class="card-control"/>
							<div class="card card--init mdl-shadow--2dp p-30" style="padding-top: 90px !important; padding-bottom: 90px !important;">
								<div class="row-fluid">
									<div class="col-xs-8 col-xs-offset-2 p-0">
										<img class="img--init" src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png">
									</div>
									<div class="col-xs-12 p-0 m-0">
										<h4 class="mdl-typography--subhead  mdl-typography--font-regular m-0 m-b-10">Hola, soy 'Steven'</h4>
										<h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px">No te preocupes, cuidar&eacute; 
										que tu identidad se mantenga completamente AN&Oacute;NIMA en esta encuesta.</h5>
									</div>
									<div class="col-xs-12 p-0 m-t-5 text-center 3">
                                        <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="initEncuestaDocente();">EMPEZAR</label> 
                                    </div>
								</div>
							</div>
							 <input id="vk" name="card-control" type="radio" class="card-control"/>
							 <div class="card card--services mdl-shadow--2dp">
								<div class="row-fluid">
									<div class="col-xs-12 p-0">
										<h2 class="mdl-typography--title m-t-20 m-b-30">Selecciona el aula</h2>
									</div>
									<div class="col-xs-12 p-0 p-r-20 p-l-20 m-0 m-b-20">
									    <select id="selectSede" name=selectSede data-live-search="true" class="pickerButn" onchange="getGradosNivel()">
									    </select>
									</div>
									<div class="col-xs-12 p-0 p-r-20 p-l-20 m-0 m-b-10">
										<div class="form-group">
											<select id="selectGradoNivel" name=selectGradoNivel
												    data-live-search="true" class="pickerButn"
												    onchange="getAulasByGradoNivel();">
											</select>
										</div>
									</div>
									<div class="col-xs-12 p-0 p-r-20 p-l-20 p-0 m-0 m-b-20">
										<div class="form-group">
											<select id="selectAula" name="selectAula" data-live-search="true" 
											        class="pickerButn" onchange="setIdAulaHidden();">
											</select>
										</div>
									</div>
									<div class="col-xs-12 p-0 p-r-20 p-l-20 p-0 m-0 m-b-20">
										<div class="form-group">
											<select id="selectEstu" name="selectEstu" data-live-search="true" 
											        class="pickerButn" onchange="setIdEstudianteHidden();">
											</select>
										</div>
									</div>
									<div class="col-xs-12 p-0 m-t-5 text-center 3">
                                        <!--label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect " onclick="initEncuesta();" style="display: block">MI ENCUESTA</label-->
                                        <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="iniciarLlenadoFisico();">EMPEZAR</label> 
                                    </div>
									<a class="btn-go" onclick="window.close();">SALIR</a>
								</div>
						   	</div>  
						</div>
					</div>
			  </div>
		  </div>
	  </section>
   </main>
</div>
<form action="c_encuesta_efqm" method="get" id="formEncuFisica">
    <input type="hidden" id="encu_fisica_empezar" name="encu_fisica_empezar" value="1">
    <input type="hidden" id="aula" name="aula" value="">
    <input type="hidden" id="idEstu" name="idEstu" value="">
    <input type="hidden" id="tipo" name="tipo" value="<?php echo _encodeCI(TIPO_ENCUESTA_PADREFAM);?>">
</form>