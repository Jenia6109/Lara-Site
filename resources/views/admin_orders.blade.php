<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
    <body>
      <main class="d-flex flex-nowrap">
         @include('inc.admin_sidebar')
         @include('inc.messages')
         @if( !empty($orders) )
            <script>
               $(function(){
               });
            </script>
            <div class="container px-4 py-5" id="featured-3">
                <h2 class="pb-2 border-bottom">Заказы</h2>
                <div class="row g-4 py-5 row-cols-1 row-cols-lg-30">
                  @foreach ($orders as $order)
                     <a href="{{ route('admin_order_edit', $order['id']) }}" class="px-3">{{$order['id']}}. {{$order['date']}} {{$order['fio']}} {{$order['email']}} {{$order['pay_type']}} {{$order['status']}}</a><br>
                  @endforeach
                </div>
            </div>
         @endif
      </main>
    </body>
</html>
