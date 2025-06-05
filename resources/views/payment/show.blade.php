<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="md:flex">
                <!-- Tour Information Section -->
                <div class="md:w-2/3 p-8 border-r border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-800 mb-8">Chi tiết đặt tour</h1>
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ $booking->tour->name }}</h2>
                            <div class="space-y-4 text-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Ngày khởi hành:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Số người lớn:</span>
                                    <span class="font-medium">{{ $booking->people }} người</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Số trẻ em:</span>
                                    <span class="font-medium">{{ $booking->children }} người</span>
                                </div>
                                @if ($booking->note)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Ghi chú:</span>
                                        <span class="font-medium">{{ $booking->note }}</span>
                                    </div>
                                @endif
                                <div class="pt-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-semibold text-gray-800">Tổng tiền:</span>
                                        <span class="text-2xl font-bold text-blue-600">
                                            {{ number_format($booking->total_price) }} VNĐ
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-gray-600">Trạng thái:</span>
                                    <span class="font-medium capitalize">
                                        @if ($booking->status === 'pending')
                                            <span class="text-yellow-600">Chờ thanh toán</span>
                                        @elseif ($booking->status === 'paid')
                                            <span class="text-green-600">Đã thanh toán</span>
                                        @elseif ($booking->status === 'cancelled')
                                            <span class="text-red-600">Đã hủy</span>
                                        @else
                                            {{ $booking->status }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="md:w-1/3 p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Phương thức thanh toán</h2>
                    <div class="space-y-4">
                        <a href="{{ route('profile.my-tours') }}"
                            class="block w-full py-3 px-4 text-center text-white bg-green-600 hover:bg-green-700 rounded-lg transition duration-200">
                            Thanh toán sau
                        </a>
                        <form action="{{ route('payment.checkout', $booking) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="block w-full py-3 px-4 text-center text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold shadow transition">
                                Thanh toán bằng thẻ (Stripe)
                            </button>
                        </form>
                        @if (session('error'))
                            <div class="mt-3 p-3 bg-red-50 border border-red-300 text-red-700 rounded-lg text-sm">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="mt-3 p-3 bg-green-50 border border-green-300 text-green-700 rounded-lg text-sm">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
