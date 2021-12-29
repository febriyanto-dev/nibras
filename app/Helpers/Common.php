<?php

namespace App\Helpers;

use Hashids\Hashids;

class Common
{
    public static function hashids(){
        return new Hashids();
    }

    public static function FormatNumber($nilai,$decimal=0,$point=".",$thousands=",", $type_data=""){

        $return = 0;
        if(is_numeric($nilai)){
            if($type_data=="ind"){
                $return = number_format($nilai, $decimal, ",", ".");
            }
            else{
                $return = number_format($nilai, $decimal, $point, $thousands);
            }
        }

        return $return;
    }

    public static function SaveInt($param){
        $hasil = '';

        if($param){
            $nilai  = trim(str_replace("`", "", $param));
            $hasil  = str_replace(",", "", $nilai);
        }
        else{
            $hasil = '0';
        }

        return $hasil;
    }

}
