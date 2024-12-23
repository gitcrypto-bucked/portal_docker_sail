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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/jquery-ui.min.css" integrity="sha512-TFee0335YRJoyiqz8hA8KV3P0tXa5CpRBSoM0Wnkn7JoJx1kaq1yXL/rb8YFpWXkMOjRcv5txv+C6UluttluCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/theme.min.css" integrity="sha512-lfR3NT1DltR5o7HyoeYWngQbo6Ec4ITaZuIw6oAxIiCNYu22U5kpwHy9wAaN0vvBj3U6Uy2NNtAfiaKcDxfhTg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/css/dropdown.css')}}">
    <link rel="stylesheet" href="{{asset('/css/search.css')}}">
    <link rel="stylesheet" href="{{asset('/css/list.css')}}">
    <link rel="stylesheet" href="{{asset('/css/tracking.css')}}">
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
        <h2 class="content-title pageName">Tracking</h2>
        <p class="pageText"></p>

        <div class="row gx-5 gy-3 mt-3 ">
            <div class="col-md-3 ">
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
                    <label for="exampleInputEmail1" class="form-label">Localidade:</label>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupSelect0"><i class="fa fa-map-marker" aria-hidden="true"></i></label>
                        <select class="form-select" id="inputGroupSelect0">
                            <option selected>Escolha...</option>
                        </select>
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
    @php($entrgues =0)
    @php($ongo =0)
    @php($cancelados =0)
    @for($i=0; $i<sizeof($track); $i++)
        @if($track[$i]->st==1)
            @php($entrgues+=1)
        @endif
        @if($track[$i]->st==0)
            @php($ongo+=1)
        @endif
            @if($track[$i]->st==2)
                @php($cancelados+=1)
            @endif
    @endfor
    <!--end table-->
    <div class="container-xxl mt-4 mobile ">
        <div class="row gx-3" >
                <!-- gutters -->
                <div class="col-sm">
                    <p class="text-center column_name  px-4">En andamento ({!! $ongo !!})</p>
                    <section class="col" id="postado">
                        @for($i=0; $i<sizeof($track); $i++)
                            @if($track[$i]->st==0)
                                <ul class="column_content" onclick="getDetails('{!! $track[$i]->numero_pedido !!}')">
                                    <li><span class="dot_cancelado"></span></li>
                                    <li><p class="text-center li_text">NF: {!! $track[$i]->nfe !!} </p></li>
                                    <li><p class="text-center li_normal">Postado: {!! date('d/m/Y', strtotime(str_replace('-','',$track[$i]->data_faturamento))) !!}</p></li>
                                    <li><p class="text-center li_normal">Previsão entrega: {!! $track[$i]->previsaoEntrega !!}</p></li>
                                    {{--                            <li><p class="text-center li_normal_end">Localidade 1</p></li>--}}
                                </ul>
                            @endif
                        @endfor
{{--                        <ul class="column_content">--}}
{{--                            <li><span class="dot_postado"></span></li>--}}
{{--                            <li><p class="text-center li_text">NF: 888939</p></li>--}}
{{--                            <li><p class="text-center li_normal">Postado 07/07/2025 15:10:23</p></li>--}}
{{--                            <li><p class="text-center li_normal">Previsão entrega 17/07/2025 15:10:23</p></li>--}}
{{--                            <li><p class="text-center li_normal_end">Localidade 1</p></li>--}}
{{--                        </ul>--}}
                    </section>
                </div>

            <div class="col-sm">

                <p class="text-center column_name  px-4">Cancelado ({!! $cancelados !!})</p>
                <section class="col" id="entregue">
                    @php($cancelados =0)
                    @for($i=0; $i<sizeof($track); $i++)
                        @if($track[$i]->st==2)
                            <ul class="column_content" onclick="getDetails('{!! $track[$i]->numero_pedido !!}')">
                            <li><span class="dot_cancelado"></span></li>
                            <li><p class="text-center li_text">NF: {!! $track[$i]->nfe !!} </p></li>
                            <li><p class="text-center li_normal">Postado: {!! date('d/m/Y', strtotime(str_replace('-','',$track[$i]->data_faturamento))) !!}</p></li>
                            <li><p class="text-center li_normal">Previsão entrega: {!! $track[$i]->previsaoEntrega !!}</p></li>
{{--                            <li><p class="text-center li_normal_end">Localidade 1</p></li>--}}
                            </ul>
                        @endif
                    @endfor
                </section>
            </div>
            <div class="col-sm">
                <p class="text-center column_name  px-4">Entregue ({!! $entrgues !!})</p>
                <section class="col" id="postado">
                    @for($i=0; $i<sizeof($track); $i++)
                        @if($track[$i]->st==1)
                            <ul class="column_content" onclick="getDetails('{!! $track[$i]->numero_pedido !!}')">
                                <li><span class="dot_entregue"></span></li>
                                <li><p class="text-center li_text">NF: {!! $track[$i]->nfe !!} </p></li>
                                <li><p class="text-center li_normal">Postado: {!! date('d/m/Y', strtotime(str_replace('-','',$track[$i]->data_faturamento))) !!}</p></li>
                                <li><p class="text-center li_normal">Previsão entrega: {!! $track[$i]->previsaoEntrega !!}</p></li>
                                <li><p class="text-center li_normal_end">Data entrega: {!! date('d/m/Y', strtotime(str_replace('-','',$track[$i]->data))) !!}</p></li>
{{--                                <li><p class="text-center li_normal_end">Localidade 1</p></li>--}}
                            </ul>
                        @endif
                    @endfor

                </section>
            </div>


                <!-- gutters-->
        </div>
    </div>
    <div class="container-xxl mt-4 mobile ">
        <div class="row">
            <div  class='col-lg-8'>
                @if($track instanceof \Illuminate\Pagination\AbstractPaginator)
                    {{$track->withQueryString()->links()}}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.i18n/1.0.9/jquery.i18n.js" integrity="sha512-OU7TUd3qODSbwnF5mHouW66/Yt54mzh58k+rxfJili7AunMl5wbF2D8kLGTGuUv5xM2MfylWZOPZaqOKpf7dZg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $.datepicker.setDefaults( $.datepicker.regional[ "pt-BR" ] );

    $( "#inputGroupSelect01" ).datepicker();
    $( "#inputGroupSelect02" ).datepicker();

    function  getDetails(elem)
    {
        const URL='{!!  route('tracking_detalhado') !!}';
        const _token = "{!! @csrf_token() !!}"
        window.location.href=URL+"?numero_pedido="+btoa(elem)+"&_token="+_token;
    }
</script>
</html>
