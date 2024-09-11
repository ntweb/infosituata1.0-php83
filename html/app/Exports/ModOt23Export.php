<?php
namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ModOt23Export implements FromView {

    use Exportable;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

//    public function query() {
//
//        Log::info($this->query->get());
//        return $this->query->get();
//    }

    public function view(): View
    {
        return view('exports.modot23', [
            'list' => $this->query->get()
        ]);
    }
}
