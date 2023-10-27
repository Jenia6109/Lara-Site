<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Controllers\Dadata\DadataClient;

class PogodaController extends Controller
{
   public function pogoda(Request $request) {
      $data = array();
      
      $user_ip = getenv( 'REMOTE_ADDR' );
      $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));

      if( !empty( $geo[ 'geoplugin_city' ] ) ){
         $token = "ee656a98b9cd6a3833449ba092e70881ac947e79";
         $dadata = new DadataClient($token, null);
         $dadata_city = $dadata -> suggest("address", $geo[ 'geoplugin_city' ]);
         $data[ 'city' ] = !empty( $dadata_city[0]['data']['city'] ) ? $dadata_city[0]['data']['city'] : $geo[ 'geoplugin_city' ]; 
         
      } else {
         $data[ 'city' ] = $geo[ 'geoplugin_countryName' ];
         
      } // End if
      
      $region = $geo[ 'geoplugin_timezone' ];
      $data[ 'cur_time' ] = Carbon::now($region) -> format('H:i');
      
      $opts = array(
         'http' => array(
            'method' => "GET",
            'header' => "X-Yandex-API-Key:45d060c4-4c8b-4334-ab37-d5ca10579f70"."\r\n",
         ),
      );
      $context = stream_context_create( $opts );
      $f = file_get_contents( "https://api.weather.yandex.ru/v2/forecast/?lat=" . $geo[ 'geoplugin_latitude' ] . "&lon=" . $geo[ 'geoplugin_longitude' ] . "&lang=ru_RU", false, $context );
      
      $f = json_decode( $f );
      
      $fact = $f -> fact;
      
      $conditions = array(
         'clear' => 'ясно',
         'partly-cloudy' => 'малооблачно',
         'cloudy' => 'облачно с прояснениями',
         'overcast' => 'пасмурно',
         'light-rain' => 'небольшой дождь',
         'rain' => 'дождь',
         'heavy-rain' => 'сильный дождь',
         'showers' => 'ливень',
         'wet-snow' => 'дождь со снегом',
         'light-snow' => 'небольшой снег',
         'snow' => 'снег',
         'snow-showers' => 'снегопад',
         'hail' => 'град',
         'thunderstorm' => 'гроза',
         'thunderstorm-with-rain' => 'дождь с грозой',
         'thunderstorm-with-hail' => 'гроза с градом',
      );
         
      $data[ 'today' ] = array(
         'temp' => ( $fact -> temp > 0 ? '+' : '' ) . $fact -> temp,
         'feels_like' => ( $fact -> feels_like > 0 ? '+' : '' ) . $fact -> feels_like,
         'icon' => "https://yastatic.net/weather/i/icons/funky/dark/" . $fact -> icon . ".svg",
         'condition' => Str::ucfirst( $conditions[ $fact -> condition ] ),
      );
      
      $cur_hour = Carbon::now($region) -> format('H');
      
      $data[ 'forecasts' ] = array();
      foreach( $f -> forecasts as $k => $forecast ) {
         if( $k == 0 ) {
            $data[ 'today' ][ 'hours' ] = array();
            foreach( $forecast -> hours as $hour ) {
               $hour_hour = explode( ':', $hour -> hour );
               if( (int) $hour_hour[ 0 ] < $cur_hour ) {
                  continue;
                  
               } // End if
               
               $data[ 'today' ][ 'hours' ][] = array(
                  'hour' => $hour -> hour,
                  'icon' => "https://yastatic.net/weather/i/icons/funky/dark/" . $hour -> icon . ".svg",
                  'temp' => ( $hour -> temp > 0 ? '+' : '' ) . $hour -> temp,
               );
               
            } // End foreach
            
         } // End if
         
         $months = array(
            1 => 'янв',
            2 => 'фев',
            3 => 'мар',
            4 => 'апр',
            5 => 'мая',
            6 => 'июня',
            7 => 'июля',
            8 => 'авг',
            9 => 'сент',
            10 => 'окт',
            11 => 'нояб',
            12 => 'дек',
         );
         $date = explode( '-', $forecast -> date );
         $date = (int) $date[ 2 ] . ' ' . $months[ (int) $date[ 1 ] ];
         
         $week_days = [
            "Пн",
            "Вт",
            "Ср",
            "Чт",
            "Пт",
            "Сб",
            "Вс",
         ];
         
         $data[ 'forecasts' ][] = array(
            'date' => $date,
            'day_of_week' => $week_days[ date( "w", $forecast -> date_ts ) ],
            'today' => $forecast -> date == Carbon::now() -> format('Y-m-d'),
            'icon' => "https://yastatic.net/weather/i/icons/funky/dark/" . $forecast -> parts -> day_short -> icon . ".svg",
            'temp' => ( $forecast -> parts -> day_short -> temp > 0 ? '+' : '' ) . $forecast -> parts -> day_short -> temp,
            'condition' => Str::ucfirst( $conditions[ $forecast -> parts -> day_short -> condition ] ),
         );
         
      } // End foreach
      
      return view( 'pogoda', $data );
      
   } // End store
   
}
