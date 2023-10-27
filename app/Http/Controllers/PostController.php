<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

class PostController extends Controller
{
   public function list(Request $request) {
      $data = array();

      $posts = DB::table( 'posts' ) -> where( 'hidden', false ) -> orderByDesc( 'created_at' ) -> get() -> toArray();
      
      foreach( $posts as $post ) {
         if( empty( $post -> name ) || empty ( $post -> text ) ) {
            continue;
            
         } // End if     
         
         $date = '';
         if( !empty( $post -> created_at ) ) {
            $date_time = explode( ' ', $post -> created_at );
            $date = explode( '-', $date_time[ 0 ] );
            $date = $date[ 2 ] . '-' . $date[ 1 ] . '-' . $date[ 0 ] . ' ' . $date_time[ 1 ];
            
         } // End if
         
         $data[ 'posts' ][] = array(
            'name' => $post -> name,
            'text' => $post -> text,
            'date' => $date,
         );
         
      } // End foreach

      return view( 'posts', $data );
      
   } // End list
   
   
   
   public function add(Request $request) {
      return view( 'posts_add' );
      
   } // End add
   
   
   
   public function submit(Request $req) {
      $user_ip = getenv( 'REMOTE_ADDR' );
      $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
      $region = $geo[ 'geoplugin_timezone' ];
      
      $post = new Post();
      $post -> created_at = Carbon::now($region);
      $post -> updated_at = Carbon::now($region);
      $post -> name = $req -> input('name');
      $post -> text = $req -> input('text');
      $post -> save();
      
      return redirect() -> route('posts') -> with('success', 'Сообщение было добавлено');
      
   } // End submit
   
   
}
