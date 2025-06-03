<x-admin-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Thêm chuyến đi mới</h1>
                </div>

                <form action="{{ route('admin.tour-departures.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="tour_id" class="block text-sm font-medium text-gray-700">Tour</label>
                            <select name="tour_id" id="tour_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tour_id') border-red-500 @enderror"
                                required>
                                <option value="">Chọn tour</option>
                                @foreach ($tours as $tour)
                                    <option value="{{ $tour->id }}"
                                        {{ old('tour_id') == $tour->id ? 'selected' : '' }}>
                                        {{ $tour->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tour_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="departure_date" class="block text-sm font-medium text-gray-700">Ngày khởi hành</label>
                            <input type="date" name="departure_date" id="departure_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('departure_date') border-red-500 @enderror"
                                value="{{ old('departure_date') }}" required>
                            @error('departure_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="return_date" class="block text-sm font-medium text-gray-700">Ngày trở về</label>
                            <input type="date" name="return_date" id="return_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('return_date') border-red-500 @enderror"
                                value="{{ old('return_date') }}" required>
                            @error('return_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="available_seats" class="block text-sm font-medium text-gray-700">Số chỗ trống</label>
                            <input type="number" name="available_seats" id="available_seats"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('available_seats') border-red-500 @enderror"
                                value="{{ old('available_seats') }}" min="1" required>
                            @error('available_seats')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Giá tour</label>
                            <input type="number" name="price" id="price"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('price') border-red-500 @enderror"
                                value="{{ old('price') }}" min="0" step="1000" required>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror"
                                required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang mở</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Ghi chú</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.tour-departures.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Hủy
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Thêm chuyến đi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
