<x-tomato-admin-layout>
    <x-slot:header>
        {{ __('Activity') }}
    </x-slot:header>
    <x-slot:buttons>
        <x-tomato-admin-button danger confirm :href="route('admin.activities.clear')" type="link">
            {{__('Clear Activity')}}
        </x-tomato-admin-button>
    </x-slot:buttons>
    <x-slot:icon>
        bx bx-history
    </x-slot:icon>

    <div class="pb-12">
        <div class="mx-auto">
            <x-splade-table :for="$table" striped>
                <x-splade-cell user>
                    <x-splade-link class="text-primary-500" href="{{route('admin.activities.show', $item->id)}}" modal>
                        {{ $item->account()->first()->name }}
                    </x-splade-link>
                </x-splade-cell>
                <x-splade-cell user_agent>
                    @php
                        $agent = new \Jenssegers\Agent\Agent();
                        $agent->setUserAgent($item->user_agent);
                    @endphp
                    <div class="flex flex-col gap-4">
                        <div class="text-md flex justify-start gap-2">
                            <div class="flex flex-col justify-center items-center">
                                <i class="bx bx-link"></i>
                            </div>
                            <div>
                                <b>{{ $item->method }}</b>
                                {{ $item->url }}
                            </div>
                        </div>
                        <div class="text-md flex justify-start gap-2">
                            <div class="flex flex-col justify-center items-center">
                                <i class="bx bx-globe"></i>
                            </div>
                            <div>
                                {{ $item->remote_address }}
                            </div>
                        </div>
                        <div class="text-md flex justify-start gap-2">
                            <div class="flex flex-col justify-center items-center">
                                <i class="bx bxs-user"></i>
                            </div>
                            <div>
                                {{ $agent->device() }}, {{  $agent->platform() }} [{{$agent->version($agent->platform())}}], {{ $agent->browser() }}[{{$agent->version($agent->browser())}}]
                            </div>
                        </div>
                        <div class="text-md flex justify-start gap-2">
                            <div class="flex flex-col justify-center items-center">
                                <i class="bx bxs-time"></i>
                            </div>
                            <div>
                                {{ $item->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </x-splade-cell>
            </x-splade-table>
        </div>
    </div>
</x-tomato-admin-layout>
