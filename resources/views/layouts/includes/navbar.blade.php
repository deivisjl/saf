<nav class="navbar navbar-expand-sm navbar-dark bg-dark sticky-top">
  <a class="navbar-brand" href="/">Inicio</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Accesos
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('permisos.index') }}">Permisos</a>
            <a class="dropdown-item" href="{{ route('roles.index') }}">Roles</a>
            <a class="dropdown-item" href="{{ route('usuarios-roles.index') }}">Asignar roles</a>
          </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Gestión
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('categorias.index') }}">Categorías</a>
          <a class="dropdown-item" href="{{ route('estados.index') }}">Estados</a>
          <a class="dropdown-item" href="{{ route('forma-pago.index') }}">Formas de pago</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('proveedores.index') }}">Proveedores</a>
          <a class="dropdown-item" href="{{ route('clientes.index') }}">Clientes</a>
          <a class="dropdown-item" href="{{ route('productos.index') }}">Productos</a>
        </div>
    </li>
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('compras.index') }}">Compras</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('inventario.index') }}">Inventario</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('ventas.index') }}">Ventas</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Pagos
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('pago-proveedores.index') }}">Pago a proveedores</a>
          <a class="dropdown-item" href="{{ route('pago-clientes.index') }}">Pago a clientes</a>
        </div>
    </li>
    </ul>
    <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
          <a class="nav-link btn btn-outline-success" data-toggle="dropdown" href="#">
             {{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Más opciones</span>
          <div class="dropdown-divider"></div>
          <a href="{{ route('logout') }}" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr2"></i> Cerrar sesión
          </a>
          </div>
      </li>
      </ul>
  </div>
</nav>