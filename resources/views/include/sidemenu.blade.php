<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark m-aside-menu--dropdown " data-menu-vertical="true" m-menu-dropdown="1" m-menu-scrollable="0" m-menu-dropdown-timeout="500">
  <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
    <li class="m-menu__item <?php if( Route::currentRouteName() == 'home'){ echo "m-menu__item--active"; } ?>" aria-haspopup="true">
      <a href="{{route('home')}}" class="m-menu__link ">
        <span class="m-menu__item-here"></span>
        <i class="m-menu__link-icon flaticon-line-graph"></i>
        <span class="m-menu__link-text">Dashboard</span>
      </a>
    </li>
    <li class="m-menu__item  <?php if( Route::currentRouteName() == 'purchaseadd'){ echo "m-menu__item--active"; } ?>" aria-haspopup="true">
      <a href="{{route('purchaseadd')}}" class="m-menu__link ">
        <span class="m-menu__item-here"></span>
        <i class="m-menu__link-icon flaticon-cart"></i>
        <span class="m-menu__link-text">POS</span>
      </a>
    </li>
    <li class="m-menu__item <?php if( in_array(Route::currentRouteName(), ["purchaselist", "purchaseedit"])){ echo "m-menu__item--active"; } ?>" aria-haspopup="true">
      <a href="{{route('purchaselist')}}" class="m-menu__link ">
        <span class="m-menu__item-here"></span>
        <i class="m-menu__link-icon flaticon-file-2"></i>
        <span class="m-menu__link-text">Purchase</span>
      </a>
    </li>
    <li class="m-menu__item <?php if( in_array(Route::currentRouteName(), ["inventorylist", "inventoryedit", "inventoryadd"])){ echo "m-menu__item--active"; } ?>" aria-haspopup="true">
      <a href="{{route('inventorylist')}}" class="m-menu__link ">
        <span class="m-menu__item-here"></span>
        <i class="m-menu__link-icon flaticon-squares"></i>
        <span class="m-menu__link-text">Inventory</span>
      </a>
    </li>
    <li class="m-menu__item <?php if( in_array(Route::currentRouteName(), ["requestlist", "requestedit", "requestadd"])){ echo "m-menu__item--active"; } ?>" aria-haspopup="true">
      <a href="{{route('requestlist')}}" class="m-menu__link ">
        <span class="m-menu__item-here"></span>
        <i class="m-menu__link-icon flaticon-add-circular-button"></i>
        <span class="m-menu__link-text">Request</span>
      </a>
    </li>
    <li class="m-menu__item <?php if( in_array(Route::currentRouteName(), ["deliveredlist", "deliverededit", "deliveredadd"])){ echo "m-menu__item--active"; } ?>" aria-haspopup="true">
      <a href="{{route('deliveredlist')}}" class="m-menu__link ">
        <span class="m-menu__item-here"></span>
        <i class="m-menu__link-icon flaticon-truck"></i>
        <span class="m-menu__link-text">Delivered</span>
      </a>
    </li>
  <?php if(session("usertype") == "manager" ){ ?>
    <li class="m-menu__item <?php if( in_array(Route::currentRouteName(), ["userlist", "useredit", "useradd"])){ echo "m-menu__item--active"; } ?>" aria-haspopup="true">
      <a href="{{route('userlist')}}" class="m-menu__link ">
        <span class="m-menu__item-here"></span>
        <i class="m-menu__link-icon flaticon-user-settings"></i>
        <span class="m-menu__link-text">User</span>
      </a>
    </li>
  <?php } ?>
    <li class="m-menu__item" aria-haspopup="true">
      <a href="{{route('logout')}}" onclick="$('#logoutForm').submit(); return false;" class="m-menu__link ">
        <span class="m-menu__item-here"></span>
        <i class="m-menu__link-icon flaticon-logout"></i>
        <span class="m-menu__link-text">Logout</span>
      </a>
    </li>
  </ul>
</div>
<form id="logoutForm" method="post" action="{{route('logout')}}">
  @csrf
</form>
