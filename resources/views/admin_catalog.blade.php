<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
    <body>
      <main class="d-flex flex-nowrap">
         @include('inc.admin_sidebar')
         @include('inc.messages')
         <div class="container px-4 py-5" id="featured-3">
            <h2>Рубрики</h2>
            @if( !empty($rubrics) )
               @foreach ($rubrics as $rubric)
                  <a href="{{ route('admin_catalog_rubric_edit', $rubric['id']) }}" class="px-3">{{$rubric['name']}}</a><br>
               @endforeach
            @endif
             <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
               <div class="col d-flex align-items-start">
                  <a href="{{ route('admin_catalog_rubric_add') }}" class="btn btn-primary">Добавить</a>
               </div>
             </div>
         </div>
      </main>
    </body>
</html>
