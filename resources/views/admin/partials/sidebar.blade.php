<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion " id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon">
         {{ 'Resource Manager' }}
        </div>
        <div class="sidebar-brand-text mx-3 d-sm-block d-md-none d-lg-none">{{ 'Resoure Manager' }}</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

  
      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
  
           <!-- Divider -->
   

    
      <li class="nav-item {{ Route::currentRouteName() == 'admin.resources.index' ? 'active' : '' }}" >
        <a class="nav-link" href="{{ route('admin.resources.index') }}" >
          <i class="fas fa-shopping-bag"></i>
          <span>Resources</span></a>
      </li>

      
      
     


      
      
     



      



     
     
      
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>


