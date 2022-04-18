<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlideController extends Controller
{
    public function getSlide(){
        $slides = Slide::where('choose', 1)->get();
        return response(['slides' => $slides], 200);
    }

    public function getFullInforSlide(Request $request){
        return response(['slide' => Slide::find($request->id)], 200);
    }

    public function getAllSlide(){
        return response(['slides' => Slide::all()], 200);
    }

    public function createSlide(Request $request){
        try{
            DB::beginTransaction();
            Slide::create(['link' => $request->link]);
            DB::commit();
            return response(['status' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['status' => $e], 400);
        }
    }

    public function updateSlide(Request $request){
        try{
            DB::beginTransaction();
            Slide::find($request->slideId)->update(['link' => $request->link]);
            DB::commit();
            return response(['status' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['status' => $e], 400);
        }
    }
}
