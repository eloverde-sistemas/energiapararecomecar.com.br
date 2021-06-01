	<style>
		.negcenter{
			text-align:center;
			font-weight:bold;
		}
		.centro{
			text-align:center;
		}
		.neg{
			font-weight:bold;
		}
		.direita{
			text-align:right;
			padding-right:5px;
		}
	</style>

<?

$idCampanha = $_GET['id'];

$campanha = executaSQL("SELECT * FROM evento WHERE id= '".$idCampanha."' ");
if( nLinhas($campanha)>0 ){
	//CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo
	$campanha = objetoPHP($campanha);


	$lojasPart		= executaSQL("SELECT l.id, l.nome_fantasia, l.cnpj FROM loja l, evento_loja el WHERE el.id_loja=l.id AND el.id_evento='".$campanha->id."' ORDER BY nome_fantasia");

?>

	<h1 class="centro"><?=$campanha->titulo?> - <?=$translate->translate('correlacao_loja')?></h1>

	
		<h2><?=$translate->translate('lojas')?></h2>

<?					
				if(nLinhas($lojasPart)>0){ ?>
							
				<table width="690" border="1" cellpadding="2" cellspacing="0">
					<tr>
						<th class="text-center"><?=$translate->translate("loja")?></th>
						<th class="text-center"><?=$translate->translate("correlacoes")?></th>
					</tr>
				
<?					$x=0;
					$primeira = false;
					
					while($loja = objetoPHP($lojasPart)){ ?>
					
							<tr>
								<td><?=$loja->nome_fantasia?> (<?=$loja->cnpj?>)</td>
								<td>
								<?
									$cuponsAdd = 0;
									
									$partCorrelacionados = executaSQL("SELECT DISTINCT(c.id_participante) as idPart
																			FROM participante_cupom c, loja l
																			WHERE c.id_evento='".$campanha->id."'
																			AND c.cnpj = l.cnpj 
																			AND REPLACE(REPLACE(REPLACE(l.cnpj, '.', ''), '/', ''), '-', '')=REPLACE(REPLACE(REPLACE('".$loja->cnpj."', '.', ''), '/', ''), '-', '')
																			AND c.id_situacao IN (2,90)");
									if(nLinhas($partCorrelacionados)>0){ 
										$correlacao = "";
										$arrayExiste = array();
										while($partCorrelacionado = objetoPHP($partCorrelacionados)){ 
										
											$cuponsCorrelacionados = executaSQL("SELECT l.nome_fantasia, l.cnpj, REPLACE(REPLACE(REPLACE(l.cnpj, '.', ''), '/', ''), '-', '') as cnpjFormatado
																				FROM participante_cupom c, loja l
																				WHERE c.id_evento='".$campanha->id."'
																				AND c.cnpj = l.cnpj 
																				AND REPLACE(REPLACE(REPLACE(c.cnpj, '.', ''), '/', ''), '-', '')<>REPLACE(REPLACE(REPLACE('".$loja->cnpj."', '.', ''), '/', ''), '-', '')
																				AND c.id_participante='".$partCorrelacionado->idPart."'
																				AND c.id_situacao IN (2,90)");
																			
											if(nLinhas($cuponsCorrelacionados)>0){ ?>
													
<?													while($cupomCorrelacionado = objetoPHP($cuponsCorrelacionados)){ 
														
														//var_dump($arrayExiste);

														if( !in_array($cupomCorrelacionado->cnpjFormatado, $arrayExiste) ){
															$arrayExiste[] = $cupomCorrelacionado->cnpjFormatado;
															//echo "<BR><strong>ACHO</strong>: ".$cupomCorrelacionado->cnpj;
															$correlacao .= $cupomCorrelacionado->nome_fantasia." (".$cupomCorrelacionado->cnpj.") <br />";
														}
													} ?>

<?													

											} ?>
										
<?										}

										echo $correlacao;
									} ?>
								</td>
							</tr>

			<?			$x++;
					} ?>
						
<?				}else{ ?>
					
					<tr>
						<td colspan="5"><?=$translate->translate("msg_nenhum_menu_encontrado_evento")?></td>
					</tr>

			<?	} ?>

			
		</table>

<?	} ?>