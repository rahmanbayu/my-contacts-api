<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function index(){
        $contacts = request()->user()->contacts()->birthdays()->get();

        return ContactResource::collection($contacts);
    }
}
