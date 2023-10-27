<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SocialController extends Controller
{
   public function redirectToProvider($provider) {
      return Socialite::driver($provider)->redirect();
   }
   
   public function handleProviderCallback($provider) {
      $socialiteUser = Socialite::driver($provider)->user();

      if( !empty( $socialiteUser[ 'email' ] ) ) {
         $user = $this->findOrCreateUser($socialiteUser);
         
      } else {
         return redirect() -> route('register') -> withErrors(['К аккаунту Google не прикреплён Email!']);
         
      }

      auth() -> login($user, true);

      return redirect() -> route('dashboard');
   }
   
   public function findOrCreateUser($socialiteUser) {
      if( $user = $this->findUserByEmail($socialiteUser[ 'email' ]) ) {
         return $user;
      }
      
      $avatar = '';
      if( !empty( $socialiteUser->avatar ) ) {
         $originalName = Str::random(25).'.png';
         
         if(Storage::disk('avatars')->put($originalName, file_get_contents($socialiteUser->avatar))) {
            $avatar = $originalName;
         }
      }

      $user = User::create([
         'name' => $socialiteUser->getName(),
         'email' => $socialiteUser->getEmail(),
         'password' => bcrypt(Str::random(25)),
         'avatar' => !empty( $avatar ) ? $avatar : '',
      ]);

      return $user;
   }
   
   public function findUserByEmail($email) {
      return !$email ? null : User::where('email', $email)->first();
   }
}