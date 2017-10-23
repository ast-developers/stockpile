<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Artisan;

class CronController extends Controller
{
    public function index()
    { 
      Artisan::call('db:seed');
    }
}
