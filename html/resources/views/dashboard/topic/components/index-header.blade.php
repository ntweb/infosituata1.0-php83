@can('can_create_topic')
<form class="form-inline d-flex justify-content-end">
    <div class="d-flex justify-content-end">
        <a href="{{ route('topic.create') }}" class="btn btn-sm btn-light ml-1 btn-block"><i class="fa fa-plus"></i> Crea nuovo topic</a>
    </div>
    <a href="javascript:void(0)" class="btn btn-sm btn-light ml-1"  data-toggle="modal" data-target="#modalTopicSearch"><i class="bx bx-search"></i></a>
</form>
<div class="clearfix"></div>
@endcan
