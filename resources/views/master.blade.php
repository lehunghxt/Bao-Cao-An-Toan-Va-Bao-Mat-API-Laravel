
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin News</title>
  <link href="{{ asset('admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/css/simple-sidebar.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
</head>
<body>
  <div id="app">
  </div>
  <script src="{{mix('js/app.js')}}"></script>
  <script src="{{ asset('admin/vendor/jquery/jquery.min.j') }}s"></script>
  <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>
  <script src="{{ asset('editor/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
  <script src="{{ asset('editor/ckfinder/ckfinder.js') }}" type="text/javascript"></script>
</body>

</html>
