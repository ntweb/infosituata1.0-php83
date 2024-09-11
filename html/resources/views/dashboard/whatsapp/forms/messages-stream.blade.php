@foreach($messages as $message)

    @if($loop->first)
        <div id="messages-prev-{{ $message->id }}"></div>
        <div id="load-other-messages"
             data-url="{{  route('whatsapp.load-other-messages', ['messaggio_id' => $message->messaggio_id, 'prev' => $message->id]) }}"
             data-prev="#messages-prev-{{ $message->id }}"
             style="height: 10px;"></div>
    @endif

    @if($message->from == 'guest')
        {{-- Right --}}
        <div class="float-right">
            <div class="chat-box-wrapper chat-box-wrapper-right">
                <div>
                    <div class="chat-box bg-tempting-azure">
                        {!! $message->message !!}
                    </div>
                    <small class="opacity-6">
                        <i class="fa fa-user mr-1"></i>
                        {!! $message->utente->label !!}
                        <i class="fa fa-calendar-alt ml-2 mr-1"></i>
                        {{ dataOra($message->created_at) }}
                    </small>
                </div>
                <div>
                    <div class="avatar-icon-wrapper mr-1">
                        <div class="avatar-icon avatar-icon-lg rounded">
                            <img src="{{ url('assets/images/avatars/1.jpg') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Left --}}
        <div class="chat-box-wrapper">
            <div>
                <div class="avatar-icon-wrapper mr-1">
                    <div class="avatar-icon avatar-icon-lg rounded">
                        <img src="{{ url('assets/images/avatars/1.jpg') }}" alt="">
                    </div>
                </div>
            </div>
            <div>
                <div class="chat-box">
                    {!! $message->message !!}
                </div>
                <small class="opacity-6">
                    <i class="fa fa-user mr-1"></i>
                    {!! $message->azienda->label !!}
                    <i class="fa fa-calendar-alt ml-2 mr-1"></i>
                    {{ dataOra($message->created_at) }}
                </small>
            </div>
        </div>
    @endif
    <div class="clearfix"></div>

    @if($loop->last)
        <div id="scrolltome" style="height: 10px;"></div>
    @endif

@endforeach
