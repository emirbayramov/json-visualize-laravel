<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request){
        $background = $request->input('background','(255,255,255)');
        $depth = $request->input('depth','max');
        $jsonString = $request->input('json','{"hello":"world"}');

        $isRgb = $this->isRgb($background);
        if(!$isRgb && !$this->isUrl($background)){
            $background = '(255,255,255)';
            $isRgb = true;
        }

        $json = json_decode($jsonString,true);
        $jsonView = $this->jsonView($json,$depth);

        return view('home')->with(compact('background','isRgb','depth','jsonView','jsonString'));
    }

    private function isUrl($background){
        return filter_var($background, FILTER_VALIDATE_URL);
    }

    private function isRgb($background): bool{
        return preg_match('/\((?:\s*\d+\s*,){2}\s*[\d]+\)/',$background)===1;
    }

    private function jsonView($json,$depth):string{
        $res = '<ul class="'.( $depth !== 'max' && $depth <=0 ?'hide-children':'').'">';

        foreach($json as $key => $value){
            $res.='<li class="list">';
            $res .= $key. ':' . $this->getType($value);
            if(gettype($value)==='array') {
                $res .= $this->jsonView($value, $depth === 'max' ? $depth : ($depth - 1));
            }else{
                $res.='<ul ><li>value:' . htmlentities($value) . '</li></ul>';
            }
            $res.='</li>';
        }
        $res .= '</ul>';
        return $res;

    }

    private function getType($arr):string{
        if (array() === $arr) return 'array';
        if(is_numeric($arr)) return 'number';
        if(gettype($arr)!=='array')return gettype($arr);
        if(array_keys($arr) !== range(0, count($arr) - 1)) return 'object';
        return 'array';
    }
}
