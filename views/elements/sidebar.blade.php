
    <!-- Nav Item - Tasks list -->
    <!-- YES, I KNOW IT'S UGLY, BUT LIMITATION OF FREE VERSION -->
    <li class="nav-item @if (isset($route) and $route['view'] == 'homepage') active @endif">
      <a class="nav-link" href="/">
        <i class="fas fa-tasks"></i>
        <span>Tasks list</span></a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-info-circle"></i>
      <span>Information</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="/about">About service</a>
        <a class="collapse-item" href="/some-wrong-way">404 page</a>
        </div>
    </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    @if (isset($user) and $user['logged'])
    <li class="nav-item @if (isset($route) and $route['view'] == 'logout') active @endif">
      <a class="nav-link" href="/logout">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </li>
    @else
    <li class="nav-item @if (isset($route) and $route['view'] == 'login') active @endif">
      <a class="nav-link" href="/login">
        <i class="fas fa-sign-in-alt"></i>
        <span>Login</span>
      </a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

