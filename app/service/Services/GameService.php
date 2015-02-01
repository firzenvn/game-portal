<?php

namespace Services;




use EModel\Games;

interface GameService {


    /**
     * @param $id
     * @return Games
     */
    public function requireById($id);

    public function delete($id);

    public function getByCategoryCode($catCode, $limit = null);


}