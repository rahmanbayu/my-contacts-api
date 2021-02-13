<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    public function index(){
        $this->authorize('viewAny', Contact::class);
        return ContactResource::collection(request()->user()->contacts);
    }

    public function store(ContactRequest $request){
        $this->authorize('create', Contact::class);
        $contact = request()->user()->contacts()->create([
            'name' => ucwords($request->name),
            'email' => $request->email,
            'birthday' => $request->birthday,
            'company' => $request->company,
        ]);

        return (new ContactResource($contact))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Contact $contact){
        $this->authorize('view', $contact);
        return new ContactResource($contact);
    }

    public function update(Contact $contact, ContactRequest $request){
        $this->authorize('update', $contact);
        $contact->update([
            'name' => ucwords($request->name),
            'email' => $request->email,
            'birthday' => $request->birthday,
            'company' => $request->company,
        ]);
        return (new ContactResource($contact))
                ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function delete(Contact $contact){
        $this->authorize('delete', $contact);
        $contact->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }
}
