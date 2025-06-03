<x-admin-layout>
    @php
        function translateStatus($status) {
            return match($status) {
                'active' => 'Đang mở',
                'completed' => 'Đã hoàn thành',
                'cancelled' => 'Đã hủy',
                default => $status
            };
        }
    @endphp

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Quản lý chuyến đi</h1>
            <a href="{{ route('admin.tour-departures.create') }}" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus"></i> Thêm chuyến đi mới
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày khởi hành</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày trở về</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chỗ trống</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($departures as $departure)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $departure->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="max-w-xs truncate">{{ $departure->tour->name }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $departure->departure_date->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $departure->return_date->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $departure->available_seats }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($departure->price) }}đ
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($departure->status == 'active') bg-green-100 text-green-800
                                    @elseif($departure->status == 'completed') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ translateStatus($departure->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.tour-departures.edit', $departure) }}"
                                        class="text-indigo-600 hover:text-indigo-900 hover:underline">Sửa</a>
                                    
                                    <form action="{{ route('admin.tour-departures.destroy', $departure) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 hover:underline"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa chuyến đi này?')">
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
            {{ $departures->links() }}
        </div>
    </div>
</x-admin-layout>
