<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact; 
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ContactController extends Controller { 
   public function store(Request $request) {
      $status = '';
      $all = 0;
      $added = 0;
      $updated = 0;
      
      $contacts = Http::get('https://randomuser.me/api/?results=5000');
      
      $contacts = array();
      $contacts[ 'results' ] = array(
         array(
            'name' => array( 'first' => '111', 'last' => '222' ),
            'email' => '333',
            'dob' => array( 'age' => '444' ),
         ),
         array(
            'name' => array( 'first' => '111', 'last' => '222' ),
            'email' => '333',
            'dob' => array( 'age' => '444' ),
         ),
      );
      if( !empty( $contacts[ 'results' ] ) ) {
         $insert_data = array();
         
         foreach( $contacts[ 'results' ] as $contact_a ) {
            if( empty( $contact_a[ 'name' ][ 'first' ] ) || empty( $contact_a[ 'name' ][ 'last' ] ) ) {
               continue;
               
            } // End if
            
            //$contact = new Contact;

            //$contact -> first_name = $contact_a[ 'name' ][ 'first' ];
            //$contact -> last_name = $contact_a[ 'name' ][ 'last' ];
            //$contact -> email = $contact_a[ 'email' ];
            //$contact -> age = $contact_a[ 'dob' ][ 'age' ];

            //$contact -> save();
            
            $insert_data[] = array(
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now(),
               'first_name' => $contact_a[ 'name' ][ 'first' ],
               'last_name' => $contact_a[ 'name' ][ 'last' ],
               'email' => $contact_a[ 'email' ],
               'age' => $contact_a[ 'dob' ][ 'age' ]
            );
            
            $added ++;
            
         } // End foreach

         Contact::insert( $insert_data );
         
         $contact = new Contact;
         $all = $contact -> count();
         
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
   }

}
