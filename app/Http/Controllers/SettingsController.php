<?php

namespace App\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{

    public function GeneralSettings(){
        $title = trans('app.general_settings');
        return view('admin.general_settings', compact('title'));
    }

    public function PaymentSettings(){
        $title = trans('app.payment_settings');
        return view('admin.payment_settings', compact('title'));
    }
    public function AdSettings(){
        $title = trans('app.ad_settings_and_pricing');
        return view('admin.ad_settings', compact('title'));
    }

    public function StorageSettings(){
        $title = trans('app.file_storage_settings');
        return view('admin.storage_settings', compact('title'));
    }
    
    public function SocialSettings(){
        $title = trans('app.social_settings');
        return view('admin.social_settings', compact('title'));
    }
    public function BlogSettings(){
        $title = trans('app.blog_settings');
        return view('admin.blog_settings', compact('title'));
    }
    public function OtherSettings(){
        $title = trans('app.other_settings');
        return view('admin.other_settings', compact('title'));
    }


    public function OtherSettingsPost(Request $request){

        if ($request->hasFile('logo')){
            $rules = ['logo'=>'mimes:jpeg,jpg,png'];
            $this->validate($request, $rules);

            $image = $request->file('logo');
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());

            $image_name = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

            $upload_dir = public_path('uploads/logo/');
            if ( ! file_exists($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }
            $imageFileName = $upload_dir.$image_name;


            $resized = Image::make($image)->resize(null, 70, function ($constraint) {
                $constraint->aspectRatio();
            });

            try{
                //Uploading thumb
                $resized->save($imageFileName);
                $previous_photo= get_option('logo');
                update_option('logo', $image_name );

                if ($previous_photo){
                    if (file_exists($upload_dir.$previous_photo)){
                        unlink($upload_dir.$previous_photo);
                    }
                }

            } catch (\Exception $e){
                return $e->getMessage();
            }

        }

        return back();

    }



    public function ThemeSettings(){
        $title = trans('app.theme_settings');
        return view('admin.theme_settings', compact('title'));
    }
    public function modernThemeSettings(){
        $title = trans('app.modern_theme_settings');
        return view('admin.modern_theme_settings', compact('title'));
    }

    public function SocialUrlSettings(){
        $title = trans('app.social_url_settings');
        return view('admin.social_url_settings', compact('title'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request) {
        $inputs = array_except($request->input(), ['_token']);

        foreach($inputs as $key => $value) {
            $option = Option::firstOrCreate(['option_key' => $key]);
            $option -> option_value = $value;
            $option->save();
        }
        //check is request comes via ajax?
        if ($request->ajax()){
            return ['success'=>1, 'msg'=>trans('app.settings_saved_msg')];
        }
        return redirect()->back()->with('success', trans('app.settings_saved_msg'));
    }


    public function monetization(){
        $title = trans('app.website_monetization');
        return view('admin.website_monetization', compact('title'));
    }

}
