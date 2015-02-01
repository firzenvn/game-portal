<?php
namespace Controllers\Admin;


use DB;
use EModel\Games;
use EModel\GameServer;
use EModel\GiftCode;
use EModel\GiftCodeType;
use Exception;
use Illuminate\Support\Facades\Form;
use Illuminate\Support\MessageBag;
use Input;
use Redirect;
use Response;
use User;
use View;

class GiftCodesController extends AdminBaseController {

	public function __construct(){
        parent::__construct();

    }

    public function getIndex(){
        $items = GiftCodeType::orderBy('created_at','desc');
        if(Input::has('id'))
        {
            $items->where('id',Input::get('id'));
        }
        if(Input::has('name'))
        {
            $items->where('name','LIKE','%'.Input::get('name').'%');
        }
        if(Input::has('game_id'))
        {
            $items->where('game_id',Input::get('game_id'));
        }
        if(Input::has('start_date'))
        {
            $items->where('created_at','>=',date("Y-m-d H:i:s", strtotime(Input::get('start_date'))));
        }
        if(Input::has('end_date'))
        {
            $items->where('created_at','<=',date("Y-m-d 23:59:59", strtotime(Input::get('end_date'))));
        }
        if(Input::has('active'))
        {
            $items->where('active',Input::get('active'));
        }

        $items = $items->paginate(10);
        $allGames = Games::lists('name','id');
        $this->layout->content = View::make('admin.giftcode_index', array(
            'items'=>$items,
            'allGames'=>$allGames
        ));
    }

    public function getNew()
    {
        $allGames = Games::lists('name','id');
        $applyFors = \Config::get('common.giftcode_apply_for');
        $this->layout->content = View::make('admin.giftcode_new',array(
            'allGames'=>$allGames,
            'applyFors'=>$applyFors
        ));
    }

    public function postNew()
    {
        $paramArr = Input::all();
        $newRecord = new GiftCodeType;
        $newRecord->name = $paramArr['name'];
        $newRecord->game_id = $paramArr['game'];
        $newRecord->active = $paramArr['active'];
        $newRecord->input_guide_link = $paramArr['input_guide_link'];
        $newRecord->gift_link = $paramArr['gift_link'];
        $newRecord->apply_for = $paramArr['apply_for'];
        $newRecord->game_server_id = ($paramArr['server'] != '') ? $paramArr['server'] : null;
        $newRecord->save();

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Thêm mới thành công!')));

    }

    public function getDelete( $id ){
        GiftCodeType::find($id)->delete();
        $message = 'Xóa thành công.';
        return Redirect::to( '/admin/giftcodes' )
            ->with('success', new MessageBag( array( $message ) ) );

    }

    public function getEdit($id){
        $item = GiftCodeType::find($id);
        $allGames = Games::lists('name','id');
        $applyFors = \Config::get('common.giftcode_apply_for');
        $allServers = GameServer::where('game_id',$item->game_id)->get()->lists('full_name','id');
        $this->layout->content = View::make('admin.giftcode_edit', array(
            'item'=>$item,
            'allGames'=>$allGames,
            'applyFors' => $applyFors,
            'allServers' => $allServers
        ));
    }

    public function postEdit($id){
        $paramArr = Input::all();

        $record = GiftCodeType::find($id);
        $record->name = $paramArr['name'];
        $record->game_id = $paramArr['game'];
        $record->active = $paramArr['active'];
        $record->input_guide_link = $paramArr['input_guide_link'];
        $record->gift_link = $paramArr['gift_link'];
        $record->apply_for = $paramArr['apply_for'];
        $record->game_server_id = ($paramArr['server'] != '') ? $paramArr['server'] : null;
        $record->save();

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Sửa thành công!')));

    }

    public function getList($id)
    {
        $query = GiftCode::leftjoin('users','user_id','=','users.id')->where('giftcode_type_id',$id)->orderBy('gift_code.id','desc');
        if(Input::has('id'))
        {
            $query->where('gift_code.id',Input::get('id'));
        }
        if(Input::has('code'))
        {
            $query->where('code',Input::get('code'));
        }
        if(Input::has('username'))
        {
            $query->where('username',Input::get('username'));
        }
        if(Input::has('start_date'))
        {
            $query->where('gift_code.created_at','>=',date("Y-m-d H:i:s", strtotime(Input::get('start_date'))));
        }
        if(Input::has('end_date'))
        {
            $query->where('gift_code.created_at','<=',date("Y-m-d 23:59:59", strtotime(Input::get('end_date'))));
        }
        $items = $query->select('gift_code.id as id','code','username')->paginate(10);
        $count_used = $query->where('user_id','!=','')->count('*');
        $type = GiftCodeType::find($id);
        $this->layout->content = View::make('admin.giftcode_list', array(
            'items'=>$items,
            'type'=>$type,
            'count_used'=>$count_used
        ));
    }

    public function postImport(){
        if(Input::file('file')->getClientOriginalExtension() != 'csv')
        {
            $message = "Chỉ được Import file *.CSV";
            return Redirect::back()->with('errors', new MessageBag( array( $message ) ) );
        }

        //upload csv
        $file = Input::file('file');
        $name = time().'-'.$file->getClientOriginalName();
        $path = public_path().'/uploads/giftcode/';
        $file->move($path,$name);

        //import csv
        DB::beginTransaction();
        try{
            $csv = fopen($path.$name, 'r');
            while (($line = fgetcsv($csv)) !== FALSE) {
                $query = new GiftCode;
                $query->code = $line[0];
                $query->giftcode_type_id = Input::get('giftcode_type_id');
                if(!$query->save())
                {
                    throw new Exception('Error');
                }
            }
            fclose($csv);
            unlink($path.$name);
        }catch (Exception $e)
        {
            DB::rollBack();
            return Redirect::back()->with('errors', new MessageBag(array($e->getMessage())));
        }
        DB::commit();
        return Redirect::back()->with('success',new MessageBag(array('Import thành công!')));
    }

    public function getDeleteCode($id)
    {
        GiftCode::find($id)->delete();
        $message = 'Xóa thành công.';
        return Redirect::back()->with('success', new MessageBag( array( $message ) ) );
    }

    public function postGameServer(){
        if(Input::has('game_id')) {
            $allServers = GameServer::where('game_id', Input::get('game_id'))->orderBy('id')->get()->lists('full_name','id');
        }else {
            $allServers = array();
        }

        return Form::select('cboServer',array(''=>'--Tất cả--')+$allServers,null,array('class'=>'selectpicker show-tick', 'id'=>'cboServer'));
    }


}