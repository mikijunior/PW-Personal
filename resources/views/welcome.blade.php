<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="../../css/app.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <div class="container">
        <div id="app"></div>
    </div>

    <script src="../../js/app.js"></script>
    </body>
    <footer>
        <p style="text-align: center">© <?php echo date('Y'); ?> <a href="#">MiJunior</a></p>
    </footer>
</html>
