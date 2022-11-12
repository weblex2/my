<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-3 m-5">
                    @foreach ($posts as $post)
                        <x-blog.blog-item :post="$post" />    
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
