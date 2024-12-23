<?php
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\AutoTimeout;
use Illuminate\Support\Facades\Route;

Route::match(['get'], '/', function () {return view('login');})->middleware('guest');

Route::match(['get'], '/login', function () {return view('login');})->middleware('guest')->name('login');

Route::match(['get'], '/forgot-password', function (){ return view('forgot-password');})->middleware('guest')->name('forgot-password');

Route::match(['post'],'/userLogin', '\App\Http\Controllers\LoginController@userLogin')->name('userLogin');

Route::match(['get'], '/list-news','\App\Http\Controllers\NewsController@dashboard')->middleware('auth',AutoTimeout::class)->name('list-news');

Route::match(['get'],'/add_news','\App\Http\Controllers\NewsController@add_News')->middleware('auth',AutoTimeout::class)->name('add_news');

Route::match(['post'],'/register_news','\App\Http\Controllers\NewsController@registerNews')->middleware('auth',AutoTimeout::class)->name('register_news');

Route::match(['get'], '/news', '\App\Http\Controllers\NotificationController@getNotifications')->middleware(AutoTimeout::class)->name('list-notifications');

Route::match(['post'], '/news', '\App\Http\Controllers\NotificationController@disableNotification')->name('remove-notification');

Route::match(['get'],'/news-manager','\App\Http\Controllers\NewsController@newsManager')->middleware('auth',AutoTimeout::class)->name('news-manager');

Route::match(['post'],'/news-action','\App\Http\Controllers\NewsController@newsAction')->middleware('auth',AutoTimeout::class)->name('newsAction');

Route::match(['get'],'/logout','\App\Http\Controllers\LoginController@userLogout')->middleware('auth',PreventBackHistory::class)->name('logout');

Route::match(['get','post'],'/new_user','\App\Http\Controllers\UserController@newUser')->middleware('auth',AutoTimeout::class)->name('new_user');

Route::match(['post'],'/add_user','\App\Http\Controllers\UserController@createNewUser')->middleware('auth',AutoTimeout::class)->name('add_user');

Route::match(['get'],'/token/{token}','\App\Http\Controllers\UserController@checkUserToken')->middleware('guest')->name('user-token');

Route::match(['get'],'/expired_link',function (){ return view('expired-link');})->middleware('guest',PreventBackHistory::class)->name('expired-link');

Route::match(['post'],'/recover-password','\App\Http\Controllers\UserController@recoverPassword')->middleware('guest')->name('recoverPassword');

Route::match(['post'],'/update-password','\App\Http\Controllers\UserController@registerUserPassword')->middleware('guest')->name('updatePassword');

Route::match(['get'],'/invoice-upload','\App\Http\Controllers\Invoice@index')->middleware('auth',AutoTimeout::class)->name('invoice-upload');

Route::match(['post'],'/upload-invoice','\App\Http\Controllers\Invoice@uploadInvoice')->middleware('auth',AutoTimeout::class)->name('uploadInvoice');

Route::match(['get'],'/chamados-upload','\App\Http\Controllers\Chamados@index')->middleware('auth',AutoTimeout::class)->name('chamados-upload');

Route::match(['post'],'/upload-chamados','\App\Http\Controllers\Chamados@uploadChamados')->middleware('auth',AutoTimeout::class)->name('upload-chamados');

Route::match(['get'],'/cliente_manager','\App\Http\Controllers\ClienteController@index')->middleware('auth', AutoTimeout::class)->name('cliente_manager');

Route::match(['post', 'get'],'/filter_clientes', '\App\Http\Controllers\ClienteController@filterClientes')->middleware('auth', AutoTimeout::class)->name('filter-clientes');

Route::match(['post'],'/remove-clientes', '\App\Http\Controllers\ClienteController@RemoveClientes')->middleware('auth', AutoTimeout::class)->name('remove-clientes');

Route::match(['post'],'/active-clientes', '\App\Http\Controllers\ClienteController@ActiveClientes')->middleware('auth', AutoTimeout::class)->name('active-clientes');

Route::match(['post'],'/disable-clientes', '\App\Http\Controllers\ClienteController@DeactiveClientes')->middleware('auth', AutoTimeout::class)->name('disable-clientes');

Route::match(['post'],'/update-logo-clientes', '\App\Http\Controllers\ClienteController@UpdateLogoClientes')->middleware('auth', AutoTimeout::class)->name('update-logo-clientes');

Route::match(['get'],'/cliente_manager','\App\Http\Controllers\ClienteController@index')->middleware('auth', AutoTimeout::class)->name('cliente_manager');

Route::match(['get'],'/usuarios_clientes','\App\Http\Controllers\ClienteController@getUsersFromCliente')->middleware('auth', AutoTimeout::class)->name('usuarios_clientes');

Route::match(['post', 'get'],'/filter_users', '\App\Http\Controllers\UserController@filterUsers')->middleware('auth', AutoTimeout::class)->name('filter-users');

Route::match(['get'],'/inventario','App\Http\Controllers\Inventario@getInventario')->middleware('auth', AutoTimeout::class)->name('inventario');

Route::match(['get'],'/faturamento','App\Http\Controllers\Invoice@getFaturamento')->middleware('auth', AutoTimeout::class)->name('faturamento');

Route::match(['get'],'/faturamento_detalhado','App\Http\Controllers\Invoice@getDetailsFaturamento')->middleware('auth', AutoTimeout::class)->name('faturamento_detalhado');

Route::match(['get'],'/dashboard-faturamento','App\Http\Controllers\Invoice@getDashFaturamento')->middleware('auth', AutoTimeout::class)->name('dashboard-faturamento');

Route::match(['get'],'/dashboard-chamados', "App\Http\Controllers\Chamados@getDashboardChamados")->middleware('auth', AutoTimeout::class)->name('dashboard-chamados');

Route::match(['get'],'/inventario_detalhado','App\Http\Controllers\Inventario@getInventarioDetails')->middleware('auth', AutoTimeout::class)->name('inventario_detalhado');

Route::match(['get'],'/chamados','App\Http\Controllers\Chamados@getChamados')->middleware('auth', AutoTimeout::class)->name('chamados');

Route::match(['get'],'/tracking', 'App\Http\Controllers\TrackingController@getTracking')->middleware('auth', AutoTimeout::class)->name('tracking');

Route::match(['get'],'/tracking_detalhado', 'App\Http\Controllers\TrackingController@getTrackingDetails')->middleware('auth', AutoTimeout::class)->name('tracking_detalhado');
