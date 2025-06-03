<x-admin-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Chuyến đi sắp khởi hành</h1>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày khởi hành</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số đơn đặt</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người lớn</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trẻ em</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng doanh thu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hướng dẫn viên</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($upcomingBookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="max-w-xs truncate">{{ $booking->tour->name }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $booking->total_bookings }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $booking->total_adults }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $booking->total_children }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($booking->total_revenue) }}đ
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                @php
                                    $departure = \App\Models\TourDeparture::where('tour_id', $booking->tour_id)
                                        ->where('departure_date', $booking->start_date)
                                        ->with('guide')
                                        ->first();
                                @endphp
                                @if($departure && $departure->guide)
                                    <div class="flex items-center">
                                        <span class="text-green-600">{{ $departure->guide->name }}</span>
                                        <button type="button" 
                                                class="ml-2 text-blue-600 hover:text-blue-800"
                                                onclick="openAssignGuideModal({{ $booking->tour_id }}, '{{ $booking->start_date }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                @else
                                    <button type="button" 
                                            class="text-blue-600 hover:text-blue-800"
                                            onclick="openAssignGuideModal({{ $booking->tour_id }}, '{{ $booking->start_date }}')">
                                        <i class="fas fa-plus"></i> Gắn hướng dẫn viên
                                    </button>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.bookings.tour-details', ['tour' => $booking->tour_id, 'date' => $booking->start_date]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $upcomingBookings->links() }}
        </div>
    </div>

    <!-- Modal Gắn hướng dẫn viên -->
    <div id="assignGuideModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Gắn hướng dẫn viên</h3>
                <form id="assignGuideForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="guide_id" class="block text-sm font-medium text-gray-700">Hướng dẫn viên</label>
                        <select name="guide_id" id="guide_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Chọn hướng dẫn viên</option>
                            @foreach($guides as $guide)
                                <option value="{{ $guide->id }}">{{ $guide->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAssignGuideModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Xác nhận
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openAssignGuideModal(tourId, startDate) {
            const modal = document.getElementById('assignGuideModal');
            const form = document.getElementById('assignGuideForm');
            form.action = "{{ route('admin.bookings.assign-guide-by-date', ['tour' => ':tourId', 'date' => ':date']) }}"
                .replace(':tourId', tourId)
                .replace(':date', startDate);
            modal.classList.remove('hidden');
        }

        function closeAssignGuideModal() {
            const modal = document.getElementById('assignGuideModal');
            modal.classList.add('hidden');
        }

        // Đóng modal khi click bên ngoài
        window.onclick = function(event) {
            const modal = document.getElementById('assignGuideModal');
            if (event.target == modal) {
                closeAssignGuideModal();
            }
        }
    </script>
    @endpush
</x-admin-layout>
