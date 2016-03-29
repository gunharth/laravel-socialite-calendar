<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GR+ | Shared Calendar</title>
    
    @yield('styles')
    

</head>

<body>

<div id="wrapper">
    
    @include('partials.mainnav')
    

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">

            @include('partials.topnav')

        </div>
        <div class="wrapper wrapper-content animated fadeInRight">

            @yield('content')

        </div>
        <div class="footer">
            <div class="pull-right">
                
            </div>
            <div>
                GR+ &copy; 2016
            </div>
        </div>

    </div>
</div>

@yield('scripts')


</body>

</html>
