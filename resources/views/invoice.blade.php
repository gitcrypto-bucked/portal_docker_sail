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
        <h2 class="content-title pageName">Listagem - Faturamento</h2>
        <p class="pageText">Veja abaixo todos os equipamentos e serviços que você possui com a LowCost. Utilzie a busca para encontrar os itens especificos!</p>
        <form method="POST">
            <div class="row gx-5 gy-3 mt-3 ">
                <div class="col-8">
                    <label for="filter" class="form-label ">Buscar</label>
                    <input type="text" class="form-control" id="filter">
                </div>
                <div class="col-4">
                    <label for="filter"  class="form-label ">Ordernar por:</label>
                    <select class="form-control" >
                        <option>Menor Data</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <!--table-->
    <!--end table-->
    <div class="container-xxl mt-4 mobile ">
        <div class="row">
            <table class="table_list">
                @for($i=0; $i<sizeof($invoice); $i++)
                    <tr class="card my-2">
                        <td class="card-header" onclick="getDetails('{!! $invoice[$i]->periodo_inicio !!}','{!! $invoice[$i]->periodo_fim !!}', {!! $invoice[$i]->cobrado !!})">
                            <div class="float-container">
                                <div class="float-child_invoice">
                                    <div class="text-secondary">Serviço</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism_alt">{!! 'Outsourcing '.date( "d/m/Y", strtotime(str_replace('-','/',$invoice[$i]->periodo_inicio))).' até '.date( "d/m/Y", strtotime(str_replace('-','/',$invoice[$i]->periodo_fim))); !!}</abbr></div>
                                </div>

                                <div class="float-child_invoice_valor">
                                    <div class="text-secondary">Valor R$</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! 'R$ '.number_format($invoice[$i]->cobrado,2,",",".");; !!}</abbr></div>
                                </div>

                                <div class="float-child_invoice_periodo">
                                    <div class="text-secondary">Início do Perído</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!!  "  ".date( "d/m/Y", strtotime(str_replace('-','/',$invoice[$i]->periodo_inicio))); !!}</abbr></div>

                                </div>

                                <div class="float-child_invoice_periodo">
                                    <div class="text-secondary">Término do Periodo</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! "  ".date( "d/m/Y", strtotime(str_replace('-','/',$invoice[$i]->periodo_fim))); !!}</abbr></div>
                                </div>

                                <div class="float-child_status">

                                @if($invoice[$i]->status =='aberto' ||$invoice[$i]->status =="" )
                                        <a class=" status_aberto  mt-2  tn-lg ">Aberto</a>
                                    @else
                                    @endif
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
        @if($invoice instanceof \Illuminate\Pagination\AbstractPaginator)
            {{$invoice->links()}}
        @endif
    </div>
        </div></div>
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
    function  getDetails(periodo_inicio, periodo_fim, total)
    {
            URL = "{!! route('faturamento_detalhado') !!}"+"?periodo_inicio="+btoa(periodo_inicio)+"&periodo_fim="+btoa(periodo_fim)+"&total="+btoa(total)+"&_token={!! @csrf_token() !!}";
            window.location.href=URL;
    }
</script>
</html>
