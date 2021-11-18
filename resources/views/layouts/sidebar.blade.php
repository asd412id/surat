<aside class="main-sidebar sidebar-dark-olive">
  <a href="{{ route('home') }}" class="brand-link">
    <img src="{{ env('APP_ICON',asset('img/logo.png')) }}" alt="AdminLTE Logo"
      class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text">{{ env('APP_ABBR','APELAH') }}</span>
  </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img
          src="{{ asset('img').(auth()->user()->is_admin || (auth()->user()->guru &&  auth()->user()->guru->jenis_kelamin=='L') || (auth()->user()->siswa && auth()->user()->siswa->jenis_kelamin=='L')?'/avatar5.png':'/avatar3.png') }}"
          class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" data-url="{{ route('account') }}" class="open-modal" title="{{ auth()->user()->name }}">{{
          auth()->user()->name }}</a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home')?'active':'' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Beranda</p>
          </a>
        </li>
        @if (auth()->user()->is_admin)
        @include('layouts.sidebars.admin')
        @else
        @include('layouts.sidebars.guru')
        @endif
      </ul>
    </nav>
  </div>
</aside>