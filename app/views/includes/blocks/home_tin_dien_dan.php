<div class="row">
    <section class="tindiendan-nav"><h3><a href="<?php echo isset($linkNav) ? $linkNav : '#' ?>" target="_blank">Tin diễn đàn</a></h3></section>
    <section class="tindiendan-content">
        <ul class="list-unstyled">
            <?php
            $rss_array = array(
                'title',
                'link',
                'pubDate'
            );
            $rss_tag = 'item';
			$fname=isset($forum_name) ? $forum_name : '';
            try{
                $items = \Util\CommonHelper::rss_to_array($rss_tag,$rss_array,$feed,$fname);
            }catch (Exception $e){
                $items = array();
            }

            $count=0;
                foreach($items as $item)
                {
                    $count++;
                    if($count>5) break;
                    ?>
                    <li>
                        <a class="col-xs-9" target="_blank" href="<?php echo str_replace('?goto=newpost','',$item['link'])?>">- [Diễn đàn] <?php echo $item['title']?></a>
                        <p class="col-xs-3">[<?php echo date_format(date_create($item['pubDate']),'d/m') ?>]</p>
                    </li>
            <?php
                }
            ?>
        </ul>
    </section>
</div>
