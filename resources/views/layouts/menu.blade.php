<?php 

use App\Menu;
use App\Helpers;

$menus = Menu::whereNull('parent')->orderBy('label')->get();
$atualMenu = explode('/',\Request::path())[0];

?>
<!--sidebar start-->
<aside id="aside" class="ui-aside">
    <ul class="nav" ui-nav>
        <li class="{{ (($atualMenu=='home')?'active':'') }}"><a href="<?php echo url('/'); ?>/home"><i class="fa fa-bar-chart"></i><span>Dashboard</span></a></li>
        @forelse( $menus as $menu )
            @if( Helper::temPermissao( $menu->permission ) )
                @php
                $submenus = $menu->getSubmenus();
                @endphp
                @if( count( $submenus ) > 0 )
                <?php
                    $subs = '';
                    $active = '';
                    $display = '';
                    foreach( $submenus as $submenu ){
                        if( Helper::temPermissao( $submenu->permission ) ){
                            $active = '';
                            if( $atualMenu == $submenu->link ){
                                $active = 'active';
                                $display = 'display: block;';
                            }
                            $subs .= '<li class="'. $active .'"><a href="'. url(($submenu->link)?:'#') .'"><i class="'. $submenu->icon .'"></i>'. $submenu->label .'</a></li>';
                        }
                    }
                ?>
                <li class="{{ (($display)?'active':'') }}">
                    <a style="cursor: pointer;"><i class="{{ $menu->icon }}"></i><span>{{ $menu->label }}</span><i class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub" style="{{ $display }}"><?=$subs?></ul>
                </li>
                @else
                <li class="{{ (($atualMenu==$menu->link)?'active':'') }}"><a href="{{ url(($menu->link)?:'#') }}"><i class="{{ $menu->icon }}"></i><span>{{ $menu->label }}</span></a></li>
                @endif
            @endif
        @empty
        @endforelse

        @if( Helper::temPermissao('configuracoes-listar') )
         <li class="{{ ((in_array($atualMenu,['configuracoes','menus','perfis']))?'active':'') }}">
            <a style="cursor: pointer;"><i class="fa fa-cogs"></i><span>Configurações</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub" style="{{ ((in_array($atualMenu,['configuracoes','menus','perfis']))?'display: block;':'') }}">
                @if( Helper::temPermissao('configuracoes-gerenciar') )
                <li class="{{ (($atualMenu=='configuracoes')?'active':'') }}"><a href="<?php echo url('/'); ?>/configuracoes">Configurações</a></li>
                <li class="{{ (($atualMenu=='menus')?'active':'') }}"><a href="<?php echo url('/'); ?>/menus">Menus</a></li>
                @endif
                @if( Helper::temPermissao('perfis-listar') )
                <li class="{{ (($atualMenu=='perfis')?'active':'') }}"><a href="<?php echo url('/'); ?>/perfis">Perfis</a></li>
                @endif
            </ul>
        </li>
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

    .pagination {
        margin: 0;
    }
    .pagination > .active > a, .pagination > .active > a:hover, .pagination > .active > a:focus, .pagination > .active > span, .pagination > .active > span:hover, .pagination > .active > span:focus {
        background: {{ \Auth::user()->empresa()->menu_background }} !important;
        color: {{ \Auth::user()->empresa()->menu_color }} !important;
        border-color: {{ \Auth::user()->empresa()->menu_background }} !important; 
    }
    .pagination > li > a:hover, .pagination > li > a:focus, .pagination > li > span:hover, .pagination > li > span:focus {
        background: {{ \Auth::user()->empresa()->menu_background }} !important;
        color: {{ \Auth::user()->empresa()->menu_color }} !important;
        border-color: {{ \Auth::user()->empresa()->menu_background }} !important; 
    }
</style>