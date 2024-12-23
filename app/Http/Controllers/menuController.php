<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Flags\Flags;
use Illuminate\Support\Facades\Session;
use Helpers\Helpers;

class menuController extends Controller
{

    public function getMenu(): string
    {
        //pega o menu de usuario logado
        if (Auth::check())
        {
            $cliente = Helpers::getUserCompanyName(Auth::user()->cliente);
            switch ($cliente) {
                case 'lowcost':
                    return $this->getLowCostMenu();
                    break;
                default:
                    return $this->getPartnerMenu();
                    break;
            }
        }
        return ''; //nothing in menu user not logged
    }


    protected function getLowCostMenu(): string
    {
        $menuAccess = Flags::getMenuFlags()['lowcost'][Auth::user()->level];

        $menu = '<li class="">' . '<a href="' . route('list-news') . '" class=""><i class="fa fa-home"></i>Dashboard</a>' . '</li>' . "\r\n";
        foreach ($menuAccess as $k => $v) {
            if ($k == 'add_news' & boolval($v) != false) {
                $menu .= '<li class="">' . '<a href="' . route('add_news') . '" class=""><i class="fa fa-newspaper-o"></i>Cadastrar noticias</a>' . '</li>' . "\r\n";
            }
            if ($k == 'news-manager' && boolval($v) != false) {
                $menu .= '<li class="">' . '<a href="' . route('news-manager') . '" class=""><i class="fa fa-calendar-minus-o"></i>Gerenciar Noticias</a>' . '</li>' . "\r\n";
            }
            if ($k == 'chamados-upload' && boolval($v) != false) {
                $menu .= '<li class="">' . '<a href="' . route('chamados-upload') . '" class=""><i class="fa fa-upload"></i>Carga Chamados</a>' . '</li>' . "\r\n";
            }
            if ($k == 'invoice-upload' && boolval($v) != false) {
                $menu .= '<li class="">' . '<a href="' . route('invoice-upload') . '" class=""><i class="fa fa-upload"></i>Carga Faturamento</a>' . '</li>' . "\r\n";
            }
            if($k== 'cliente_manager' & boolval($v) != false){
                $menu .= '<li class="">' . '<a href="' . route('cliente_manager') . '" class=""><i class="fa fa-id-badge"></i>Gerenciar Clientes</a>' . '</li>' . "\r\n";
            }
        }
        unset($menuAccess);
        return ($menu);

    }

    protected function getPartnerMenu(): string
    {
        $menuAccess = Flags::getMenuFlags()['partner'][Auth::user()->level];
        $menu ='';
        if(isset($menuAccess['dashboard-faturamento']) & boolval($menuAccess['dashboard-faturamento']) != false   &&
           isset($menuAccess['dashboard-chamados']) & boolval($menuAccess['dashboard-chamados']) != false){
                $menu.= $this->getSubmenu('dashboard-faturamento', 'dashboard-chamados');
        }

        if(isset($menuAccess['dashboard-faturamento']) & boolval($menuAccess['dashboard-faturamento']) != false   &&
            !isset($menuAccess['dashboard-chamados']) ){
            $menu.= $this->getSubmenu('dashboard-faturamento', '');
        }
        if(!isset($menuAccess['dashboard-faturamento'])  &&
            isset($menuAccess['dashboard-chamados']) & boolval($menuAccess['dashboard-chamados']) != false){
            $menu.= $this->getSubmenu('', 'dashboard-chamados');
        }

        foreach ($menuAccess as $k => $v)
        {
            if ($k == 'chamados' & boolval($v) != false) {
                $menu .= '<li class="">' . '<a href="' . route('chamados') . '" class=""><i class="fa fa-cogs" aria-hidden="true"></i></i>Chamados</a>' . '</li>' . "\r\n";
            }
            if ($k == 'inventario' & boolval($v) != false) {
                $menu .= '<li class="">' . '<a href="' . route('inventario') . '" class=""><i class="fa fa-tasks"></i>Inventario</a>' . '</li>' . "\r\n";
            }
            if ($k == 'faturamento' & boolval($v) != false) {
                $menu .= '<li class="">' . '<a href="' . route('faturamento') . '" class=""><i class="fa fa-money"></i>Faturamento</a>' . '</li>' . "\r\n";
            }
            if ($k == 'tracking' & boolval($v) != false) {
                $menu .= '<li class="">' . '<a href="' . route('tracking') . '" class=""><i class="fa fa-truck" aria-hidden="true"></i>Tracking</a>' . '</li>' . "\r\n";
            }
        }
        $menu .= '<li class="">' . '<a href="' . route('list-news') . '" class=""><i class="fa fa-newspaper-o"></i>Noticias</a>' . '</li>' . "\r\n";
        return $menu;

    }


    protected  function getSubmenu($access1, $access2): string
    {
        $submenu = ' <div class="accordion accordion-flush" id="accordionFlushExample">
                 <div class="accordion-item">
                     <h2 class="accordion-header" id="flush-headingOne">
                         <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                             <i class="fa fa-home" style="font-size: 24px; padding-right:8px; "></i> Dashboard
                         </button>
                     </h2>';

        $close =  '</div>
             </div> ';


        if($access1=='dashboard-faturamento')
        {
            $submenu.= '<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                     <div class="accordion-body">
                         <ul id="accordion_list">
                             <li class="">' . '<a href="' . route('dashboard-faturamento') . '" class="text-white" style="word-break: break-all;font-size: 13px "><i class="fa fa-bar-chart"></i>&nbsp;Faturamento </a>' . '</li>
                         </ul>
                     </div>
                 </div>';
        }
        if($access2=='dashboard-chamados')
        {
            $submenu.= '<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                     <div class="accordion-body">
                         <ul id="accordion_list">
                             <li class="">' . '<a href="' . route('dashboard-chamados') . '" class="text-white" style="word-break: break-all; font-size: 13px "><i class="fa fa-bar-chart"></i>&nbsp;Chamados </a>' . '</li>
                         </ul>
                     </div>
                 </div>';
        }
        $submenu.=$close;
        if($access1 =='' & $access2=='')
        {
                return '';
        }
        return  $submenu;

    }


    public function getDesktopLogo(): string
    {
        $menuHeader = '';
        if (Auth::check()) {
            $menuHeader = '<a href = "#" class="m-0" ><img src ="' . ( Helpers::getUserCompanyLogo(Auth::user()->cliente)) . '" width = "137" class=" ml-2 logo_desktop" ></a >
            <p class="text-black lh-1  m-1 userDesktop" >
                <strong class="text-black ml-2" >' .
                Auth::user()->name.
                '</strong>
                <br >
                <p class="text-black mt-1 lh-1"  style="font-weight: 500">'
                .(ucwords(Helpers::getUserCompanyNameFormated(Auth::user()->cliente))) .
                '</p >
            </p>';
        } else {
            $menuHeader = '<a href = "#" class="m-0" ><img src = "' . asset("/empresas/lowcost.svg") . '" width = "137" class=" ml-2 logo_desktop" ></a >
                <p class="text-black lh-1  m-1 userDesktop" >
                    <br >
                    <strong class="text-black mt-0 lh-1" > LowCost</strong >
                </p >';

        }
        return $menuHeader;
    }


    public function getMobileLogo(): string
    {
        $menuHeader = '';
        if(Auth::check())
        {
            $menuHeader= '<div class="mobile_user">
                <div class="">
                    <a  class="userDisplay" aria-expanded="false">
                        <div style="float: left">
                            <img src="'.( Helpers::getUserCompanyLogo(Auth::user()->cliente)) .'" alt="" width="32" height="32"  id="userComapny"  >
                        </div>
                        &nbsp;<strong class="userName">'.Auth::user()->name.'</strong><br>'.ucfirst(Helpers::getUserCompanyNameFormated(Auth::user()->cliente)).'
                    </a>
                </div>
            </div>';
        }
        else
        {
            $menuHeader=  '<div class="mobile_user">
                <div class="">
                    <a  class="userDisplay" aria-expanded="false">
                        <div style="float: left">
                            <img src="'.asset("empresas/lowcost.png").'" alt="" width="32" height="32"  id="userComapny"  >
                        </div>
                    </a>
                </div>
            </div>';
        }


        return $menuHeader;
    }






}//{{--    <ul class="sidebar-nav">--}}
//{{--        <li class="active">--}}
//{{--            <!--        <a href="#" class=""><i class="fa fa-home"></i>Dashboard</a>-->--}}
//{{--            <div class="accordion accordion-flush" id="accordionFlushExample">--}}
//{{--                <div class="accordion-item">--}}
//{{--                    <h2 class="accordion-header" id="flush-headingOne">--}}
//{{--                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">--}}
//{{--                            <i class="fa fa-home" style="font-size: 24px; padding-right:8px; "></i> Dashboard--}}
//{{--                        </button>--}}
//{{--                    </h2>--}}
//{{--                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">--}}
//{{--                        <div class="accordion-body">--}}
//{{--                            <ul id="accordion_list">--}}
//{{--                                <li>Xpto</li>--}}
//{{--                            </ul>--}}
//{{--                        </div>--}}
//{{--                    </div>--}}
//{{--                </div>--}}
//{{--            </div>--}}
//{{--        </li>--}}
//{{--        <li>--}}
//{{--            <a href="#"><i class="fa fa-plug"></i>Plugins</a>--}}
//{{--        </li>--}}
//{{--        <li>--}}
//{{--            <a href="#"><i class="fa fa-user"></i>Users</a>--}}
//{{--        </li>--}}
//{{--    </ul>--}}
