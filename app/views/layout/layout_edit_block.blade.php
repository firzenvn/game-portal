<?php
$user = Auth::user();
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    @section('css')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">


    <link rel="stylesheet" href="{{ asset('lib/kendo/styles/kendo.common-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/kendo/styles/kendo.bootstrap.min.css') }}">


    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('lib/kendo/js/kendo.all.min.js') }}"></script>
    <script src="{{ asset('lib/kendo/js/cultures/kendo.culture.vi-VN.min.js') }}"></script>
    @show

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>
<body class="interface">
<div class="container bg-info">
    <div class="row">

        <div class="col-sm-12">
            <h4>Quản lý block trên trang:</h4>
        </div>


    </div>
</div>
<div class="container">
    <div class="row">



            {{$content}}


    </div>
</div>


@section('scripts')

<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script src="{{ asset('js/bootbox.js') }}"></script>
<script src="{{ asset('js/blockUI.js') }}"></script>




<script>
    kendo.culture("vi-VN");

    $(document).ready(function(){
    });

</script>
@show
</body>
</html>