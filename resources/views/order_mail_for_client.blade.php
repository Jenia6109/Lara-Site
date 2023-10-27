ID заказа: {{$id}}<br>
ФИО: {{$fio}}<br>
Email: {{$email}}<br>
Телефон: {{$phone}}<br>
Адрес: {{$address}}<br>
Способ оплаты: {{$pay_type}}<br>
<h4>Товары<h4>
@if( !empty($items) )
   <table>
      <tr>
         <td>Название</td>
         <td>Количество</td>
         <td>Цена</td>
      </tr>
      @foreach ($items as $item)
         <tr>
            <td>{{$item['item_name']}}</td>
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