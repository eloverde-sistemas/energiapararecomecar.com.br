limparSelect = function(selectID) {
	$(selectID + " option").each(function() {
		$(this).remove();
	});
};

carregaCidadesPeloEstado = function(destinoElem, estadoVal, cidadeSel){
	limparSelect(destinoElem);
	$(destinoElem).closest('div[class^="col-"]').addClass('hide');
	
	$.ajax({
			url: 'inc/genericoJSON.php',
			type: 'post',
			data: { 
					acao	:'consultaCidadesPeloEstado',
					estado	:estadoVal
			},
			cache: false,
			success: function(data) {
				if(data.status==false){
					alert('Erro ao carregar as Cidades do Estado.');
				}else{
					$('<option value=""></option>').appendTo($(destinoElem));
					for(var i = 0; i < data.length; i++) {	
						if( cidadeSel == data[i].optionValue ){
							$('<option value="' + data[i].optionValue + '" selected="selected" >' + data[i].optionDisplay + '</option>').appendTo($(destinoElem));
						}else{
							$('<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>').appendTo($(destinoElem));
						}
					}
					$(destinoElem).closest('div[class^="col-"]').removeClass('hide');
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest.responseText);
			},
			dataType: 'json'
	});
	return false;
}

/*
	destinoElem 	-> Campo da cidade qu vai receber os options
	cidadeVal		-> Cidade que ficará selecionada
	estadoSel 		-> Estado que será selecionado
*/

carregaCidadesPelaCidade = function(destinoElem, cidadeVal, estadoSel){
	limparSelect(destinoElem);
	$(destinoElem).closest('div').addClass('hide');

	var objSel = null;
	if( cidadeVal>0 ){
		objSel = cidadeVal;
	
		$.ajax({
				url: 'inc/genericoJSON.php',
				type: 'post',
				data: { 
						acao	:'consultaCidadesPelaCidade',
						cidade	:cidadeVal
				},
				cache: false,
				success: function(data) {
					
					if(data.status==false){
						alert('Erro ao carregar as Cidades do Estado.');
					}else{
						$('<option value=""></option>').appendTo($(destinoElem));
						
						for(var i = 0; i < data['option'].length; i++) {	
							if( objSel == data['option'][i].optionValue ){
								$('<option value="' + data['option'][i].optionValue + '" selected="selected" >' + data['option'][i].optionDisplay + '</option>').appendTo($(destinoElem));
								
							}else{
								$('<option value="' + data['option'][i].optionValue + '">' + data['option'][i].optionDisplay + '</option>').appendTo($(destinoElem));
							}
						}
						
						//Se for passado o estado
						if(estadoSel.length>0){
							$(estadoSel + " option").attr('selected', false);
							$(estadoSel + " option[value='" + data['estado'] + "']").attr('selected', true);
						}
						
						$(destinoElem).closest('div').removeClass('hide');
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert(XMLHttpRequest.responseText);
				},
				dataType: 'json'
		});
	}
	
	return false;
}

/*
	destinoElem -> Campo que receberá os options
	acaoJson	-> Nome da função que será executada no json
	paramsJson	-> Parametros a serem passado para o json
	objSel		-> Id do item a ser selecionado
	
*/
carregaGerericOption = function(destinoElem, acaoJson, paramsJson, itemSel, funcao){
	limparSelect(destinoElem);
	$(destinoElem).closest('div[class~="col-"]').addClass('hide');
	
	var objSel = null;
	if( itemSel>0 ){
		objSel = itemSel;
	}

	$.ajax({
		url: 'inc/genericoJSON.php',
		type: 'post',
		data: {
				acao	: acaoJson,
				params	: paramsJson
		},
		cache: false,
		success: function(data) {			

			if(data.status==false){
				alert("Erro");
			}else{
				
				if(data.selName!='' && data.selName!=null){
					//Adiciona o select com o nome do campo e valor vazio
					$('<option value="">' + data.selName + '</option>').appendTo($(destinoElem));
				}
				
				//Popula os option
				if( Array.isArray(data.option) ){
					for(var i = 0; i < data.option.length; i++){
					
					//->Caso seja passada atributos extras
						var attr = null;
						for(var key in data.option[i].attr) {
							attr+= ' data-' + key + '="' + data.option[i].attr[key] + '"';
						}
					//<-Caso seja passada atributos extras
					
						if( objSel == data.option[i].optionValue ){
							$('<option value="' + data.option[i].optionValue + '" selected="selected" ' + attr + '>' + data.option[i].optionDisplay + '</option>').appendTo($(destinoElem));
						}else{
							$('<option value="' + data.option[i].optionValue + '" ' + attr + '>' + data.option[i].optionDisplay + '</option>').appendTo($(destinoElem));
						}
					}
				}
				
				$(destinoElem).closest('div[class^="col-"]').removeClass('hide');
				
				if( jQuery.isFunction(funcao) )
					funcao();
			}
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			console.dir(errorThrown);			
		},
		dataType: 'json'
	});
	return false;
}

bannerClique = function(id_banner, id_posicao){
	$.ajax({
			url: 'inc/genericoJSON.php',
			type: 'post',
			data: { 
					acao		: 'bannerClique',
					id_banner	: id_banner,
					id_posicao	: id_posicao
			},
			cache: false,
			async: false,
			success: function(data) {
				//console.dir(data.status);
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
			//	console.dir(textStatus);
			//	console.dir(XMLHttpRequest);
				alert(XMLHttpRequest.responseText);
			},
			dataType: 'json'
	});
}

mudaCampanhaAcesso = function(loja){
	$.ajax({
			url: 'inc/genericoJSON.php',
			type: 'post',
			data: { 
					acao : 'mudaCampanhaAcesso',
					campanha : loja
			},
			cache: false,
			async: false,
			success: function(data) {
				window.location.reload()
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
			//	console.dir(textStatus);
			//	console.dir(XMLHttpRequest);
				alert(XMLHttpRequest.responseText);
			},
			dataType: 'json'
	});
}

getEventsCalendar = function (data){
	var dados;
	
	$.ajax({
			url: 'inc/genericoJSON.php',
			type: 'post',
			data: { 
					acao    :'getEventsCalendar',
					ano 	:data.ano,
					mes 	:data.mes
			},
			cache: false,
			async: false,
			success: function(data) {
				dados = data;
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert('Error: ' + XMLHttpRequest.responseText);
			},
			dataType: 'json'
	});
	return dados;
	
}

formataDinheiroJson = function(valor, simbolo){
	if(valor == "" || valor == null || valor == "undefined"){
		var valor = 0;
	}
	$.ajax({
			url: 'inc/genericoJSON.php',
			type: 'post',
			data: { 
				acao    :'formataDinheiro',
				simbolo :simbolo,
				valor 	:valor
			},
			cache: false,
			async: false,
			success: function(data) {
				valor = data.valor;
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert('Error: ' + XMLHttpRequest.responseText);
			},
			dataType: 'json'
	});
	return valor;
}

formataValorParaBancoJson = function(valor){
	if(valor == "" || valor == null || valor == "undefined"){
		var valor = 0;
	}
	$.ajax({
		url: 'inc/genericoJSON.php',
		type: 'post',
		data: { 
			acao    :'formataValorParaBanco',
			valor 	:valor
		},
		cache: false,
		async: false,
		success: function(data) {
			valor = data.valor;
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert('Error: ' + XMLHttpRequest.responseText);
		},
		dataType: 'json'
	});
	return valor;
}

carregaMilitarPostoPorMilitarLocal = function(destinoElem, militarLocalVal, militarPostoSel){
	limparSelect(destinoElem);
	//$(destinoElem).closest('li').addClass('hide');
	
	$.ajax({
			url: 'inc/genericoJSON.php',
			type: 'post',
			data: { 
					acao	:'consultaMilitarPostoPorMilitarLocal',
					local	:militarLocalVal
			},
			cache: false,
			success: function(data) {
				if(data.status==false){
					alert('Erro ao carregar.');
				}else{
					$('<option value=""></option>').appendTo($(destinoElem));
					for(var i = 0; i < data.length; i++) {	
						if( militarPostoSel == data[i].optionValue ){
							$('<option value="' + data[i].optionValue + '" selected="selected" >' + data[i].optionDisplay + '</option>').appendTo($(destinoElem));
						}else{
							$('<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>').appendTo($(destinoElem));
						}
					}
					$(destinoElem).closest('li').removeClass('hide');
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest.responseText);
			},
			dataType: 'json'
	});
	return false;
}

encerrarCampanhaAcesso = function(){
	$.ajax({
			url: 'inc/genericoJSON.php',
			type: 'post',
			data: { 
					acao : 'encerrarCampanhaAcesso'
			},
			cache: false,
			async: false,
			success: function(data) {
				window.location.reload()
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
			//	console.dir(textStatus);
			//	console.dir(XMLHttpRequest);
				alert(XMLHttpRequest.responseText);
			},
			dataType: 'json'
	});
}