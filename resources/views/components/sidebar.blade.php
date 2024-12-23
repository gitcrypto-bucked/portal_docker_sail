@php($menu = new \App\Http\Controllers\menuController())
<aside id="sidebar-wrapper" >
    <div class="sidebar-brand">
        <!--desktop display -->
        {!! $menu->getDesktopLogo() !!}
        <!--desktop display end-->
        <!--user display on mobile -->
        {!! $menu->getMobileLogo() !!}
        <!--user display on mobile end-->
    </div>
    <ul class="sidebar-nav">
      {!! $menu->getMenu() !!}
    </ul>
    <br>
</aside>
