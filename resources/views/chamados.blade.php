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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/jquery-ui.min.css" integrity="sha512-TFee0335YRJoyiqz8hA8KV3P0tXa5CpRBSoM0Wnkn7JoJx1kaq1yXL/rb8YFpWXkMOjRcv5txv+C6UluttluCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/theme.min.css" integrity="sha512-lfR3NT1DltR5o7HyoeYWngQbo6Ec4ITaZuIw6oAxIiCNYu22U5kpwHy9wAaN0vvBj3U6Uy2NNtAfiaKcDxfhTg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/css/dropdown.css')}}">
    <link rel="stylesheet" href="{{asset('/css/search.css')}}">
    <link rel="stylesheet" href="{{asset('/css/list.css')}}">
    <link rel="stylesheet" href="{{asset('/css/rating.css')}}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('/css/sweetalert.css')}}">

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
        <h2 class="content-title pageName">Gestão de Chamados</h2>
        <p class="pageText"></p>
        <div class=" container-xxl mt-2 " style="padding-right:  0px !important; padding-left: 0px!important">
                <table class="table" style="background-color: #4a5568 !important; ">
                    <tr >
                        <td colspan="2" >
                            <p class="initialism_alt float-start">Chamados Abertos:&nbsp;0 </p>
                        </td>
                        <td colspan="2">
                            <p class="initialism_alt ">Chamados Fechados:&nbsp;0 </p>
                        </td>
                        <td colspan="2">
                            <p class="initialism_alt float-send">Chamados Cancelados:&nbsp;0 </p>
                        </td>
                    </tr>
                </table>
        </div>
        <br>
        <div class="row gx-5 gy-3 mt-3 ">
            <div class="col">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="todos">
                    <label class="form-check-label" for="inlineRadio1">Todos</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="ongoing" value="ongoing">
                    <label class="form-check-label" for="inlineRadio2">Em Andamento</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="fechado" value="fechado" >
                    <label class="form-check-label" for="fechado">Fechado</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="cancelado" value="cancelado" >
                    <label class="form-check-label" for="cancelado">Cancelado</label>
                </div>
            </div>
            <div class="col">
                <button type="button" class="btn btn-success float-end px-4"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Abrir</button>
            </div>
        </div>
        <div class="row gx-5 gy-3 mt-3 ">
                <div class="col-md-6 ">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Buscar:</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                            <span class="input-group-text bg-transparent" id="inputGroup-sizing-default"><i class="fa fa-search" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Data Inicial:</label>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect01"><i class="fa fa-calendar" aria-hidden="true"></i></label>
                                <input type="text" id="inputGroupSelect01" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                            </div>
                    </div>
                </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Data Final:</label>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupSelect02"><i class="fa fa-calendar" aria-hidden="true"></i></label>
                        <input type="text" id="inputGroupSelect02" class="form-control">

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--table-->
    <!--end table-->
    <div class="container-xxl mt-4 mobile ">
        <div class="row">
            <table class="table_list">
                @for($i=0; $i<sizeof($chamados); $i++)
                    <tr class="card my-2">
                        <td class="card-header" onclick="getDetails('{!! $chamados[$i]->localidade !!}','{!! $chamados[$i]->numero_chamado_interno !!}')">
                            <div class="float-container">
                                <div class="float-child_invoice_chamados_first">
                                    <div class="text-secondary">N° Chamado</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism_alt">{!! strtoupper($chamados[$i]->numero_chamado_interno) !!}</abbr></div>
                                </div>

                                <div class="float-child_invoice_chamados d-none  d-md-block d-lg-block">
                                    <div class="text-secondary">Aberto por</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! ucwords(@$chamados[$i]->aberto_por) !!}</abbr></div>
                                </div>

                                <div class="float-child_invoice_chamados ">
                                    <div class="text-secondary">Serial</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! intval($chamados[$i]->numero_serial) !!}</abbr></div>
                                </div>

                                <div class="float-child_invoice_chamados">
                                    <div class="text-secondary">Status</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! intval($chamados[$i]->numero_serial) !!}</abbr></div>
                                </div>

                                <div class="float-child_invoice_chamados d-none  d-md-block d-lg-block">
                                    <div class="text-secondary">Data Abertura</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! date('d/m/Y', strtotime(str_replace('-','/',$chamados[$i]->periodo_inicio))) !!}</abbr></div>
                                </div>

                                <div class="float-child_invoice_chamados d-none  d-md-block d-lg-block">
                                    <div class="text-secondary">Data da Solução</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! date('d/m/Y', strtotime(str_replace('-','/',$chamados[$i]->periodo_fim))) !!}</abbr></div>
                                </div>
                                <div class="float-child_invoice_rating d-none d-md-block d-lg-block">
                                    <div class="text-secondary">Avaliação</div>
                                    <div class="green">
                                        <fieldset class="rating">
                                            <input type="radio" id="star5" name="rating" value="5" />
                                            <label for="star5">5 stars</label>
                                            <input type="radio" id="star4" name="rating" value="4" />
                                            <label for="star4">4 stars</label>
                                            <input type="radio" id="star3" name="rating" value="3" />
                                            <label for="star3">3 stars</label>
                                            <input type="radio" id="star2" name="rating" value="2" />
                                            <label for="star2">2 stars</label>
                                            <input type="radio" id="star1" name="rating" value="1" />
                                            <label for="star1">1 star</label>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="float-child_invoice_eye">
                                    <div class="text-secondary">&nbsp;</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism"><i class="fa-lg fa fa-eye" aria-hidden="true"></i></abbr></div>
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
                @if($chamados instanceof \Illuminate\Pagination\AbstractPaginator)
                    {{$chamados->withQueryString()->links()}}
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.i18n/1.0.9/jquery.i18n.js" integrity="sha512-OU7TUd3qODSbwnF5mHouW66/Yt54mzh58k+rxfJili7AunMl5wbF2D8kLGTGuUv5xM2MfylWZOPZaqOKpf7dZg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script><script>
    $.datepicker.setDefaults( $.datepicker.regional[ "pt-BR" ] );

    $( "#inputGroupSelect01" ).datepicker();
    $( "#inputGroupSelect02" ).datepicker();
</script>
</html>
