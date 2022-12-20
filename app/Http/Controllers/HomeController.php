<?php

namespace App\Http\Controllers;

use App\Models\ProductTag;
use App\Models\Promo;
use App\Models\Size;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function product_home(){
        $tags = Tag::orderBy('created_at', 'asc')->get();

        foreach($tags as $t){
            $product_tag = ProductTag::where('tag', $t->uuid)
            ->join('product','product_tag.product','=','product.uuid')
            ->select('product.*','product_tag.uuid as puuid')
            ->get();

            foreach($product_tag as $p){
                $size = Size::where('product',$p->uuid)->orderBy('order','asc')->get();

                $p->size = $size;
                $p->sampul0 = config('user_add.image').$p->sampul0;
            }

            $t->product = $product_tag;
        }

        return response()->json($tags, 200);
    }
    public function product_slider(){
        $promos = Promo::join('product','promo.product','=','product.uuid')->orderBy('promo.created_at','asc')->get();
        foreach($promos as $p){
            $size = Size::where('product',$p->uuid)->orderBy('order','asc')->get();

            $p->size = $size;
            $p->sampul0 = config('user_add.image').$p->sampul0;
        }

        return response()->json($promos, 200);
    }
}
