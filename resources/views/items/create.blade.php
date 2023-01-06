<x-guest-layout>
    <x-slot name="title">
        Új tárgy
    </x-slot>



    <div class="container mx-auto p-3 lg:px-36 w-full">
        <form action="{{ route('items.store') }}" method="POST" x-data="{ itemName: '{{ old('name', '') }}', itemDesc: '{{ old('description', '') }}', itemImage: '{{ old('image', '') }}', obtainedDate: '{{ old('obtained', '') }}', colorCode: '{{ old('color', '#00ff00') }}' }" enctype="multipart/form-data">
            @csrf

            <div class="rounded-lg shadow-lg bg-white w-full mb-2">
                <div class="grid grid-cols-2 gap-2">
                    <div class="grid grid-cols-1 content-center rounded-l-lg"
                        style="background-image: url({{ Storage::url('images/' . 'placeholder.jpg') }}); background-repeat: no-repeat; background-size: cover; background-position: center">
    
                    </div>
                    <div class="grid grid-cols-1 p-6">
                        <div>
                            <h5 class="text-gray-900 text-2xl font-semibold mb-1 truncate" x-text="itemName"></h5>
                            <div class="flex flex-wrap space-x-2">
                                <span
                                                class="px-2 py-1 rounded-full text-white font-semibold text-xs flex align-center w-max"
                                                style="background-color: #ffea2a">
                                                <span class="mix-blend-difference">
                                                    új tárgyfelvétel
                                                </span>
                                            </span>
    
                            </div>
    
    
                            <p class="pt-5 text-gray-700 text-base mb-4 break-words" x-text="itemDesc">
                            </p>
                        </div>
                        <div class="grid grid-cols-1 text-gray-700 text-xs text-right content-end" x-text="obtainedDate">
                        </div>
                        {{-- <a href="{{ route('items.show', $item) }}"
                            class=" inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">Megnyitás</a> --}}
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 place-items-center mx-auto p-3 lg:px-36 w-full">

                <div class="grid grid-cols-1 w-full">



                    <div class="w-full">
                        <label for="name" class="block w-full font-medium text-gray-700">Név</label>
                        <input class="w-full" type="text" name="name" id="name" x-model='itemName'>
                        @error('name')
                            <div class='font-medium text-red-600 w-full'>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="w-full">
                        <label for="description" class="block w-full font-medium text-gray-700">Leírás</label>
                        <textarea class="w-full" name="description" id="description" x-model='itemDesc'></textarea>
                        @error('description')
                            <div class='font-medium text-red-600 w-full'>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="w-full">
                        <label for="obtained" class="block w-full font-medium text-gray-700">Beszerzés dátuma</label>
                        <input class="w-full" type="date" name="obtained" id="obtained" x-model='obtainedDate'>
                        @error('obtained')
                            <div class='font-medium text-red-600 w-full'>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="w-full">
                        <label for="image" class="block w-full font-medium text-gray-700">Kép</label>
                        <input class="w-full" type="file" name="image" id="image" x-model='itemImage'>
                        @error('image')
                            <div class='font-medium text-red-600 w-full'>{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="w-full">
                        <label for="labels" class="block w-full font-medium text-gray-700">Címkék</label>
                        

                        <div class="border-2 border-gray-200 p-2 rounded-lg">
                            <div class="flex flex-wrap space-x-2">
                                @foreach ($labels as $label)
                                        
                                        <span
                                            class="px-2 py-1 my-0.5 rounded-full text-white font-semibold text-xs flex align-center w-max"
                                            style="background-color: {{ $label->color }}">
                                            <span class="mix-blend-difference">
                                                {{ $label->name }}
                                            </span>
                                            <input type="checkbox" class="mx-1 rounded-full" name="labels[]"
                                        value="{{ $label->id }}" @checked(is_array(old('labels')) and in_array($label->id, old('labels')))>
                                        </span>
                                    
                                @endforeach
        
                            </div>
                        </div>

                        {{-- @foreach ($labels as $label)
                            <div class="flex flex-row pb-1">
                                <input type="checkbox" class="mt-1 mx-1" name="labels[]"
                                    value="{{ $label->id }}">
                                <div class="py-0.5 px-1.5 font-semibold text-sm">
                                    <span
                                        class="ml-2 px-2 py-1 rounded-full text-white font-semibold text-xs flex align-center w-max"
                                        style="background-color: {{ $label->color }}">
                                        <span class="mix-blend-difference">
                                            {{ $label->name }}
                                        </span>
                                    </span>

                                </div>
                            </div>
                        @endforeach --}}
                    </div>



                    <button type="submit"
                        class="mt-6 bg-blue-500 hover:bg-blue-600 text-gray-100 font-semibold px-2 py-1 text-xl">Létrehozás</button>

                </div>

            </div>
        </form>
    </div>



</x-guest-layout>
