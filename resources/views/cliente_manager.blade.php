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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Portal LowCost</title>
    <!--  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/css/dropdown.css')}}">
    <link rel="stylesheet" href="{{asset('/css/news.css')}}">
    <link rel="stylesheet" href="{{asset('/css/placeholder.css')}}">
    <link rel="stylesheet" href="{{asset('/css/search.css')}}">
    <link rel="stylesheet" href="{{asset('/css/list.css')}}">

    <link rel="stylesheet" href="{{asset('/css/sweetalert.css')}}">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

</head>
<body>
<!-- partial:index.partial.html -->
<div id="wrapper">
    <!--sidebar-->
    @include('components.sidebar')

    <!--sidebar-->
    <!--navbar -->
    @include('components.navwrapper')

    <!--navbar -->
    <!--content-->
    <div class="container-xxl mt-4 mobile ">
        <h2 class="content-title pageName">Gerenciar clientes   </h2>
        <p class="pageText">Permite ativar e desativar clientes cadastrados.</p>
        @if(sizeof($clients)==1)
            <p class="pageText">Cliente: <p  class=" pageName" > {!! @ucfirst($clients[0]->cliente) !!}</p></p>
        @else
            <p class="pageText" > Cliente:  <p  class=" pageName" id="filterOutput"></p></p>
        @endif
        <div class="row gx-5 gy-3 mt-3 ">
            <!-- News Block -->
            <div class="col">
                <form method="POST" action="{{route('filter-clientes')}}" enctype="multipart/form-data">
                    <div class=" form-group px-2 mb-4">
                        <div class="alert alert-success text-white bg-success" id="success" style="display:none">
                        </div>
                        <div class="alert alert-success text-white bg-success" id="erno" style="display:none">
                        </div>
                        @if(Session::has('error'))
                            <div class="alert alert-success bg-danger text-white" id="error">
                                {{ Session::get('error')}}
                            </div>
                        @endif
                        @if (session('success') || Session::has('success'))
                            <div class="alert alert-success text-white bg-success" id="sucess">
                                {{ session('success') || Session::get('success')}}
                            </div>
                        @endif
                        @csrf
                        <input type="text" id="myInput"  name="filter" onkeyup="myFunction()"  class="search" placeholder="Busca" @if(sizeof($clients)==1) value="{!! @ucfirst($clients[0]->cliente) !!}" @endif>
                        <div class="form-check mb-2 pb-2">
                            <input class="form-check-input" type="checkbox" id="flexCheckDefault" name="flexCheckDefault" value="1">
                            <label class="form-check-label text-dark" for="flexCheckDefault">
                                Só ativos
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary reset" id="reset">Buscar&nbsp;<i class="fa fa-database"></i></button>
                        <button type="reset" class="btn btn-secondary reset" id="reset" onclick="window.location.href='{{route('cliente_manager')}}'">Limpar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <div class="container-xxl mt-4 mobile ">
        <!-- form -->
                <!-- table button block -->
            <table class="container" id="myTable">
                    @for($i=0; $i<sizeof($clients); $i++)
                    <tr class="card my-2">
                        <td class="card-header">
                            <div class="float-container">
                                <div class="float-child">
                                    <div class="text-secondary">#</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! $clients[$i]->id !!}</abbr></div>
                                </div>

                                <div class="float-child">
                                    <div class="text-secondary">Cliente</div>
                                    <div class="green"><abbr title="HyperText Markup Language" class="initialism">{!! $clients[$i]->cliente !!}</abbr></div>
                                </div>

                                <div class="float-child">
                                    <div class="text-secondary">ações</div>
                                    @if($clients[$i]->active =='1')
                                        <button  class="btn btn-outline-secondary px-2" id="{{$clients[$i]->idCliente}}" value="desativar" type="button" onclick="desativar(this)"  name="submitbutton" >Desativar</button>
                                    @else
                                        <button class="btn btn-outline-primary px-2" id="{{$clients[$i]->idCliente}}" value="ativar" type="button" onclick="ativar(this)" name="submitbutton" >Ativar</button>
                                    @endif
                                    <button class="btn btn-outline-danger px-2"  id="{{$clients[$i]->idCliente}}" value="excluir" type="button" onclick="excluir(this)"   name="submitbutton">Excluir</button>
                                    @if(@ $clients[$i]->total>0)
                                        <button class="btn btn-outline-success" id="{{$clients[$i]->cliente}}" value="userlist" type="button" onclick="window.location.href='{{route('usuarios_clientes')}}?cliente={!! base64_encode($clients[$i]->cliente) !!}&_token={!! @csrf_token() !!}'">Ver Usuarios</button>
                                    @endif
                                    @if(@$clients[$i]->logo!='' || @$clients[$i]->logo!=NULL)
                                        <button class="btn btn-outline-info px-2"  id="{{$clients[$i]->idCliente}}"  value="{{asset('storage/public/'.$clients[$i]->path)}}"  aria="{{$clients[$i]->path}}"
                                                           type="button" onclick="update(this)" name="submitbutton">
                                            <i class="fa fa-picture-o" aria-hidden="true"></i>
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </td>
                    </tr>

                    @endfor
                </table>
                <!-- table button block -->

        <div  class='col-lg-8 mt-4'>
            @if($clients instanceof \Illuminate\Pagination\AbstractPaginator)
            {{$clients->links()}}
            @endif
        </div>
        </div>
    </div>
    <!--content-->
    <!-- modal  active user -->
    <div class="modal" tabindex="-1" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Logo do Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <p id='msg'></p>
                     <input type="hidden" id="clienteID">
                     <div style="width: 300px; outline: none; border: 0px !important;margin-left: 30% !important;">
                         <img class="card-img-left img-fluid " alt="" id="image" width="200" height="200" style="border: 0px !important;outline: none !important;
                         padding:10px !important">
                     </div>
                     <input type="file" id="img" name="img" accept="image/*">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"  onclick="validate(this)">Ativar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal  active user -->
    <!--modal update cliente image -->
    <div class="modal" tabindex="-1" id="modalUpdate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Atualizar Logo do Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id='msg2'></p>
                    <input type="hidden" id="clienteIDUpdate">
                    <div style="width: 300px; outline: none; border: 0px !important; margin-left: 30% !important;">
                        <img class="card-img-left img-fluid " alt="" id="imgUpdate" width="220" height="250" style="border: 0px !important;outline: none !important;
                             padding:10px !important">
                    </div>
                    <input type="file" id="logoCliente" name="logoCliente" accept="image/*">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"  onclick="check(this)">Atualizar</button>
                </div>
            </div>
        </div>
    </div>
    <!--end modal cliente image -->
</div>
<!-- partial -->
<script  src="{{asset('/js/script.js')}}"></script>
<script  src="{{asset('/js/main.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" i
        ntegrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">

    function myFunction() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }


    const img = document.getElementById('img');
    img.onchange = evt => {
        const [file] = img.files
        if (file) {
            document.getElementById('image').src = URL.createObjectURL(file)
        }
    }

    const logoCliente = document.getElementById('logoCliente');
    logoCliente.onchange = evt =>{
        const [file] = logoCliente.files
        if (file) {
            document.getElementById('imgUpdate').src = URL.createObjectURL(file)
        }
    }

    function validate(elem)
    {
        if( document.getElementById('img').value==''||  document.getElementById('img')==undefined )
        {
            document.getElementById('msg').innerHTML='Por favor envie o logo do cliente';
            return;
        }
        else
        {
            $("#modal").modal('hide')
            doActivate(document.getElementById('clienteID').value );
        }
    }

    function desativar(elem)
    {
        doDeactivate(elem.id)
    }

    function ativar(elem)
    {
        document.getElementById('clienteID').value = elem.id;
        $("#modal").modal('toggle');
    }

    function excluir(elem)
    {
        console.log(elem.id)
        Swal.fire({
            title: "O cliente selecionado será excluído, não será possivel aos usuarios deste cliente entrar no portal",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Excluir",
            denyButtonText: `Cancelar`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed)
            {
                 doRemove(elem.id);
            }
            else if (result.isDenied)
            {
                setTimeout(function (){
                    window.location.reload();
                },6500);
            }
        });
    }

    async function doRemove(id)
    {
        form = new FormData();
        form.append('id',id);
        form.append('_token','{!! @csrf_token() !!}');
        form.append('csrf','{!! @csrf_token() !!}');
        const response = await fetch(
            '{{route('remove-clientes')}}',
            {
                method: 'POST',
                headers: {
                    'x-rapidapi-host': 'carbonfootprint1.p.rapidapi.com',
                    'x-rapidapi-key': 'your_api_key'
                },
                body: form
            }
        );
        const res = await response.text();
        console.log(res)
        if(res)
        {
            resp = JSON.parse(res);
            console.log(resp);
            if(resp.status==202)
            {
                document.getElementById('success').style.display='block';
                document.getElementById('success').innerHTML='Cliente excluido com sucesso';
                setTimeout(function (){
                    document.getElementById('success').style.display='none';
                    location.reload();
                },6500);
            }
            else
            {
                document.getElementById('erno').style.display='block';
                document.getElementById('erno').innerHTML='Houve um erro ao excluir o cliente';
                setTimeout(function (){
                    document.getElementById('erno').style.display='none';
                    location.reload();
                },6500);
            }
        }
    }

    async function doActivate(id)
    {
        form = new FormData();
        form.append('id',id);
        form.append('_token','{!! @csrf_token() !!}');
        form.append('csrf','{!! @csrf_token() !!}');
        var input = document.querySelector('input[type="file"]');
        form.append('logo',input.files[0] );

        const response = await fetch(
            '{{route('active-clientes')}}',
            {
                method: 'POST',
                headers: {
                    'x-rapidapi-host': 'carbonfootprint1.p.rapidapi.com',
                    'x-rapidapi-key': 'your_api_key'
                },
                body: form
            }
        );
        const res = await response.text();
        if(res)
        {
            resp = JSON.parse(res);
            console.log(resp);

            if(resp.status==202)
            {
                document.getElementById('success').style.display='block';
                document.getElementById('success').innerHTML='Cliente ativado com sucesso';
                setTimeout(function (){
                    document.getElementById('success').style.display='none';
                    location.reload();
                },6500);
            }
            else
            {
                document.getElementById('erno').style.display='block';
                document.getElementById('erno').innerHTML='Houve um erro ao ativar o cliente';
                setTimeout(function (){
                    document.getElementById('erno').style.display='none';
                    location.reload();
                },6500);
            }
        }
    }

    async function doDeactivate(id)
    {
        form = new FormData();
        form.append('id',id);
        form.append('_token','{!! @csrf_token() !!}');
        form.append('csrf','{!! @csrf_token() !!}');
        const response = await fetch(
            '{{route('disable-clientes')}}',
            {
                method: 'POST',
                headers: {
                    'x-rapidapi-host': 'carbonfootprint1.p.rapidapi.com',
                    'x-rapidapi-key': 'your_api_key'
                },
                body: form
            }
        );
        const res = await response.text();
        console.log(res)
        if(res)
        {
            resp = JSON.parse(res);
            console.log(resp);
            if(resp.status==202)
            {
                document.getElementById('success').style.display='block';
                document.getElementById('success').innerHTML='Cliente desativado com sucesso';
                setTimeout(function (){
                    document.getElementById('success').style.display='none';
                    location.reload();
                },6500);
            }
            else
            {
                document.getElementById('erno').style.display='block';
                document.getElementById('erno').innerHTML='Houve um erro ao desativar o cliente';
                setTimeout(function (){
                    document.getElementById('erno').style.display='none';
                    location.reload();
                },6500);
            }
        }
    }

    function update(elem)
    {
        console.log(elem.value);
        //update cliente logo
        document.getElementById('clienteIDUpdate').value=elem.id;
        document.getElementById('imgUpdate').src=elem.value;
        $('#modalUpdate').modal('toggle');
    }

    function check(elem)
    {
            if(document.getElementById('logoCliente').value=="" || document.getElementById('logoCliente').value==undefined)
            {
                document.getElementById('msg2').innerHTML='Para atualizar o logo do cliente por favor selecione uma imagem.'
                return;
            }
            else
            {
                doUpdate(); return;
            }
    }

    async function doUpdate()
    {
        form = new FormData();
        form.append('idCliente',document.getElementById('clienteIDUpdate').value);
        form.append('_token','{!! @csrf_token() !!}');
        form.append('csrf','{!! @csrf_token() !!}');
        form.append('logo', document.getElementById('logoCliente').files[0]);

        const response = await fetch(
            '{{route('update-logo-clientes')}}',
            {
                method: 'POST',
                headers: {
                    'x-rapidapi-host': 'carbonfootprint1.p.rapidapi.com',
                    'x-rapidapi-key': 'your_api_key'
                },
                body: form
            }
        );
        const res = await response.text();
        if(res)
        {
            $('#modalUpdate').modal('hide');
            resp = JSON.parse(res);
            if(resp.status==202)
            {
                Swal.fire({
                    title: "Logo atualizado com sucesso!",
                    text: "",
                    icon: "success"
                });
            }
            else
            {
                document.getElementById('erno').style.display='block';
                document.getElementById('erno').innerHTML='Houve um erro ao ativar o cliente';
                setTimeout(function (){
                    document.getElementById('erno').style.display='none';
                    location.reload();
                },6500);
            }
        }
    }

</script>
</body>
</html>

