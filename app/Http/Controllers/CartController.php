<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CatalogItem;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Mail;

class CartController extends Controller
{
   public function cart_add_item(Request $request) {
      $status = '';
      
      $cart = $this::get_cart();
      
      $cart_item_model = new CartItem;
      $cart_item = $cart_item_model -> where( 'cart_id', $cart[ 'id' ] ) -> where( 'item_id', $request -> item_id ) -> first();
      if( !empty( $cart_item ) ) {
         $cart_item -> count = $cart_item -> count + 1;
         $cart_item -> save();
         
      } else {
         $cart_item = new CartItem();
         $cart_item -> count = 1;
         $cart_item -> item_id = $request -> item_id;
         $cart_item -> cart_id = $cart[ 'id' ];
         $cart_item -> save();
         
      } // End if
      
      $count = self::cart_count();
      
      return response()->json( [ 
         'status' => $status,
         'count' => $count,
      ]);
      
   } // End cart_add_item
   
   
   
   private static function get_cart() {
      $cart_key = Cookie::get('cart_key');
      if( !empty( $cart_key ) ) {
         $cart_model = new Cart;
         $cart = $cart_model -> where( 'key', $cart_key ) -> first();
         if( !empty( $cart ) ) {
            $cart = $cart -> toArray();
            
         } // End if

      } // End if   

      if( empty( $cart ) ) {
         $cart_key = Str::random(25);
         
         Cookie::queue(Cookie::make('cart_key', $cart_key, 1000000));
         
         $cart = new Cart();
         $cart -> key = $cart_key;
         $cart -> save();
         
         $cart = $cart -> toArray();
         
      } // End if
      
      return $cart;
      
   } // End get_cart
   
   
   
   public function cart(Request $request) {
      $data = array();
      
      $cart = $this::get_cart();
      
      $cart_item_model = new CartItem;
      $cart_items = $cart_item_model -> where( 'cart_id', $cart[ 'id' ] ) -> orderBy( 'id' ) -> get() -> toArray();

      $catalog_item_model = new CatalogItem;
      foreach( $cart_items as $cart_item ) {
         $item = $catalog_item_model -> find($cart_item[ 'item_id' ]);
         if( empty( $item ) ) {
            continue;
            
         } // End if
      
         $data[ 'cart_items' ][] = array(
            'id'   => $cart_item[ 'id' ],
            'name' => $item -> name,
            'total_cost' => $item -> cost * $cart_item[ 'count' ],
            'count' => $cart_item[ 'count' ],
         );
         
      } // End foreach
      
      return view( 'cart', $data );
      
   } // End cart
   
   
   
   public function cart_delete_cart_item(Request $request) {
      $status = '';
      
      $cart = $this::get_cart();
      
      $cart_item_model = new CartItem;
      $cart_item = $cart_item_model -> where( 'cart_id', $cart[ 'id' ] ) -> where( 'id', $request -> cart_item_id ) -> first();
      if( !empty( $cart_item ) ) {
         CartItem::destroy( $cart_item -> id );
         
      } // End if
      
      $count = self::cart_count();
      
      return response()->json( [ 
         'status' => $status,
         'count' => $count,
      ]);
      
   } // End cart_delete_cart_item
   
   
   
   public function cart_send_order(Request $req) {
      $cart = $this::get_cart();
      
      
      
      $user_ip = getenv( 'REMOTE_ADDR' );
      $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
      $region = $geo[ 'geoplugin_timezone' ];
      
      $order = new Order();
      $order -> created_at = Carbon::now($region);
      $order -> updated_at = Carbon::now($region);
      $order -> fio = $req -> input('fio');
      $order -> email = $req -> input('email');
      $order -> phone = !empty( $req -> input('phone') ) ? $req -> input('phone') : '';
      $order -> address = !empty( $req -> input('address') ) ? $req -> input('address') : '';
      $order -> pay_type = $req -> input('pay_type');
      $order -> status = 1;
      $order -> save();
      
      $email_data = array();
      $email_data[ 'id' ] = $order -> id;
      $email_data[ 'fio' ] = $order -> fio;
      $email_data[ 'email' ] = $order -> email;
      $email_data[ 'phone' ] = $order -> phone;
      $email_data[ 'address' ] = $order -> address;
      $email_data[ 'pay_type' ] = $order -> pay_type == 1 ? 'наличными' : 'банковской картой';
      
      
      
      $email_data[ 'items' ] = array();
      $email_data[ 'total_cost' ] = 0;
      $cart_item_model = new CartItem;
      $cart_items = $cart_item_model -> where( 'cart_id', $cart[ 'id' ] ) -> orderBy( 'id' ) -> get() -> toArray();

      $catalog_item_model = new CatalogItem;
      foreach( $cart_items as $cart_item ) {
         $item = $catalog_item_model -> find($cart_item[ 'item_id' ]);
         if( empty( $item ) ) {
            continue;
            
         } // End if
      
         $order_item = new OrderItem();
         $order_item -> item_name = $item -> name;
         $order_item -> count = $cart_item[ 'count' ];
         $order_item -> cost = $item -> cost;
         $order_item -> order_id = $order -> id;
         $order_item -> save();
         
         $email_data[ 'items' ][] = array( 'item_name' => $order_item -> item_name, 'count' => $order_item -> count, 'cost' => $order_item -> cost * $order_item -> count );
         $email_data[ 'total_cost' ] += $order_item -> cost * $order_item -> count;
         
         CartItem::destroy( $cart_item[ 'id' ] );
         
      } // End foreach
      
      
      
      Mail::send('order_mail_for_admin', $email_data, function($message) {
         $message->to('eugene.texter@gmail.com')->subject('Заказ на сайте Lara Site');
         $message->from('eugenetext.temp.swtest.ru@gmail.com', 'Lara Site');
      });
      
      Mail::send('order_mail_for_client', $email_data, function($message) use ($email_data) {
         $message->to($email_data[ 'email' ])->subject('Заказ на сайте Lara Site');
         $message->from('eugenetext.temp.swtest.ru@gmail.com', 'Lara Site');
      });
      
      
      
      return redirect() -> route('cart') -> with('success', 'Заказ был отправлен, номер заказа: ' . $order -> id );
      
   } // End cart_send_order
   
   
   
   public static function cart_count() {
      $count = 0;
      
      $cart = self::get_cart();
      
      $cart_item_model = new CartItem;
      $cart_items = $cart_item_model -> where( 'cart_id', $cart[ 'id' ] ) -> get() -> toArray();
      foreach( $cart_items as $cart_item ) {
         $count += $cart_item[ 'count' ];
      
      } // End foreach
      
      return $count;
      
   } // End cart_count
}
