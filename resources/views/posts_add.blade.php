<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
   <body>
      @include('inc.header')
      <script>
         $(function(){
            $('#posts-form').submit(function(){
               const selectors = document.querySelectorAll('div[contenteditable="true"][required]');

               if( $('#posts-div-text').html() == '' ) {
                  $('#posts-div-text').css('border', '2px solid red');
                  return false;
               }
            });
            $('.posts-smile').click(function(){
               $('#posts-div-text').append($(this).clone());
               placeCaretAtEnd($('#posts-div-text').get(0));
               
               posts_div_update();
               
               if( $(this).hasClass('posts-stiker') ) {
                  $('#stikersModal .btn-close').click();
               }
            });
            $('#posts-div-text').keyup(function(){
               posts_div_update();
            });
            $('#posts-div-text').focusout(function(){
               posts_div_update();
            });
         });
         
         function placeCaretAtEnd(el) {
             el.focus();
             if (typeof window.getSelection != "undefined"
                     && typeof document.createRange != "undefined") {
                 var range = document.createRange();
                 range.selectNodeContents(el);
                 range.collapse(false);
                 var sel = window.getSelection();
                 sel.removeAllRanges();
                 sel.addRange(range);
             } else if (typeof document.body.createTextRange != "undefined") {
                 var textRange = document.body.createTextRange();
                 textRange.moveToElementText(el);
                 textRange.collapse(false);
                 textRange.select();
             }
         }
         
         function posts_div_update(){
            var html = $('#posts-div-text').html();
            
            if( html == '<div><br></div>' || html == '<br>' ) {
               html = '';
               $('#posts-div-text').html('');
            }
            
            if( $('#posts-div-text').html() != '' ) {
               $('#posts-div-text').css('border', '');
            }
            $('#lastName').val($('#posts-div-text').html());
         }
         
         var myModal = new bootstrap.Modal(document.getElementById('stikersModal'), {backdrop: true});
      </script>
      <form class="needs-validation" id="posts-form" method="post" action="{{ route('posts_add_submit', null, false) }}">
         @csrf
         <div class="container">
            <div class="row g-5">
            <div class="col-md- col-lg-">
              <h4 class="mb-3">Написать на заборе</h4>
                <div class="row g-3">
                  <div>
                    <label for="firstName" class="form-label">Имя</label>
                     @if( Auth::user() )
                        <input type="text" class="form-control" placeholder="" value="{{ Auth::user() -> name }}" required name="name" id="firstName" style="width: 800px;">
                     @else
                        <input type="text" class="form-control" placeholder="" value="" required name="name" id="firstName" style="width: 800px;">
                     @endif
                  </div><br>
                  <div>
                     <label for="lastName" class="form-label">Текст</label>
                     <div class="form-control" style="width: 800px; height: 200px;" contenteditable="true" required id="posts-div-text"></div>
                     <img src="/i/f09f9885.png" style="width:16px; height:16px; cursor:pointer;" class="posts-smile">
                     <img src="/i/f09f988a.png" style="width:16px; height:16px; cursor:pointer;" class="posts-smile">
                     <img src="/i/f09f988b.png" style="width:16px; height:16px; cursor:pointer;" class="posts-smile">
                     <img src="/i/e298ba.png" style="width:16px; height:16px; cursor:pointer;" class="posts-smile">
                     <img src="/i/f09f9891.png" style="width:16px; height:16px; cursor:pointer;" class="posts-smile">
                     <img src="/i/f09f9892.png" style="width:16px; height:16px; cursor:pointer;" class="posts-smile">
                     <img src="/i/f09fa494.png" style="width:16px; height:16px; cursor:pointer;" class="posts-smile">
                     <img src="/i/f09f918c.png" style="width:16px; height:16px; cursor:pointer;" class="posts-smile">
                     <img src="/i/f09f918d.png" style="width:16px; height:16px; cursor:pointer;" class="posts-smile">
                     <a href="javascript://" onclick="$('#button-stikers').click();">стикеры</a>
                     <textarea class="form-control" placeholder="" value="" style="width: 800px; height: 200px; display:none;" name="text" id="lastName"></textarea>
                  </div>
                </div>
               </div>
            </div>
         </div>
         <div class="container px-4" id="hanging-icons">
          <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
            <div class="col d-flex align-items-start">
               <a href="javascript://" class="btn btn-primary" onclick="$('#posts-submit-button').click();">
                  Написать
               </a>
            </div>
          </div>
         </div>
         <input type="submit" id="posts-submit-button" style="display:none;">
      </form>
      
      
      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stikersModal" id="button-stikers" style="display:none;"></button>

      <!-- Modal -->
      <div class="modal fade" id="stikersModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Стикеры</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <img src="/i/1-63-128.png" style="width:128px; height:128px; cursor:pointer;" class="posts-smile posts-stiker">
               <img src="/i/1-8328-128.png" style="width:128px; height:128px; cursor:pointer;" class="posts-smile posts-stiker">
               <img src="/i/1-3375-128.png" style="width:128px; height:128px; cursor:pointer;" class="posts-smile posts-stiker">
            </div>
          </div>
        </div>
      </div>
   </body>
</html>
