<x-guest-layout>
    <x-slot name="title">
        {{$item->name}}
    </x-slot>



    <div class="container mx-auto p-3 lg:px-36 w-full">

        @can('update', $item)
            <div class="text-right my-2">
                <a href="{{ route('items.edit', $item) }}"
                    class="rounded-lg bg-blue-600 hover:bg-blue-700 px-2 py-1 text-white"><i class="fas fa-edit"></i>
                    Szerkesztés</a>
            </div>
        @endcan
        @can('delete', $item)
            <div class="text-right my-1">
                <form method="POST" action="{{ route('items.destroy', $item) }}" id="delete">
                    @csrf
                    @method('DELETE')
                    <a href="{{ route('items.destroy', $item) }}"
                        onclick="event.preventDefault(); document.querySelector('#delete').submit();"
                        class="rounded-lg bg-red-600 hover:bg-red-700 px-2 py-1 text-white"><i class="fas fa-trash"></i>
                        Törlés</a>
                </form>
            </div>
        @endcan
        <div class="rounded-lg shadow-lg bg-white w-full mb-2">
            <div class="grid grid-cols-2 gap-2">
                <div class="grid grid-cols-1 rounded-l-lg content-center"
                    style="background-image: url({{ Storage::url('images/' . ($item->image === null ? 'placeholder.jpg' : $item->image)) }}); background-repeat: no-repeat; background-size: cover; background-position: center">

                </div>
                <div class="grid grid-cols-1 p-6">
                    <div>
                        <h5 class="text-gray-900 text-2xl font-semibold mb-1 truncate">{{ $item->name }}</h5>
                        <div class="flex flex-wrap space-x-2">
                            @foreach ($labels as $label)
                                @if ($label->display)
                                    <a href="{{ route('labels.show', $label) }}">
                                        <span
                                            class="px-2 py-1 rounded-full text-white font-semibold text-xs flex align-center w-max"
                                            style="background-color: {{ $label->color }}">
                                            <span class="mix-blend-difference">
                                                {{ $label->name }}
                                            </span>
                                        </span>
                                    </a>
                                @endif
                            @endforeach

                        </div>


                        <p class="pt-5 text-gray-700 text-base mb-4 break-words">
                            {{ $item->description }}
                        </p>
                    </div>
                    <div class="grid grid-cols-1 text-gray-700 text-xs text-right content-end">
                        {{ $item->obtained }}
                    </div>
                    {{-- <a href="{{ route('items.show', $item) }}"
                        class=" inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">Megnyitás</a> --}}
                </div>
            </div>
        </div>

        @auth
            <div class="w-full rounded-lg shadow-lg bg-white mb-2 px-2 py-1">

                <div>
                    <span>{{ Auth::user()->name }}</span>

                    <form action="{{ route('comments.store') }}" method="POST" x-data="{ textData: '{{ old('text', '') }}' }">
                        @csrf

                        <div class="w-full">
                            <label for="text" class="block w-full font-medium text-gray-700">Hozzászólás</label>
                            <textarea class="w-full" name="text" id="text" x-model='textData'></textarea>
                            @error('text')
                                <div class='font-medium text-red-600 w-full'>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                class="mb-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-gray-100 font-semibold px-2 py-1 text-l">Küldés</button>
                        </div>

                        <input type="text" name="item_id" id="item_id" value="{{ $item->id }}" hidden>



                    </form>


                </div>

            </div>
        @endauth
        @forelse ($comments as $comment)
            <div class="w-full rounded-lg shadow-lg bg-white mb-2 px-2 py-1"
                @if (Session::has('editcomment') && Session::get('editcomment')->is($comment)) {{ 'hidden' }} @endif>

                <div>
                    <div class="flex">
                        <span class="underline decoration-1 underline-offset-8 decoration-slate-300">{{ $comment->author->name . ':' }}</span>
                        <div class="w-full text-right">
                            @can('update', $comment)
                                <a href="{{ route('comments.edit', $comment) }}"
                                    class="mb-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-gray-100 font-semibold px-2 py-1 text-l fas fa-edit"></a>
                            @endcan
                            @can('delete', $comment)
                                <form method="POST" class="inline" action="{{ route('comments.destroy', $comment) }}"
                                    id="delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="mb-1 rounded-lg bg-red-600 hover:bg-red-700 text-gray-100 font-semibold px-2 py-1 text-l fas fa-trash"></button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    <p>{{ $comment->text }}</p>
                </div>
                <div class="text-right text-xs">
                    {{ $comment->created_at }}
                </div>


            </div>
            @if (Session::has('editcomment') && Session::get('editcomment')->is($comment))
                @can('update', $comment)
                    <div class="w-full rounded-lg shadow-lg bg-white mb-2 px-2 py-1">

                        <div>
                            <span class="underline decoration-1 underline-offset-8 decoration-slate-300">{{ $comment->author->name }}</span>

                            <form action="{{ route('comments.update', $comment) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="w-full">
                                    <label for="text" class="block w-full font-medium text-gray-700">Hozzászólás</label>

                                    <textarea class="w-full" name="text" id="text">{{ old('text', $comment->text) }}</textarea>

                                    @error('text')
                                        <div class='font-medium text-red-600 w-full'>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-right">
                                    <button type="submit"
                                        class="mb-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-gray-100 font-semibold px-2 py-1 text-l">Szerkesztés</button>
                                </div>

                                <input type="text" name="item_id" id="item_id" value="{{ $item->id }}" hidden>



                            </form>


                        </div>

                    </div>
                @endcan
            @endif
        @empty
            <div class="w-full rounded-lg shadow-lg bg-white mb-2 px-2 py-1">
                <div>
                    <p>Nincs hozzászólás</p>
                </div>
            </div>
        @endforelse


    </div>



</x-guest-layout>
