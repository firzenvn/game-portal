<?php



App::bind('Services\TemplateService', function(){
    return new Services\ImplTemplateService();
});

App::bind('Services\PageService', function(){
    return new Services\ImplPageService();
});

App::bind('Services\CatalogService', function(){
    return new Services\ImplCatalogService();
});

App::bind('Services\ArticleService', function(){
    return new Services\ImplArticleService();
});


App::bind('Services\GameService', function(){
    return new Services\ImplGameService();
});


App::bind('Services\UploadService', function(){
    return new Services\ImplUploadService();
});

App::bind('Services\TagService', function(){
    return new Services\ImplTagService();
});

App::bind('Services\BlockService', function(){
    return new Services\ImplBlockService();
});
