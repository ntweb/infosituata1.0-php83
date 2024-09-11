<?php
namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsAttrezzaturaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManutenzioneDetail extends Model
{

    protected $table = 'manutenzioni_dettagli';

    public function manutenzione() {
        return $this->belongsTo('App\Models\Manutenzione', 'manutenzioni_id');
    }

    public function ricambio() {
        return $this->belongsTo('App\Models\Ricambio', 'ricambi_id');
    }

}

