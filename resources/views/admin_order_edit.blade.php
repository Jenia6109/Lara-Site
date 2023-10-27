<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
   <body>
      <main class="d-flex flex-nowrap">
         @include('inc.admin_sidebar')
         @include('inc.messages')
         @if( !empty($order) )
            <script>
               $(function(){
               });
            </script>
            <div class="container">
                  <div class="row g-5">
                  <div class="col-md- col-lg-">
                    <h4 class="mb-3">Редактирование заказа</h4>
                      <div class="row g-3">
                        ID: {{$order['id']}}<br>
                        Дата: {{$order['date']}}<br>
                        ФИО: {{$order['fio']}}<br>
                        Email: {{$order['email']}}<br>
                        Телефон: {{$order['phone']}}<br>
                        Адрес: {{$order['address']}}<br>
                        Способ оплаты: {{$order['pay_type']}}<br>
                        Статус: {{$order['status']}}<br>
                        <h4>Товары</h4>
                        @if( !empty($items) )
                           <table>
                              <tr>
                                 <td>Название</td>
                                 <td>Количество</td>
                                 <td>Цена</td>
                              </tr>
                              @foreach ($items as $item)
                                 <tr>
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['count']}}</td>
                                    <td>{{number_format($item['cost'], 0, '.', ' ')}} руб.</td>
                                 </tr>
                              @endforeach
                              <tr>
                                 <td colspan="2">Итого</td>
                                 <td>{{number_format($total_cost, 0, '.', ' ')}} руб.</td>
                              </tr>
                           </table>
                        @endif
                      </div>
                  </div>
               </div>
            </div>
         @endif
      </main>
   </body>
</html>
