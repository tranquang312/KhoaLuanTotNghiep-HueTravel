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
    @endphp

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
                            <div class="flex items-center gap-2">
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
                                <button type="button" onclick="openStatusModal()"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Trạng thái thanh toán:</span>
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $booking->payment_status === 'paid'
                                        ? 'bg-green-100 text-green-700'
                                        : ($booking->payment_status === 'refunded'
                                            ? 'bg-blue-100 text-blue-700'
                                            : 'bg-red-100 text-red-700') }}">
                                    {{ $booking->payment_status === 'paid'
                                        ? 'Đã thanh toán'
                                        : ($booking->payment_status === 'refunded'
                                            ? 'Đã hoàn tiền'
                                            : 'Chưa thanh toán') }}
                                </span>
                                @if ($booking->status === 'cancelled' && $booking->payment_status === 'paid')
                                    <button type="button" onclick="openRefundModal()"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @elseif($booking->status !== 'cancelled')
                                    <button type="button" onclick="openPaymentModal()"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif
                            </div>
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
                                <span class="text-gray-500">Số tiền thanh toán:</span>
                                <span class="font-semibold text-green-600">{{ number_format($booking->total_price) }}
                                    VNĐ</span>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Modal Cập nhật trạng thái -->
    <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Cập nhật trạng thái</h3>
                <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                            Trạng thái
                        </label>
                        <select name="status" id="status"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Chờ xác nhận
                            </option>
                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Đã xác
                                nhận</option>
                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Hoàn
                                thành</option>
                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Đã hủy
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end">
                        <button type="button" onclick="closeStatusModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-400">
                            Hủy
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cập nhật trạng thái thanh toán -->
    <div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Cập nhật trạng thái thanh toán</h3>
                <form action="{{ route('admin.bookings.update-payment-status', $booking) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_status">
                            Trạng thái thanh toán
                        </label>
                        <select name="payment_status" id="payment_status"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="unpaid" {{ $booking->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa
                                thanh toán</option>
                            <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Đã
                                thanh toán</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_method">
                            Phương thức thanh toán
                        </label>
                        <select name="payment_method" id="payment_method"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Chọn phương thức thanh toán</option>
                            <option value="cash" {{ $booking->payment_method === 'cash' ? 'selected' : '' }}>Tiền
                                mặt</option>
                            <option value="bank_transfer"
                                {{ $booking->payment_method === 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản
                            </option>
                            <option value="stripe" {{ $booking->payment_method === 'stripe' ? 'selected' : '' }}>Visa
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end">
                        <button type="button" onclick="closePaymentModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-400">
                            Hủy
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cập nhật trạng thái hoàn tiền -->
    <div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Cập nhật trạng thái hoàn tiền</h3>
                <form action="{{ route('admin.bookings.update-payment-status', $booking) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_status">
                            Trạng thái thanh toán
                        </label>
                        <select name="payment_status" id="payment_status"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Chờ
                                hoàn tiền</option>
                            <option value="refunded" {{ $booking->payment_status === 'refunded' ? 'selected' : '' }}>
                                Đã hoàn tiền</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end">
                        <button type="button" onclick="closeRefundModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-400">
                            Hủy
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openStatusModal() {
                const modal = document.getElementById('statusModal');
                modal.classList.remove('hidden');
            }

            function closeStatusModal() {
                const modal = document.getElementById('statusModal');
                modal.classList.add('hidden');
            }

            function openPaymentModal() {
                const modal = document.getElementById('paymentModal');
                modal.classList.remove('hidden');
            }

            function closePaymentModal() {
                const modal = document.getElementById('paymentModal');
                modal.classList.add('hidden');
            }

            function openRefundModal() {
                const modal = document.getElementById('refundModal');
                modal.classList.remove('hidden');
            }

            function closeRefundModal() {
                const modal = document.getElementById('refundModal');
                modal.classList.add('hidden');
            }
        </script>
    @endpush
</x-admin-layout>
