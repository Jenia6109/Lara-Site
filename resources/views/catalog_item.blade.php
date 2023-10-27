<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
    <body>
      @include('inc.header')
      @include('inc.messages')
      <script>
         $(function(){
            $('.add-to-cart').click(function(){
               $.ajax({
                  url: "{{ route('cart_add_item') }}",
                  type:"POST",
                  data:{
                     "_token": "{{ csrf_token() }}",
                     'item_id': $(this).attr('data-item-id')
                  },
                  success:function(response){
                     $('#cart-top').html('Корзина (' + response.count + ')');
                     $('#modal-text').html('Товар добавлен в корзину');
                     $('#modal-button').click();
                  },
               });
            });
         });
      </script>
         <div class="album py-5 bg-body-tertiary">
            <div class="container">
               @if( !empty($item) )
                  <div class="bd-example m-0 border-0">
                     <nav aria-label="breadcrumb">
                       <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="/">Главная</a></li>
                         <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Каталог</a></li>
                         <li class="breadcrumb-item"><a href="{{ route('catalog_rubric', $rubric['id']) }}">{{$rubric['name']}}</a></li>
                         <li class="breadcrumb-item active" aria-current="page">{{$item['name']}}</li>
                       </ol>
                     </nav>
                  </div>
                  <h1>{{$item['name']}}</h1>
                  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                     @if( !empty($item['img']) )
                        <img src="/items_imgs/{{$item['img']}}">
                     @endif
                     @if( !empty($item['text']) )
                        <p>{{$item['text']}}</p>
                     @endif
                     @if( !empty($item['text']) )
                        <p>{{number_format($item['cost'], 0, '.', ' ')}} руб.</p>
                     @endif
                  </div>
                  <br>
                  <button type="button" class="btn btn-primary btn-lg px-4 gap-3 add-to-cart" data-item-id="{{$item['id']}}">В корзину</button>
               @else
                  <p>Товар не найден</p>
               @endif
            </div>
        </div>
    </body>
</html>
