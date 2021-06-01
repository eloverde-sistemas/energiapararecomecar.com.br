	var array_paises = new Array ();
	var array_estado = new Array ();
	var array_cidade = new Array ();
	var array_cidade_fora = new Array ();
	
	var troca_pais = '';
	var troca_estado = '';
	var troca_cidade = '';
	var troca_cidade_fora = '';
	
	// array(pais, estado, cidade, cidade_fora, id cidade selecionada)
	trocaEndereco = function(dados){
		var PAIS = 30, ESTADO = 24;
		
		
	//- Passa por todos itens passados
		for(x=0; x<dados.length; x++){
		
		//- Agrupa os campos do mesmo 'Tipo'
		
				array_paises.push(dados[x][0]);
				array_estado.push(dados[x][1]);
				array_cidade.push(dados[x][2]);
				array_cidade_fora.push(dados[x][3]);
	
		//- Agrupa os campos do mesmo 'Tipo'
		
		
			
		//- Faz uma string dos campos para utilizar nos Events do JS
		
				troca_pais 			+= (troca_pais.length>0 || x>0 ? ', ' : '') + dados[x][0];
				troca_estado 		+= (troca_estado.length>0 || x>0 ? ', ' : '') + dados[x][1];
				troca_cidade 		+= (x>0 ? ', ' : '') + dados[x][2];
				troca_cidade_fora	+= (x>0 ? ', ' : '') + dados[x][3];
		
		//- Faz uma string dos campos para utilizar nos Events do JS
		
		
		//-	Carrega as opções iniciais
				
				var pais_atual			= $(dados[x][0]);
				var estado_atual		= $(dados[x][1]);
				var cidade_atual 		= $(dados[x][2]);
				var cidade_fora_atual	= $(dados[x][3]);
				var cidade_seleciona	= dados[x][4];
			
				//Se o País for Brasil
				if( pais_atual.val()==PAIS){
					cidade_fora_atual.closest('div[class^="col-md-"]').addClass('hide');
					
					//Se a Cidade seleciona passada for maior que zero, carrega as Cidades e marca o Estado relacionados a mesma
					if(cidade_seleciona>0){
						carregaCidadesPelaCidade(dados[x][2], cidade_seleciona, dados[x][1]);
					}else{
						cidade_atual.closest('div[class^="col-md-"]').addClass('hide');
					}
					
				// Se for outro País mostra a Cidade Fora e esconde o Estado e a Cidade
				}else if(pais_atual.val()>0){
					cidade_atual.closest('div[class^="col-md-"]').addClass('hide');
					estado_atual.closest('div[class^="col-md-"]').addClass('hide');
					
				// Se o país e o estado não estão selecionados, seleciona os padrões
				}else if(!$(dados[x][0] + ' :selected').val()>0){
					//Brasil
					$(dados[x][0] + ' > option[value="' + PAIS + '"]').attr('selected', true);
					//Rio de janeiro
					$(dados[x][1] + ' > option[value="' + ESTADO + '"]').attr('selected', true);
					
					carregaCidadesPeloEstado(dados[x][2], ESTADO, '');
					
					pais_atual.closest('div[class^="col-md-"]').removeClass('hide');
					estado_atual.closest('div[class^="col-md-"]').removeClass('hide');
					cidade_atual.closest('div[class^="col-md-"]').removeClass('hide');
					cidade_fora_atual.closest('div[class^="col-md-"]').addClass('hide');
					
				// Senão esconde Estado/Cidade/Cidade fora
				}else{
					cidade_atual.closest('div[class^="col-md-"]').addClass('hide');
					cidade_fora_atual.closest('div[class^="col-md-"]').addClass('hide');
					estado_atual.closest('div[class^="col-md-"]').addClass('hide');
					
				}				
		//-	Fim Carrega as opções iniciais
			
		}
		
		
		//- Recarrega os Eventos
		
			//-	Mostra Estado/Cidade/Cidade fora -//
				$(troca_pais).bind('change', function(){
					//Marca qual select está sendo manejado
					var selec = null;
					
					for(x=0; x<array_paises.length; x++){
						if( array_paises[x]=='#'+this.id ){
							selec = x;
						}
					}
					
					//console.dir("Entrou: " + troca_pais);
					
					//Se 'achar' o item a ser manejado
					if(selec!=null){
					
						if( $(array_paises[selec]).val()==30){
							$(array_estado[selec]).closest('div[class^="col-md-"]').removeClass('hide');
							$(array_cidade_fora[selec]).closest('div[class^="col-md-"]').addClass('hide');
							
							//Mostra a cidade se tiver um estado selecionado
							if( $(array_cidade[selec]).val()>0 ) $(array_cidade[selec]).closest('div[class^="col-md-"]').removeClass('hide');
							
						}else if( $(array_paises[selec]).val()>0){
							$(array_estado[selec] + ', ' + array_cidade[selec]).closest('div[class^="col-md-"]').addClass('hide');
							$(array_cidade_fora[selec]).closest('div[class^="col-md-"]').removeClass('hide');
							
						}else{
							$(array_estado[selec] + ', ' + array_cidade[selec] + ', ' + array_cidade_fora[selec]).closest('div[class^="col-md-"]').addClass('hide');
						}
					
					}
					
				});
			//-	Fim Mostra Estado/Cidade/Cidade fora -//
				
			//-	Carrega as cidades -//
				$(troca_estado).change(function(){
					
					var selec = null;
					
					for(x=0; x<array_estado.length; x++){
						if( array_estado[x]=='#'+this.id ){
							selec = x;
						}
					}
	
					//Se 'achar' o item a ser manejado
					if(selec!=null){					
						carregaCidadesPeloEstado(array_cidade[selec], $(array_estado[selec]).val(), '');
					}
					
				});
			//-	Fim Carrega as cidades -//
		//- Fim recarrega Eventos
	}