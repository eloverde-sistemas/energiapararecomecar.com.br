
function redir(url){
	window.location = url;
}

function openWindow(theURL,winName,features) {
	  window.open(theURL,winName,features);
}

function isDate2(pStr) {	
	var reDate = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{2}$/;
	return (reDate.test(pStr));
}

function isDate4(pStr) {
   reDate = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}$/;
   return (reDate.test(pStr));
}

function isTime(pStr) {
   reTime = /^([0-1]\d|2[0-3]):[0-5]\d$/;
   return (reTime.test(pStr));
}

function isEmail(pStr){
	var reEmail = /^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;
	return reEmail.test(pStr);
}

function isNumberAndLetter(pStr){
	var reNL = /^[A-Za-z0-9]+$/;
	return Format.format(pStr, new RegExp('[^0-9A-Za-z]', 'g'), '');
}

function numberNoZero(strValue) {
	return Format.format(strValue, new RegExp('[^1-9]*', 'g'), '');
}

function MascaraCep(keypress, objeto) {
		campo = eval(objeto);
		caracteres = '0123456789';
		separacoes = 1;
		separacao1 = '-';
		conjuntos = 2;
		conjunto1 = 5;
		conjunto2 = 3;
		if ((caracteres.search(String.fromCharCode(keypress))!=-1) && campo.value.length < (conjunto1 + separacoes + conjunto2)) {
			if (campo.value.length == conjunto1) campo.value = campo.value + separacao1;
		}
		else {
			event.returnValue=false;
		}
}


//UTILIZADO PARA RETIRAR OS ESPAÇOS À ESQUERDA E À DIREITA 
//RESOLVE O PROBLEMA DE PREENCHIMENTO DOS CAMPOS COM ESPAÇO
jQuery.fn.trimFields = function(){
	this.each(function(){
		$(this).val( jQuery.trim($(this).val()) );
	});
}


jQuery.fn.counter = function(qtde) {
  $(this).each(function() {
	var max = qtde;
	var val = $(this).attr('value');
	var cur = 0;
	if(val) //value="", or no value at all will cause an error
	cur = val.length;

	var left = max-cur;
	$(this).after("<div class='counter'>" + left.toString() + " caracteres restantes" + "</div>");
		
		$(this).keyup(function(i) {
		  var max = qtde;
		  var val = $(this).attr('value');
		  var cur = 0;
		  if(val)
		  cur = val.length;
		  var left = max-cur;
			if(left <= 3){
				$('.counter').css('color', '#ff0000');
			} else {
				$('.counter').css('color', '#666666');
			}
			if(left<1){
				$(this).val( ($(this).val()).substring(0,qtde) );	
			}
			if(left>=0){
				$(this).next(".counter").text(left.toString() + " caracteres restantes");
			}
		  return this;
		});
	
  });
  return this;
}


// Utility function to trim spaces from both ends of a string
function Trim(inString) {
  var retVal = "";
  var start = 0;
  while ((start < inString.length) && (inString.charAt(start) == ' ')) {
	++start;
  }
  var end = inString.length;
  while ((end > 0) && (inString.charAt(end - 1) == ' ')) {
	--end;
  }
  retVal = inString.substring(start, end);
  return retVal;
}


// Mostra uma mensagem de sucesso/erro/informação. 
//Mensagem de Erro: classe = error
//Mensagem de Sucesso: classe = success
//Mensagem de Alerta: classe = warn
msgInfo = function(msg, classe) {
	if(classe==null || classe==""){
		var classe = "warn";
	}
	var div = document.createElement('div');
	$(div).addClass(classe+' top').attr("id", "mensagem").html(msg).appendTo('#alertas').css('margin-top', '-45px');
	$('.contendo').prepend($(div)).focus();

	$('#mensagem.top').animate({
		marginTop: "0px"
	}).focus();

}

limparMensagens = function() {
	$("#alertas div").each(function() {
		$(this).remove();
	});
	
	$("#alertas").hide();
}

function BrowserDetector(ua) {
// Defaults
  this.browser = "Unknown";
  this.platform = "Unknown";
  this.version = "";
  this.majorver = "";
  this.minorver = "";

  uaLen = ua.length;

// ##### Split into stuff before parens and stuff in parens
  var preparens = "";
  var parenthesized = "";

  i = ua.indexOf("(");
  if (i >= 0) {
	preparens = Trim(ua.substring(0,i));
		parenthesized = ua.substring(i+1, uaLen);
		j = parenthesized.indexOf(")");
		if (j >= 0) {
		  parenthesized = parenthesized.substring(0, j);
		}
  }
  else {
	preparens = ua;
  }

// ##### First assume browser and version are in preparens
// ##### override later if we find them in the parenthesized stuff
  var browVer = preparens;

  var tokens = parenthesized.split(";");
  var token = "";
// # Now go through parenthesized tokens
  for (var i=0; i < tokens.length; i++) {
	token = Trim(tokens[i]);
		//## compatible - might want to reset from Netscape
		if (token == "compatible") {
		  //## One might want to reset browVer to a null string
		  //## here, but instead, we'll assume that if we don't
		  //## find out otherwise, then it really is Mozilla
		  //## (or whatever showed up before the parens).
		//## browser - try for Opera or IE
	}
		else if (token.indexOf("MSIE") >= 0) {
	  browVer = token;
	}
	else if (token.indexOf("Opera") >= 0) {
	  browVer = token;
	}
		//'## platform - try for X11, SunOS, Win, Mac, PPC
	else if ((token.indexOf("X11") >= 0) || (token.indexOf("SunOS") >= 0) ||
			(token.indexOf("Linux") >= 0)) {
	  this.platform = "Unix";
		}
	else if (token.indexOf("Win") >= 0) {
	  this.platform = token;
		}
	else if ((token.indexOf("Mac") >= 0) || (token.indexOf("PPC") >= 0)) {
	  this.platform = token;
		}
  }

  var msieIndex = browVer.indexOf("MSIE");
  if (msieIndex >= 0) {
	browVer = browVer.substring(msieIndex, browVer.length);
  }

  var leftover = "";
  if (browVer.substring(0, "Mozilla".length) == "Mozilla") {
	this.browser = "Netscape";
		leftover = browVer.substring("Mozilla".length+1, browVer.length);
  }
  else if (browVer.substring(0, "Lynx".length) == "Lynx") {
	this.browser = "Lynx";
		leftover = browVer.substring("Lynx".length+1, browVer.length);
  }
  else if (browVer.substring(0, "MSIE".length) == "MSIE") {
	this.browser = "IE";
	leftover = browVer.substring("MSIE".length+1, browVer.length);
  }
  else if (browVer.substring(0, "Microsoft Internet Explorer".length) ==
"Microsoft Internet Explorer") {
	this.browser = "IE"
		leftover = browVer.substring("Microsoft Internet Explorer".length+1,
browVer.length);
  }
  else if (browVer.substring(0, "Opera".length) == "Opera") {
	this.browser = "Opera"
	leftover = browVer.substring("Opera".length+1, browVer.length);
  }

  leftover = Trim(leftover);

  // # Try to get version info out of leftover stuff
  i = leftover.indexOf(" ");
  if (i >= 0) {
	this.version = leftover.substring(0, i);
  }
  else
  {
	this.version = leftover;
  }
  j = this.version.indexOf(".");
  if (j >= 0) {
	this.majorver = this.version.substring(0,j);
	this.minorver = this.version.substring(j+1, this.version.length);
  }
  else {
	this.majorver = this.version;
  }

} // function BrowserCap


function goPage(url){
//	$("#form-filtro").attr("action",url)
//	$("#form-filtro").submit();
//	console.dir(url);
	windon.location = url;
}

function contadorCaracter(caixaDeTexto, contador, qtde){
	var length = $(caixaDeTexto).val().length;
	
	if(length>qtde){
		$(caixaDeTexto).val( $(caixaDeTexto).val().substr(0, qtde) );
		$(contador).html(0);
		
	}else{
		$(contador).html(qtde - length);
	}
}

function strtr(str, from, to) {
	//  discuss at: http://phpjs.org/functions/strtr/
	// original by: Brett Zamir (http://brett-zamir.me)
	//    input by: uestla
	//    input by: Alan C
	//    input by: Taras Bogach
	//    input by: jpfle
	// bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: Brett Zamir (http://brett-zamir.me)
	// bugfixed by: Brett Zamir (http://brett-zamir.me)
	//  depends on: krsort
	//  depends on: ini_set
	//   example 1: $trans = {'hello' : 'hi', 'hi' : 'hello'};
	//   example 1: strtr('hi all, I said hello', $trans)
	//   returns 1: 'hello all, I said hi'
	//   example 2: strtr('äaabaåccasdeöoo', 'äåö','aao');
	//   returns 2: 'aaabaaccasdeooo'
	//   example 3: strtr('ääääääää', 'ä', 'a');
	//   returns 3: 'aaaaaaaa'
	//   example 4: strtr('http', 'pthxyz','xyzpth');
	//   returns 4: 'zyyx'
	//   example 5: strtr('zyyx', 'pthxyz','xyzpth');
	//   returns 5: 'http'
	//   example 6: strtr('aa', {'a':1,'aa':2});
	//   returns 6: '2'

	var fr = '',
    	i = 0,
    	j = 0,
    	lenStr = 0,
    	lenFrom = 0,
    	tmpStrictForIn = false,
    	fromTypeStr = '',
    	toTypeStr = '',
    	istr = '';
	var tmpFrom = [];
	var tmpTo = [];
	var ret = '';
	var match = false;

	// Received replace_pairs?
	// Convert to normal from->to chars
	if (typeof from === 'object') {
		// Not thread-safe; temporarily set to true
		tmpStrictForIn = this.ini_set('phpjs.strictForIn', false);
		from = this.krsort(from);
		this.ini_set('phpjs.strictForIn', tmpStrictForIn);

		for (fr in from) {
			if (from.hasOwnProperty(fr)) {
				tmpFrom.push(fr);
				tmpTo.push(from[fr]);
			}
		}

		from = tmpFrom;
		to = tmpTo;
	}

	// Walk through subject and replace chars when needed
	lenStr = str.length;
	lenFrom = from.length;
	fromTypeStr = typeof from === 'string';
	toTypeStr = typeof to === 'string';

	for (i = 0; i < lenStr; i++) {
    	match = false;
    	if (fromTypeStr) {
			istr = str.charAt(i);
    		for (j = 0; j < lenFrom; j++) {
        		if (istr == from.charAt(j)) {
					match = true;
					break;
				}
			}
		} else {
			for (j = 0; j < lenFrom; j++) {
        		if (str.substr(i, from[j].length) == from[j]) {
					match = true;
        			// Fast forward
        			i = (i + from[j].length) - 1;
        			break;
				}
			}
		}
    	if (match) {
			ret += toTypeStr ? to.charAt(j) : to[j];
		} else {
			ret += str.charAt(i);
		}
	}

	return ret;
}

arrumaOBotaoDeAcoes = function(elem){	
	if(elem==undefined) elem = '';
	
	$(elem + '.dataTable .btn-group .dropdown-toggle').each(function(){
		$(this).closest('.btn-group').css('position', 'absolute').after( $(this).clone() );
	});
}