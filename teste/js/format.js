	$(document).ready(function(){


    	jQuery.extend(jQuery.validator.messages, {
			required: "Este campo &eacute; requerido.",
			remote: "Por favor, corrija este campo.",
			email: "Por favor, forne&ccedil;a um endere&ccedil;o eletr&ocirc;nico v&aacute;lido.",
			url: "Por favor, forne&ccedil;a uma URL v&aacute;lida.",
			date: "Por favor, forne&ccedil;a uma data v&aacute;lida.",
			dateISO: "Por favor, forne&ccedil;a uma data v&aacute;lida (ISO).",
			number: "Por favor, forne&ccedil;a um n&uacute;mero v&aacute;lido.",
			digits: "Por favor, forne&ccedil;a somente d&iacute;gitos.",
			creditcard: "Por favor, forne&ccedil;a um cart&atilde;o de cr&eacute;dito v&aacute;lido.",
			equalTo: "Por favor, forne&ccedil;a o mesmo valor novamente.",
			accept: "Por favor, forne&ccedil;a um valor com uma extens&atilde;o v&aacute;lida.",
			extension: jQuery.validator.format("Por favor, insira um arquivo com extens&atilde;o permitida ({0})."),
			maxlength: jQuery.validator.format("Por favor, forne&ccedil;a n&atilde;o mais que {0} caracteres."),
			minlength: jQuery.validator.format("Por favor, forne&ccedil;a ao menos {0} caracteres."),
			rangelength: jQuery.validator.format("Por favor, forne&ccedil;a um valor entre {0} e {1} caracteres de comprimento."),
			range: jQuery.validator.format("Por favor, forne&ccedil;a um valor entre {0} e {1}."),
			max: jQuery.validator.format("Por favor, forne&ccedil;a um valor menor ou igual a {0}."),
			min: jQuery.validator.format("Por favor, forne&ccedil;a um valor maior ou igual a {0}.")
		});

		jQuery.validator.addMethod("dateBR", function (value, element) {
		    //contando chars
		    if (value.length != 10)
		        return this.optional(element) || false;
		    // verificando data
		    var data = value;
		    var dia = data.substr(0, 2);
		    var barra1 = data.substr(2, 1);
		    var mes = data.substr(3, 2);
		    var barra2 = data.substr(5, 1);
		    var ano = data.substr(6, 4);
		    if (data.length != 10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12) {
		        return this.optional(element) || false;
		    }
		    if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31) {
		        return this.optional(element) || false;
		    }
		    if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 !== 0))) {
		        return this.optional(element) || false;
		    }
		    if (ano < 1900) {
		        return this.optional(element) || false;
		    }
		    return this.optional(element) || true;
		}, "Informe uma data válida"); /* Mensagem padrão */
	
		$("table.default-list tbody tr:nth-child(odd)").addClass("odd");
		$("table.default-list tbody tr:nth-child(even)").addClass("even");


		/* cheat para o aviso de erro ficar em baixo do campo */
		var labels = new Array;
		$('input.left.labelBugfix, select.left.labelBugfix').each(function(){
			labels.push("label.error[for='" + this.id + "']");
			labels.push("label.error3[for='" + this.id + "']");
		});
				
		if(labels.length>0){
			$('body').append("<style>" + (labels.join()) + "{ margin-top: 25px; } </style>");
		}



		$( document ).on( "blur keyup", ".unidadeFormata", function() { 
			unidade = Format.number(this.value);
			this.value = ("000000000000" + unidade).slice(-12);
		});


		$('.data').mask('00/00/0000');
		$('.hora').mask('00:00:00');
		
		var maskBehavior = function (val) {
		  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		options = {onKeyPress: function(val, e, field, options) {
				field.mask(maskBehavior.apply({}, arguments), options);
			}
		};
		
		$('.fone').mask(maskBehavior, options);
		$('.cpf').mask('000.000.000-00', {reverse: true});
		$('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});

	});


	
	Format = function() {};
	
	//formatacao generia para uma ocorrencia
	Format.format = function(strValue, objRegExp, strReplace) {
		if(objRegExp.test(strValue))
			return strValue.replace(objRegExp, strReplace);
		return strValue;
	};
	
	//formatacao generica para varias ocorrencias
	Format.formatAll = function(strValue,objRegExp,strReplace) {
		while(objRegExp.test(strValue))
			strValue = strValue.replace(objRegExp,strReplace);
		return strValue;
	};
	
	//formatacao de Numeros
	Format.number = function (strValue) {
		return Format.format(strValue, new RegExp('[^0-9]*', 'g'), '');
	};
