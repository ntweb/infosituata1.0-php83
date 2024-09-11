<?php

namespace App\Models;

use App\Scopes\AziendaIdOrNullScope;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $keyType = 'string';
}
