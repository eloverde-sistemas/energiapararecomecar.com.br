<?

	$regs = executaSQLPadrao('evento', "1=1 ORDER BY titulo");



	if(nLinhas($regs)>0){

?>

		<br /><br />

		<label for="buscaLoja" class="text-right">

			<strong><?=$translate->translate("buscar")?></strong>: <input type="text" id="buscaLoja" style="width:100px">

		</label>

		

        <div class="portlet box grey-cascade">

            <div class="portlet-title">

                <div class="caption">

                    <?=$translate->translate("campanhas")?>

                </div>

                <div class="tools"></div>

            </div>

            <div class="portlet-body">

                <table class="table table-striped table-bordered table-hover list" id="lojasAcesso">

                    <thead>

                        <tr>

                            <th><?=$translate->translate("nome")?></th>

                        </tr>

                    </thead>

                    <tbody>

					<?

                        while($reg = objetoPHP($regs)){

                    ?>

                            <tr>

                                <td>

                                    <a href="#" class="void" onclick="mudaCampanhaAcesso(<?=$reg->id?>)"><?=$reg->titulo?></a>

                                </td>

                            </tr>

                    <?

                        }

                    ?>

                    </tbody>

                </table>

        	</div>

		</div>

<?

	}

?>



<script>

	$(document).ready(function(){

		$('#buscaLoja').bind("blur keyup", function(){

			busca = this.value;

			elem = $('#lojasAcesso td > a');

			

			if(busca.length>0){

			//	Esconde todas as categorias

				elem.closest('tr').hide();

			

			//	Passa por todas as categorias

				elem.each(function(){

				//	Mostra as categorias que contm o termo da busca

					if( $(this).html().toLowerCase().indexOf(busca.toLowerCase())>=0 ){

						$(this).closest('tr').show();

					}

					

				});

				

			}else{

				elem.closest('tr').show();

			}

		}).focus();

	});

</script>