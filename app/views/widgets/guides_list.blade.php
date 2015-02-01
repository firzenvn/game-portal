<ul>
@foreach($allArticles as $anArticle)
    <li>
        <img src="{{$anArticle->path}}" alt="{{$anArticle->title}}"/>
        <a href="{{$anArticle->getGameGuideUrl()}}#top" title="{{$anArticle->title}}"><span>[{{$anArticle->category_name}}]</span> {{$anArticle->title}}</a>
        <span>{{$anArticle->created_at->format('d/m')}}</span>
        <p>{{$anArticle->description}}</p>
    </li>
@endforeach
</ul>
@if($paginate)
<section>
{{$allArticles->links()}}
</section>
@endif