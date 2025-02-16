@php
    $class = $formClass ?? 'ns';
@endphp


<div class="modal fade" id="modalChecklist" tabindex="-1" role="dialog" aria-labelledby="modalCreateNode" aria-hidden="true">
        <div class="modal-dialog @if(isset($checklistData)) modal-xl @endif" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $label ?? $checklist->label }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="checklist-create-body">

                    <div class="row">

                        <div class="@if(isset($checklistData)) col-md-7 @else col-12 @endif">

                            <form class="{{ $class }}" id="frmChecklist" action="{{ $action }}" autocomplete="none" method="post" data-callback="{{ $callback ?? null }}">
                                @csrf
                                <input type="hidden" name="checklists_templates_id" value="{{ $checklist->id }}">
                                @if(isset($id))
                                    <input type="hidden" name="id" value="{{ $id }}">
                                @endif
                                @if(isset($reference_id))
                                    <input type="hidden" name="reference_id" value="{{ $reference_id }}">
                                    <input type="hidden" name="reference_controller" value="{{ $reference_controller }}">
                                @endif
                                @if(isset($reopenForm))
                                    <input type="hidden" name="reopenForm" value="1">
                                @endif

                                {{-- Checklist render --}}
                                @foreach($checklist->children as $section)
                                    <div class="row">
                                        <div class="col-12">
                                            <h6>{{ $section->label }}</h6>
                                            <p>{{ $section->description }}</p>
                                            @foreach($section->children as $field)
                                                <div class="row">
                                                    @if($field->type == 'input')
                                                        @component('layouts.components.forms.text', ['name' => $field->key, 'class' => 'col-md-12', 'value' => @$checklistData[$field->key][0]['value'], 'helper' => $field->description])
                                                            {{ $field->label }}
                                                        @endcomponent
                                                    @elseif($field->type == 'textarea')
                                                        @component('layouts.components.forms.textarea', ['name' => $field->key, 'class' => 'col-md-12', 'value' => @$checklistData[$field->key][0]['value_big'], 'helper' => $field->description])
                                                            {{ $field->label }}
                                                        @endcomponent
                                                    @elseif($field->type == 'date')
                                                        @component('layouts.components.forms.date-native', ['name' => $field->key, 'class' => 'col-md-12', 'value' => @$checklistData[$field->key][0]['value'], 'helper' => $field->description])
                                                            {{ $field->label }}
                                                        @endcomponent
                                                    @elseif($field->type == 'select')
                                                        @php
                                                            $elements = explode(',', $field->value);
                                                            array_unshift($elements , '');
                                                            $elements = array_combine($elements, $elements)
                                                        @endphp
                                                        @component('layouts.components.forms.select', ['name' => $field->key, 'class' => 'col-md-12', 'value' => @$checklistData[$field->key][0]['value'], 'helper' => $field->description, 'elements' => $elements])
                                                            {{ $field->label }}
                                                        @endcomponent
                                                    @elseif($field->type == 'radio')
                                                        @php
                                                            $elements = explode(',', $field->value);
                                                            $elements = array_combine($elements, $elements)
                                                        @endphp
                                                        @component('layouts.components.forms.radio', ['name' => $field->key, 'class' => 'col-md-12', 'value' => @$checklistData[$field->key][0]['value'], 'helper' => $field->description, 'elements' => $elements, 'inline' => true])
                                                            {{ $field->label }}
                                                        @endcomponent
                                                    @else($field->type == 'checkbox')
                                                        @php
                                                            $elements = explode(',', $field->value);
                                                            $elements = array_combine($elements, $elements);

                                                            $values = [];
                                                            if (@$checklistData[$field->key][0]['value']) {
                                                                $values = json_decode($checklistData[$field->key][0]['value']);
                                                                if (!$values) $values = [];
                                                                $values = array_combine($values, $values);
                                                                // dump($values);
                                                            }
                                                        @endphp
                                                        @component('layouts.components.forms.checkbox', ['name' => $field->key, 'class' => 'col-md-12', 'value' => $values, 'helper' => $field->description, 'elements' => $elements, 'inline' => true])
                                                            {{ $field->label }}
                                                        @endcomponent
                                                    @endif

                                                    {{--                                        @if($field->description)--}}
                                                    {{--                                            <div class="col-12">--}}
                                                    {{--                                                <small>{{ $field->description }}</small>--}}
                                                    {{--                                            </div>--}}
                                                    {{--                                        @endif--}}
                                                </div>

                                            @endforeach
                                        </div>
                                        @if (!$loop->last)
                                            <div class="col-12">
                                                <hr>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </form>
                        </div>

                        @if(isset($checklistData))
                            <div class="col-md-5">
                                @component('dashboard.upload.s3.upload', ['reference_id' => $id, 'reference_table' => 'checklists'])
                                    Checklist
                                @endcomponent
                            </div>
                        @endif

                    </div>
                </div>
                @if(!request()->has('_demo'))
                <div class="modal-footer">
                    @if(isset($checklistId))
                        <a href="{{ route('checklist.print', $checklistId) }}" class="btn btn-default"><i class="bx bx-printer"></i> Stampa</a>
                    @endif
                    <button type="button" class="btn btn-primary btnSubmitFormById" data-form-selector="#frmChecklist" data-new="0">Salva</button>
                </div>
                @endif
            </div>
        </div>
    </div>


