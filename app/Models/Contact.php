<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

class Contact extends Model
{
    use HasFactory, Searchable;
    protected $dates = ['birthday'];

    protected $fillable = ['name', 'email', 'birthday', 'company'];

    public function setBirthdayAttribute($birthday){
        $this->attributes['birthday'] = Carbon::parse($birthday);
    }

    public function scopeBirthdays($query){
        return $query->whereRaw('birthday like "%-'. now()->format('m') . '-%"');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function path(){
        return "/contacts/$this->id";
    }
}
