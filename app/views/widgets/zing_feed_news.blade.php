<ul>
<?php $count = 0; ?>
    @foreach($news_items as $item)
    <?php
        $count++;
        if($count > $feed_display) break;
    ?>
    <li>
        <a target="_blank" href="{{$item['link'] }}">{{ $item['title'] }}</a>
    </li>
    @endforeach
</ul>
