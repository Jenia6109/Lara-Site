<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
   <body>
      <main class="d-flex flex-nowrap">
         @include('inc.admin_sidebar')
         @include('inc.messages')
         @if( !empty($rubric) )
            <script>
               $(function(){
                  $('.admin-catalog-rubric-delete-a').click(function(){
                     if (confirm('Действительно хотите удалить?')) {
                        $('.admin-catalog-rubric-delete-form-'+$(this).attr('data-id')).submit();
                     }
                  });
               });
            </script>
            <form class="needs-validation" method="post" action="{{ route('admin_catalog_rubric_update', $rubric['id']) }}" enctype="multipart/form-data">
               @csrf
               <div class="container">
                  <div class="row g-5">
                  <div class="col-md- col-lg-">
                    <h4 class="mb-3">Редактирование рубрики</h4>
                      <div class="row g-3">
                        <div>
                          <label for="firstName" class="form-label">Имя</label>
                          <input type="text" class="form-control" placeholder="" value="{{$rubric['name']}}" required name="name" id="firstName" style="width: 800px;">
                        </div>
                        <div>
                           <label for="lastName" class="form-label">Текст</label>
                           <textarea class="form-control" placeholder="" value="" style="width: 800px; height: 200px;" name="text" id="lastName">{{$rubric['text']}}</textarea>
                        </div>
                        <div>
                           <input type="checkbox" class="form-check-input" id="hiddenElement" name="hidden">
                           <label class="form-check-label" for="hiddenElement">Скрыть</label>
                           <script>
                              @if( !empty($rubric['hidden']) )
                                 $('#hiddenElement:not(:checked)').click();
                              @else
                                 $('#hiddenElement:checked').click();
                              @endif
                           </script>
                        </div>
                        <div>
                          <label for="order" class="form-label">Сортировка</label>
                          <input type="text" class="form-control" placeholder="" value="{{$rubric['order']}}" required name="order" id="order" style="width: 800px;">
                        </div>
                        <div>
                           <label for="img" class="form-label">Изображение</label>
                           @if( !empty($rubric['img']) )
                              <br><img src="/rubrics_imgs/{{$rubric['img']}}"><br><br>
                           @endif
                           <input type="file" class="form-control" placeholder="" name="img" id="img">
                        </div>
                      </div>
                     </div>
                  </div>
               </div>
               <div class="container px-4" id="hanging-icons">
                <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
                  <div class="col d-flex align-items-start">
                     <a href="javascript://" class="btn btn-primary" onclick="$('#submit-button').click();">
                        Сохранить
                     </a>
                     &nbsp;&nbsp;&nbsp;
                     <a href="javascript://" class="btn btn-danger rounded-pill px-3 admin-catalog-rubric-delete-a" data-id="{{$rubric['id']}}">Удалить</a>
                  </div>
                </div>
                <h2>Товары<h2>
                  @if( !empty($items) )
                     @foreach ($items as $item)
                        <a href="{{ route('admin_catalog_item_edit', $item['id']) }}" class="px-3">{{$item['name']}}</a><br>
                     @endforeach
                  @endif
                  <a href="{{ route('admin_catalog_item_add', array('rubric' => $rubric['id'])) }}" class="btn btn-primary">Добавить</a>
               </div>
               <input type="submit" id="submit-button" style="display:none;">
            </form>
            <form action="{{ route('admin_catalog_rubric_delete', $rubric['id']) }}" method="post" style="display:none;" class="admin-catalog-rubric-delete-form-{{$rubric['id']}}">
               @csrf
            </form>
         @endif
      </main>
   </body>
</html>
