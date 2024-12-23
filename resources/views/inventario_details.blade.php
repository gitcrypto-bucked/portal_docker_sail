@php($perm = new App\Policies\PagePolicy())
@php($perm->userCan( Route::currentRouteName()))
    <!DOCTYPE html>
<html lang="pt_BR" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Ti team &amp; Low Cost contributors">
    <meta name="generator" content="Pedro Henrique & Washington">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Portal LowCost </title>
    <!--  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/css/dropdown.css')}}">
    <link rel="stylesheet" href="{{asset('/css/search.css')}}">
    <link rel="stylesheet" href="{{asset('/css/list.css')}}">
</head>
<body>
<!-- partial:index.partial.html -->
<div id="wrapper">
    <!--sidebar-->
    @include('components.sidebar')

    <!--sidebar-->
    <!--navbar -->
    @include('components.navwrapper')
    <!--content-->

    <div class="container-xxl mt-4 mobile ">
        <h2 class="content-title pageName">Inventário de Equipamentos e Serviços</h2>
        <p class="pageText">Veja abaixo todos os equipamentos e serviços que você possui com a LowCost. Utilzie a busca para encontrar os itens especificos!</p>
        <div class="row gx-5 gy-3 mt-3 ">

            <div class="col-md-6">
                <label for="cliente" class="form-label d-none d-lg-block">Cliente</label>
                <select  class="form-select" id="cliente" name="cliente">
                    <option value="0" >Cliente</option>
                    <option class="text-muted" selected value="{!!  strtolower(Helpers\Helpers::getUserCompanyName(Auth::user()->cliente) )!!}">{!! strtolower(Helpers\Helpers::getUserCompanyName(Auth::user()->cliente)) !!}</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="localidade" class="form-label d-none d-lg-block">Localidade</label>
                <select  class="form-select" id="localidade" name="localidade">
                    <option value="0" >Localidade</option>
                        <option class="text-muted" selected>{!! ucwords($localidade) !!}</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="modelo" class="form-label d-none d-lg-block">Modelo</label>
                <select  class="form-select" id="modelo" name="modelo">
                    <option value="0" >Modelo</option>
                    @for($i=0; $i< sizeof($modelo); $i++)
                        <option class="text-muted">{!! $modelo[$i]->modelo !!}</option>
                    @endfor
                </select>
            </div>
            <div class="col-sm-3">
                <label for="serial" class="form-label d-none d-lg-block">Serial</label>
                <select  class="form-select" id="serial" name="serial">
                    <option value="0" >Serial</option>
                    @for($i=0; $i< sizeof($serial); $i++)
                        <option class="text-muted">{!! $serial[$i]->serial !!}</option>
                    @endfor
                </select>
            </div>
            <div class="col-sm-3">
                <label for="ced" class="form-label d-none d-lg-block">Centro de Custo</label>
                <select  class="form-select" id="ced" name="ced" >
                    <option value="0">Centro de Custo</option>
                    @if(!is_null($cdc))
                        @for($i=0; $i< sizeof($cdc); $i++)
                            <option class="text-muted">{!! $cdc[$i]->cdc !!}</option>
                        @endfor
                    @endif
                </select>
            </div>
        </div>
    </div>
    <!--table-->
    <!--end table-->
    <div class="container-xxl mt-4 mobile ">
        <div class="row">
            <!-- content -->
            @for($i=0; $i<sizeof($inventario); $i++)
            <div class="card mb-3" style="max-width:100%;" >
                <div class="row g-0">
                    <div class="col-md-9">
                        <div class="card-body">
                               <!--card body -->
                            <table class=" align-middle mb-1 bg-white mt-4">
                                <tr>
                                    <td class="text-secondary tdr" colspan="2">Equipamento</td>
                                    <td class="text-secondary tdr">N° de Série</td>
                                    <td class="text-secondary tdr">IP do Equipamento</td>
                                    <td class="text-secondary tdr">Centro de Custo</td>
                                    <td class="text-secondary tdr" >Situação</td>
                                </tr>
                                <tr>
                                    <td class="modelo tdr" colspan="2">{!! ucwords(strtolower( $inventario[$i]->modelo)) !!}</td>
                                    <td class="serial tdr">{!! (strtoupper( $inventario[$i]->serial)) !!}</td>
                                    <td class="serial tdr">{!! (( @$inventario[$i]->ip)) !!}</td>
                                    <td class="serial tdr">{!! (( @$inventario[$i]->cdc)) !!}</td>
                                    <td class="serial tdr" >{!! (( @$inventario[$i]->situacao)) !!}</td>
                                </tr>
                                <tr><td class="tdlr"></td></tr>
                                <tr>
                                    <td class="text-secondary tdr">N° Contrato</td>
                                    <td class="text-secondary tdr">Início Contrato</td>
                                    <td class="text-secondary tdr">Término Contrato</td>
                                    <td class="text-secondary tdr">Nível Suprimentos</td>
                                    <td class="text-secondary tdr">Total pag. Impressas(dia)</td>
                                    <td class="text-secondary tdr">Total pag. Impressas(Mês)</td>
                                </tr>
                                <tr>
                                    <td class="serial tdr">{!! (strtoupper( $inventario[$i]->ncontrato)) !!}</td>
                                    <td class="serial tdr">{!!  strlen($inventario[$i]->data_inicio)? date('d/m/Y', strtotime(str_replace('-','/',$inventario[$i]->data_inicio)))  :  $inventario[$i]->data_inicio !!} </td>
                                    <td class="serial tdr">{!!  strlen($inventario[$i]->data_fim)? date('d/m/Y', strtotime(str_replace('-','/',$inventario[$i]->data_fim)))  :  $inventario[$i]->data_fim !!}</td>
                                    <td class="serial tdr">{!! strlen(@$inventario[$i]->suprimentos)? @$inventario[$i]->suprimentos : '&nbsp;' !!}</td>
                                    <td class="serial tdr">{!! @$inventario[$i]->paginas_dia !!}</td>
                                    <td class="serial tdr">{!! @$inventario[$i]->paginas_mes !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary tdr" colspan="2">Endereço</td>
                                    <td class="text-secondary tdr" colspan="1">Telefone</td>
                                    <td class="text-secondary tdr" colspan="2">Colaborador</td>

                                </tr>
                                <tr>
                                    <td class="serial tdr" colspan="2">{!! strlen(@$inventario[$i]->endereco) ? @$inventario[$i]->endereco : "&nbsp;"!!}</td>
                                    <td class="serial tdr" colspan="1">{!! @$inventario[$i]->telefone !!} </td>
                                    <td class="serial tdr" colspan="2">{!! @$inventario[$i]->colaborador !!}</td>

                                </tr>
                                <tr>
                                    <td class="text-secondary tdr" colspan="2">Cidade</td>
                                    <td class="text-secondary tdr" colspan="1">UF</td>
                                    <td class="text-secondary tdr" >E-mail</td>
                                    <td class="text-secondary tdr">Departamento</td>

                                </tr>
                                <tr>
                                    <td class="serial tdr">{!!@$inventario[$i]->cidade!!}</td>
                                    <td class="serial tdr">{!! @$inventario[$i]->uf !!} </td>
                                    <td class="serial tdr">{!! @$inventario[$i]->email !!}</td>
                                    <td class="serial tdr">{!! @$inventario[$i]->departamento !!}</td>
                                    <td class="serial tdr">&nbsp;</td>
                                    <td class="serial tdr">&nbsp;</td>
                                </tr>
                            </table>
                                <!--card body end-->
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        @if( $inventario[$i]->imagem!=null ||  $inventario[$i]->imagem!='')
                        <img src="{!! $inventario[$i]->imagem !!}" class="img-fluid rounded-start pb-0" alt="..." width="221" height="221">
                        @else
                            <img src="{{ asset('img/no_image.png') }}" class="img-fluid rounded-start pb-0" alt="..." width="221" height="221" style="opacity: 0.7 !important">
                        @endif
                            <button type="button" class="btn round-btn text-white mb-4 small-text" onclick="details('{!! $inventario[$i]->serial !!}')">Detalhes do Equipamento</button>
                    </div>
                </div>
            </div>
            @endfor
            <!--end content-->
        </div>
    </div>
    <div class="container-xxl mt-4 mobile ">
        <div class="row">
            <div  class='col-lg-8'>
                @if($inventario instanceof \Illuminate\Pagination\AbstractPaginator)
                    {{$inventario->withQueryString()->links()}}
                @endif
            </div>
        </div>
    </div>
    <!--wrapper-->
    <!--modal-->
    <div class="modal modal-lg" tabindex="-1" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                    <!-- Tabs n avs -->
                    <div class="w3-row">
                        <a href="javascript:void(0)" onclick="openCity(event, 'Gerais');">
                            <div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">Dados Gerais</div>
                        </a>
                        <a href="javascript:void(0)" onclick="openCity(event, 'Impressao');">
                            <div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">Impressão</div>
                        </a>
                        <a href="javascript:void(0)" onclick="openCity(event, 'Historico');">
                            <div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">Histórico de Troca</div>
                        </a>
                    </div>

                    <div id="Gerais" class="w3-container city" style="display:block">
                        <p class="btn-nav mt-4 text-center">Contadores Gerais</p>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">Tipo</th>
                                <th scope="col" colspan="2" class="text-center">Total</th>
                                <th scope="col" class="text-center">Última Leitura</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">Mark</td>
                                <td class="text-center"colspan="2">Otto</td>
                                <td class="text-center">@mdo</td>
                            </tr>
                            <tr>
                                <td class="text-center">Jacob</td>
                                <td class="text-center" colspan="2">Thornton</td>
                                <td class="text-center">@fat</td>
                            </tr>
                            <tr>
                                <td class="text-center">Larry</td>
                                <td class="text-center" colspan="2">the Bird</td>
                                <td class="text-center">@twitter</td>
                            </tr>
                            </tbody>
                        </table>
                        <p class="btn-nav mt-4 text-center">Suprimento</p>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">Tipo</th>
                                <th scope="col" colspan="2" class="text-center">Total</th>
                                <th scope="col" class="text-center">Última Leitura</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">Mark</td>
                                <td class="text-center"colspan="2">Otto</td>
                                <td class="text-center">@mdo</td>
                            </tr>
                            <tr>
                                <td class="text-center">Jacob</td>
                                <td class="text-center" colspan="2">Thornton</td>
                                <td class="text-center">@fat</td>
                            </tr>
                            <tr>
                                <td class="text-center">Larry</td>
                                <td class="text-center" colspan="2">the Bird</td>
                                <td class="text-center">@twitter</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="Impressao" class="w3-container city" style="display:none">
                        <h2>Paris</h2>
                        <p>Paris is the capital of France.</p>
                    </div>

                    <div id="Historico" class="w3-container city" style="display:none">
                        <h2>Tokyo</h2>
                        <p>Tokyo is the capital of Japan.</p>
                    </div>
                    <!-- end -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!--modal end -->
</div>
</body>
<script  src="{{asset('/js/script.js')}}"></script>
<script  src="{{asset('/js/main.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
    function openCity(evt, cityName) {
        var i, x, tablinks;
        x = document.getElementsByClassName("city");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < x.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" w3-border-cyan", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.firstElementChild.className += " w3-border-cyan";
    }

    function  details(elem)
    {
        console.log(elem);
        $("#myModal").modal('toggle');
    }

    //show labels into select for mobile

    function showFirstOptionOnMobile() {
        // Detecta se o dispositivo é móvel (simplificado)
        const isMobile = window.innerWidth <= 768;

        if (isMobile)
        {
            document.getElementById("serial").options.selectedIndex= 0;
            $('#serial').children('option[value="0"]').css('display','block');
            document.getElementById("localidade").options.selectedIndex= 0;
            $('#localidade').children('option[value="0"]').css('display','block');
            document.getElementById("modelo").options.selectedIndex= 0;
            $('#modelo').children('option[value="0"]').css('display','block');
            document.getElementById("cliente").options.selectedIndex= 0;
            $('#cliente').children('option[value="0"]').css('display','block');
            document.getElementById("ced").options.selectedIndex= 0;
            $('#ced').children('option[value="0"]').css('display','block');
        }
        else
        {
            document.getElementById("serial").options.selectedIndex= 1;
            $('#serial').children('option[value="0"]').css('display','none');
            document.getElementById("localidade").options.selectedIndex= 1;
            $('#localidade').children('option[value="0"]').css('display','none');
            document.getElementById("modelo").options.selectedIndex= 1;
            $('#modelo').children('option[value="0"]').css('display','none');
            document.getElementById("cliente").options.selectedIndex= 1;
            $('#cliente').children('option[value="0"]').css('display','none');
            document.getElementById("ced").options.selectedIndex= 1;
            $('#ced').children('option[value="0"]').css('display','none');

        }

    }

    window.onload= function(){
        showFirstOptionOnMobile();
    };

    window.addEventListener('resize', function(event) {
        showFirstOptionOnMobile();
    }, true);



</script>
</html>
