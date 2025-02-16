<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ScadenzeRepo
{

    public function get($id) {
        $scadenza = \App\Models\Scadenza::with('gruppi', 'module', 'detail')->whereId($id)->first();
        if (!$scadenza) return null;

        return $scadenza;
    }

    public function check($id) {
        $scadenza = $this->get($id);
        if (!$scadenza) return null;

        $scadenza->checked_at = \Carbon\Carbon::now();
        $scadenza->checked_by = Auth::user()->id;
        $scadenza->save();

        return $scadenza;
    }

    public function edit($id)
    {
        $scadenza = $this->get($id);
        if (!$scadenza) return null;

        if ($scadenza->item_id == 0) {
            $data['scadenza'] = $this->addExtraProperties($scadenza);
            return $data;
        }

        $item = \App\Models\Item::find($scadenza->item_id);
        if (!$item) return null;

        switch ($item->controller) {
            case 'utente':
                return $this->_utente($item, $scadenza);

            case 'attrezzatura':
                return $this->_attrezzatura($item, $scadenza);

            case 'materiale':
                return $this->_materiale($item, $scadenza);

            case 'mezzo':
                return $this->_mezzo($item, $scadenza);

            case 'risorsa':
                return $this->_risorsa($item, $scadenza);
        }

        return null;
    }

    public function _utente($item, $scadenza = null) {
        $modulo = \App\Models\InfosituataModule::with('details')->whereModule($item->controller)->first();
        if (!$modulo) abort(404);
        $data['moduli_details'] = $modulo->details()->with('scadenze')->get();

        $data['item'] = $item;
        $data['scadenza'] = $this->addExtraProperties($scadenza);
        $data['gruppi'] = \App\Models\Gruppo::whereAziendaId($item->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($scadenza) {
            $data['gruppiSel'] = $scadenza->gruppi->pluck('id', 'id');
            // return view('dashboard.scadenzario.edit', $data);
        }

        // return view('dashboard.scadenzario.create', $data);

        return $data;
    }

    public function _attrezzatura($item, $scadenza = null) {
        $modulo = \App\Models\InfosituataModule::with('details')->whereModule('attrezzature')->first();
        if (!$modulo) abort(404);
        $data['moduli_details'] = $modulo->details()->with('scadenze')->get();

        $data['item'] = $item;
        $data['scadenza'] = $this->addExtraProperties($scadenza);
        $data['gruppi'] = \App\Models\Gruppo::whereAziendaId($item->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($scadenza) {
            $data['gruppiSel'] = $scadenza->gruppi->pluck('id', 'id');
            // return view('dashboard.scadenzario.edit', $data);
        }

        // return view('dashboard.scadenzario.create', $data);

        return $data;
    }

    public function _materiale($item, $scadenza = null) {
        $modulo = \App\Models\InfosituataModule::with('details')->whereModule('materiali')->first();
        if (!$modulo) abort(404);
        $data['moduli_details'] = $modulo->details()->with('scadenze')->get();

        $data['item'] = $item;
        $data['scadenza'] = $this->addExtraProperties($scadenza);
        $data['gruppi'] = \App\Models\Gruppo::whereAziendaId($item->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($scadenza) {
            $data['gruppiSel'] = $scadenza->gruppi->pluck('id', 'id');
            // return view('dashboard.scadenzario.edit', $data);
        }

        // return view('dashboard.scadenzario.create', $data);
        return $data;
    }

    public function _mezzo($item, $scadenza = null) {
        $modulo = \App\Models\InfosituataModule::with('details')->whereModule('attrezzature')->first();
        if (!$modulo) abort(404);
        $data['moduli_details'] = $modulo->details()->with('scadenze')->get();

        $data['item'] = $item;
        $data['scadenza'] = $this->addExtraProperties($scadenza);
        $data['gruppi'] = \App\Models\Gruppo::whereAziendaId($item->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($scadenza) {
            $data['gruppiSel'] = $scadenza->gruppi->pluck('id', 'id');
            // return view('dashboard.scadenzario.edit', $data);
        }

        // return view('dashboard.scadenzario.create', $data);

        return $data;
    }

    public function _risorsa($item, $scadenza = null) {
        $modulo = \App\Models\InfosituataModule::with('details')->whereModule('risorse')->first();
        if (!$modulo) abort(404);
        $data['moduli_details'] = $modulo->details()->with('scadenze')->get();

        $data['item'] = $item;
        $data['scadenza'] = $this->addExtraProperties($scadenza);
        $data['gruppi'] = \App\Models\Gruppo::whereAziendaId($item->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($scadenza) {
            $data['gruppiSel'] = $scadenza->gruppi->pluck('id', 'id');
            // return view('dashboard.scadenzario.edit', $data);
        }

        // return view('dashboard.scadenzario.create', $data);

        return $data;
    }

    private function addExtraProperties ($scadenza) {
        if (!$scadenza) return null;

        $scadenza->scaduto = !$scadenza->checked_at && $scadenza->end_at < date('Y-m-d');
        $scadenza->can_save = Gate::allows('can-create');
        return $scadenza;
    }

}
