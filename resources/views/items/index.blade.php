<x-guest-layout>
    <x-slot name="title">
        Tárgyak
    </x-slot>


    <div class="container mx-auto p-3 lg:px-36">

        <div class="grid grid-cols-3 gap-3">
            @if (Session::has('message'))
                <div class="col-span-3 bg-green-200 text-center rounded-lg py-1">
                    {{ Session::get('message') }}
                </div>
            @endif
            @can('create', App\Models\Item::class)
            <div class="mx-auto col-span-3">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    Adminisztrációs felület
                </h1>
            </div>
            
                <div class="flex items-center gap-2 justify-center border-2 border-gray-200 rounded-lg">
                    <a href="{{ route('items.create') }}"
                        class="rounded-lg bg-blue-600 hover:bg-blue-700 px-2 py-1 text-white"><i
                            class="fas fa-plus-circle"></i> Új tárgy</a>
                    <a href="{{ route('labels.create') }}"
                        class="rounded-lg bg-blue-600 hover:bg-blue-700 px-2 py-1 text-white"><i
                            class="fas fa-plus-circle"></i> Új címke</a>
                </div>
                <div class="col-span-2 border-2 border-gray-200 p-2 rounded-lg">
                    <div class="flex flex-wrap space-x-2">
                        @foreach ($labels as $label)
                            <a href="{{ route('labels.edit', $label) }}">
                                <span
                                    class="px-2 py-1 my-0.5 rounded-full text-white font-semibold text-xs flex align-center w-max"
                                    style="background-color: {{ $label->color }}">
                                    <span class="mix-blend-difference">
                                        {{ $label->name }}
                                    </span>
                                </span>
                            </a>
                        @endforeach

                    </div>
                </div>
            @endcan
            <div class="mx-auto col-span-3">

                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tárgyak listája
                </h1>
            </div>
            @foreach ($items as $item)
                <div class="col-span-3 lg:col-span-1">

                    <div class="flex justify-center w-full h-full">
                        <div class="rounded-lg shadow-lg bg-white w-full h-full">

                            <div class="flex rounded-t-lg w-full p-24 bg-cover bg-center bg-no-repeat"
                                style="background-image: url({{ Storage::url('images/' . ($item->image === null ? 'placeholder.jpg' : $item->image)) }})">
                            </div>
                            <div class="flex flex-col px-4 justify-around h-60">
                                <div class="flex flex-col gap-3">
                                <h5 class="text-gray-900 text-2xl font-semibold truncate">{{ $item->name }}</h5>
                                {{-- <div class="flex flex-wrap space-x-2">
                                @foreach ($item->labels as $label)

                                    @if ($label->display)
                                    <span
                                    class="px-2 py-1 rounded-full text-white font-semibold text-xs flex align-center w-max" style="background-color: {{$label->color}}">
                                    {{
                                        $label->name
                                    }}
                                  </span>
                                  @endif

                                @endforeach
                                
                                </div> --}}


                                <span class="text-gray-700 text-base break-words">
                                    {{ Str::limit($item->description, 120) }}
                                </span>
                                </div>
                                <div class="container">
                                    <a href="{{ route('items.show', $item) }}"
                                        class="inline-block text-center px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">Olvasás</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            @endforeach

            <div class="mx-auto col-span-3">
                {{ $items->links() }}
            </div>
        </div>
    </div>

</x-guest-layout>
