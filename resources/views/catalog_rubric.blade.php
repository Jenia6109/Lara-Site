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
               @if( !empty($rubric) )
                  <div class="bd-example m-0 border-0">
                     <nav aria-label="breadcrumb">
                       <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="/">Главная</a></li>
                         <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Каталог</a></li>
                         <li class="breadcrumb-item active" aria-current="page">{{$rubric['name']}}</li>
                       </ol>
                     </nav>
                  </div>
                  <h1>{{$rubric['name']}}</h1>
                  @if( !empty($rubric['text']) )
                     <p>{{$rubric['text']}}</p>
                  @endif
                  @if( !empty($items) )
                     <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        @foreach ($items as $item)
                          <div class="col">
                            <div class="card shadow-sm">
                              <a href="{{ route('catalog_item', $item['id']) }}">
                                 @if( !empty($item['img']) )
                                    <img src="/items_imgs/{{$item['img']}}" width="419" height="225">
                                 @else
                                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"></svg>
                                 @endif
                              </a>
                              <div class="card-body">
                                 <p class="card-text"><a href="{{ route('catalog_item', $item['id']) }}">{{$item['name']}}</a></p>
                                <p class="card-text">{{number_format($item['cost'], 0, '.', ' ')}} руб.</p>
                              </div>
                            </div>
                          </div>
                        @endforeach
                     </div>
                  @else
                     <p>Товаров не найдено</p>
                  @endif
               @else
                  <p>Рубрика не найдена</p>
               @endif
            </div>
        </div>
    </body>
</html>
