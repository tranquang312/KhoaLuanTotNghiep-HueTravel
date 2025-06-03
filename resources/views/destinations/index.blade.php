@section('title', 'Điểm Đến - HueTravel')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">Điểm Đến Nổi Bật</h1>
                        <div class="text-sm text-gray-600">
                            Tổng số: {{ $destinations->count() }} điểm đến
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($destinations as $destination)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col hover:shadow-lg transition duration-300">
                                <div class="relative">
                                    <img src="{{ $destination->images->first() ? asset('storage/' . $destination->images->first()->image_path) : 'https://via.placeholder.com/400x300' }}"
                                        class="w-full h-56 object-cover" alt="{{ $destination->name }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <h2 class="absolute bottom-4 left-4 text-xl font-semibold text-white">{{ $destination->name }}</h2>
                                </div>
                                <div class="p-6 flex flex-col flex-grow">
                                    <p class="text-gray-600 mb-4 flex-grow">{!! Str::limit($destination->description, 150) !!}</p>
                                    <div class="mt-auto">
                                        <a href="{{ route('destinations.show', $destination) }}"
                                            class="inline-block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                                            Khám phá ngay
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $destinations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
