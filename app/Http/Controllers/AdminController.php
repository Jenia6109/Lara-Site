<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\CatalogRubric;
use App\Models\CatalogItem;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use App\Models\Order;
use App\Models\OrderItem;

class AdminController extends Controller
{
   public function admin(Request $request) {
      $data = array();
      
      return view( 'admin', $data );
      
   } // End admin
   
   
   public function admin_posts(Request $request) {
      $data = array();
        
      $posts = DB::table( 'posts' ) -> orderByDesc( 'id' ) -> get() -> toArray();
      
      foreach( $posts as $post ) {
         $date = '';
         if( !empty( $post -> created_at ) ) {
            $date_time = explode( ' ', $post -> created_at );
            $date = explode( '-', $date_time[ 0 ] );
            $date = $date[ 2 ] . '-' . $date[ 1 ] . '-' . $date[ 0 ] . ' ' . $date_time[ 1 ];
            
         } // End if
         
         $data[ 'posts' ][] = array(
            'id'   => $post -> id,
            'hidden' => $post -> hidden,
            'name' => $post -> name,
            'text' => $post -> text,
            'date' => $date,
         );
         
      } // End foreach

      return view( 'admin_posts', $data );
      
   } // End admin_posts
   
   
   
   public function admin_posts_edit($id) {
      $post_model = new Post;
      
      $data = array();
      $post = $post_model -> find($id);
      $data[ 'post' ] = array(
         'id' => $post -> id,
         'hidden' => $post -> hidden,
         'name' => $post -> name,
         'text' => $post -> text,
      );

      return view( 'admin_posts_edit', $data );
   }
   
   
   
   public function admin_posts_update($id, Request $req) {
      $post = Post::find($id);
      $post -> hidden = (bool) $req -> input('hidden');
      $post -> name = $req -> input('name');
      $post -> text = $req -> input('text');
      $post -> save();
      
      return redirect() -> route('admin_posts', $id) -> with('success', 'Элемент был обновлён');
   }
   
   
   
   public function admin_posts_delete($id) {
      Post::destroy( $id );
      
      return redirect() -> route('admin_posts', $id) -> with('success', 'Элемент был удалён');
   }
   
   
   
   public function admin_catalog(Request $request) {
      $data = array();
        
      $rubrics = DB::table( 'catalog_rubrics' ) -> orderBy( 'order' ) -> get() -> toArray();
      
      foreach( $rubrics as $rubric ) {
         $data[ 'rubrics' ][] = array(
            'id'   => $rubric -> id,
            'hidden' => $rubric -> hidden,
            'name' => $rubric -> name,
            'text' => $rubric -> text,
         );
         
      } // End foreach

      return view( 'admin_catalog', $data );
      
   } // End admin_catalog
   
   
   
   public function admin_catalog_rubric_add(Request $req) {
      return view( 'admin_catalog_rubric_add' );
   }
   
   
   
   public function admin_catalog_rubric_add_submit(Request $req) {
      $image = '';
      if($req->file()) {
         $originalName = Str::random(25).'.png';
            
         //$req->file('img')->storeAs('', $originalName, 'rubrics_imgs');
         
         $path = public_path('rubrics_imgs/' . $originalName);
         $image_manager = new ImageManager(['driver' => 'imagick']);
         $image_manager -> make($req->file('img')->getRealPath()) -> resize(419, 225) -> save($path);
         
         $image = $originalName;
      }
      
      $rubric = new CatalogRubric();
      $rubric -> name = $req -> input('name');
      $rubric -> text = $req -> input('text');
      $rubric -> order = $req -> input('order');
      $rubric -> img = $image;
      $rubric -> save();
      
      return redirect() -> route('admin_catalog') -> with('success', 'Рубрика была добавлена');
      
   } // End admin_catalog_rubric_add_submit
   
   
   
   public function admin_catalog_rubric_edit($id) {
      $data = array();
      
      $catalog_rubric_model = new CatalogRubric;
      $rubric = $catalog_rubric_model -> find($id);
      $data[ 'rubric' ] = array(
         'id' => $rubric -> id,
         'hidden' => $rubric -> hidden,
         'name' => $rubric -> name,
         'text' => $rubric -> text,
         'order' => $rubric -> order,
         'img' => $rubric -> img,
      );

      $data[ 'items' ] = array();
      $catalog_item_model = new CatalogItem;
      $items = $catalog_item_model -> where( 'rubric_id', $id ) -> orderBy( 'order' ) -> get() -> toArray();
      foreach( $items as $item ) {
         $data[ 'items' ][] = array(
            'id' => $item[ 'id' ],
            'name' => $item[ 'name' ],
         );
      }
      
      return view( 'admin_catalog_rubric_edit', $data );
   }
   
   
   
   public function admin_catalog_rubric_update($id, Request $req) {
      $image = '';
      if($req->file()) {
         $originalName = Str::random(25).'.png';
         
         //$req->file('img')->storeAs('', $originalName, 'rubrics_imgs');

         $path = public_path('rubrics_imgs/' . $originalName);
         $image_manager = new ImageManager(['driver' => 'imagick']);
         $image_manager -> make($req->file('img')->getRealPath()) -> resize(419, 225) -> save($path);

         $image = $originalName;
      }
      
      $rubric = CatalogRubric::find($id);
      $rubric -> hidden = (bool) $req -> input('hidden');
      $rubric -> name = $req -> input('name');
      $rubric -> text = $req -> input('text');
      $rubric -> order = $req -> input('order');
      if( !empty($image) ) {
         $rubric -> img = $image;
      }
      $rubric -> save();
      
      return redirect() -> route('admin_catalog', $id) -> with('success', 'Элемент был обновлён');
   }
   
   
   
   public function admin_catalog_rubric_delete($id) {
      $items = CatalogItem::where( 'rubric_id', $id ) -> get() -> toArray();

      foreach( $items as $item ) {
         CatalogItem::destroy( $item[ 'id' ] );
         
      } // End foreach
      
      CatalogRubric::destroy( $id );
      
      return redirect() -> route('admin_catalog', $id) -> with('success', 'Элемент был удалён');
   }
   
   
   
   public function admin_catalog_item_add(Request $req) {
      $data = array();
        
      $rubrics = DB::table( 'catalog_rubrics' ) -> orderBy( 'order' ) -> get() -> toArray();
      
      $data[ 'rubrics' ] = array();
      foreach( $rubrics as $rubric ) {
         $data[ 'rubrics' ][] = array(
            'id'   => $rubric -> id,
            'name' => $rubric -> name,
         );
         
      } // End foreach
      
      $data[ 'current_rubric' ] = $req -> rubric;
      
      return view( 'admin_catalog_item_add', $data );
   }
   
   
   
   public function admin_catalog_item_add_submit(Request $req) {
      $image = '';
      if($req->file()) {
         $originalName = Str::random(25).'.png';
            
         $path = public_path('items_imgs/' . $originalName);
         $image_manager = new ImageManager(['driver' => 'imagick']);
         $image_manager -> make($req->file('img')->getRealPath()) -> resize(419, 225) -> save($path);
         
         $image = $originalName;
      }
      
      $item = new CatalogItem();
      $item -> rubric_id = $req -> input('rubric');
      $item -> name = $req -> input('name');
      $item -> text = !empty( $req -> input('text')) ? $req -> input('text') : '';
      $item -> order = $req -> input('order');
      $item -> cost = $req -> input('cost');
      $item -> img = $image;
      $item -> save();
      
      return redirect() -> route('admin_catalog_rubric_edit', $req -> input('rubric')) -> with('success', 'Товар был добавлен');
      
   } // End admin_catalog_item_add_submit
   
   
   
   public function admin_catalog_item_edit($id) {
      $data = array();
      
      $catalog_item_model = new CatalogItem;
      $item = $catalog_item_model -> find($id);
      $data[ 'item' ] = array(
         'id' => $item -> id,
         'rubric_id' => $item -> rubric_id,
         'hidden' => $item -> hidden,
         'name' => $item -> name,
         'text' => $item -> text,
         'order' => $item -> order,
         'cost' => $item -> cost,
         'img' => $item -> img,
      );

      $data[ 'rubrics' ] = array();
      $rubrics = DB::table( 'catalog_rubrics' ) -> orderBy( 'order' ) -> get() -> toArray();
      foreach( $rubrics as $rubric ) {
         $data[ 'rubrics' ][] = array(
            'id'   => $rubric -> id,
            'name' => $rubric -> name,
         );
         
      } // End foreach
      
      return view( 'admin_catalog_item_edit', $data );
   }
   
   
   
   public function admin_catalog_item_update($id, Request $req) {
      $image = '';
      if($req->file()) {
         $originalName = Str::random(25).'.png';
         
         $path = public_path('items_imgs/' . $originalName);
         $image_manager = new ImageManager(['driver' => 'imagick']);
         $image_manager -> make($req->file('img')->getRealPath()) -> resize(419, 225) -> save($path);

         $image = $originalName;
      }

      $item = CatalogItem::find($id);
      $item -> rubric_id = $req -> input('rubric');
      $item -> hidden = (bool) $req -> input('hidden');
      $item -> name = $req -> input('name');
      $item -> text = !empty( $req -> input('text')) ? $req -> input('text') : '';
      $item -> order = $req -> input('order');
      $item -> cost = $req -> input('cost');
      if( !empty($image) ) {
         $item -> img = $image;
      }
      $item -> save();
      
      return redirect() -> route('admin_catalog_rubric_edit', $req -> input('rubric')) -> with('success', 'Элемент был обновлён');
   }
   
   
   
   public function admin_catalog_item_delete($id) {
      $catalog_item_model = new CatalogItem;
      $item = $catalog_item_model -> find($id);
      $rubric_id = $item -> rubric_id;
      
      CatalogItem::destroy( $id );
      
      return redirect() -> route('admin_catalog_rubric_edit', $rubric_id) -> with('success', 'Элемент был удалён');
   }
   
   
   
   public function admin_orders(Request $request) {
      $data = array();
        
      $order_model = new Order;
      $order_item_model = new OrderItem;
      $orders = $order_model -> orderByDesc( 'id' ) -> get() -> toArray();
      
      $data[ 'orders' ] = array();
      foreach( $orders as $order ) {
         $date = '';
         if( !empty( $order[ 'created_at' ] ) ) {
            $date_time = explode( 'T', $order[ 'created_at' ] );
            $date = explode( '-', $date_time[ 0 ] );
            $time = explode( '.', $date_time[ 1 ] );
            $date = $date[ 2 ] . '-' . $date[ 1 ] . '-' . $date[ 0 ] . ' ' . $time[ 0 ];
            
         } // End if
         
         $order[ 'date' ] = $date;
         
         $order[ 'pay_type' ] = $order[ 'pay_type' ] == 1 ? 'наличными' : 'банковской картой';
         $order[ 'status' ] = $order[ 'status' ] == 1 ? 'новый' : '';
         
         $order[ 'order_items' ] = array();
         $order_items = $order_item_model -> where('order_id', $order['id']) -> orderBy( 'id' ) -> get() -> toArray();
         foreach( $order_items as $order_item ) {
            $order[ 'order_items' ][] = $order_item;
            
         } // End foreach
         
         $data[ 'orders' ][] = $order;
         
      } // End foreach

      return view( 'admin_orders', $data );
      
   } // End admin_posts
   
   
   
   public function admin_order_edit($id) {
      $data = array();
      
      $order_model = new Order;
      $order = $order_model -> find($id);
      $order = $order -> toArray();
      $data[ 'order' ] = array(
         'id' => $order[ 'id' ],
         'fio' => $order[ 'fio' ],
         'email' => $order[ 'email' ],
         'phone' => $order[ 'phone' ],
         'address' => $order[ 'address' ],
         'pay_type' => $order[ 'pay_type' ] == 1 ? 'наличными' : 'банковской картой',
         'status' => $order[ 'status' ] == 1 ? 'новый' : '',
      );
      
      $date = '';
      if( !empty( $order[ 'created_at' ] ) ) {
         $date_time = explode( 'T', $order[ 'created_at' ] );
         $date = explode( '-', $date_time[ 0 ] );
         $time = explode( '.', $date_time[ 1 ] );
         $date = $date[ 2 ] . '-' . $date[ 1 ] . '-' . $date[ 0 ] . ' ' . $time[ 0 ];
         
      } // End if
      
      $data[ 'order' ][ 'date' ] = $date;

      $data[ 'total_cost' ] = 0;
      $data[ 'order_items' ] = array();
      $order_item_model = new OrderItem;
      $order_items = $order_item_model -> where( 'order_id', $id ) -> orderBy( 'id' ) -> get() -> toArray();
      foreach( $order_items as $order_item ) {
         $data[ 'items' ][] = array(
            'id' => $order_item[ 'id' ],
            'name' => $order_item[ 'item_name' ],
            'count' => $order_item[ 'count' ],
            'cost' => $order_item[ 'cost' ] * $order_item[ 'count' ],
         );
         
         $data[ 'total_cost' ] += $order_item[ 'cost' ] * $order_item[ 'count' ];
      }
      
      return view( 'admin_order_edit', $data );
   }
   
   
}
