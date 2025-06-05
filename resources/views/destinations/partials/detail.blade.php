<!-- Description -->
<div class="prose max-w-none mb-8">
    <h2 class="text-2xl font-semibold mb-4">Giới thiệu</h2>
    <p class="text-gray-600">{!! $destination->description !!}</p>
</div>

<!-- Image Gallery -->
@if ($destination->images->count() > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Hình ảnh</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($destination->images as $index => $image)
                <div class="relative group cursor-pointer" onclick="openGallery({{ $index }})">
                    <img src="{{ asset('storage/' . $image->image_path) }}"
                        class="w-full h-48 object-cover rounded-lg transition-transform duration-300 group-hover:scale-105" 
                        alt="{{ $destination->name }}">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg"></div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Location -->
<div class="mb-8">
    <h2 class="text-2xl font-semibold mb-4">Vị trí</h2>
    <div class="bg-gray-100 p-4 rounded-lg">
        <p class="text-gray-600">{{ $destination->address ?? 'Chưa cập nhật' }}</p>
    </div>
</div>

<!-- Related Tours -->
@if ($destination->tours->count() > 0)
    <div>
        <h2 class="text-2xl font-semibold mb-4">Tour liên quan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($destination->tours as $tour)
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                    <img src="{{ $tour->images->first() ? asset('storage/' . $tour->images->first()->image_path) : 'https://via.placeholder.com/400x300' }}"
                        class="w-full h-48 object-cover" alt="{{ $tour->name }}">
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold mb-2">{{ $tour->name }}</h3>
                        <p class="text-gray-600 mb-4 flex-grow">{!! Str::limit($tour->description, 100) !!}</p>
                        <div class="flex justify-between items-center mt-auto">
                            <span class="text-blue-600 font-bold">{{ number_format($tour->price) }}đ</span>
                            <a href="{{ route('tours.show', $tour) }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                Chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif 