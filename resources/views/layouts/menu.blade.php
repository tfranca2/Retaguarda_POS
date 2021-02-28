<?php 

use App\Menu;
use App\Helpers;

$menus = Menu::whereNull('parent')->orderBy('label')->get();

?>
<!--sidebar start-->
<aside id="aside" class="ui-aside">
    <ul class="nav" ui-nav>
        <li><a href="<?php echo url('/'); ?>/home"><i class="fa fa-bar-chart"></i><span>Dashboard</span></a></li>

        @forelse( $menus as $menu )
            @if( Helper::temPermissao( $menu->permission ) )
                @php
                $submenus = $menu->getSubmenus();
                @endphp
                @if( count( $submenus ) > 0 )
                <li>
                    <a style="cursor: pointer;"><i class="{{ $menu->icon }}"></i><span>{{ $menu->label }}</span><i class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub">
                        @foreach( $submenus as $submenu )
                            @if( Helper::temPermissao( $submenu->permission ) )
                                <li><a href="{{ url(($submenu->link)?:'#') }}"><i class="{{ $submenu->icon }}"></i>{{ $submenu->label }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </li>
                @else
                <li><a href="{{ url(($menu->link)?:'#') }}"><i class="{{ $menu->icon }}"></i><span>{{ $menu->label }}</span></a></li>
                @endif
            @endif
        @empty
        @endforelse

        @if( Helper::temPermissao('configuracoes-listar') )
         <li>
            <a style="cursor: pointer;"><i class="fa fa-cogs"></i><span>Configurações</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                @if( Helper::temPermissao('configuracoes-gerenciar') )
                <li><a href="<?php echo url('/'); ?>/configuracoes">Configurações</a></li>
                <li><a href="<?php echo url('/'); ?>/menus">Menus</a></li>
                @endif
                @if( Helper::temPermissao('perfis-listar') )
                <li><a href="<?php echo url('/'); ?>/perfis">Perfis</a></li>
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
</style>