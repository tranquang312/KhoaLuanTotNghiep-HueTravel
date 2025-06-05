<x-admin-layout>
    @php
        function translateStatus($status)
        {
            return match ($status) {
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'cancelled' => 'Đã hủy',
                'completed' => 'Hoàn thành',
                default => $status,
            };
        }

        function translatePaymentStatus($status)
        {
            return match ($status) {
                'unpaid' => 'Chưa thanh toán',
                'paid' => 'Đã thanh toán',
                'refunded' => 'Đã hoàn tiền',
                default => $status,
            };
        }
    @endphp

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Quản lý đặt tour</h1>
        </div>



        <!-- Filter Form -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <form action="{{ route('admin.bookings.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="tour" class="block text-sm font-medium text-gray-700 mb-1">Tour</label>
                    <select name="tour" id="tour"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tất cả tour</option>
                        @foreach ($tours as $tour)
                            <option value="{{ $tour->id }}" {{ request('tour') == $tour->id ? 'selected' : '' }}>
                                {{ $tour->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                    <select name="status" id="status"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                        </option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                        </option>
                    </select>
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Lọc
                    </button>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách
                            hàng</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày
                            đi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số
                            người</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng
                            tiền</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                            thái</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh
                            toán</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao
                            tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $booking->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="max-w-xs truncate">{{ $booking->tour->name }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="font-medium">{{ $booking->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $booking->email }}</div>
                                <div class="text-gray-500 text-xs">{{ $booking->phone }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div>Người lớn: {{ $booking->people }}</div>
                                <div>Trẻ em: {{ $booking->children }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($booking->total_price) }}đ
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ translateStatus($booking->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($booking->payment_status === 'unpaid') bg-red-100 text-red-800
                                    @elseif($booking->payment_status === 'paid') bg-green-100 text-green-800
                                    @elseif($booking->payment_status === 'refunded') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ translatePaymentStatus($booking->payment_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                        class="text-indigo-600 hover:text-indigo-900 hover:underline">Chi tiết</a>

                                    @if ($booking->status === 'pending')
                                        <a href="{{ route('admin.bookings.show-confirm', $booking) }}"
                                            class="text-green-600 hover:text-green-900">
                                            Xác nhận
                                        </a>
                                    @endif

                                    @if ($booking->status !== 'cancelled')
                                        <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 hover:underline"
                                                onclick="return confirm('Bạn có chắc chắn muốn hủy đơn đặt tour này?')">
                                                Hủy
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    </div>
</x-admin-layout>
