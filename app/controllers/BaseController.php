<?php


use EModel\Games;
use Illuminate\Routing\Controller;
class BaseController extends Controller {



    /**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
        if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
            //TODO:move to admin only
            $this->layout->with('custom_config',Config::get('::app.custom_config'));
		}
	}


    protected function errorToString($errors){
        $result = ' <div class="alert alert-danger"><p><strong>Có lỗi phát sinh.</strong></p>';
        if($errors->all()){
            foreach ( $errors->all('<p>:message</p>')as $msg) {
                $result = $result.$msg;
            }
        }
        $result = $result.' </div>';

        return $result;
    }


    protected function successToString($successString){
        $result = ' <div class="alert alert-success">';
        $result = $result.'<p><strong>'.$successString.'</strong></p>';
        $result = $result.' </div>';

        return $result;
    }


    protected  function loadLayout( $layout){
        $myApp=App::make('myApp');
        $game=$myApp->game;
        $subdomain=$game->subdomain;
        $this->layout = 'layouts.'.$layout;
        $this->setupLayout();
        $this->game = Games::where('subdomain', '=', $subdomain)->first();
        $this->layout->with('gameId', $this->game->id)
            ->with('subdomain', $subdomain);
    }



}
