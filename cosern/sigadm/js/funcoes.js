// JavaScript Document
msgInfo = function(container, place, type, mensagem, fechar, resetar, focar, segundos, icone){
	Metronic.alert({
		container: container, // alerts parent container(by default placed after the page breadcrumbs)
		place: place, // append or prepent in container 
		type: type,  // alert's type /// warning, danger, info, success
		message: mensagem,  // alert's message
		close: fechar, // make alert closable
		reset: resetar, // close all previouse alerts first
		focus: focar, // auto scroll to the alert after shown
		closeInSeconds: segundos, // auto close after defined seconds
		icon: icone // put icon before the message
	});
}

arrumaOBotaoDeAcoes = function(elem){	
	if(elem==undefined) elem = '';
	
	$(elem + '.dataTable .btn-group .dropdown-toggle').each(function(){
		$(this).closest('.btn-group').css('position', 'absolute').after( $(this).clone() );
	});
}

/*
	elementoTD = elemento da td que se encontra a situacao, obviamente
	classe = a classe que vai ser adicionada a TR
	situacao = a situacao necessaria para que a classe seja adicionada
*/
colocaClasseTR = function(elementoTD, classe, situacao){
	
	$(elementoTD).each(function(){
		var valor = $(this).html();
		if(valor==situacao){
			$(this).closest("tr").addClass(classe);
		}		
	});
	
}

/*
	data1		-> Primeira data da comparaçao
	data2		-> Segunda data da comparaçao, quando passado null, pega a data atual
	comparacao	-> Tipo de comparação entre as datas
*/
comparaDatas = function(data1, data2, comparacao){
	if( data1.toString().indexOf("/")>0 ){
		dt1 = data1.split('/');
		data1 = dt1[2]+dt1[1]+dt1[0];
	}else{
		data1 = data1.replace(/-/g, '');
	}
	
	if(data2==null){
		data2 = new Date().toISOString().slice(0, 10).replace(/-/g, '');
	}else if( data2.toString().indexOf("/")>0 ){
		dt2 = data2.split('/');
		data2 = dt2[2]+dt2[1]+dt2[0];
	}else{
		data2 = data2.replace(/-/g, '');
	}
	
	if(comparacao=="==")
		return data1 == data2 ? true : false;
	else if(comparacao=="<")
		return data1 < data2 ? true : false;
	else if(comparacao=="<=")
		return data1 <= data2 ? true : false;
	else if(comparacao==">")
		return data1 > data2 ? true : false;
	else if(comparacao==">=")
		return data1 >= data2 ? true : false;
}