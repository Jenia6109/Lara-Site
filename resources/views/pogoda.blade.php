<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
    <body>
      @include('inc.header')
      <div class="container">
         <div class="bd-example m-0 border-0">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Погода</li>
              </ol>
            </nav>
         </div>
         <h1>Погода</h1>
         <script>
            $(function(){
               
            });
         </script>
         <p>
            {{$city}}<br>
            Сейчас {{ $cur_time }}<br>
            <img src='{{$today['icon']}}'>
            {{$today['temp']}}°
            {{$today['condition']}}<br>
            Ощущается как {{$today['feels_like']}}°<br>
         </p>
         @if( !empty($today['hours']) )
            <table class="table table-sm">
               <tr>
                  @foreach ($today['hours'] as $hour)
                     <td>
                        {{$hour['hour']}}:00<br>
                        <img src='{{$hour['icon']}}'><br>
                        {{$hour['temp']}}°
                     </td>
                  @endforeach
               </tr>
            </table>
         @endif
         @if( !empty($forecasts) )
            <table class="table table-sm">
               <tr>
                  @foreach ($forecasts as $forecast)
                     <td>
                        @if( !empty($forecast['today']) )Сегодня@else{{$forecast['day_of_week']}}@endif<br>
                        {{$forecast['date']}}<br>
                        <img src='{{$forecast['icon']}}'>
                        {{$forecast['temp']}}°<br>
                        {{$forecast['condition']}}
                     </td>
                  @endforeach
               </tr>
            </table>
         @endif
      </div>
    </body>
</html>
