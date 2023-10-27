@if($errors->any())
   <div class="container mt-5">
      <div class="alert alert-danger">
         <ul>
            @foreach($errors->all() as $error)
               <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
   </div>
@endif
@if(session('success'))
   <div class="container mt-5">
      <div>
         <div class="alert alert-success">
          {{ session('success') }}
         </div>
      </div>
   </div>
@endif