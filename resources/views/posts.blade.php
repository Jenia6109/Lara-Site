<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
    <body>
      @include('inc.header')
      @include('inc.messages')
      <script>
         $(function(){
            
         });
      </script>
      <div class="container px-4 py-5" id="featured-3">
         <div class="bd-example m-0 border-0">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Забор</li>
              </ol>
            </nav>
         </div>
         <h1>Забор</h1>
         @if( !empty($posts) )
          <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
            @foreach ($posts as $post)
               <div class="feature col">
                  <h3 class="fs-2 text-body-emphasis">{{$post['name']}}</h3>
                  {{$post['date']}}
                  <p>{!!$post['text']!!}</p>
               </div>
            @endforeach
          </div>
         @endif
      </div>
      <div class="container px-4" id="hanging-icons">
       <div class="row g-4 row-cols-1 row-cols-lg-3">
         <div class="col d-flex align-items-start">
            <a href="{{ route('posts_add', null, false) }}" class="btn btn-primary">Написать на заборе</a>
         </div>
       </div>
     </div>
    </body>
</html>
