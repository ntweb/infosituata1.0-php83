<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cliente extends Model
{
    protected $table = 'clienti';
    protected $keyType = 'string';

    /** Servono per l'import da excel **/
    protected $fillable = ['rs','nome','cognome','piva','cf','indirizzo','cap','citta','provincia','telefono','sdi','pec'];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function getLabelAttribute() {
        $label = $this->rs ?? $this->cognome . ' ' . $this->nome;
        return Str::title($label);
    }
}
