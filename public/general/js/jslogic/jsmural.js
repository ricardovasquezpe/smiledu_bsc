$('.mdl-ranking .mdl-card').click(function(){
	var idRanking = $(this).attr('id');
	$('.mdl-ranking .mdl-card').removeClass('mdl-flipped');
	$('#'+idRanking).toggleClass('mdl-flipped');
}).mouseleave(function() {
	$('.mdl-ranking .mdl-card').removeClass('mdl-flipped');
});

$( document ).ready(function() {	
	$('#menu .mfb-component__button--main').css('display', 'none');
});

$('.mdl-layout__tab[href="#tab-1"], .mdl-layout__tab[href="#tab-3"]').click(function(){
	$('#menu .mfb-component__button--main').css('display', 'none');
});

$('.mdl-layout__tab[href="#publico"]').click(function(){
	$('#menu .mfb-component__button--main').css('display', 'block');
});

function elegirImagen() {
	$('#fotosPublicacion').trigger('click');
}

function elegirDocumento() {
	$('#documentosPublicacion').trigger('click');
}

function abrirModalNuevaPublicacion() {
	$('textarea#comentario').val('')
	$('.imagesPrev').remove();
	$('.contentLive').html('');
	$('.docsPrev').remove();
	abrirCerrarModal('modalNuevaPublicacion');
}

$("#fotosPublicacion")
		.change(
				function(e) {
					$('.imagesPrev').remove();
					var files = e.target.files, filesLength = files.length;
					for (var i = 0; i < filesLength; i++) {
						var f = files[i]
						var fileReader = new FileReader();
						fileReader.onload = (function(e) {
							var file = e.target;
							var content = '<div class="col-sm-4 imagesPrev" style="text-align:center"><img src="'
									+ e.target.result
									+ '" title="'
									+ file.name
									+ '" class="imageThumb"></div>';
							$(content).insertAfter("#imagenesPreview");
						});
						fileReader.readAsDataURL(f);
					}
				});

$(function() {
	$('.animated').autosize();
});

$("#documentosPublicacion")
		.change(
				function(e) {
					$('.docsPrev').remove();
					var files = e.target.files, filesLength = files.length;
					for (var i = 0; i < filesLength; i++) {
						var f = files[i];
						var ext = f.name.split('.').pop();
						var fileReader = new FileReader();
						var content = '<div class="col-sm-4 docsPrev thing" style="text-align:center"><img src="'
								+ window.location.origin
								+ '/smiledu/public/general/files/images/mural/file-'
								+ ext
								+ '.png" title="'
								+ f.name
								+ '" class="imageThumb"><p>'
								+ f.name
								+ '</p></div>';
						$(content).insertAfter("#imagenesPreview");
						fileReader.readAsDataURL(f);
					}
				});

function publicar() {
	var formData = new FormData();
	var tipMural = $('.mdl-layout__tab.is-active').attr('href').substring(1,
			$('.mdl-layout__tab.is-active').attr('href').length);

	formData.append('comentario', $('#sample5').val());
	formData.append('tipMural', tipMural);
	$.ajax({
		data : formData,
		url : "c_main/publicar",
		cache : false,
		contentType : false,
		processData : false,
		type : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.error == 0) {
			/*$('#sample5').val('').end();
			$('#sample5').parent().removeClass('is-dirty');*/
			$('.imagesPrev').remove();
			$('#' + tipMural + " .col-sm-10.col-sm-offset-1.p-0").prepend(data.publicacion).fadeIn('slow');
			// abrirCerrarModal('modalNuevaPublicacion');
			mostrarNotificacion('success', data.msj, '');
		} else {
			mostrarNotificacion('error', 'Hubo un error', '');
		}
	});
}

function changeInput() {
	$("#comentario").urlive({
		callbacks : {
			onStart : function() {
				$(".contentLive").empty();
			},
			onLoadEnd : function() {
				$('.urlive-img-wrapper').addClass('col-sm-4');
				$('.urlive-image').addClass('img-responsive');
			},
			onSuccess : function(data) {
			}
		},
		container : '.contentLive'
	});
}

var row_no = 6;
function loadmore() {
	var tipMural = $('.mdl-layout__tab.is-active').attr('href').substring(1,
			$('.mdl-layout__tab.is-active').attr('href').length);
	$.ajax({
		data : {
			row : row_no,
			tipo : tipMural
		},
		url : 'c_mural/getMorePublish',
		async : false,
		type : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);
		$("#contentPublicaciones").append(data.publicaciones);
		if (data.publicaciones != "") {
			row_no = row_no + 6;
		}
	});
}

function like(id, comp) {
	$.ajax({
		data : {
			id : id
		},
		url : 'c_mural/like',
		async : false,
		type : 'POST'
	}).done(function(data) {
		$(comp).find('i').html(" " + (parseInt($(comp).find('i').html()) + 1));
	});
}

function logOut() {
	$.ajax({
		url : 'c_main/logOut',
		async : false,
		type : 'POST'
	}).done(function(data) {
		window.location.href = "";
	});
}

var cons_tipo = 0;
function getMuralTipo(tipo) {
	$.ajax({
		data : {
			tipo : tipo
		},
		url : 'c_mural/getMorePublish',
		async : false,
		type : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);
		cons_tipo = tipo;
		row_no = 6;
		$("#contentPublicaciones").html(data.publicaciones);
	});
}

var eve = 0;
function mostrarComentarios(id) {

	if (eve == 0) {
		$('#' + id).fadeIn();
		eve = 1;
	} else if (eve == 1) {
		$('#' + id).fadeOut();
		eve = 0;
	}
}

function darLike(corazon, idPubli) {
	$.ajax({
		data : {
			id : idPubli
		},
		url : 'c_mural/like',
		type : 'POST',
		async : false
	}).done(
			function(data) {
				if (!corazon.hasClass('active')) {
					corazon.addClass('active');
					corazon.parent().find('span.span-like').html(
							parseInt(corazon.parent().find('span.span-like')
									.html()) + 1);
				} else {
					corazon.removeClass('active');
					corazon.parent().find('span.span-like').html(
							parseInt(corazon.parent().find('span.span-like')
									.html()) - 1);
				}
			});
}

function viewComments(id) {
	$('#' + id).find('.mdl-card__actions').fadeIn();
}
function closeComents(id) {
	$('#' + id).find('.mdl-card__actions').fadeOut();
}

function like(id) {
	$('#' + id).find('.mdl-like').addClass('active');
}
function dislike(id) {
	$('#' + id).find('.mdl-like').removeClass('active');
}
function moreText(clase) {
	var showChar = 100000000000;
	var ellipsestext = "...";
	var moretext = "more";
	var lesstext = "less";

	$('.' + clase)
			.each(
					function() {
						var content = $(this).html();
						var id = $(this).attr('id');
						if (content.length > showChar) {

							var c = content.substr(0, showChar);
							var h = content.substr(showChar - 1, content.length
									- showChar);

							var html = c
									+ '<span class="moreelipses">'
									+ ellipsestext
									+ '</span>&nbsp;<span class="morecontent"><span>'
									+ h
									+ '</span>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="seeMoreText('
									+ id + ')" class="morelink">' + moretext
									+ '</a></span>';

							$(this).html(html);
						}
					});
}

function seeMoreText(id) {
	if ($('#comentario' + id).find(this).hasClass("less")) {
		$('#comentario' + id).find(this).removeClass("less");
		$('#comentario' + id).find(this).html("more");
	} else {
		$('#comentario' + id).find(this).addClass("less");
		$('#comentario' + id).find(this).html("less");
	}
	$('#comentario' + id).find(this).parent().prev().toggle();
	$('#comentario' + id).find(this).prev().toggle();
	return false;
}

$(function() {
	$('.animated').autosize();
});

function limpiarinput() {
	$('#sample5').val("");
	$('#sample5').html();
}

$(document).ready(function() {
	$(this).scrollTop(0);
});

$(window).scroll(function() {
	if ($(document).height() <= $(window).scrollTop() + $(window).height()) {
		loadmore();
	}
});

(function($) {
	$.fn.clickToggle = function(func1, func2) {
		var funcs = [ func1, func2 ];
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
$(window).scroll(function(event) {
	var st = $(this).scrollTop();
	if (st > lastScrollTop) {// OCULTAR
		$("#menuPubli").fadeOut();
	} else {
		if (st + $(window).height() < $(document).height()) {// MOSTRAR
			$("#menuPubli").fadeIn();

		}
	}
	lastScrollTop = st;
});