<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BirthdayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function current_month_birthdays(){
        $user = User::factory()->create();
        $birthdaysContact = \App\Models\Contact::factory()->create([
            'user_id' => $user->id,
            'birthday' => now()->subYear()
        ]);

        $noBirthday = \App\Models\Contact::factory()->create([
            'user_id' => $user->id,
            'birthday' => now()->subMonth()
        ]);

        $this->get('/api/birthdays')
        ->assertJsonCount(1);
    }
}
