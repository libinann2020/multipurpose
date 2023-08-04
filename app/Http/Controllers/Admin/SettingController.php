<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index(){
        $settings = Setting::pluck('value','key')->toArray();
        if(!$settings){
            return config('settings.default');
        }
        return $settings;
    }

    public function update() {
        $settings = request()->validate([
            'app_name' => ['required', 'string'],
            'date_format' => ['required','string'],
            'pagination_limit' => ['required','int','min:1','max:100'],
        ]);
        // $settings = request()->all();
        foreach($settings->validate() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            // Setting::where('key',$key)->update(['value'=>$value]);
        }
        Cache::flush('settings');
        return response()->json(['success'=> true]);
    }
}
