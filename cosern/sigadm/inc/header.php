<!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
					
                	<h3 class="font-red-intense spacer-10"><img src="images/lycx-logo.png" width="25" border='0'/> <span class="text-white">LYCX</span></h3>
		<? /*/ ?>
                <img src="metronic/assets/admin/layout/img/logo.png" alt="logo" class="logo-default"/>
				<img src="images/logo-small.png" alt="logo" class="logo-default"/>
		<? /*/ ?>
                <div class="menu-toggler sidebar-toggler hide">
                    <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
					<!-- END TODO DROPDOWN -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="dropdown dropdown-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?	
							$user = consultaPessoaById($_SESSION['pessoaId'], array('thumb'));
							if($user){
								$nome = explode(' ', $_SESSION["pessoaNome"]);
								
								if( $user->thumb!='' && is_file("../".$user->thumb) ){ ?>
	                        		<img alt="<?=mb_strtoupper($nome[0])?>" title="<?=mb_strtoupper($nome[0])?>" class="img-circle" src="/<?=$user->thumb?>"/>
						<?		}else{ ?>
									<img alt="<?=mb_strtoupper($nome[0])?>" title="<?=mb_strtoupper($nome[0])?>" class="img-circle" src="/images/user.gif"/>
						<?		}
							} ?>
                        	<span class="username"><?=mb_strtoupper($nome[0])?></span>
                        	<i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
					<?	if( temPermissao('ADMIN_TROCAR-CAMPANHA') ){ ?>
                            <li>
                                <a href="#trocar-loja" data-toggle="modal">
                                    <i class="fa fa-exchange"></i> <?=$translate->translate('tt_trocar_loja_acesso')?> </a>
                                </a>
                            </li>
							
							<li>
								<a href="#" class="void" onclick="encerrarCampanhaAcesso()" data-toggle="modal">
									<i class="icon-key"></i> <?=$translate->translate('encerrar_campanha_acesso')?> </a>
								</a>
							</li>							
					<?	} ?>
                            <li>
                                <a href="/adm/restrito/cadastro/dados">
									<i class="fa fa-user"></i> <?=$translate->translate("meus_dados")?>
								</a>
                            </li>
                            <li>
                                <a href="/adm/alterar-senha">
                                	<i class="fa fa-cog"></i> <?=$translate->translate('alterar_senha')?>
                                </a>
                            </li>
                            <li>
                                <a href="/logout">
                                	<i class="icon-key"></i> <?=$translate->translate('logout')?>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    
    <div class="modal fade bs-modal-lg" id="trocar-loja" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><?=$translate->translate('tt_trocar_loja_acesso')?></h4>
                </div>
                <div class="modal-body">
                    <div class="row no-margin">
                        <div class="col-xs-12">
                            <? include('inc/trocar_campanha.php');?>
                        </div>
                    </div>
				</div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default"><?=$translate->translate('fechar')?></button>
                </div>
            </div>
        </div>
    </div>