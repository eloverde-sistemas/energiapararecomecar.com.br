<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU .page-sidebar-menu-hover-submenu -->
        <ul class="page-sidebar-menu" data-auto-scroll="false" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
			<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
            <li class="sidebar-search-wrapper hidden-xs hide">
                <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                <form class="sidebar-search" action="extra_search.html" method="POST">
                    <a href="javascript:;" class="remove">
                    <i class="icon-close"></i>
                    </a>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                        <a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
                        </span>
                    </div>
                </form>
				<!-- END RESPONSIVE QUICK SEARCH FORM -->
            </li>


            <li class="start active spacer-10">
                <a href="/adm/inicio">
                	<i class="fa fa-home"></i>
                    <span class="title"><?=$translate->translate('dashboard')?></span>
                	<?=$_GET['pagina']=='inicio' || $_GET['pagina']=='' ? '<span class="selected"></span>' : NULL?>
                </a>
            </li>
        
            <li>
                <a href="javascript:;">
                	<i class="fa fa-user"></i>
                    <span class="title"><?=$translate->translate('minhas_informacoes')?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                	<li>
                        <a href="/adm/restrito/cadastro/dados/<?=$_SESSION['pessoaId']?>"><?=$translate->translate("meus_dados")?></a>
                    </li>
                	<li>
                        <a href="/adm/alterar-senha"><?=$translate->translate("alterar_senha")?></a>
                    </li>                  
                </ul>
            </li>

<?		if( temPermissao( array('ADMIN_LOJAS_LISTAR') )  ){ ?>    
            <li>
                <a href="/adm/admin/lojas/listar">
                    <i class="fa fa-group"></i>
                    <span class="title">
                        <?=$translate->translate('lojas')?>
                    </span>
                </a>
            </li>
<?		} ?>

<?		if( temPermissao( array('ADMIN_EVENTOS_LISTAR') )  ){ ?>    
            <li>
                <a href="/adm/admin/eventos/listar">
                    <i class="fa fa-calendar"></i>
                    <span class="title">
                        <?=$translate->translate('eventos')?>
                    </span>
                </a>
            </li>
<?		} ?>

<?		if( temPermissao( array('ADMIN_PARTICIPANTES_LISTAR') )  ){ ?>    
            <li>
                <a href="/adm/admin/participantes/listar">
                    <i class="fa fa-users"></i>
                    <span class="title">
                        <?=$translate->translate('participantes')?>
                    </span>
                </a>
            </li>
<?		} ?>

<?		if( temPermissao( array('ADMIN_MENU_LISTAR') )  ){ ?>    
            <li>
                <a href="/adm/admin/menu/listar">                    
                    <i class="fa fa-bars"></i>
                    <span class="title">
                        <?=$translate->translate('menu')?>
                    </span>
                </a>
            </li>
<?		} ?>

<?		if( temPermissao( array('ADMIN_FAQ_LISTAR') )  ){ ?>    
            <li>
                <a href="/adm/admin/faq/listar">
                    <i class="fa fa-comments"></i>
                    <span class="title">
                        <?=$translate->translate('faq')?>
                    </span>
                </a>
            </li>
<?		} ?>

<?		if( temPermissao( array('ADMIN_NOTICIAS_LISTAR') )  ){ ?>    
            <li>
                <a href="/adm/admin/noticias/listar">                    
                    <i class="fa fa-file-text"></i>
                    <span class="title">
                        <?=$translate->translate('noticias')?>
                    </span>
                </a>
            </li>
<?		} ?>

<?		if( temPermissao( array('ADMIN_BANNERS_LISTAR') )  ){ ?>    
            <li>
                <a href="/adm/admin/banners/listar">                    
                    <i class="fa fa-image"></i>
                    <span class="title">
                        <?=$translate->translate('banners')?>
                    </span>
                </a>
            </li>
<?		} ?>

<?		if( temPermissao( array('ADMIN_MIDIA_LISTAR') )  ){ ?>    
            <li>
                <a href="/adm/admin/midia/listar">                    
                    <i class="fa fa-file"></i>
                    <span class="title">
                        <?=$translate->translate('midias')?>
                    </span>
                </a>
            </li>
<?		} ?>

<?		if( temPermissao( array('ADMIN_SORTEIO-LOTERIA_LISTAR') )  ){ ?>    
            <li>
                <a href="/adm/admin/sorteio-loteria/listar">                    
                    <i class="fa fa-sort-numeric-asc"></i>
                    <span class="title">
                        <?=$translate->translate('sorteios_loteria')?>
                    </span>
                </a>
            </li>
<?		} ?>


<?		if( temPermissao( array('ADMIN_USUARIOS_LISTAR') ) || temPermissao( array('ADMIN_PERMISSOES_LISTAR') ) || temPermissao( array('ADMIN_PERFIS_LISTAR') ) || temPermissao( array('ADMIN_ATUALIZACAO_LISTAR') )  ){ ?>    
            <li>
                <a href="javascript:;">
                    <i class="fa fa-key"></i>
                    <span class="title"><?=$translate->translate('ml_seguranca')?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="/adm/admin/atualizacao/listar"><?=$translate->translate("atualizacao_base")?></a>
                    </li>
                    <li>
                        <a href="/adm/admin/usuarios/listar"><?=$translate->translate("ml_usuarios")?></a>
                    </li>
                    <li>
                        <a href="/adm/admin/permissoes/listar"> <?=$translate->translate("ml_permissoes")?> </a>
                    </li>
                    <li>
                        <a href="/adm/admin/perfis/listar"> <?=$translate->translate("ml_perfis")?> </a>
                    </li>
                
                </ul>
            </li>
<?		} ?>

        </ul>
		<!-- END SIDEBAR MENU -->
    </div>
</div>
<!-- END SIDEBAR -->