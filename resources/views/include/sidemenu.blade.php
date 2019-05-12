<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark m-aside-menu--dropdown " data-menu-vertical="true" m-menu-dropdown="1" m-menu-scrollable="0" m-menu-dropdown-timeout="500">
  <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
    <li class="m-menu__item  m-menu__item--active" aria-haspopup="true">
      <a href="{{route('home')}}" class="m-menu__link ">
        <span class="m-menu__item-here"></span>
        <i class="m-menu__link-icon flaticon-line-graph"></i>
        <span class="m-menu__link-text">Dashboard</span>
      </a>
    </li>
    <li class="m-menu__item  m-menu__item--submenu m-menu__item--bottom-2" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-settings"></i><span
         class="m-menu__link-text">Settings</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
      <div class="m-menu__submenu m-menu__submenu--up"><span class="m-menu__arrow"></span>
        <ul class="m-menu__subnav">
          <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1">
            <a href="{{route('logout')}}" onclick="$('#logoutForm').submit(); return false;" class="m-menu__link ">
              <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
              <span class="m-menu__link-text">Logout</span>
            </a>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</div>
<form id="logoutForm" method="post" action="{{route('logout')}}">
  @csrf
</form>
