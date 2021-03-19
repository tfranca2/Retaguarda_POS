<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){

        $totalUsers = User::All()->count();

        return view('home',[
            'usuarios' => $totalUsers,
        ]);

    }

}
