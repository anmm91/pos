<?php

//function __($key, $replace = [], $locale = null)
//{
//    return app('translator')->getFromJson($key, $replace, $locale);
//}
function ___($key,$replace=[],$locale=null){
    return app('translator')->getFormJson($key,$replace,$locale);
}

    function responseJson($status,$message,$data=null){

        $response=['status'=>$status,'msg'=>$message,'data'=>$data];

return response()->json($response);
    }

    function responseJson1($status,$message,$data){


        $response=[
            'status'=>$status,
            'msg'=>$message,
            'data'=>$data
        ];
        return response()->json($response);
    }
