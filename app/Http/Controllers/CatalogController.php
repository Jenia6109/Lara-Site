<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatalogRubric;
use App\Models\CatalogItem;

class CatalogController extends Controller
{
   public function catalog(Request $request) {
      $data = array();

      $rubrics = CatalogRubric::where( 'hidden', false ) -> orderBy( 'order' ) -> get() -> toArray();

      foreach( $rubrics as $rubric ) {
         $data[ 'rubrics' ][] = array(
            'id' => $rubric[ 'id' ],
            'name' => $rubric[ 'name' ],
            'text' => $rubric[ 'text' ],
            'img' =>  $rubric[ 'img' ],
         );
         
      } // End foreach

      $items = CatalogItem::where( 'hidden', false ) -> whereNull( 'rubric_id' ) -> orderBy( 'order' ) -> get() -> toArray();

      foreach( $items as $item ) {
         $data[ 'items' ][] = array(
            'id' => $item[ 'id' ],
            'name' => $item[ 'name' ],
            'text' => $item[ 'text' ],
            'img' =>  $item[ 'img' ],
         );
         
      } // End foreach

      return view( 'catalog', $data );
      
   } // End list
   
   
   public function catalog_rubric($id, Request $request) {
      $data = array();

      $rubric = CatalogRubric::where( 'hidden', false ) -> where( 'id', (int) $id ) -> first();
      if( empty( $rubric ) ){
         return view( 'catalog_rubric', $data );
      }
      
      $rubric = $rubric -> toArray();
      $data[ 'rubric' ] = $rubric;

      $items = CatalogItem::where( 'hidden', false ) -> where( 'rubric_id', $rubric[ 'id' ] ) -> orderBy( 'order' ) -> get() -> toArray();

      foreach( $items as $item ) {
         $data[ 'items' ][] = array(
            'id' => $item[ 'id' ],
            'name' => $item[ 'name' ],
            'cost' => $item[ 'cost' ],
            'text' => $item[ 'text' ],
            'img' =>  $item[ 'img' ],
         );
         
      } // End foreach

      return view( 'catalog_rubric', $data );
      
   } // End list
   
   public function catalog_item($id, Request $request) {
      $data = array();

      $item = CatalogItem::where( 'hidden', false ) -> where( 'id', (int) $id ) -> first();
      if( empty( $item ) ){
         return view( 'catalog_item', $data );
      }
      
      $item = $item -> toArray();
      $data[ 'item' ] = $item;
      
      
      $data[ 'rubric' ] = CatalogRubric::find($item[ 'rubric_id' ]);

      return view( 'catalog_item', $data );
      
   } // End list
   
}
