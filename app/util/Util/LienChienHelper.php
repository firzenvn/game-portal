<?php
namespace Util;




class LienChienHelper{

    public static function getLeagueWin($allMatch){
        $winArr = array('r1'=>array(),'r2'=>array(),'r3'=>array(),
            'v1'=>array(),'v2'=>array(),'v3'=>array(),);
        foreach ($allMatch as $aMatch) {
            if($aMatch->round == 8){
            }elseif($aMatch->round == 4){
                if($aMatch->result == 1){
                    $winArr['r3'][$aMatch->first_username] = 'win';
                    $winArr['r3'][$aMatch->second_username] = '';
                }
                else{
                    $winArr['r3'][$aMatch->first_username] = '';
                    $winArr['r3'][$aMatch->second_username] = 'win';
                }
                $winArr['v3'][$aMatch->first_username] = $aMatch->video_key;
                $winArr['v3'][$aMatch->second_username] = $aMatch->video_key;
            }
            elseif($aMatch->round == 2){
                if($aMatch->result == 1){
                    $winArr['r2'][$aMatch->first_username] = 'win';
                    $winArr['r2'][$aMatch->second_username] = '';
                }
                else{
                    $winArr['r2'][$aMatch->first_username] = '';
                    $winArr['r2'][$aMatch->second_username] = 'win';
                }

                $winArr['v2'][$aMatch->first_username] = $aMatch->video_key;
                $winArr['v2'][$aMatch->second_username] = $aMatch->video_key;
            }
            else{
                if($aMatch->result == 1){
                    $winArr['r1'][$aMatch->first_username] = 'win';
                    $winArr['r1'][$aMatch->second_username] = '';
                }
                else{
                    $winArr['r1'][$aMatch->first_username] = '';
                    $winArr['r1'][$aMatch->second_username] = 'win';
                }
                $winArr['v1'][$aMatch->first_username] = $aMatch->video_key;
                $winArr['v1'][$aMatch->second_username] = $aMatch->video_key;
            }
        }

        return $winArr;

    }

    public static function getLeagueMap($allMatch){
        $leftArr = array('r1'=>array(), 'r2'=>array(), 'r3'=>array());
        $rightArr = array('r1'=>array(), 'r2'=>array(), 'r3'=>array());
        $champion = null;

        $leftR2CtrArr = array();
        $rightR2CtrArr = array();

        $leftR3CtrArr = array();
        $rightR3CtrArr = array();

        foreach ($allMatch as $aMatch) {
            if($aMatch->round == 8){
                $champion = $aMatch;
            }
            elseif($aMatch->round == 4){
              if(count($leftArr['r3']) < 2){
                  array_push($leftArr['r3'],$aMatch->first_username);
                  array_push($leftArr['r3'],$aMatch->second_username);
                  $leftR3CtrArr[$aMatch->first_username] = count($leftR3CtrArr) + 1;
                  $leftR3CtrArr[$aMatch->second_username] = count($leftR3CtrArr) + 1;
              }else{
                  array_push($rightArr['r3'],$aMatch->first_username);
                  array_push($rightArr['r3'],$aMatch->second_username);
                  $rightR3CtrArr[$aMatch->first_username] = count($rightR3CtrArr) + 1;
                  $rightR3CtrArr[$aMatch->second_username] = count($rightR3CtrArr) + 1;
              }
            }
            elseif($aMatch->round == 2){
                $belongToParent = false;
                if(in_array($aMatch->first_username, $leftArr['r3'] )
                    || in_array($aMatch->second_username, $leftArr['r3'])){

                    $index1 =  isset($leftR3CtrArr[$aMatch->first_username])?$leftR3CtrArr[$aMatch->first_username]:0 ;
                    $index2 =  isset($leftR3CtrArr[$aMatch->second_username])?$leftR3CtrArr[$aMatch->second_username]:0 ;
                    $index = $index1 + $index2;
                    $leftArr['r2'][($index-1)*2] = $aMatch->first_username;
                    $leftArr['r2'][($index-1)*2 + 1] = $aMatch->second_username;
                    $leftR2CtrArr[$aMatch->first_username] = ($index-1)*2;
                    $leftR2CtrArr[$aMatch->second_username] = ($index-1)*2 + 1;

                    $belongToParent = true;
                }
                if(in_array($aMatch->first_username, $rightArr['r3'])
                    || in_array($aMatch->second_username, $rightArr['r3'])){

                    $index1 =  isset($rightR3CtrArr[$aMatch->first_username])?$rightR3CtrArr[$aMatch->first_username]:0 ;
                    $index2 =  isset($rightR3CtrArr[$aMatch->second_username])?$rightR3CtrArr[$aMatch->second_username]:0 ;
                    $index = $index1 + $index2;
                    $rightArr['r2'][($index-1)*2] = $aMatch->first_username;
                    $rightArr['r2'][($index-1)*2 + 1] = $aMatch->second_username;

                    $rightR2CtrArr[$aMatch->first_username] =  ($index-1)*2;;
                    $rightR2CtrArr[$aMatch->second_username] = ($index-1)*2 + 1;
                    $belongToParent = true;
                }
                if(!$belongToParent){
                    if(count($leftArr['r2']) < 4 && count($leftR3CtrArr) == 0){
                        $index = count($leftArr['r2']) ;
                        $leftArr['r2'][$index]= $aMatch->first_username;
                        $leftArr['r2'][$index + 1]= $aMatch->second_username;

                        $leftR2CtrArr[$aMatch->first_username] = count($leftR2CtrArr) + 1;
                        $leftR2CtrArr[$aMatch->second_username] = count($leftR2CtrArr) + 1;

                    }else{
                        $index = count($rightArr['r2']) ;
                        $rightArr['r2'][$index]= $aMatch->first_username;
                        $rightArr['r2'][$index + 1]= $aMatch->second_username;

                        $rightR2CtrArr[$aMatch->first_username] = count($rightR2CtrArr) + 1;
                        $rightR2CtrArr[$aMatch->second_username] = count($rightR2CtrArr) + 1;
                    }
                }


            }elseif($aMatch->round == 1){
                $belongToParent = false;
                if(in_array($aMatch->first_username, $leftArr['r2'] )
                    || in_array($aMatch->second_username, $leftArr['r2'] )){
                    $index1 =  isset($leftR2CtrArr[$aMatch->first_username])?$leftR2CtrArr[$aMatch->first_username]:0 ;
                    $index2 =  isset($leftR2CtrArr[$aMatch->second_username])?$leftR2CtrArr[$aMatch->second_username]:0 ;
                    $index = $index1 + $index2;
                    $leftArr['r1'][($index-1)*2] = $aMatch->first_username;
                    $leftArr['r1'][($index-1)*2 + 1] = $aMatch->second_username;

                    $belongToParent = true;
                }
                if(in_array($aMatch->first_username, $rightArr['r2'] )
                    || in_array($aMatch->second_username, $rightArr['r2'])){
                    $index1 =  isset($rightR2CtrArr[$aMatch->first_username])?$rightR2CtrArr[$aMatch->first_username]:0 ;
                    $index2 =  isset($rightR2CtrArr[$aMatch->second_username])?$rightR2CtrArr[$aMatch->second_username]:0 ;
                    $index = $index1 + $index2;
                    $rightArr['r1'][($index-1)*2] = $aMatch->first_username;
                    $rightArr['r1'][($index-1)*2 + 1] = $aMatch->second_username;
                    $belongToParent = true;
                }
                if(!$belongToParent){
                    $slotForLeft =  count($leftR2CtrArr);
                    if($slotForLeft > 0){
                        if(count($leftArr['r1']) > 0)
                            $tmpMaxIndex = max($slotForLeft*2, max(array_keys($leftArr['r1'])) + 1);
                        else
                            $tmpMaxIndex = $slotForLeft*2;
                    }
                    else
                        $tmpMaxIndex = count($leftArr['r1']);


                    if($tmpMaxIndex < 8 ){
                        $leftArr['r1'][$tmpMaxIndex]= $aMatch->first_username;
                        $leftArr['r1'][$tmpMaxIndex + 1]= $aMatch->second_username;
                    }else{
                        $slotForRight =  count($rightR2CtrArr);
                        if($slotForRight > 0){
                            if(count($rightArr['r1']) > 0)
                                $tmpMaxIndex = max(array_keys($rightArr['r1'])) + 1;
                            else
                                $tmpMaxIndex = $slotForRight*2;
                        }
                        else
                            $tmpMaxIndex = count($rightArr['r1']);
                        $rightArr['r1'][$tmpMaxIndex]= $aMatch->first_username;
                        $rightArr['r1'][$tmpMaxIndex + 1]= $aMatch->second_username;
                    }
                }
            }
        }

        return array('lft'=>$leftArr,'rgt'=>$rightArr, 'champion'=>$champion);

    }

   }

