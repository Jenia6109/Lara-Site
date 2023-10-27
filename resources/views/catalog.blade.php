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
         <div class="album py-5 bg-body-tertiary">
            <div class="container">
               <div class="bd-example m-0 border-0">
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="/">Главная</a></li>
                      <li class="breadcrumb-item active" aria-current="page">Каталог</li>
                    </ol>
                  </nav>
               </div>
               <h1>Каталог</h1>
               @if( !empty($rubrics) )
                  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                     @foreach ($rubrics as $rubric)
                        <a href="{{ route('catalog_rubric', $rubric['id']) }}">
                          <div class="col">
                            <div class="card shadow-sm">
                              @if( !empty($rubric['img']) )
                                 <img src="/rubrics_imgs/{{$rubric['img']}}" width="419" height="225">
                              @else
                                 <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"></svg>
                              @endif
                              <div class="card-body">
                                <p class="card-text">{{$rubric['name']}}</p>
                              </div>
                            </div>
                          </div>
                        </a>
                     @endforeach
                  </div>
               @endif
            </div>
        </div>
      @if( !empty($items) )
         <div class="album py-5 bg-body-tertiary">
             <div class="container">
               <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                  @foreach ($items as $item)
                     <a href="{{ route('catalog_item', $item['id']) }}">
                       <div class="col">
                         <div class="card shadow-sm">
                           @if( !empty($item['img']) )
                              <img src="/items_imgs/{{$item['img']}}" width="419" height="225">
                           @else
                              <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"></svg>
                           @endif
                           <div class="card-body">
                             <p class="card-text">{{$item['name']}}</p>
                           </div>
                         </div>
                       </div>
                     </a>
                  @endforeach
               </div>
             </div>
           </div>
      @endif
    </body>
</html>
