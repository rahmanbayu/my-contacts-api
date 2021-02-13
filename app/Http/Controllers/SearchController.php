<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(){
        $data = request()->validate([
            'searchTerm' => 'required',
        ]);
        $contacts = Contact::search($data['searchTerm'])->where('user_id', Auth::id())->get();
        return ContactResource::collection($contacts);
    }
}
