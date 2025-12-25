<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Board: {{ $board->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-4 overflow-x-auto pb-4">
                @foreach($lists as $list)
                    <div class="bg-gray-200 p-4 rounded-lg min-w-[250px]">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold">{{ $list->title }}</h3>
                            <form action="{{ route('lists.destroy', $list) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 text-sm">Hapus</button>
                            </form>
                        </div>

                        <div class="space-y-2">
                            @foreach($list->cards as $card)
                                <div class="bg-white p-2 rounded shadow flex justify-between">
                                    <span>{{ $card->title }}</span>
                                    <form action="{{ route('cards.destroy', $card) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400">×</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <form action="{{ route('cards.store', $list) }}" method="POST" class="mt-4">
                            @csrf
                            <input type="text" name="title" placeholder="Tambah kartu..." class="w-full text-sm rounded border-gray-300">
                            <button type="submit" class="mt-2 text-blue-600 text-sm w-full text-left">+ Tambah Kartu</button>
                        </form>
                    </div>
                @endforeach

                <div class="min-w-[250px]">
                    <form action="{{ route('lists.store', $board) }}" method="POST" class="bg-gray-100 p-4 rounded-lg">
                        @csrf
                        <input type="text" name="title" placeholder="Nama list baru..." class="w-full text-sm rounded border-gray-300">
                        <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-1 rounded text-sm w-full">Tambah List</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>