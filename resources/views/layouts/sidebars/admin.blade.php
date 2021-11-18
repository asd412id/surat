<li class="nav-item">
  <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user*')?'active':'' }}">
    <i class="nav-icon fas fa-users"></i>
    <p>User</p>
  </a>
</li>
<li class="nav-item">
  <a href="{{ route('jenis-surat.index') }}" class="nav-link {{ request()->routeIs('jenis-surat*')?'active':'' }}">
    <i class="nav-icon fas fa-tags"></i>
    <p>Jenis Surat</p>
  </a>
</li>
<li class="nav-item">
  <a href="{{ route('surat.index') }}" class="nav-link {{ request()->routeIs('surat*')?'active':'' }}">
    <i class="nav-icon fas fa-envelope-open"></i>
    <p>Surat</p>
  </a>
</li>