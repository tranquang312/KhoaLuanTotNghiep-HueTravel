<x-admin-layout>
    @php
        function translateStatus($status)
        {
            return match ($status) {
                'pending' => 'Chờ xác nhận',
                'active' => 'Đang hoạt động',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy',
                default => $status,
            };
        }
        function translateStatusGuide($status)
        {
            return match ($status) {
                'pending' => 'Chờ xác nhận',
                'rejected' => 'Từ chối',
                'confirmed' => 'Đã xác nhận',
                default => $status,
            };
        }
    @endphp

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Quản lý chuyến đi</h1>
            <div class="flex space-x-3">
                <div class="flex space-x-2">
                    <a href="{{ route('admin.tour-departures.index', ['filter' => 'today']) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Hôm nay
                    </a>
                    <a href="{{ route('admin.tour-departures.index', ['filter' => 'next_7_days']) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        7 ngày tới
                    </a>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.tour-departures.index', ['status' => 'pending']) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Chờ phân công
                    </a>
                    <a href="{{ route('admin.tour-departures.index', ['status' => 'confirmed']) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Đã xác nhận
                    </a>
                </div>
                @if(request()->has('filter') || request()->has('status'))
                    <a href="{{ route('admin.tour-departures.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Bỏ lọc
                    </a>
                @endif
            </div>
        </div>


        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày
                            khởi hành</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hướng
                            dẫn viên</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số
                            người đã đặt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                            thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Xác
                            nhận hướng dẫn viên</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao
                            tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($departures as $departure)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $departure->tour->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $departure->departure_date->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $departure->guide ? $departure->guide->name : 'Chưa phân công' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $departure->bookings->sum('people') + $departure->bookings->sum('children') }}
                                    người
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($departure->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($departure->status === 'active') bg-green-100 text-green-800
                                    @elseif($departure->status === 'completed') bg-blue-100 text-blue-800
                                    @elseif($departure->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if ($departure->status === 'pending')
                                        Chờ khởi hành
                                    @elseif ($departure->status === 'active')
                                        Đang diễn ra
                                    @elseif ($departure->status === 'completed')
                                        Đã hoàn thành
                                    @elseif ($departure->status === 'cancelled')
                                        Đã hủy
                                    @else
                                        Không xác định
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($departure->guide_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif ($departure->guide_status === 'rejected')
                                        bg-red-100 text-red-800
                                    @else
                                        bg-green-100 text-green-800 @endif">
                                    @if ($departure->guide_status === 'pending')
                                        Chờ xác nhận
                                    @elseif ($departure->guide_status === 'rejected')
                                        Từ chối
                                    @else
                                        Đã xác nhận
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if (!$departure->guide)
                                    <button type="button"
                                        onclick="openAssignModal({{ $departure->id }}, '{{ $departure->tour->name }}', '{{ $departure->departure_date->format('d/m/Y') }}')"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        Phân công
                                    </button>
                                @else
                                    <div class="flex items-center space-x-4">
                                        @if(auth()->id() === $departure->guide_id && $departure->guide_status === 'pending')
                                            <form action="{{ route('admin.tour-departures.guide-confirm', $departure) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                    Xác nhận
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.tour-departures.guide-reject', $departure) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Từ chối
                                                </button>
                                            </form>
                                        @endif
                                        @if(auth()->id() === $departure->guide_id)
                                            <a href="{{ route('admin.tour-departures.show-bookings', $departure) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Xem chi tiết
                                            </a>
                                        @endif
                                        @can('assign-guide')
                                        <button type="button"
                                            onclick="openAssignModal({{ $departure->id }}, '{{ $departure->tour->name }}', '{{ $departure->departure_date->format('d/m/Y') }}')"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Thay đổi
                                        </button>
                                        @endcan
                                        @if($departure->status === 'pending' && $departure->guide_status === 'confirmed')
                                            <form action="{{ route('admin.tour-departures.start-tour', $departure) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                    Khởi hành
                                                </button>
                                            </form>
                                        @endif
                                        @if($departure->status === 'active')
                                            <form action="{{ route('admin.tour-departures.complete-tour', $departure) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                    Hoàn thành
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                Không có chuyến đi nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $departures->links() }}
        </div>
    </div>

    <!-- Modal Phân công -->
    <div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Phân công hướng dẫn viên</h3>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Tour: <span id="modalTourName" class="font-medium"></span></p>
                    <p class="text-sm text-gray-600">Ngày khởi hành: <span id="modalDepartureDate" class="font-medium"></span></p>
                </div>
                <form id="assignForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="guide_id">
                            Hướng dẫn viên
                        </label>
                        <select name="guide_id" id="guide_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">Chọn hướng dẫn viên</option>
                            @foreach ($guides as $guide)
                                <option value="{{ $guide->id }}" 
                                    data-tours="{{ $guide->tour_departures_count }}"
                                    data-name="{{ $guide->name }}"
                                    @if($guide->tour_departures_count > 0) disabled @endif
                                >
                                    {{ $guide->name }} 
                                    @if($guide->tour_departures_count > 0)
                                        (Đã có tour trong ngày)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-sm text-gray-500">
                            <span class="font-medium text-yellow-600">Lưu ý:</span> 
                            Hướng dẫn viên đã có tour trong ngày sẽ được đánh dấu
                        </p>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="button" onclick="closeAssignModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-400">
                            Hủy
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Xác nhận
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openAssignModal(departureId, tourName, departureDate) {
                const modal = document.getElementById('assignModal');
                const form = document.getElementById('assignForm');
                const modalTourName = document.getElementById('modalTourName');
                const modalDepartureDate = document.getElementById('modalDepartureDate');
                
                form.action = `/admin/tour-departures/${departureId}/assign-guide`;
                modalTourName.textContent = tourName;
                modalDepartureDate.textContent = departureDate;
                
                // Reload guides list with the selected departure date
                fetch(`/admin/tour-departures?departure_date=${departureDate.split('/').reverse().join('-')}`)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newGuideSelect = doc.getElementById('guide_id');
                        const currentGuideSelect = document.getElementById('guide_id');
                        currentGuideSelect.innerHTML = newGuideSelect.innerHTML;
                        
                        // Highlight guides with tours on the same day
                        Array.from(currentGuideSelect.options).forEach(option => {
                            if (option.dataset.tours > 0) {
                                option.style.color = '#B45309'; // Amber-700
                                option.style.fontWeight = 'bold';
                            }
                        });
                    });
                
                modal.classList.remove('hidden');
            }

            function closeAssignModal() {
                const modal = document.getElementById('assignModal');
                modal.classList.add('hidden');
            }
        </script>
    @endpush
</x-admin-layout>
