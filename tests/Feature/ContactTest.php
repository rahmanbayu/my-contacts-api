<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_should_redirected_to_login(){
        $reqponse = $this->post('/api/contacts', $this->data());
        $reqponse->assertRedirect('http://localhost:3000/login');
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function an_authenticated_user_can_add_contact(){
        \App\Models\User::factory()->create();
        $this->post('/api/contacts', $this->data());
        $contact = Contact::first();
        $this->assertEquals('rahman', $contact->name);
        $this->assertEquals('test@gmai.com', $contact->email);
        $this->assertEquals('05/04/1998', $contact->birthday->format('m/d/Y'));
        $this->assertEquals('ABC Comp', $contact->company);
    }

    /** @test */
    public function field_is_required(){
        collect(['name', 'email', 'birthday', 'company'])->each(function ($field) {
            $response = $this->post('/api/contacts', array_merge($this->data(), [$field => '']));
            $response->assertSessionHasErrors($field);
            $this->assertCount(0, Contact::all());
        });

    }

    /** @test */
    public function a_email_must_valid_is_required(){
        $response = $this->post('/api/contacts', array_merge($this->data(), ['email' => 'This Is Not Email']));

        $response->assertSessionHasErrors('email');
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function birthday_are_properly_store(){
        $this->withoutExceptionHandling();
        $response = $this->post('/api/contacts', $this->data());
        $this->assertCount(1, Contact::all());
        $this->assertInstanceOf(Carbon::class, Contact::first()->birthday);
        $this->assertEquals('05-04-1998', Contact::first()->birthday->format('m-d-Y'));
    }

    /** @test */
    public function get_single_contact(){
        $contact = \App\Models\Contact::factory()->create();
        $response = $this->get("/api/contacts/1");
        $response->assertJsonFragment([
            'name' => $contact->name,
            'email' => $contact->email,
            'birthday' => $contact->birthday,
            'company' => $contact->company,
        ]);
    }

    /** @test */
    public function contact_can_be_updated(){
        $this->withoutExceptionHandling();
        $contact = \App\Models\Contact::factory()->create();
        $response = $this->patch("/api/contacts/1", $this->data());
        $contact = $contact->fresh();          // re get contact setelah di update
        $this->assertEquals('rahman', $contact->name);
        $this->assertEquals('test@gmai.com', $contact->email);
        $this->assertEquals('05/04/1998', $contact->birthday->format('m/d/Y'));
        $this->assertEquals('ABC Comp', $contact->company);
    }

    /** @test */
    public function contact_can_be_deleted(){
        $contact = \App\Models\Contact::factory()->create();
        $response = $this->delete("/api/contacts/1");
        $this->assertCount(0, Contact::all());
    }



    /** @test */
    public function a_name_is_required(){
        $response = $this->post('/api/contacts', array_merge($this->data(), ['name' => '']));

        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function a_email_is_required(){
        $response = $this->post('/api/contacts',
    array_merge($this->data(), ['email' => '']));

        $response->assertSessionHasErrors('email');
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function a_birthday_is_required(){
        $response = $this->post('/api/contacts',
    array_merge($this->data(), ['birthday' => '']));

        $response->assertSessionHasErrors('birthday');
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function a_company_is_required(){
        $response = $this->post('/api/contacts',
    array_merge($this->data(), ['company' => '']));

        $response->assertSessionHasErrors('company');
        $this->assertCount(0, Contact::all());
    }

    private function data(){
        return [
            'name' => 'rahman',
            'email' => 'test@gmai.com',
            'birthday' => '05/04/1998',
            'company' => 'ABC Comp'
        ];
    }
}
