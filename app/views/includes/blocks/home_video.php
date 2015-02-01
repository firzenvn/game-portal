<section class="video">
    <a id="video" href="https://www.youtube.com/watch?v=<?php echo isset($keyVideo) ? $keyVideo : '' ?>?fs=1&autoplay=1">
        <img src="http://img.youtube.com/vi/<?php echo isset($keyVideo) ? $keyVideo : '' ?>/0.jpg" alt="video">
        <section class="playvideo">Play</section>
    </a>
</section>
<script>
    $(function(){
        $("a#video").click(function() {
            $.fancybox({
                'padding'		: 0,
                'autoScale'		: false,
                'transitionIn'	: 'none',
                'transitionOut'	: 'none',
                'title'			: this.title,
                'width'			: 870,
                'height'		: 523,
                'href'			: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
                'type'			: 'swf',
                'swf'			: {
                    'wmode'				: 'transparent',
                    'allowfullscreen'	: 'true'
                }
            });

            return false;
        });
    });
</script>