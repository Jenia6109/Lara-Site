<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   @include('inc.head')
    <body>
      <main class="d-flex flex-nowrap">
         @include('inc.admin_sidebar')
      </main>
    </body>
</html>
