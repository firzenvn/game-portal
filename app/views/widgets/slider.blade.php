   <div id="slider" class="nivoSlider">
   @foreach($allArticles as $anArticle)
    <a href="{{$anArticle->getGameUrl($category->alias)}}#top" class="nivo-imageLink" style="display: block">
        <img src="{{$anArticle->path}}" alt="{{$anArticle->title}}" title="{{$anArticle->title}}" style="visibility: hidden"/>
    </a>
   @endforeach
    </div>
    <script type="text/javascript">
        $(window).load(function() {
            $('#slider').nivoSlider({directionNav: false });
        });
    </script>
