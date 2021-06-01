	$(document).ready(function(){
	
		$("table.default-list tbody tr:nth-child(odd)").addClass("odd");
		$("table.default-list tbody tr:nth-child(even)").addClass("even");
		
		$("ul.destaques li:nth-child(odd) ").addClass("destaque1");
		$("ul.destaques li:nth-child(even)").addClass("destaque2");
		
		$('table.resultados tbody tr').hover(function(){
			$(this).toggleClass('hoverRow'); },
			function(){
				$(this).toggleClass('hoverRow');
		});

		/* cheat para o aviso de erro ficar em baixo do campo */
		var labels = new Array;
		$('input.left.labelBugfix, select.left.labelBugfix').each(function(){
			labels.push("label.error[for='" + this.id + "']");
			labels.push("label.error3[for='" + this.id + "']");
		});
				
		if(labels.length>0){
			$('body').append("<style>" + (labels.join()) + "{ margin-top: 25px; } </style>");
		}
	/*	
		$(".data").bind("blur keyup", function() { formataData($(this)); });
		$(".dataMMYYYY").bind("blur keyup", function() { formataMesAno($(this)); });
		$(".numero").bind("blur keyup", function() { formataNumero($(this)); });
		$(".cep").bind("blur keyup", function() { formataCEP($(this)); });
		$(".hora").bind("blur keyup", function() { formataHora($(this)); });
		$(".cnpj").bind("blur keyup", function() { formataCNPJ($(this)); });
		$('.fone').bind("blur keyup", function() { formataDDDTelefone($(this)); });
		$('.cim').bind("blur keyup", function() { formataCIM($(this)); });
		$('.cpf').bind("blur keyup", function() { formataCPF($(this)); });
		$('.money').bind("blur keyup", function() { formataDinheiro($(this)); });
	*/	
		$( document ).on( "blur keyup", ".data", function() { formataData($(this)); });
		$( document ).on( "blur keyup", ".dataMMYYYY", function() { formataMesAno($(this)); });
		$( document ).on( "blur keyup", ".numero", function() { formataNumero($(this)); });
		$( document ).on( "blur keyup", ".numeroVirgula", function() { formataNumeroComVirgula($(this)); });
		$( document ).on( "blur keyup", ".cep", function() { formataCEP($(this)); });
		$( document ).on( "blur keyup", ".hora", function() { formataHora($(this)); });
		$( document ).on( "blur keyup", ".cnpj", function() { formataCNPJ($(this)); });
		$( document ).on( "blur keyup", ".fone", function() { formataDDDTelefone($(this)); });
		$( document ).on( "blur keyup", ".cim", function() { formataCIM($(this)); });
		$( document ).on( "blur keyup", ".cpf", function() { formataCPF($(this)); });
		$( document ).on( "blur keyup", ".money", function() { formataDinheiro($(this)); });
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

	//formatacao de Numeros sem Zero (1-9)
	Format.numberNoZero = function(strValue) {
		return Format.format(strValue, new RegExp('[^1-9]*', 'g'), '');
	};

	//formatacao de texto somente texto sem acento
	Format.text = function(strValue) {
		return Format.format(strValue, new RegExp('[^A-Za-z]*', 'g'), '');
	};

	//formatacao de em formato de texto 10.100.100 ou -10.100.100
	Format.textNumber = function(strValue) {
		return Format.format(strValue, new RegExp('[^A-Za-z0-9]*', 'g'), '');
	};

	//Formata Texto(a-z, A-Z), Número, - , _
	Format.textNumberNoSpace = function(strValue) {
		return Format.format(strValue, new RegExp('[^a-z0-9-_]*', 'g'), '');
	};

	//Formata Texto(a-z, A-Z), Número e Espaço
	Format.textNumberSpace = function(strValue) {
		return Format.format(strValue, new RegExp('[^\\sA-Za-z0-9]*', 'g'), '');
	};
	
	//Formata Texto(a-z, A-Z), Número e Espaço
	Format.urlConteudoPersonalizado = function(strValue) {
		return Format.format(strValue, new RegExp('[^\sa-z0-9-_]*', 'g'), '');
	};

	//Formata Texto(a-z, A-Z), Número, Espaço, Sinal Agudo e Sinal Crase
	Format.textNumberSpaceAcuteCrase = function(strValue) {
		return Format.format(strValue, new RegExp('[^\\s´`A-Za-z0-9]*', 'g'), '');
	};
	
	//Formata Texto(a-z, A-Z), Número, menos ' " ^ ~ * (FRANKE)
	Format.textNumberNoSpecials = function(strValue) {
		return Format.format(strValue, new RegExp('[^\\s´`A-Za-z0-9!#$%&()+,-.//:;<=>?@[\\]_{|}]*', 'g'), '');
	};

	//formatacao de número e vírgula
	Format.numberComma = function(strValue) {
		return Format.format(strValue, new RegExp('[^0-9,]', 'g'), '');
	};

	//formatacao de CEP
	Format.cep = function (strValue) {
		strValue = Format.number(strValue);
		if(strValue.length>8) strValue = strValue.substring(0,8);
		return Format.format(strValue, new RegExp('^([0-9]{5})([0-9])'), '$1-$2');
	};	
	
	//FORMATAÇÃO DE CIM
	Format.cim = function (strValue) {
		var zeros = "000000";
		var primeiroDigito = strValue.substr(0,1);
		
		var antesTraco ="";
		var depoisTraco = "";
		
		strValue = strValue.replace("-","");	
		if(strValue.length>0){
			
			if(primeiroDigito==0 || strValue.length<7){			
				if(strValue.length>1){	
					antesTraco = strValue.substr( 0, (strValue.length-1) );
					antesTraco = parseInt(antesTraco).toString();
					depoisTraco = strValue.substr((strValue.length-1), 1 );
					strValue = antesTraco+"-"+depoisTraco;
					strValue = zeros.substr( 0, (6-antesTraco.length) )+strValue;
				}else{
					strValue = zeros+strValue;
				}
				
			}else{
				strValue = strValue.toString();
				antesTraco  = strValue.substr(0,6);
				depoisTraco = strValue.substr(6,1);
				strValue = antesTraco+'-'+depoisTraco;
				
			}		
			
		}
		return strValue;
	};
	
	//005.432.519-69
	Format.cpf = function (strValue) {
		strValue = Format.number(strValue);
		if(strValue.length>11) strValue = strValue.substring(0,11);
		strValue = Format.format(strValue, new RegExp('([0-9]{3})'), '$1');
		strValue = Format.format(strValue, new RegExp('([0-9]{3})([0-9])'), '$1.$2');
		strValue = Format.format(strValue, new RegExp('([0-9]{3}).([0-9]{3})([0-9])'), '$1.$2.$3');
		return Format.format(strValue, new RegExp('^([0-9]{3}).([0-9]{3}).([0-9]{3})([0-9])'), '$1.$2.$3-$4');
	};
	
	//00.000.000/0000-00
	Format.cnpj = function (strValue) {
		strValue = Format.number(strValue);
		if(strValue.length>14) strValue = strValue.substring(0,14);
		/*
			strValue = Format.format(strValue, new RegExp('([0-9]{2})'), '$1.');
			strValue = Format.format(strValue, new RegExp('([0-9]{2}).([0-9]{3})'), '$1.$2.');
			strValue = Format.format(strValue, new RegExp('([0-9]{2}).([0-9]{3}).([0-9]{3})'), '$1.$2.$3/');
			strValue = Format.format(strValue, new RegExp('([0-9]{2}).([0-9]{3}).([0-9]{3}).([0-9]{4})'), '$1.$2.$3/$4-');
			strValue = Format.format(strValue, new RegExp('([0-9]{2}).([0-9]{3}).([0-9]{3})/([0-9]{4})([0-9])'), '$1.$2.$3/$4-$5');
		*/
		if(strValue.length<3)
			strValue = Format.format(strValue, new RegExp('([0-9])'), '$1');
		else if(strValue.length<6)
			strValue = Format.format(strValue, new RegExp('([0-9]{2})([0-9])'), '$1.$2');
		else if(strValue.length<9)
			strValue = Format.format(strValue, new RegExp('([0-9]{2})([0-9]{3})([0-9])'), '$1.$2.$3');
		else if(strValue.length<13)
			strValue = Format.format(strValue, new RegExp('([0-9]{2})([0-9]{3})([0-9]{3})([0-9])'), '$1.$2.$3/$4');
		else
			strValue = Format.format(strValue, new RegExp('([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9])'), '$1.$2.$3/$4-$5');
		
		return strValue;
	};
	
	//formatacao de DATA 88/88/8888
	Format.date = function (strValue) {
		p = new RegExp('^([0-9]{2})/([0-9]{2})/([0-9]{4})$');
		if(p.test(strValue)) strValue;
		strValue = Format.number(strValue);
		
		if(strValue.length>8) strValue = strValue.substring(0,8);
		if(strValue.length>4) strValue = Format.format(strValue, new RegExp('([0-9]{2})([0-9]{2})([0-9])'), '$1/$2/$3');
		else if(strValue.length>2) strValue = Format.format(strValue, new RegExp('([0-9]{2})'), '$1/');
			
		return strValue;
	};
	
	//formatacao de DATA 88/8888
	Format.dateMMYYYY = function (strValue) {
		p = new RegExp('^([0-9]{2})/([0-9]{4})$');		
		if(p.test(strValue)) strValue;
		strValue = Format.number(strValue);
		
		if(strValue.length>6) strValue = strValue.substring(0,6);	
		if(strValue.length>2) strValue = Format.format(strValue, new RegExp('([0-9]{2})([0-9]{4})'), '$1/$2');	
		return strValue;
	};
	
	//formatacao de Hora 88:88
	Format.time = function (strValue) {
	//	strValue = Format.number(strValue);
		console.dir("Entrou");
		if(strValue.length>4) strValue = strValue.substring(0,4);
		return Format.format(strValue, new RegExp('([0-9]{2})([0-9]{2})'), '$1:$2');
	};

	//formatacao de Telefone 1234-1234
	Format.phone = function (strValue) {
		strValue = Format.number(strValue);
		if(strValue.length>9) strValue = strValue.substring(0,8);
		return Format.format(strValue, new RegExp('([0-9]{4})([0-9]{4})'), '$1-$2');
	};	

	//formatacao de Telefone (12)1234-1234
	//	\((10)|([1-9][1-9])\) [2-9][0-9]{3}-[0-9]{4}
	Format.DDDphone = function (strValue) {
		strValue = Format.number(strValue).replace(/^(0+)(\d)/g,"$2");
		if(strValue.length>11) strValue = strValue.substring(0,11);
		
		if(strValue.length>2 && strValue.length<=6)
			strValue = Format.format(strValue, new RegExp('([1-9][0-9])([0-9]+)'), '($1) $2');
		if(strValue.length==11)
			strValue = Format.format(strValue, new RegExp('([1-9][0-9])([0-9]{5})([0-9]+)'), '($1) $2-$3')
		else if(strValue.length>6)
			strValue = Format.format(strValue, new RegExp('([1-9][0-9])([0-9]{4})([0-9]+)'), '($1) $2-$3')
		return strValue;
	};

	//Valida se é uma string com 2 dígitos no ano
	isDate2 = function(pStr) {	
	   var reDate = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{2}$/;
	   return (reDate.test(pStr));
	};
	
	//Valida se é uma string com 4 dígitos no ano
	isDate4 = function(pStr) {
	   var reDate = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}$/;
	   return (reDate.test(pStr));
	};

	//Valida se é uma string no formato hora 00:00
	isTime = function(pStr) {
	   var reTime = /^([0-1]\d|2[0-3]):[0-5]\d$/;
	   return (reTime.test(pStr));
	};

	//Valida se é uma string com email válido
	isEmail = function(pStr){
		var reEmail = /^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;
		return reEmail.test(pStr);
	};

	//UTILIZADO PARA VALIDAR VALOR DECIMAL, SENDO POSSÍVEL INFORMAR SINAL +/- E PERMITE VALOR = 0
	isDecimalPt = function(pStr) {
	   var reDecimalPt = /^[+-]?((\d+|\d{1,3}(\.\d{3})+)(\,\d*)?|\,\d+)$/;
	   return (reDecimalPt.test(pStr));
	};

	//UTILIZADO PARA VALIDAR SE A STRING TEM SOMENTE NÚMERO(S) E LETRA(S)
	isNumberAndLetter = function(pStr){
		var reNL = /^[A-Za-z0-9]+$/;
		return (reNL.test(pStr));
	}

	//UTILIZADO PARA VALIDAR SE A STRING TEM SOMENTE NÚMERO(S), LETRA(S) E ESPAÇO(S)
	isNumberAndLetterSpace = function(pStr){
		var reNL = /^[\sfA-Za-z0-9]+$/;
		return (reNL.test(pStr));
	}
	
	//UTILIZADO PARA VALIDAR SE A STRING TEM SOMENTE NÚMERO(S), LETRA(S), ESPAÇO(S), AGUDO E CRASE
	isNumberAndLetterSpaceAcuteCrase = function(pStr){
		var reNL = /^[\sf´`A-Za-z0-9]+$/;
		return (reNL.test(pStr));
	}

	//UTILIZADO PARA VALIDAR SE A STRING TEM CARACTERES ESPECIAIS (FRANKE)
	isNumberLetterNoSpecial = function(pStr){
		var reNL = /^[\sf´`A-Za-z0-9!#$%&()+,-./:;<=>?@[\]_{|}]+$/;
		return (reNL.test(pStr));
	}
	
	
	//MÁSCARA PARA MOEDA, OU SEJA, VALOR DECIMAL SEM O SINAL +/-
	Format.money = function(pStr){  
        v=pStr.replace(/\D/g,"");  //permite digitar apenas números
		v=v.replace(/[0-9]{15}/,"");   //limita pra máximo 999.999.999.999,99
		v=v.replace(/(\d{1})(\d{11})$/,"$1.$2");  //coloca ponto antes dos últimos 11 digitos
		v=v.replace(/(\d{1})(\d{8})$/,"$1.$2");   //coloca ponto antes dos últimos 8 digitos
		v=v.replace(/(\d{1})(\d{5})$/,"$1.$2");   //coloca ponto antes dos últimos 5 digitos
		v=v.replace(/(\d{1})(\d{1,2})$/,"$1,$2"); //coloca virgula antes dos últimos 2 digitos
		return v;
    };

	//FORMATA O ELEMENTO APLICANDO A MÁSCARA DE MOEDA - MAXIMO 18 CARACTERES
	formataDinheiro = function(elemento){
		elemento.val ( Format.money(elemento.val()) );
	};

	//FORMATA O ELEMENTO PERMITINDO SOMENTE TEXTO A-Z E a-z
	formataTexto = function(elemento){
		elemento.val ( Format.text(elemento.val()) );
	};
	
	//FORMATA O ELEMENTO PERMITINDO SOMENTE NÚMEROS
	formataNumero = function(elemento){
		elemento.val ( Format.number(elemento.val()) );
	};

	//FORMATA O ELEMENTO PERMITINDO SOMENTE NÚMEROS
	formataNumeroComVirgula = function(elemento){
		elemento.val ( Format.numberComma(elemento.val()) );
	};

	//FORMATA O ELEMENTO PARA NÚMERO DE TELEFONE SEPARADO POR HÍFEN
	formataTelefone = function(elemento){
		elemento.val ( Format.phone(elemento.val()) );
	};
	
	//FORMATA O ELEMENTO PARA NÚMERO DE TELEFONE SEPARADO POR HÍFEN
	formataDDDTelefone = function(elemento){
		elemento.val ( Format.DDDphone(elemento.val()) );
	};
	
	//FORMATA O ELEMENTO PARA NÚMERO DE TELEFONE SEPARADO POR HÍFEN
	formataData = function(elemento){
		elemento.val ( Format.date(elemento.val()) );
	};
	
	//FORMATA O ELEMENTO PARA MES E ANO EX: MM/YYYY
	formataMesAno = function(elemento){
		elemento.val ( Format.dateMMYYYY(elemento.val()) );
	};
	
	//FORMATA HORA
	formataHora = function(elemento){
		elemento.val ( Format.time(elemento.val()) );
	};
	
	//FORMATA O ELEMENTO PARA SOMENTE NÚMEROS, LETRAS E ESPAÇOS
	formataNumerosLetrasEspacos = function(elemento){
		if( elemento.val()!='' && !isNumberAndLetterSpace( elemento.val() ) ){
			novo = Format.textNumberSpace( elemento.val() );
			elemento.val(novo.toUpperCase());
			alert('Este campo deve conter somente Números, Letras (sem caracteres especiais) e Espaços');
			elemento.focus();
		}else{
			elemento.val($(elemento).val().toUpperCase());
		}
	}
	
	// FORMATA O ELEMENTO APLICANDO A MÁSCARA CNPJ 
	formataCNPJ = function(elemento){
		elemento.val ( Format.cnpj(elemento.val()) );
	};
	
	//FORMATA O ELEMENTO APLICANDO A MÁSCARA DE CPF
	formataCPF = function(elemento){
		elemento.val ( Format.cpf(elemento.val()) );
	};
	
	//FORMATA O ELEMENTO APLICANDO A MÁSCARA DE CPF
	formataTextoNumero = function(elemento){
		elemento.val ( Format.textNumberNoSpace(elemento.val()) );
	};
	
	//FORMATA O ELEMENTO APLICANDO A MÁSCARA DE CEP
	formataCEP = function(elemento){
		elemento.val ( Format.cep(elemento.val()) );
	};
	
	//FORMATA O ELEMENTO PERMITINDO SOMENTE TEXTO "a-z" "-_" "1-9" 
	formataUrlConteudoPersonalizado = function(elemento){
		elemento.val ( Format.urlConteudoPersonalizado(elemento.val()) );
	};
	
	//FORMATA O CIM NO FORMATO 000000-0
	formataCIM = function(elemento){
		elemento.val ( Format.cim(elemento.val()) );
	};	
	
	//FORMATA O ELEMENTO PARA SOMENTE NÚMEROS, LETRAS E ESPAÇOS
	formataNumerosLetrasEspacosAgudoCrase = function(elemento){
		if( elemento.val()!='' && !isNumberAndLetterSpaceAcuteCrase( elemento.val() ) ){
			novo = Format.textNumberSpaceAcuteCrase( elemento.val() );
			elemento.val(novo.toUpperCase());
			alert('Este campo deve conter somente Número, Letra (sem caractere especial), Espaço, ´ (agudo) e ` (crase).');
			elemento.focus();
		}else{
			elemento.val($(elemento).val().toUpperCase());
		}
	}
	
	//FORMATA O ELEMENTO PARA SOMENTE NÚMEROS, LETRAS E ESPAÇOS
	formataSemEspeciais = function(elemento){
		if( elemento.val()!='' && !isNumberLetterNoSpecial( elemento.val() ) ){
			novo = Format.textNumberNoSpecials( elemento.val() );
			elemento.val(novo.toUpperCase());
			alert('Este não pode ter caracteres especiais');
			elemento.focus();
		}else{
			elemento.val($(elemento).val().toUpperCase());
		}
	}
	
	formataValorParaCalculo = function(valor){
		valor = valor.replace('.', '')
		return valor.replace(',', '.');
	}
	
	formataValorParaTela = function(valor){
		return valor.replace('.', ',');
	}
	
	
	// FORMATA CNAE
	//0000-0/00
	Format.cnae = function (strValue) {
		strValue = Format.number(strValue);
		if(strValue.length>7) strValue = strValue.substring(0,7);
		strValue = Format.format(strValue, new RegExp('([0-9]{4})'), '$1-');
		strValue = Format.format(strValue, new RegExp('([0-9]{4})-([0-9]{1})'), '$1-$2/');
		strValue = Format.format(strValue, new RegExp('([0-9]{4})-([0-9]{1})/([0-9]{2})'), '$1-$2/$3');
		return Format.format(strValue, new RegExp('^([0-9]{4})-([0-9]{2})/([0-9]{2})'), '$1-$2/$3');
	};
	
	formataCNAE = function(elemento){
		elemento.val ( Format.cnae(elemento.val()) );
	};
	
	
	// formata hora 00:00
	Format.hora = function (strValue) {
		strValue = Format.number(strValue);
		if(strValue.length>4) strValue = strValue.substring(0,4);
		strValue = Format.format(strValue, new RegExp('([0-9]{2})'), '$1');
		return Format.format(strValue, new RegExp('([0-9]{2})([0-9])'), '$1:$2');
	};
	
	formataHora = function(elemento){
		elemento.val ( Format.hora(elemento.val()) );
	};	
	
		number_format = function(number, decimals, dec_point, thousands_sep) {
		// Strip all characters but numerical ones.
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',

		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);

			return '' + Math.round(n * k) / k;
		};

		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}

		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}

		return s.join(dec);
	}
	
	formatarBytes = function(bytes){
		var retorno;
		var unidade;
		if(bytes < 1024) {
			retorno = bytes;
			unidade = ' Bytes';
		} else if(bytes < 1048576) {
			retorno = Math.round(bytes / 1024);
			unidade = ' Kb';
		} else if(bytes < 1073741824) {
			retorno = Math.round(bytes / 1048576);
			unidade = ' Mb';
		} else if(bytes < 1099511627776) {
			retorno = Math.round(bytes / 1073741824);
			unidade = ' Gb';
		} else if(bytes < 1125899906842624) {
			retorno = Math.round(bytes / 1099511627776);
			unidade = ' Tb';
		} else if(bytes < 1152921504606846976) {
			retorno = Math.round(bytes / 1125899906842624);
			unidade = ' Pb';
		} else if(bytes < 1180591620717411303424) {
			retorno = Math.round(bytes / 1152921504606846976);
			unidade = ' Eb';
		} else if(bytes < 1208925819614629174706176) {
			retorno = Math.round(bytes / 1180591620717411303424);
			unidade = ' Zb';
		} else {
			retorno = Math.round(bytes / 1208925819614629174706176);
			unidade = ' Yb';
		}
		
		return (retorno > 0) 
					? number_format(retorno, 0, ",", "") + unidade
					: 0 + unidade;
	}
	
	function formataMoneyJS(number){
		return parseFloat( number.replace(new RegExp('[R$ .]', 'g'), '').replace(',', '.') );
	}
	
	function formataDataParaBanco(data, idioma){
		
		if(data!='' && data!=null && data!='undefined'){
			if(idioma=='es'){
				var dt = data.split(".");
				return dt[2]+"-"+dt[1]+"-"+dt[0];			
			}else if(idioma=='pt'){
				var dt = data.split("/");
				return dt[2]+"-"+dt[1]+"-"+dt[0];
			}else{
				return data;
			}
		}else{
			return "";
		}
	}