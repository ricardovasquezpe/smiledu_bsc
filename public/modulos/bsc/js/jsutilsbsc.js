function initGauge(porcentaje, orden, amarillo, verde, colorRojo, colorAmarillo, colorVerde, inicioG, finG){
	setDivHeight();
    $('.linEst'+orden).highcharts({
        chart: {
            type: 'gauge',
          	margin: [0, 0, 0, 0],
            size: '150%',
            backgroundColor:'transparent',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false,
            dataLabels: false
        },

        title: {
        	text: ''
        },
        exporting: { enabled: false },
        pane: {
        	startAngle: -100,
            endAngle: 100,
            background: [{
                borderWidth: 0,
                backgroundColor: 'transparent'
            }]
        },
        credits: {
            enabled: false
        },

        yAxis: {
            min: inicioG,
            max: finG,

            minorTickInterval : 'auto',
            minorTickWidth    : 0,
            minorTickLength   : 100,
            minorTickPosition : 'inside',
            minorTickColor    : '#666',

            tickPixelInterval : 0,
            tickWidth         : 0,
            tickPosition      : 'inside',
            tickLength        : 0,
            tickColor         : '#666',
            tickPositions: [amarillo,verde],
            plotBands: [{
                from: inicioG,
                to: amarillo,
                color: colorRojo
            }, {
                from: amarillo,
                to: verde,
                color: colorAmarillo
            }, {
                from: verde,
                to: finG,
                color: colorVerde
            }]
        },
        
        tooltip: {
            enabled: false
        },

        series: [{
            name: 'Logro',
            data: [porcentaje],
            tooltip: {
                valueSuffix: '%',
                enabled:false
            },
            dial: {
                backgroundColor : '#959595',
                baseLength      : '1%',
                baseWidth       : 6,
                borderColor     : '#959595'
            }
        }]

    });
}


function initGaugeIndica(porcentaje, orden){
    $('.linEst'+orden).highcharts({
        chart: {
            type: 'gauge',
          	margin: [0, 0, 0, 0],
            size: '100%',
            backgroundColor:'rgba(255, 255, 255, 0.0)',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false,
            dataLabels: false
        },

        title: {
        	text: ''
        },
        exporting: { enabled: false },
        pane: {
        	startAngle: -100,
            endAngle: 100,
            background: [{
                borderWidth: 0,
                backgroundColor: 'transparent'
            }]
        },
        credits: {
            enabled: false
        },

        yAxis: {
            min: 0,
            max: 100,

            minorTickInterval : 'auto',
            minorTickWidth    : 0,
            minorTickLength   : 0,
            minorTickPosition : 'inside',
            minorTickColor    : '#666',

            tickPixelInterval : 30,
            tickWidth         : 0,
            tickPosition      : 'inside',
            tickLength        : 0,
            tickColor         : '#666',
            labels: {
                enabled: false
            },
            plotBands: [{
                from: 0,
                to: 33,
                color: '#CC5667'
            }, {
                from: 33,
                to: 66,
                color: '#FFEC00'
            }, {
                from: 66,
                to: 100,
                color: '#78B16F'
            }]
        },
        
        tooltip: {
            enabled: false
        },

        series: [{
            name: 'Logro123',
            data: [porcentaje],
            tooltip: {
            	enabled: false,
                valueSuffix: '%'
            },
            dial: {
                backgroundColor : '#959595',
                baseLength      : '5%',
                baseWidth       : 2,
                borderColor     : '#959595'
            }
        }]

    });
}

function setDivHeight() {
    var div = $('.container-gauge');
    div.height(div.width() * 1);
    div = $('.container-rpm');
    div.height(div.width() * 1);
}

function getCheckedFromTablaByAttrFOCOL(idTabla, indiceColumnaCB){
	arryDiv = [];
	var jason = JSON.stringify($('#'+idTabla).bootstrapTable('getOptions'));
	var obj = jQuery.parseJSON( jason );
    $.each(obj.data, function(key, value){
        $.each(value, function(key, value){
    		if(key == indiceColumnaCB){
    			//console.log('val: '+$(value).find(':checkbox').attr('attr-cambio'));
    			var foco = $(value).attr('attr-focol');
    			if(foco == 'true') {
    				arryDiv.push(value);
    			}
    		}	            
        });
    });
    return arryDiv;
}

function getCheckedFromTablaByAttrFOCOO(idTabla, indiceColumnaCB){
	arryDiv = [];
	var jason = JSON.stringify($('#'+idTabla).bootstrapTable('getOptions'));
	var obj = jQuery.parseJSON( jason );
    $.each(obj.data, function(key, value){
        $.each(value, function(key, value){
    		if(key == indiceColumnaCB){
    			//console.log('val: '+$(value).find(':checkbox').attr('attr-cambio'));
    			var foco = $(value).attr('attr-focoo');
    			if(foco == 'true') {
    				arryDiv.push(value);
    			}
    		}		
            
        });
    });
    return arryDiv;
}