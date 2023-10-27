<div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary" style="width: 280px;">
    <a href="{{ route('admin') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
      <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
      <span class="fs-4">Админ. панель</span>
    </a>
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
      <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
      <span class="fs-4">Lara Site</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a href="{{ route('admin_posts') }}" class="nav-link @if( request()->is(trim(route('admin_posts', null, false), '/')) ) active @else link-body-emphasis @endif">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
          Забор
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin_catalog') }}" class="nav-link @if( request()->is(trim(route('admin_catalog', null, false), '/')) ) active @else link-body-emphasis @endif">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
          Каталог
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin_orders') }}" class="nav-link @if( request()->is(trim(route('admin_orders', null, false), '/')) ) active @else link-body-emphasis @endif">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
          Заказы
        </a>
      </li>
    </ul>
  </div>
  <div class="b-example-divider b-example-vr"></div>