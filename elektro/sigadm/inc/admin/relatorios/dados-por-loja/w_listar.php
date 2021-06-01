<?
	echo $idCampanha = $_GET['id'];
	echo "<br>Loja: ";
	echo $idLoja 	= $_GET['id2'];

?>

	<h3 class="page-title">
		<?=$translate->translate('dados_por_loja')?> <small><?=$translate->translate('listagem')?></small>
	</h3>
	<div class="page-bar">
		<ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('relatorios')?>
                <i class="fa fa-angle-right"></i>
            </li>

            <li>
                <?=$translate->translate('dados_por_loja')?>
            </li>
        </ul>
    </div>
	
