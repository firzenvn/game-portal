<?php
$urlSegment = $custom_config['admin_url_segment'];
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
    <link rel="stylesheet" href="{{ asset('css/jquery.tagsinput.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/redactor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">


    <link rel="stylesheet" href="{{ asset('lib/kendo/styles/kendo.common-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/kendo/styles/kendo.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap-select/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap-tag-input/bootstrap-tagsinput.css') }}">

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('lib/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/kendo/js/kendo.all.min.js') }}"></script>
    <script src="{{ asset('lib/kendo/js/cultures/kendo.culture.vi-VN.min.js') }}"></script>
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tag-input/bootstrap-tagsinput.min.js') }}"></script>
    @show

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>
<body class="interface">
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">

        <div class="navbar-header">

            {{-- The Responsive Menu Button --}}
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            {{-- The CMS Home Button --}}

            <a class="navbar-brand" href="{{ $urlSegment}}">{{ $custom_config['vendor'] }}</a>
        </div>

        @include('includes.snippets.top_mutil_level_menu')

    </div><!-- /.container -->

</div><!-- /.navbar -->
<div class="container">
    <div class="row">
        <div class="col-sm-9">
            Hello <a href="#">{{$user->username}}</a>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">

        <!--<div class="col-sm-3">

            @if($menu_items)
            <div class="list-group">
                @foreach($menu_items as $url=>$item)
                <a class="list-group-item {{ Request::is( "$urlSegment/$url*" ) ? 'active' : '' }}" href="{{ url( $urlSegment.'/'.$url ) }}">
                <span class="glyphicon glyphicon-{{ $item['icon'] }}"></span> {{ $item['name'] }}
                </a>
                @endforeach
            </div>
            @endif

        </div>-->
        <div class="col-sm-12">

            {{$content}}
        </div>

    </div>
</div>


@section('scripts')
<script>
    $(function() {
        $("#start_date" ).datepicker({ dateFormat: 'dd-mm-yy' }).val();
        $("#end_date").datepicker({ dateFormat: 'dd-mm-yy' }).val();
    });
</script>

<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('js/redactor.min.js') }}"></script>
<script src="{{ asset('js/bootbox.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/blockUI.js') }}"></script>
<script src="{{ asset('lib/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script src="{{ asset('lib/ckfinder/ckfinder.js') }}"></script>



<script>
    kendo.culture("vi-VN");

    $(document).ready(function(){
        var taggables = $('input[name="tags"]');
        var richText = $('textarea.rich');

        if( taggables.length )
            $(taggables).tagsInput({});

        if( richText.length )
            $(richText).redactor();

    });

</script>
@show
</body>
</html>