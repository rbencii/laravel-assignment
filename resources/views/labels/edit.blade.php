<x-guest-layout>
    <x-slot name="title">
        Címke szerkesztése
    </x-slot>

    <div class="grid grid-cols-1 place-items-center mx-auto p-3 lg:px-36 w-full">

        
            <form class="grid grid-cols-1 w-1/2" action="{{ route('labels.update',$label) }}" method="POST"
            x-data="{ labelName: '{{ old('name', $label->name) }}', colorCode: '{{ old('color', $label->color)}}' }">
                @csrf
                @method('PATCH')
                
                <span
        class="px-2 py-1 rounded-full text-white font-semibold text-xs flex align-center w-max"
        :style="`background-color: ${colorCode};`" >
        <span class="mix-blend-difference" x-text="labelName"></span>
        </span>

                <div class="w-full">
                    <label for="name" class="block w-full font-medium text-gray-700">Címke neve</label>
                    <input class="w-full" type="text" name="name" id="name" x-model='labelName'>
                    @error('name')
                        <div class='font-medium text-red-600 w-full'>{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full">
                    <label for="color" class="block w-full font-medium text-gray-700">Szín kódja</label>
                    <input class="w-full" type="text" name="color" id="color" x-model='colorCode'>
                    <br>
                    <input class="w-full" type="color" x-model='colorCode'>
                    @error('color')
                        <span class='font-medium text-red-600 w-full'>{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="display" class="font-medium text-gray-700">Megjelenítési státusz: </label>
                    <input class="w-5 h-5 rounded" type="checkbox" name="display" id="display" value=1 {{$label->display?'checked':''}}>
                    @error('display')
                        <div class='font-medium text-red-600 w-full'>{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="mt-6 bg-blue-500 hover:bg-blue-600 text-gray-100 font-semibold px-2 py-1 text-xl">Szerkesztés</button>
                
                
        

            </form>
            @can('delete', $label)
            <form method="POST" class="grid grid-cols-1 w-1/2" action="{{ route('labels.destroy', $label) }}" id="delete">
                @csrf
                @method('DELETE')
                    <button type="submit" class="mt-4 bg-red-500 hover:bg-red-600 text-gray-100 font-semibold px-2 py-1 text-xl">Törlés</button>
            </form>
            @endcan
        
    </div>

</x-guest-layout>
