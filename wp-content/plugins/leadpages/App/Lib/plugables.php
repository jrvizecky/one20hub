<?php

if(!function_exists('mb_strtoupper')){
    function mb_strtoupper($string, $endcoding = null){
        return strtoupper($string);
    }
}