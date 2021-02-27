<?php use App\Helpers; ?>
<!--sidebar start-->
<aside id="aside" class="ui-aside">
    <ul class="nav" ui-nav>
        <li><a href="<?php echo url('/'); ?>/home"><i class="fa fa-bar-chart"></i><span>Dashboard</span></a></li>
        @if( Helper::temPermissao('usuarios-listar') || Helper::temPermissao('perfis-listar') )
        <li>
            <a style="cursor: pointer;"><i class="fa fa-user-plus"></i><span>Usuários</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                @if( Helper::temPermissao('usuarios-listar') )
                <li><a href="<?php echo url('/'); ?>/usuarios">Usuários</a></li>
                @endif
                @if( Helper::temPermissao('perfis-listar') )
                <li><a href="<?php echo url('/'); ?>/perfis">Perfis</a></li>
                @endif
            </ul>
        </li>
        @endif
        @if( Helper::temPermissao('clientes-listar') )
        <li><a href="<?php echo url('/'); ?>/clientes"><i class="fa fa-home"></i><span>Clientes</span></a></li>
        @endif
        @if( Helper::temPermissao('configuracoes-listar') )
        <li><a href="<?php echo url('/'); ?>/configuracoes"><i class="fa fa-cogs"></i><span>Configurações</span></a></li>
        @endif

    </ul>
</aside>
<!--sidebar end-->

<style>
    .ui-aside-compact .nav > li .nav-sub,
    .navbar-header--dark, 
    #aside {
        background: {{ \Auth::user()->empresa()->menu_background }};
        color: {{ \Auth::user()->empresa()->menu_color }};
    }

    .nav li a:hover,
    .ui-aside .nav > li.active > a {
        color: {{ \Auth::user()->empresa()->menu_color }};
    }
</style>