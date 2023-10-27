<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
    <body>
      @include('inc.header')
      <script>
         $(function(){
            $('#import-button').click(function(){
               $('#loader-button').show();
               $('.invalid-feedback').hide();

               $.ajax({
                  url: "/contacts-form",
                  type:"POST",
                  data:{
                     "_token": "{{ csrf_token() }}",
                  },
                  success:function(response){
                     $('#loader-button').hide();
                     if( response.status == 'success' ) {
                        $('#result-p').show();
                        $('#all-strong').html(response.all);
                        $('#added-strong').html(response.added);
                        $('#updated-strong').html(response.updated);
                     } else {
                        $('.invalid-feedback').html(response.status);
                        $('.invalid-feedback').show();
                     }
                  },
               });
            });
         });
      </script>
      <div class="col-lg-6 col-xxl-3 my-5 mx-auto">
         <div class="d-grid gap-2">
            <button class="btn btn-primary" type="button" id="import-button">импортировать пользователей</button>
         </div>
         <div class="d-flex gap-2 justify-content-center py-5">
            <button class="btn btn-primary" type="button" disabled="" style="display:none;" id="loader-button">
               <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
               <span class="visually-hidden" role="status">Loading...</span>
            </button>
            <div class="invalid-feedback"></div>
            <p style="display:none;" id="result-p">Всего: <strong id="all-strong">140</strong>, Добавлено: <strong id="added-strong">100</strong>, Обновлено: <strong id="updated-strong">20</strong></p>
         </div>
      </div>
    </body>
</html>
