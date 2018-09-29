<?php

namespace App\Providers;

use App\Post;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ( file_exists(base_path('.env')) ){
            /**
             * Set dynamic configuration for third party services
             */
            $facebookConfig = [
                'services.facebook' =>
                    [
                        'client_id' => get_option('fb_app_id'),
                        'client_secret' => get_option('fb_app_secret'),
                        'redirect' => url('login/facebook-callback'),
                    ]
            ];
            $googleConfig = [
                'services.google' =>
                    [
                        'client_id' => get_option('google_client_id'),
                        'client_secret' => get_option('google_client_secret'),
                        'redirect' => url('login/google-callback'),
                    ]
            ];
            config($facebookConfig);
            config($googleConfig);

            /**
             * Email from name
             */

            $emailConfig = [
                'mail.from' =>
                    [
                        'address' => get_option('email_address'),
                        'name' => get_option('site_name'),
                    ]
            ];
            config($emailConfig);

            view()->composer('*', function ($view) {
                $header_menu_pages = Post::whereStatus(1)->where('show_in_header_menu', 1)->get();
                $show_in_footer_menu = Post::whereStatus(1)->where('show_in_footer_menu', 1)->get();

                $view->with(['header_menu_pages' => $header_menu_pages, 'show_in_footer_menu' => $show_in_footer_menu]);
            });

        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
