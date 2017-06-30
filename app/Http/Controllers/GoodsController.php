<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Request;
use App\Goods;
use Illuminate\Support\Facades\Validator;


class GoodsController extends Controller
{
    public function create(Request $request)
    {
        $input = $request::all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|regex:/^\d*(\.\d{1,2}?$)/',
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }

        
        $goods = Goods::create($input);
        
        return response()->json($goods);
    }

    public function get(Request $request)
    {
        $category = $request->get('category', '');
        $characteristic = $request->get('characteristic', '');
        
        $goods = Goods::where('goods.category', 'like', '%'.$category.'%')
            ->orWhere('goods.characteristic', 'like', '%'.$characteristic.'%')->get();
        if ($goods)
            return response()->json($goods, 200);

        return response()->json(204);

    }

    public function update(Request $request, $id)
    {
        $input = $request::all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|regex:/^\d*(\.\d{1,2}?$)/',
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }

        $goods = Goods::find($id);

        $goods->update($input);

        return response()->json($goods);
    }

    public function delete(Request $request, $id)
    {
        $goods = Goods::find($id);
        if(!$goods){
            return response()->json([],404);
        }
        $goods->delete();
        return response()->json(null, 204);
    }

    public function autocomplete(Request $request)
    {

        $q = $request->get('q','');

        $tags = Tag::where('goods.category', 'like', $q.'%')
            ->orWhere('goods.characteristic', 'like', $q.'%')->get();


        return response()->json($tags);
    }
}
