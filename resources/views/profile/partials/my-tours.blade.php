<div class="p-6 text-gray-900">
    @if($bookings->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg">Bạn chưa đặt tour nào.</p>
            <a href="{{ route('tours.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Khám phá tour ngay
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img class="w-full h-48 object-cover" 
                            src="{{ $booking->tour->images->first() ? asset('storage/' . $booking->tour->images->first()->image_path) : 'https://via.placeholder.com/400x300' }}" 
                            alt="{{ $booking->tour->name }}">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $statusText = [
                                'pending' => 'Chờ xác nhận',
                                'confirmed' => 'Đã xác nhận',
                                'completed' => 'Đã hoàn thành',
                                'cancelled' => 'Đã hủy'
                            ];
                        @endphp
                        <span class="absolute top-2 right-2 px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$booking->status] }}">
                            {{ $statusText[$booking->status] }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $booking->tour->name }}</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                @if($booking->tourDeparture)
                                    {{ $booking->tourDeparture->departure_date->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-500">Chưa có lịch</span>
                                @endif
                            </p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $booking->people }} người lớn
                                @if($booking->children > 0)
                                    , {{ $booking->children }} trẻ em
                                @endif
                            </p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ number_format($booking->total_price) }}đ
                            </p>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('tours.show', $booking->tour) }}" 
                               class="flex-1 bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Xem chi tiết
                            </a>
                            @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition"
                                            onclick="return confirm('Bạn có chắc chắn muốn hủy tour này?')">
                                        Hủy tour
                                    </button>
                                </form>
                            @endif
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