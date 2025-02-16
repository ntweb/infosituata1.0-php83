<table class="table table-sm table-hover">
    @component('dashboard.commesse.analisi.components.overview-table-header', ['el' => $el])
    @endcomponent
    <tbody>
    @foreach($tree as $node)
        @component('dashboard.commesse.analisi.components.overview-table-row', ['el' => $el, 'node' => $node])
        @endcomponent
    @endforeach
    </tbody>
</table>
