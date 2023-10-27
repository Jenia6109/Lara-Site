<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
    <body>
      <main class="d-flex flex-nowrap">
         @include('inc.admin_sidebar')
         @include('inc.messages')
         @if( !empty($posts) )
            <script>
               $(function(){
                  $('.admin-posts-delete-a').click(function(){
                     if (confirm('Действительно хотите удалить?')) {
                        $('.admin-posts-delete-form-'+$(this).attr('data-id')).submit();
                     }
                  });
               });
            </script>
            <div class="container px-4 py-5" id="featured-3">
                <h2 class="pb-2 border-bottom">Забор</h2>
                <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
                  @foreach ($posts as $post)
                     <div class="feature col">
                        <h3 class="fs-2 text-body-emphasis">{{$post['name']}}</h3>
                        @if( !empty($post['hidden']) )
                           <div style="color:red;">скрыт</div>
                        @else
                           <div style="color:green;">отображается</div>
                        @endif
                        {{$post['date']}}
                        <p>{!!$post['text']!!}</p>
                        <a href="{{ route('admin_posts_edit', $post['id']) }}" class="btn btn-warning rounded-pill px-3">Редактировать</a>
                        <a href="javascript://" class="btn btn-danger rounded-pill px-3 admin-posts-delete-a" data-id="{{$post['id']}}">Удалить</a>
                        <form action="{{ route('admin_posts_delete', $post['id']) }}" method="post" style="display:none;" class="admin-posts-delete-form-{{$post['id']}}">
                           @csrf
                        </form>
                     </div>
                  @endforeach
                </div>
            </div>
         @endif
      </main>
    </body>
</html>
