<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IO+</title>
    <link href="css/app.css" rel="stylesheet">
</head>
<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            

        <!-- Content Start -->
        @yield('content')
        <!-- Content end -->
        

    </div>
</div>

@yield('hidden')

<script src="js/app.js"></script>
@yield('scripts')
</body>

</html>
