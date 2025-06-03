<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-16 w-16 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Thanh toán bị hủy</h1>
            <p class="text-lg text-gray-600 mb-8">
                Bạn đã hủy quá trình thanh toán. Nếu bạn gặp vấn đề khi thanh toán, vui lòng liên hệ với chúng tôi để được hỗ trợ.
            </p>
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin đặt tour</h2>
                <div class="space-y-4 text-left">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tour:</span>
                        <span class="font-medium">{{ $booking->tour->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày khởi hành:</span>
                        <span class="font-medium">{{ $booking->start_date }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Số người:</span>
                        <span class="font-medium">{{ $booking->people }} người lớn, {{ $booking->children }} trẻ em</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tổng tiền:</span>
                        <span class="font-medium text-gray-900">{{ number_format($booking->total_price) }}đ</span>
                    </div>
                </div>
            </div>
            <div class="space-x-4">
                <a href="{{ route('payment.show', $booking) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Thử thanh toán lại
                </a>
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
</x-app-layout> 