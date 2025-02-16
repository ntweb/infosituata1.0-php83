<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AziendaIdScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
//        if (Auth::user()) {
//            if (Auth::user()->azienda_id > 0) {
//                $builder->where('azienda_id', '=', Auth::user()->azienda_id);
//            }
//            else if (Auth::user()->utente_id > 0) {
//                $utente = DB::table('items')->whereId(Auth::user()->utente_id)->first();
//                $builder->where('azienda_id', '=', $utente->azienda_id);
//            }
//        }

         if (Auth::user()) {
             $azienda_id = getAziendaId();
             if ($azienda_id > 0) {
                 $builder->where('azienda_id', $azienda_id);
             }
         }

    }
}
