<x-admin-layout>
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-check-circle text-indigo-500"></i>
                    Xác nhận đơn đặt tour
                </h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Thông tin đặt tour -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Thông tin đặt tour</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tour</p>
                        <p class="font-medium">{{ $booking->tour->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ngày khởi hành</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Số người</p>
                        <p class="font-medium">{{ $booking->people }} người lớn, {{ $booking->children }} trẻ em</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tổng tiền</p>
                        <p class="font-medium text-green-600">{{ number_format($booking->total_price) }} VNĐ</p>
                    </div>
                </div>
            </div>

            <!-- Chọn chuyến đi -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Chọn chuyến đi</h3>

                @if($departures->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-600 mb-3">Chuyến đi hiện có</h4>
                        <div class="space-y-4">
                            @foreach($departures as $departure)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium">Hướng dẫn viên: {{ $departure->guide->name }}</p>
                                            <p class="font-medium">Ngày khởi hành: {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}</p>
                                            <p class="text-sm text-gray-500">
                                                Tổng số người đã đặt: {{ $departure->bookings->sum('people') + $departure->bookings->sum('children') }}
                                            </p>
                                        </div>
                                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="use_existing">
                                            <input type="hidden" name="departure_id" value="{{ $departure->id }}">
                                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                                Sử dụng chuyến này
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="border-t pt-6">
                    <h4 class="text-md font-medium text-gray-600 mb-3">Tạo chuyến đi mới</h4>
                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="create_new">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            Tạo chuyến đi mới
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 