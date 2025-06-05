<div class="p-6 text-gray-900">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Tour của tôi</h2>
        <a href="{{ route('tours.index') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Đặt tour mới
        </a>
    </div>

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($bookings->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Bạn chưa đặt tour nào</h3>
            <p class="mt-2 text-gray-500">Hãy khám phá các tour du lịch hấp dẫn của chúng tôi</p>
            <div class="mt-6">
                <a href="{{ route('tours.index') }}"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Khám phá tour ngay
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach ($bookings as $booking)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="md:flex">
                        <div class="md:flex-shrink-0 md:w-64">
                            <img class="h-full w-full object-cover"
                                src="{{ $booking->tour->images->first() ? asset('storage/' . $booking->tour->images->first()->image_path) : 'https://via.placeholder.com/400x300' }}"
                                alt="{{ $booking->tour->name }}">
                        </div>
                        <div class="p-6 flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $booking->tour->name }}</h3>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'active' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-gray-100 text-gray-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusText = [
                                            'pending' => 'Chờ xác nhận',
                                            'confirmed' => 'Đã xác nhận',
                                            'active' => 'Đang diễn ra',
                                            'completed' => 'Đã hoàn thành',
                                            'cancelled' => 'Đã hủy',
                                        ];
                                        $canCancel = $booking->status === 'pending' || $booking->status === 'confirmed';
                                        $daysUntilDeparture = now()->diffInDays($booking->start_date, false);
                                        $canCancel = $canCancel && $daysUntilDeparture >= 2;
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$booking->status] }}">
                                        {{ $statusText[$booking->status] }}
                                    </span>
                                    @if($canCancel)
                                        <span class="ml-2 text-sm text-gray-500">
                                            (Có thể hủy trước 2 ngày khởi hành)
                                        </span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ number_format($booking->total_price) }}đ</p>
                                    <p class="text-sm text-gray-500">Tổng tiền</p>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium">Ngày khởi hành</p>
                                        <p class="text-sm">
                                            @if ($booking->tourDeparture)
                                                {{ $booking->tourDeparture->departure_date->format('d/m/Y') }}
                                            @else
                                                <span class="text-gray-500">Chưa có lịch</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium">Số người</p>
                                        <p class="text-sm">
                                            {{ $booking->people }} người lớn
                                            @if ($booking->children > 0)
                                                , {{ $booking->children }} trẻ em
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($booking->tourDeparture && $booking->tourDeparture->guide)
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Hướng dẫn viên: {{ $booking->tourDeparture->guide->name }}</p>
                                            <p class="text-sm text-gray-600">
                                                @if($booking->tourDeparture->guide_status === 'pending')
                                                    <span class="text-yellow-600">Đang chờ xác nhận</span>
                                                @elseif($booking->tourDeparture->guide_status === 'confirmed')
                                                    <span class="text-green-600">Đã xác nhận</span>
                                                @elseif($booking->tourDeparture->guide_status === 'rejected')
                                                    <span class="text-red-600">Đã từ chối</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-6 flex space-x-3">
                                @if($booking->status === 'pending')
                                    <a href="{{ route('payment.show', $booking) }}"
                                        class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Thanh toán
                                    </a>
                                @endif
                                @if($booking->status === 'completed' && !$booking->review)
                                    <a href="{{ route('tour.review', $booking) }}"
                                        class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        Đánh giá tour
                                    </a>
                                @endif
                                @if($canCancel)
                                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                                        class="flex-1">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            onclick="return confirm('Bạn có chắc chắn muốn hủy tour này?')">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Hủy tour
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
