<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact; 
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContactController extends Controller { 
   public function store(Request $request) {
      $status = '';
      $all = 0;
      $added = 0;
      $updated = 0;
      
      $contacts = Http::get('https://randomuser.me/api/?results=5000');
      
      if( !empty( $contacts[ 'results' ] ) ) {
         $insert_data = array();
         
         foreach( $contacts[ 'results' ] as $contact_a ) {
            if( empty( $contact_a[ 'name' ][ 'first' ] ) || empty( $contact_a[ 'name' ][ 'last' ] ) ) {
               continue;
               
            } // End if
            
            $item_exist = DB::table( 'contacts' ) -> where( 'first_name', $contact_a[ 'name' ][ 'first' ] ) -> where( 'last_name', $contact_a[ 'name' ][ 'last' ] ) -> first();
            if( !empty( $item_exist ) ) {
               if( $item_exist -> email != $contact_a[ 'email' ] || $item_exist -> age != $contact_a[ 'dob' ][ 'age' ] ) {
                  DB::table( 'contacts' ) -> where( 'first_name', $contact_a[ 'name' ][ 'first' ] ) -> where( 'last_name', $contact_a[ 'name' ][ 'last' ] ) -> update( [ 'email' => $contact_a[ 'email' ], 'age' => $contact_a[ 'dob' ][ 'age' ] ] );
                  
                  $updated ++;
                  
               } // End if
               
            } else {
               $insert_data[] = array(
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now(),
                  'first_name' => $contact_a[ 'name' ][ 'first' ],
                  'last_name' => $contact_a[ 'name' ][ 'last' ],
                  'email' => $contact_a[ 'email' ],
                  'age' => $contact_a[ 'dob' ][ 'age' ],
               );
               
               $added ++;
               
            } // End if
            
         } // End foreach

         if( !empty( $insert_data ) ) {
            Contact::insert( $insert_data );
            
         } // End if
         
         $all = Contact::count();
         
         $status = 'success';
         
      } else {
         $status = 'Не удалось подгрузить данные';
         
      } // End if

      return response()->json( [ 
         'status' => $status,
         'all' => $all,
         'added' => $added,
         'updated' => $updated,
      ]);
      
   } // End store

}
