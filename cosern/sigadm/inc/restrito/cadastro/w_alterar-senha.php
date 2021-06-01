    <h2><?=$translate->translate('minhas_informacoes')?> / <?=$translate->translate("alterar_senha")?></h2>
    
    <div class="clear">&nbsp;</div>
    
    <form method="post">
        <input type="hidden" name="id" id="id" value="<?=$_SESSION['pessoaId']?>">
        
        <table class="table table-striped table-bordered table-hover" style="color:#000; !important">
            <tr>
                <th><?=$translate->translate("nova_senha")?></th> 
                <th><?=$translate->translate("confirme_senha")?></th>
                <th>&nbsp;</th>
            </tr>
            
            <tr>
                <td valign="top">
                    <input type="password" name="senhaNova" id="senhaNova" class="form-control"  size="20" maxlength="25" onKeyUp="testaSenha(this.value);" />
                    <br /><span id='seguranca'></span><br />
                </td>
                <td valign="top">
                    <div id="senhaNovaOculta">
                        <input type="password" name="senhaNovaRep"  id="senhaNovaRep" class="form-control" size="20" maxlength="25" />
                        <br /> <br />
                        <button type="reset" class="btn red"><?=$translate->translate("bt_limpar")?></button>
                        <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate("bt_salvar")?></button>                            
                    </div>
                </td>
                <td>
                    <strong><?=$translate->translate("as_texto_1")?></strong><br />
                    <?=$translate->translate("as_texto_2")?><br />
                    <?=$translate->translate("as_texto_3")?><br />
                    <?=$translate->translate("as_texto_4")?><br /><br />
                    <strong style="color:#990000;"><?=$translate->translate("as_texto_5")?></strong>
                </td>
            </tr>	

        </table>
        

    </form>
        
<script>
				
	function verCaracterDaSenha(valor) {
	
	  var erespeciais = /[@!#$%¨&*()+=:;<>?|-]/;
	  var ermaiuscula = /[A-Z]/;
	  var erminuscula = /[a-z]/;
	  var ernumeros   = /[0-9]/;
	  var cont = 0;
	  
	  if (erespeciais.test(valor)) cont++;
	  if (ermaiuscula.test(valor)) cont++;
	  if (erminuscula.test(valor)) cont++;
	  if (ernumeros.test(valor))   cont++;
	  return cont;
	}					


	function testaSenha(valor) {
		  var d = document.getElementById('seguranca');
		  var c = verCaracterDaSenha(valor);
		  var t = valor.length;
		  var frase = "<b><?=$translate->translate("as_forca_senha")?></b>";
		
		  //console.dir('teste');
		  if(t > 7 && c >= 3){
			frase += "<span style='color: green; font-weight:bold;'><?=$translate->translate("as_forca_senha_alta")?></span>";
			document.getElementById('senhaNovaOculta').style.display='';			
		  }else if(t > 7 && c >= 2 || t > 7 && c >= 3){
					frase += "<span style='color: orange; font-weight:bold;'><?=$translate->translate("as_forca_senha_media")?></span>";
					document.getElementById('senhaNovaOculta').style.display='';					
				}else{
					frase += "<span style='color: red; font-weight:bold;'><?=$translate->translate("as_forca_senha_baixa")?></span>";
					//document.getElementById('senhaNovaOculta').style.display='none';					
				}
		  d.innerHTML = frase;
	}


	

	$(function(){
		
		$("#senhaNova").focus();
		
		$("form").submit( function() { 

			var senhaNova = $("#senhaNova");
			if ( senhaNova.val()=='' ){
				alert('<?=$translate->translate("as_informe_senha")?>');
				return false;
			}

			var senhaNovaRep = $("#senhaNovaRep");
			if (senhaNovaRep.attr('disabled')==true){
				msgInfo('<?=$translate->translate("as_mensagem_2")?>');
				senhaNova.focus();
				return false;
			}
			
			if (senhaNovaRep.val() == ""){
				msgInfo('<?=$translate->translate("as_mensagem_3")?>');
				senhaNovaRep.focus();
				return false;
			}
			
			if (senhaNova.val() != senhaNovaRep.val()){
				msgInfo('<?=$translate->translate("as_mensagem_4")?>');
				senhaNovaRep.focus();
				return false;
			}
			
			$.ajax({
					url: '/inc/genericoJSON.php',
					type: 'post',
					data: { 
							acao 		: 'usuarioAlterarSenha', 
							pessoaId 	: $("#id").val(),
							nova		: $("#senhaNova").val()
					},
					cache: false,
					success: function(data) {						
						if(data.status==true){
							window.location = '/restrito/cadastro/dados';
						}else{
							location.reload();
						}
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						alert(XMLHttpRequest.responseText);
					},
					dataType: 'json'
			});
			return false;
		});
		
	});
</script>