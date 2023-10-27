<div class="container">
   <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-31 border-bottom1">
      <div class="col-md-3 mb-2 mb-md-0">
        <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
          <span class="fs-4">Lara Site</span>
        </a>
         @if( Auth::user() && Auth::user()->admin == 1 )
           <a href="{{ route('admin') }}" class="d-inline-flex link-body-emphasis text-decoration-none">
             <span class="fs-4">Админ. панель</span>
           </a>
         @endif
      </div>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li>
            @if( request()->is(str_replace('/', '', route('pogoda', null, false))) )
               <span class="nav-link px-2">Погода</span>
            @else
               <a href="{{ route('pogoda') }}" class="nav-link px-2">Погода</a>
            @endif
        </li>
        <li>
            @if( request()->is(str_replace('/', '', route('posts', null, false))) )
               <span class="nav-link px-2">Забор</span>
            @else
               <a href="{{ route('posts') }}" class="nav-link px-2">Забор</a>
            @endif
        </li>
        <li>
            @if( request()->is(str_replace('/', '', route('catalog', null, false))) )
               <span class="nav-link px-2">Каталог</span>
            @else
               <a href="{{ route('catalog') }}" class="nav-link px-2">Каталог</a>
            @endif
        </li>
      </ul>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li>
            @php
               $cart_count = App\Http\Controllers\CartController::cart_count();
            @endphp
            @if( request()->is(str_replace('/', '', route('cart', null, false))) )
               <span class="nav-link px-2" id="cart-top">Корзина{{!empty($cart_count)? (' ('.$cart_count.')'):''}}</span>
            @else
               <a href="{{ route('cart') }}" class="nav-link px-2" id="cart-top">Корзина{{!empty($cart_count)? (' ('.$cart_count.')'):''}}</a>
            @endif
        </li>
      </ul>
      @if( Auth::user() )
         <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <div class="dropdown text-end">
                <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                  @if( !empty(Auth::user()->avatar) )
                     <img src="/avatars/{{Auth::user()->avatar}}" alt="mdo" width="32" height="32" class="rounded-circle">
                  @endif
                  {{ Auth::user() -> name }}
                </a>
                <ul class="dropdown-menu text-small" style="">
                  <li><a class="dropdown-item" href="{{route('profile.edit')}}">Профиль</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                     <form method="POST" action="{{ route('logout') }}">
                         @csrf
                         <a class="dropdown-item" href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Выйти') }}</a>
                     </form>
                  </li>
                </ul>
            </div>
         </div>
      @else
         <div class="col-md-3 text-end">
            <a href="{{ route('login') }}"><button type="button" class="btn btn-outline-primary me-2">Войти</button></a>
            <a href="{{ route('register') }}"><button type="button" class="btn btn-primary" style="background-color:var(--bs-btn-bg)!important;">Зарегистрироваться</button></a>
         </div>
      @endif
    </header>
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" id="modal-button" style="display:none;"></button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-text"></div>
    </div>
  </div>
</div>