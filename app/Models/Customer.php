<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Customer extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'customers';
    protected $fillable = [
        'Customer_name',
        'Customer_email',
        'first_name',
         'last_name'
    ];
}
