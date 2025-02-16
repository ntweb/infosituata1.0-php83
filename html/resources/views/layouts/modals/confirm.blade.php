<form id="frmConfirmModal" class="ns-payload" action="" method="post" data-callback="">
    @csrf
    @method('PUT')
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('labels.confirm_message') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 lead">
                        <span id="confirm-message"></span>
                    </p>
{{--                    <p class="mb-0 text-dark">--}}
{{--                        {{ __('labels.confirm_message') }}--}}
{{--                    </p>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('labels.close') }}</button>
                    <button type="submit" class="btn btn-warning">{{ __('labels.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
