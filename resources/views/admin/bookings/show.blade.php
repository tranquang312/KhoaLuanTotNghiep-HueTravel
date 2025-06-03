<x-admin-layout>
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-indigo-500"></i>
                Chi tiết đặt tour
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Thông tin đặt tour -->
                <div>
                    <h3 class="text-lg font-medium text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-ticket-alt text-blue-400"></i>
                        Thông tin đặt tour
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Mã đặt tour:</span>
                            <span class="font-semibold text-gray-800">#{{ $booking->id }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Tour:</span>
                            <span class="font-semibold text-gray-800">{{ $booking->tour->name }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Ngày khởi hành:</span>
                            <span
                                class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Số người lớn:</span>
                            <span class="font-semibold text-gray-800">{{ $booking->people }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Số trẻ em:</span>
                            <span class="font-semibold text-gray-800">{{ $booking->children }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Tổng tiền:</span>
                            <span class="font-semibold text-green-600">{{ number_format($booking->total_price) }}
                                VNĐ</span>
                </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Trạng thái:</span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $booking->status === 'confirmed'
                                    ? 'bg-green-100 text-green-700'
                                    : ($booking->status === 'pending'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : ($booking->status === 'completed'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700')) }}">
                                            {{ translateStatus($booking->status) }}
                                        </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Trạng thái thanh toán:</span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-700') }}">
                                {{ $booking->payment_status === 'paid' ? 'Đã thanh toán' : ($booking->payment_status === 'pending' ? 'Chờ thanh toán' : 'Chưa thanh toán') }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Thông tin khách hàng -->
                <div>
                    <h3 class="text-lg font-medium text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-pink-400"></i>
                        Thông tin khách hàng
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Họ tên:</span>
                            <span class="font-semibold text-gray-800">{{ $booking->name }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Email:</span>
                            <span class="font-semibold text-gray-800">{{ $booking->email }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Số điện thoại:</span>
                            <span class="font-semibold text-gray-800">{{ $booking->phone }}</span>
                        </div>
                        @if ($booking->note)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Ghi chú:</span>
                                <span class="font-semibold text-gray-800">{{ $booking->note }}</span>
                            </div>
                                @endif
                    </div>
                        </div>
                    </div>

            <!-- Thông tin thanh toán -->
            <div class="mt-8">
                <div class="bg-gray-50 rounded-lg shadow-inner p-6">
                    <h3 class="text-lg font-semibold text-indigo-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-indigo-400"></i>
                        Thông tin thanh toán
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-3">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-500">Phương thức thanh toán:</span>
                                <span class="font-semibold text-gray-800">
                                    {{ $booking->payment_method === 'bank_transfer'
                                        ? 'Chuyển khoản ngân hàng'
                                        : ($booking->payment_method === 'cash'
                                            ? 'Tiền mặt'
                                            : ($booking->payment_method === 'stripe'
                                                ? 'Visa'
                                                : 'Chưa chọn')) }}
                                </span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-500">Trạng thái thanh toán:</span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $booking->payment_status === 'paid'
                                        ? 'bg-green-100 text-green-700'
                                        : ($booking->payment_status === 'pending'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-700') }}">
                                    {{ $booking->payment_status === 'paid'
                                        ? 'Đã thanh toán'
                                        : ($booking->payment_status === 'pending'
                                            ? 'Chờ thanh toán'
                                            : 'Chưa thanh toán') }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-500">Số tiền cần thanh toán:</span>
                                <span class="font-semibold text-green-600">{{ number_format($booking->total_price) }}
                                    VNĐ</span>
                            </div>
                            @if ($booking->payment_method === 'bank_transfer')
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-500">Thông tin chuyển khoản:</span>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-800">Ngân hàng: Vietcombank</p>
                                        <p class="font-semibold text-gray-800">STK: 1234567890</p>
                                        <p class="font-semibold text-gray-800">Chủ TK: Công ty TNHH HueTravel</p>
                                    </div>
                                </div>
                            @endif
                            @if ($booking->payment_status === 'paid')
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Ngày thanh toán:</span>
                                    <span class="font-semibold text-gray-800">
                                        {{ $booking->paid_at ? \Carbon\Carbon::parse($booking->paid_at)->format('d/m/Y H:i') : 'N/A' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($booking->status === 'pending')
                <div class="mt-10">
                    <div class="bg-gray-50 rounded-lg shadow-inner p-6">
                        <h3 class="text-lg font-semibold text-indigo-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-user-tie text-indigo-400"></i>
                            Phân công hướng dẫn viên
                        </h3>
                        <form action="{{ route('admin.bookings.assign-guide', $booking) }}" method="POST"
                            class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        @csrf
                            <div>
                                <label for="guide_id" class="block text-sm font-medium text-gray-700 mb-1">Hướng dẫn
                                    viên</label>
                                <select name="guide_id" id="guide_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('guide_id') border-red-500 @enderror"
                                    required>
                                                        <option value="">Chọn hướng dẫn viên</option>
                                    @foreach ($guides as $guide)
                                        <option value="{{ $guide->id }}"
                                            {{ old('guide_id') == $guide->id ? 'selected' : '' }}>
                                                                {{ $guide->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('guide_id')
                                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                            <div>
                                <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày
                                    khởi hành</label>
                                                    <input type="date" name="departure_date" id="departure_date" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('departure_date') border-red-500 @enderror"
                                    value="{{ old('departure_date', \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d')) }}"
                                    required>
                                                    @error('departure_date')
                                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                            <div>
                                <label for="max_capacity" class="block text-sm font-medium text-gray-700 mb-1">Sức chứa
                                    tối đa</label>
                                                    <input type="number" name="max_capacity" id="max_capacity" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('max_capacity') border-red-500 @enderror"
                                                           value="{{ old('max_capacity', 20) }}" min="1" required>
                                                    @error('max_capacity')
                                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                            <div class="md:col-span-3 flex justify-end mt-4">
                                <button type="submit"
                                    class="inline-flex items-center px-5 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 transition">
                                    <i class="fas fa-check mr-2"></i> Phân công và xác nhận
                                </button>
                            </div>
                        </form>
                                            </div>
                                        </div>
            @elseif($booking->tourDeparture)
                <div class="mt-10">
                    <div class="bg-gray-50 rounded-lg shadow-inner p-6">
                        <h3 class="text-lg font-semibold text-indigo-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-bus text-indigo-400"></i>
                            Thông tin chuyến đi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-3">
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-500">Hướng dẫn viên:</span>
                                    <span class="font-semibold text-gray-800">{{ $booking->tourDeparture->guide->name }}</span>
                                        </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-500">Ngày khởi hành:</span>
                                    <span class="font-semibold text-gray-800">{{ $booking->tourDeparture->departure_date->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3">
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-500">Sức chứa tối đa:</span>
                                    <span class="font-semibold text-gray-800">{{ $booking->tourDeparture->max_capacity }} người</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Số người đã đặt:</span>
                                    <span class="font-semibold text-gray-800">
                                        {{ $booking->tourDeparture->bookings->sum('people') + $booking->tourDeparture->bookings->sum('children') }} người
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($booking->status === 'confirmed')
                <div class="mt-4 flex justify-end">
                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST"
                        class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md shadow hover:bg-green-700 transition">
                            <i class="fas fa-check-circle mr-2"></i> Đánh dấu đã hoàn thành
                        </button>
                    </form>
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>
