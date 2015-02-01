<?php
namespace Services;



use EModel\Articles;
use Util\Exceptions\BusinessException;
use Util\Exceptions\EntityNotFoundException;

class ImplArticleService extends BaseService implements ArticleService
{
    /**
     * @param $id
     * @return Articles
     */
    public function requireById($id)
    {
        $model = Articles::find($id);

        if ( ! $model) {
            throw new EntityNotFoundException;
        }
        return $model;
    }

    public function delete($id)
    {
        $article = $this->requireById($id);
        if(!$article) throw new BusinessException('Không tồn tại id bài viết: '.$id);
        // delete category
        $article->categories()->detach();
        //delete image
        $article->deleteAllImagery();
        //delete myself
        $article->delete();
        return true;
    }
}