<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?php echo site_url('/'); ?>" class="brand-link">
    <span class="brand-text font-weight-light">Abadia App</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo html_escape($this->ion_auth->user()->row()->first_name ?? 'Usuario'); ?></a>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
        <li class="nav-item">
          <a href="<?php echo site_url('dashboard'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'dashboard') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <?php if ($this->ion_auth->is_admin()): ?>
        <!-- Admin Configuration Section -->
        <li class="nav-header">CONFIGURACIÓN DEL SISTEMA</li>
        
        <!-- Geographical Structure -->
        <li class="nav-item has-treeview <?php echo ($this->router->fetch_class() == 'ubicaciones') ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo ($this->router->fetch_class() == 'ubicaciones') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-map-marker-alt"></i>
            <p>
              Estructura Geográfica
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview" style="<?php echo ($this->router->fetch_class() == 'ubicaciones') ? 'display: block;' : 'display: none;'; ?>">
            <li class="nav-item">
              <a href="<?php echo site_url('ubicaciones/estados'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'ubicaciones' && $this->router->fetch_method() == 'estados') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Estados</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo site_url('ubicaciones/ciudades'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'ubicaciones' && $this->router->fetch_method() == 'ciudades') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Ciudades</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo site_url('ubicaciones/municipios'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'ubicaciones' && $this->router->fetch_method() == 'municipios') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Municipios</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo site_url('ubicaciones/parroquias'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'ubicaciones' && $this->router->fetch_method() == 'parroquias') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Parroquias</p>
              </a>
            </li>
          </ul>
        </li>
        
        <!-- Visitors Management -->
        <li class="nav-item has-treeview <?php echo ($this->router->fetch_class() == 'visitantes') ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo ($this->router->fetch_class() == 'visitantes') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Visitantes del Sitio
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview" style="<?php echo ($this->router->fetch_class() == 'visitantes') ? 'display: block;' : 'display: none;'; ?>">
            <li class="nav-item">
              <a href="<?php echo site_url('visitantes'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'visitantes' && $this->router->fetch_method() == 'index') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Gestionar Visitantes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo site_url('visitantes/stats'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'visitantes' && $this->router->fetch_method() == 'stats') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Estadísticas</p>
              </a>
            </li>
          </ul>
        </li>
        
        <!-- Agentes Management (Admin only) -->
        <li class="nav-item">
          <a href="<?php echo site_url('agentes'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'agentes') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>Gestión de Agentes</p>
          </a>
        </li>
        <?php endif; ?>
        
        <!-- Business Operations Section -->
        <?php if ($this->ion_auth->in_group('Gerente General') || $this->ion_auth->in_group('Gerente de Zona') || $this->ion_auth->in_group('Supervisor') || $this->ion_auth->in_group('Agente')): ?>
        <li class="nav-header">OPERACIONES COMERCIALES</li>
        
        <!-- Afiliaciones Menu -->
        <li class="nav-item has-treeview <?php echo ($this->router->fetch_class() == 'afiliaciones') ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo ($this->router->fetch_class() == 'afiliaciones') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-file-contract"></i>
            <p>
              Afiliaciones
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview" style="<?php echo ($this->router->fetch_class() == 'afiliaciones') ? 'display: block;' : 'display: none;'; ?>">
            <?php if ($this->ion_auth->in_group('Gerente General') || $this->ion_auth->in_group('Gerente de Zona') || $this->ion_auth->in_group('Supervisor')): ?>
            <li class="nav-item">
              <a href="<?php echo site_url('afiliaciones'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'afiliaciones' && $this->router->fetch_method() == 'index') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado de Afiliaciones</p>
              </a>
            </li>
            <?php endif; ?>
            
            <li class="nav-item">
              <a href="<?php echo site_url('afiliaciones/create'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'afiliaciones' && $this->router->fetch_method() == 'create') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Nueva Afiliación</p>
              </a>
            </li>
          </ul>
        </li>
        
        <!-- Performance Stats Menu -->
        <li class="nav-item has-treeview <?php echo ($this->router->fetch_class() == 'stats') ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo ($this->router->fetch_class() == 'stats') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>
              Estadísticas de Desempeño
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview" style="<?php echo ($this->router->fetch_class() == 'stats') ? 'display: block;' : 'display: none;'; ?>">
            <?php if ($this->ion_auth->in_group('Agente')): ?>
            <li class="nav-item">
              <a href="<?php echo site_url('stats/agent'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'stats' && $this->router->fetch_method() == 'agent') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Mis Estadísticas</p>
              </a>
            </li>
            <?php endif; ?>
            
            <?php if ($this->ion_auth->in_group('Supervisor')): ?>
            <li class="nav-item">
              <a href="<?php echo site_url('stats/supervisor'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'stats' && $this->router->fetch_method() == 'supervisor') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Mi Equipo</p>
              </a>
            </li>
            <?php endif; ?>
            
            <?php if ($this->ion_auth->in_group('Gerente de Zona')): ?>
            <li class="nav-item">
              <a href="<?php echo site_url('stats/zone_manager'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'stats' && $this->router->fetch_method() == 'zone_manager') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Mi Zona</p>
              </a>
            </li>
            <?php endif; ?>
            
            <?php if ($this->ion_auth->in_group('Gerente General')): ?>
            <li class="nav-item">
              <a href="<?php echo site_url('stats/general_manager'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'stats' && $this->router->fetch_method() == 'general_manager') ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>General</p>
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>
        
        <!-- Reports Section -->
        <?php if ($this->ion_auth->in_group('Gerente General') || $this->ion_auth->in_group('Gerente de Zona') || $this->ion_auth->in_group('Supervisor')): ?>
        <li class="nav-header">REPORTES</li>
        <li class="nav-item">
          <a href="<?php echo site_url('reports'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'reports') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Reportes Generales</p>
          </a>
        </li>
        <?php endif; ?>
        
        <!-- User Settings -->
        <li class="nav-header">CONFIGURACIÓN</li>
        <li class="nav-item">
          <a href="<?php echo site_url('auth/profile'); ?>" class="nav-link <?php echo ($this->router->fetch_class() == 'auth' && $this->router->fetch_method() == 'profile') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Mi Perfil</p>
          </a>
        </li>
        
        <!-- Logout -->
        <li class="nav-item">
          <a href="<?php echo site_url('auth/logout'); ?>" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Cerrar Sesión</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>