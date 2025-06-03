<x-admin-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Chi tiết chuyến đi</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $tour->name }} - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                </p>
            </div>
            <a href="{{ route('admin.bookings.upcoming') }}" class="text-indigo-600 hover:text-indigo-900">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách
                            hàng</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người
                            lớn</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trẻ
                            em</th>
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
                                <div class="font-medium">{{ $booking->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $booking->email }}</div>
                                <div class="text-gray-500 text-xs">{{ $booking->phone }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $booking->people }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $booking->children }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($booking->total_price) }}đ
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($booking->payment_status == 'unpaid') bg-red-100 text-red-800
                                    @elseif($booking->payment_status == 'paid') bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $booking->payment_status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                        class="text-indigo-600 hover:text-indigo-900 hover:underline">Chi tiết</a>

                                    @if ($booking->status == 'pending')
                                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-green-600 hover:text-green-900 hover:underline">
                                                Xác nhận
                                            </button>
                                        </form>
                                    @endif

                                    @if ($booking->status != 'cancelled')
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
    </div>
</x-admin-layout>
