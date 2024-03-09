<x-tomato-admin-container label="{{trans('tomato-admin::global.crud.view')}} {{__('Activity')}} #{{$model->id}}">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">


          <x-tomato-admin-row :label="__('User')" :value="$model->account?->name" type="string" />

          <x-tomato-admin-row :label="__('Request hash')" :value="$model->request_hash" type="string" />

          <x-tomato-admin-row :label="__('Http version')" :value="$model->http_version" type="string" />

          <x-tomato-admin-row :label="__('Response time')" :value="$model->response_time" type="number" />

          <x-tomato-admin-row :label="__('Status')" :value="$model->status" type="number" />

          <x-tomato-admin-row :label="__('Method')" :value="$model->method" type="string" />

          <x-tomato-admin-row :label="__('Url')" :value="$model->url" type="string" />

          <x-tomato-admin-row :label="__('Referer')" :value="$model->referer" type="string" />


          <x-tomato-admin-row :label="__('Remote address')" :value="$model->remote_address" type="string" />



        @php
            $agent = new \Jenssegers\Agent\Agent();
            $agent->setUserAgent($model->user_agent);
        @endphp
        <x-tomato-admin-row :label="__('User agent')" value="{{ $agent->device() }}, {{  $agent->platform() }} [{{$agent->version($agent->platform())}}], {{ $agent->browser() }}[{{$agent->version($agent->browser())}}]" type="text" />



          <x-tomato-admin-row :label="__('Level')" :value="$model->level" type="string" />

          <x-tomato-admin-row :label="__('Email')" :value="$model->user" type="string" />

          <x-tomato-admin-row :label="__('Date')" :value="$model->created_at" type="datetime" />

        <div class="col-span-2">
            <x-tomato-admin-row :label="__('Query')" :value="$model->query" type="string" />
        </div>


    </div>
    <div class="flex justify-start gap-2 pt-3">
        <x-tomato-admin-button danger :href="route('admin.activities.destroy', $model->id)"
                               confirm="{{trans('tomato-admin::global.crud.delete-confirm')}}"
                               confirm-text="{{trans('tomato-admin::global.crud.delete-confirm-text')}}"
                               confirm-button="{{trans('tomato-admin::global.crud.delete-confirm-button')}}"
                               cancel-button="{{trans('tomato-admin::global.crud.delete-confirm-cancel-button')}}"
                               method="delete"  label="{{__('Delete')}}" />
        <x-tomato-admin-button secondary type="button" @click.prevent="modal.close()" label="{{__('Cancel')}}"/>
    </div>
</x-tomato-admin-container>
