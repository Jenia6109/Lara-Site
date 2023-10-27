<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
    <body>
      @include('inc.header')
      @include('inc.messages')
      <script>
         $(function(){
            $('.delete-from-cart').click(function(){
               var el = $(this);
               $.ajax({
                  url: "{{ route('cart_delete_cart_item') }}",
                  type:"POST",
                  data:{
                     "_token": "{{ csrf_token() }}",
                     'cart_item_id': $(this).attr('data-cart-item-id')
                  },
                  success:function(response){
                     el.parent().parent().remove();
                     $('#cart-top').html('Корзина (' + response.count + ')');
                     if( $('#cart-table tr').length == 1 ) {
                        location.reload();
                     }
                  },
               });
            });
         });
      </script>
      <div class="container px-4 py-5" id="featured-3">
         <div class="bd-example m-0 border-0">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Корзина</li>
              </ol>
            </nav>
         </div>
         <h1>Корзина</h1>
         @if( !empty($cart_items) )
            <table class="table" id="cart-table">
               <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Товар</th>
                  <th scope="col">Количество</th>
                  <th scope="col">Цена</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($cart_items as $k => $cart_item)
                   <tr>
                     <th scope="row">{{$k+1}}</th>
                     <td>{{$cart_item['name']}}</td>
                     <td>{{$cart_item['count']}}</td>
                     <td>{{number_format($cart_item['total_cost'], 0, '.', ' ')}} руб.</td>
                     <td><button type="button" class="btn btn-primary btn-lg px-4 gap-3 delete-from-cart" data-cart-item-id="{{$cart_item['id']}}">Удалить</button></td>
                   </tr>
                @endforeach
              </tbody>
           </table>
           <br><br>
           <div class="col-md-7 col-lg-8">
              <h4 class="mb-3">Оформить заказ</h4>
              <form class="needs-validation" method="post" action="{{ route('cart_send_order') }}">
               @csrf
                <div class="row g-3">
                  <div class="col-sm-6">
                    <label for="firstName" class="form-label">ФИО</label>
                     @if( Auth::user() )
                        <input type="text" class="form-control" id="firstName" name="fio" placeholder="" value="{{ Auth::user() -> name }}" required="">
                     @else
                        <input type="text" class="form-control" id="firstName" placeholder="" value="" required="">
                     @endif
                    <div class="invalid-feedback">
                      Valid first name is required.
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                     @if( Auth::user() )
                        <input type="email" class="form-control" id="email" name="email" placeholder="" value="{{ Auth::user() -> email }}" required="">
                     @else
                        <input type="email" class="form-control" id="email" name="email" placeholder="" required="">
                     @endif
                    <div class="invalid-feedback">
                      Please enter a valid email address for shipping updates.
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="">
                    <div class="invalid-feedback">
                      Please enter your phone.
                    </div>
                  </div>
                  
                  <div class="col-12">
                    <label for="address" class="form-label">Адрес</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="">
                    <div class="invalid-feedback">
                      Please enter your shipping address.
                    </div>
                  </div>
                </div>

                <hr class="my-4">
                <h4 class="mb-3">Оплата</h4>
                <div class="my-3">
                  <div class="form-check">
                    <input id="credit" name="pay_type" type="radio" class="form-check-input" checked="" required="" value="1">
                    <label class="form-check-label" for="credit">Наличными</label>
                  </div>
                  <div class="form-check">
                    <input id="debit" name="pay_type" type="radio" class="form-check-input" required="" value="2">
                    <label class="form-check-label" for="debit">Банковской картой</label>
                  </div>
                </div>
                <hr class="my-4">
                <button class="w-1 btn btn-primary btn-lg" type="submit">Заказать</button>
              </form>
            </div>
         @else
            <h4>В корзине нет товаров</h4>
         @endif
      </div>
    </body>
</html>
