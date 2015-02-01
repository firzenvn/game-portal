@if(!isset($linkRss) || !isset($numNews))

<p class="text-danger">Thiếu tham số</p>

@else
<?php
$rss_array = array(
                'title',
                'link',
                'pubDate'
            );
$rss_tag = 'item';
$items = \Util\CommonHelper::rss_to_array($rss_tag,$rss_array,$linkRss);
$count=0;
?>


<ul>
@foreach($items as $item)
<?php
$count++;
    if($count > $numNews) break;
?>
    <li>
        <a href="{{str_replace('?goto=newpost','',$item['link'])}}" target="_blank">[Diễn đàn] {{$item['title']}}</a>
        <p>{{date_format(date_create($item['pubDate']),'d/m')}}</p>
    </li>
@endforeach

</ul>

@endif