<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PodcastProcessed;

use App\Events\PostCreated;
use App\Providers\LoginHistory;
use \Log;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orderid = date('i:s');
        $orderid = str_replace(':','',$orderid);
        \Log::debug('*** Home Page : '.$orderid.'***');

        DB::table('test')->insert(
            ['name' => 'john.com']
        );
        

        event(new PostCreated($orderid));

        //new PodcastProcessed($orderid);
        //event(new LoginHistory($orderid));
        echo $orderid;;
    }
}
