<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Schema;
use Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

       // error_reporting(0);
        define('PURCHORDER',101);
        define('PURCHINVOICE',102);
        define('SALESORDER',201);
        define('SALESINVOICE',202);
        define('DELIVERYORDER',301);
        define('STOCKMOVEIN',401);
        define('STOCKMOVEOUT',402);


        if(env('DB_DATABASE') != '') {
       
            if(Schema::hasTable('email_config'))
            {
                $result = DB::table('email_config')->first();
                Config::set([
                        'mail.driver'     => $result->email_protocol,
                        'mail.host'       => $result->smtp_host,
                        'mail.port'       => $result->smtp_port,
                        'mail.from'       => ['address' => $result->from_address,
                                              'name'    => $result->from_name ],
                        'mail.encryption' => $result->email_encryption,
                        'mail.username'   => $result->smtp_username,
                        'mail.password'   => $result->smtp_password
                        ]);
            }
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
