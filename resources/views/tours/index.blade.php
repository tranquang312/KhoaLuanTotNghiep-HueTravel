@section('title', 'Danh sách tour - HueTravel')
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Filter Sidebar -->
            <div class="w-full md:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bộ lọc</h3>

                    <form action="{{ route('tours.index') }}" method="GET" id="filterForm">
                        <!-- Price Range Filter -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Khoảng giá</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" name="price_range" value="0-1000000" id="price1"
                                        class="h-4 w-4 text-blue-600"
                                        {{ request('price_range') == '0-1000000' ? 'checked' : '' }}>
                                    <label for="price1" class="ml-2 text-sm text-gray-600">Dưới 1 triệu</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="price_range" value="1000000-2000000" id="price2"
                                        class="h-4 w-4 text-blue-600"
                                        {{ request('price_range') == '1000000-2000000' ? 'checked' : '' }}>
                                    <label for="price2" class="ml-2 text-sm text-gray-600">1 - 2 triệu</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="price_range" value="2000000-3000000" id="price3"
                                        class="h-4 w-4 text-blue-600"
                                        {{ request('price_range') == '2000000-3000000' ? 'checked' : '' }}>
                                    <label for="price3" class="ml-2 text-sm text-gray-600">2 - 3 triệu</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="price_range" value="3000000-999999999" id="price4"
                                        class="h-4 w-4 text-blue-600"
                                        {{ request('price_range') == '3000000-999999999' ? 'checked' : '' }}>
                                    <label for="price4" class="ml-2 text-sm text-gray-600">Trên 3 triệu</label>
                                </div>
                            </div>
                        </div>

                        <!-- Destination Filter -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Địa điểm</h4>
                            <div class="space-y-2">
                                @foreach ($destinations as $destination)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="destinations[]" value="{{ $destination->id }}"
                                            id="destination{{ $destination->id }}" class="h-4 w-4 text-blue-600"
                                            {{ in_array($destination->id, request('destinations', [])) ? 'checked' : '' }}>
                                        <label for="destination{{ $destination->id }}"
                                            class="ml-2 text-sm text-gray-600">
                                            {{ $destination->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Apply Filters Button -->
                        <div class="space-y-3">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                Áp dụng bộ lọc
                            </button>
                            <a href="{{ route('tours.index') }}"
                                class="block w-full text-center bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 transition">
                                Xóa bộ lọc
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tours Grid -->
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($tours as $tour)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                            <img src="{{ $tour->images->first() ? asset('storage/' . $tour->images->first()->image_path) : 'https://images.unsplash.com/photo-1596422846543-75c6fc197f11?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80' }}"
                                class="w-full h-48 object-cover" alt="{{ $tour->name }}">
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-xl font-semibold mb-2">{{ $tour->name }}</h3>
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
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500">Không tìm thấy tour nào phù hợp với bộ lọc của bạn.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $tours->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
