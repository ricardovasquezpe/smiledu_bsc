<div class="mdl-card mdl-evaluator m-b-15" style="display:inline-block">
    <div class="mdl-card__title p-b-0">
        <h2 class="mdl-card__title-text">Subdirector</h2>
    </div>
    <div class="mdl-card__supporting-text br-b p-r-25 p-l-25">
        <div class="row">
            <div class="col-sm-12 mdl-input-group m-b-0">
                <div class="mdl-icon"><i class="mdi mdi-comment"></i></div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea class="mdl-textfield__input" id="observaciones_entrevista" name="observaciones_entrevista" 
                              <?php echo $disabledSubDir;?> rows="3"><?php echo isset($observacionEntrevista) ? $observacionEntrevista : null?></textarea>
                    <label class="mdl-textfield__label" for="observaciones_entrevista">Observaci&oacute;n (*)</label>
                </div>
            </div>
            <div class="col-sm-12 mdl-input-group">
                <div class="mdl-icon"><i class="mdi mdi-build"></i></div>                                                    
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label no-transparent">
                    <textarea class="mdl-textfield__input" id="select_taller_verano_entrevista" rows="3"
                              name="select_taller_verano_entrevista" <?php echo $disabledSubDir;?>><?php echo isset($tallerVeranoEntrevista)?$tallerVeranoEntrevista:null?></textarea>
                    <label class="mdl-textfield__label" for="select_taller_verano_entrevista">&#191;A Taller&#63;</label>
                </div>
            </div>
            <!-- button id="btnSubirArchivoEntrevista" onclick="abrirModalAgregarDocumentoEntrevista()" class="mdl-button mdl-js-button mdl-js-ripple-effect p-l-0" 
                    onclick="" <?php echo $disableEntrevista?> style="text-transform: capitalize; float: left; left: 50px" 
                    data-toggle="tooltip" data-placement="bottom" data-original-title="Subir Fotos">
                <i class="mdi mdi-attach_file" style="vertical-align:middle;"></i>Adjuntar doc. (*)
            </button-->
            <div style="height: 40px;" class="col-sm-10 col-md-10 p-l-15 text-left" id="cont_archivos_entrevista"><?php echo isset($archivosEntrevista)?$archivosEntrevista:null?></div>
            <div id="content_publicaciones_entrevista"></div>
            <div class="col-sm-12 mdl-input-group">
                <div class="mdl-icon"><i class="mdi mdi-straighten"></i></div>
                <div class="mdl-select p-t-10 no-transparent">
                    <select id="select_diagnostico_entrevista" name="select_diagnostico_entrevista" class="form-control selectButton" 
                            data-live-search="true" data-noneSelectedText="Seleccione un diagn&oacute;stico"
                            <?php echo $disabledSubDir;?> >
                        <option value="">Seleccione un diagn&oacute;stico (*)</option>
                        <?php echo $comboDiagnostico?>
		            </select>
                </div>
            </div>
        </div>
    </div>
    <div class="mdl-card__actions">
        <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised " id="btnSaveEditEntrevista"
                onclick="<?php echo $onclickEntrevista?>" <?php echo $disabledSubDir?>> Guardar </button>
    </div>
    <div class="mdl-card__menu">
        <?php echo (isset($fotoEntrevista) ? $fotoEntrevista : '<i id="foto_persona_entrevista"></i>')?> <?php echo $estadoEntrevista?>
    </div>
</div>