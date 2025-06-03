<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Quản lý Tour</h2>
                        <a href="{{ route('admin.tours.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Thêm Tour
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3 px-6">ID</th>
                                    <th scope="col" class="py-3 px-6">Tên tour</th>
                                    <th scope="col" class="py-3 px-6">Giá</th>
                                    <th scope="col" class="py-3 px-6">Điểm đến</th>
                                    <th scope="col" class="py-3 px-6">Hình ảnh</th>
                                    <th scope="col" class="py-3 px-6">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tours as $tour)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="py-4 px-6">{{ $tour->id }}</td>
                                        <td class="py-4 px-6 font-medium text-gray-900">{{ $tour->name }}</td>
                                        <td class="py-4 px-6">{{ number_format($tour->price) }}đ</td>
                                        <td class="py-4 px-6">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($tour->destinations as $destination)
                                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                        {{ $destination->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            @if ($tour->images->count() > 0)
                                                <div class="flex items-center space-x-2">
                                                    <img src="{{ Storage::url($tour->images->first()->image_path) }}"
                                                        alt="{{ $tour->name }}"
                                                        class="h-16 w-16 object-cover rounded-lg shadow">
                                                    @if($tour->images->count() > 1)
                                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                                            +{{ $tour->images->count() - 1 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('admin.tours.edit', $tour) }}"
                                                    class="font-medium text-blue-600 hover:text-blue-900 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Sửa
                                                </a>
                                                <form action="{{ route('admin.tours.destroy', $tour) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="font-medium text-red-600 hover:text-red-900 flex items-center"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $tours->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
