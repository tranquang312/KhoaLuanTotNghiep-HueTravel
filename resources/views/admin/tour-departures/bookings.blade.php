<x-admin-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Chi tiết chuyến đi</h1>
            <a href="{{ route('admin.tour-departures.index') }}" class="text-indigo-600 hover:text-indigo-900">
                Quay lại
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin chuyến đi</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Tour:</p>
                    <p class="font-medium">{{ $departure->tour->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ngày khởi hành:</p>
                    <p class="font-medium">{{ $departure->departure_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Trạng thái:</p>
                    <p class="font-medium">
                        @if($departure->guide_status === 'pending')
                            <span class="text-yellow-600">Chờ xác nhận</span>
                        @elseif($departure->guide_status === 'confirmed')
                            <span class="text-green-600">Đã xác nhận</span>
                        @else
                            <span class="text-red-600">Đã từ chối</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin tour</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Lịch trình:</p>
                    <div class="mt-1 text-gray-900 prose max-w-none">
                        {!! $departure->tour->itinerary !!}
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Giá tour:</p>
                        <p class="font-medium">{{ number_format($departure->tour->price) }} VNĐ/người</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Điểm đến:</p>
                        <p class="font-medium">
                            @foreach($departure->tour->destinations as $destination)
                                {{ $destination->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Danh sách đặt tour</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số điện thoại</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số người lớn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số trẻ em</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->people }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->children }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($booking->total_price) }} VNĐ</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    switch ($booking->status) {
                                        case 'confirmed':
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Đã xác nhận';
                                            break;
                                      
                                        case 'completed':
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                            $statusText = 'Đã hoàn thành';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'bg-red-100 text-red-800';
                                            $statusText = 'Đã hủy';
                                            break;
                                        default:
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            $statusText = 'Không xác định';
                                            break;
                                    }
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Không có đơn đặt tour nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($departure->guide_status === 'pending')
            <div class="mt-6 flex justify-end space-x-4">
                <form action="{{ route('admin.tour-departures.guide-reject', $departure) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Từ chối
                    </button>
                </form>
                <form action="{{ route('admin.tour-departures.guide-confirm', $departure) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Xác nhận
                    </button>
                </form>
            </div>
        @elseif($departure->guide_status === 'confirmed')
            <div class="mt-6 flex justify-end space-x-4">
                <form action="{{ route('admin.tour-departures.send-tour-info', $departure) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Gửi thông tin chuyến đi
                    </button>
                </form>
                @if($departure->status == 'active')
                    <form action="{{ route('admin.tour-departures.complete-tour', $departure) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            Xác nhận hoàn thành
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</x-admin-layout> 