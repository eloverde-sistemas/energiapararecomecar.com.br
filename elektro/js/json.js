limparSelect = function(selectID) {
	$(selectID + " option").each(function() {
		$(this).remove();
	});
};

/*
	destinoElem -> Campo que receberá os options
	acaoJson	-> Nome da função que será executada no json
	paramsJson	-> Parametros a serem passado para o json
	objSel		-> Id do item a ser selecionado
	
*/
carregaGerericOption = function(destinoElem, acaoJson, paramsJson, itemSel){
	limparSelect(destinoElem);
	$(destinoElem).closest('li').addClass('hide');

	var objSel = null;
	if( itemSel>0 ){
		objSel = itemSel;
	}
	
	$.ajax({
			url: '/inc/genericoJSON.php',
			type: 'post',
			data: { 
					acao	: acaoJson,
					params	: paramsJson
			},
			cache: false,
			success: function(data) {
				if(data.status==false){
					alert(data.message);
				}else{
					//console.dir(data.sql);
					
					//Adiciona o select com o nome do campo e valor vazio
					$('<option value="">' + data.selName + '</option>').appendTo($(destinoElem));
					
					//Popula os option
					if( Array.isArray(data.option) ){
						for(var i = 0; i < data.option.length; i++) {	
							if( objSel == data.option[i].optionValue ){
								$('<option value="' + data.option[i].optionValue + '" selected="selected" >' + data.option[i].optionDisplay + '</option>').appendTo($(destinoElem));
							}else{
								$('<option value="' + data.option[i].optionValue + '">' + data.option[i].optionDisplay + '</option>').appendTo($(destinoElem));
							}
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

bannerClique = function(id_banner, id_posicao){
	$.ajax({
			url: '/inc/genericoJSON.php',
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


formataDinheiroJson = function(valor){
	var valor = valor;
	
	$.ajax({
			url: 'inc/genericoJSON.php',
			type: 'post',
			data: { 
					acao    :'formataDinheiro',
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
