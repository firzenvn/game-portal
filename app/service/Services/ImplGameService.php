<?php
namespace Services;




use EModel\Category;
use EModel\Games;
use Util\Exceptions\BusinessException;
use Util\Exceptions\EntityNotFoundException;

class ImplGameService extends BaseService implements GameService
{
    /**
     * @param $id
     * @return Games
     */
    public function requireById($id)
    {
        $model = Games::find($id);

        if ( ! $model) {
            throw new EntityNotFoundException;
        }
        return $model;
    }

    public function delete($id)
    {
        $game = $this->requireById($id);
        if(!$game) throw new BusinessException('Không tồn tại id game: '.$id);
        // delete category
        $game->categories()->detach();
        //delete image
        $game->deleteAllImagery();
        //delete myself
        $game->delete();
        return true;
    }

    public function getByCategoryCode($catCode, $limit = null)
    {
        $category = Category::where('code', '=', $catCode)->first();
        return $category->games;
    }
}