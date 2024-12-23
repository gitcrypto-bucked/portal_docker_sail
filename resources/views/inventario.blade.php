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
                    <option class="text-muted" selected value="{!!  Helpers\Helpers::getUserCompanyName(Auth::user()->cliente) !!}">{!! strtoupper(Helpers\Helpers::getUserCompanyName(Auth::user()->cliente)) !!}</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="localidade" class="form-label d-none d-lg-block">Localidade</label>
                <select  class="form-select" id="localidade" name="localidade">
                    <option value="0" >Localidade</option>
                    @for($i=0; $i< sizeof($localidades); $i++)
                        <option class="text-muted">{!! $localidades[$i]->localidade !!}</option>
                    @endfor
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
        <table class="table_list">
            @for($i=0; $i<sizeof($inventario); $i++)
                <tr class="card my-2">
                    <td class="card-header" onclick="getDetails('{!! $inventario[$i]->localidade !!}','{!! $inventario[$i]->quantidade !!}')">
                        <div class="float-container">
                            <div class="float-child">
                                <div class="text-secondary">Cliente</div>
                                <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! strtoupper($inventario[$i]->cliente) !!}</abbr></div>
                            </div>

                            <div class="float-child">
                                <div class="text-secondary">Localidade</div>
                                <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! strtoupper($inventario[$i]->localidade) !!}</abbr></div>
                            </div>

                            <div class="float-child_invent_qtde">
                                <div class="text-secondary">Quantidade</div>
                                <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! intval($inventario[$i]->quantidade) !!}</abbr></div>

                            </div>

                        </div>
                    </td>
                </tr>

            @endfor
        </table>
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
</div>
</body>
<script  src="{{asset('/js/script.js')}}"></script>
<script  src="{{asset('/js/main.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>

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


    function  getDetails(localidade, total)
    {
        URL = "{!! route('inventario_detalhado') !!}"+"?localidade="+btoa(localidade)+"&total="+btoa(total)+"&_token={!! @csrf_token() !!}";
        window.location.href=URL;
    }


</script>
</html>
