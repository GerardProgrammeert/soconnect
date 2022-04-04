<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspressoMachine extends Model
{
    use HasFactory;

    public $fillable = ['water_container_level','water_container_capacity', 'beans_container_capacity','beans_container_level'];
}
