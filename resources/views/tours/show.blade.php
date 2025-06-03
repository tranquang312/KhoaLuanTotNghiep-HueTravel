@section('title', $tour->name . ' - HueTravel')

<x-app-layout>
    <!-- Cover Image -->
    <div class="relative h-[400px] mb-8 rounded-lg overflow-hidden cursor-pointer" onclick="openGallery(0)">
        <img src="{{ $tour->images->first() ? asset('storage/' . $tour->images->first()->image_path) : 'https://via.placeholder.com/1200x400' }}"
            class="w-full h-full object-cover" alt="{{ $tour->name }}">
        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
            <div class="text-white text-center">
                <h1 class="text-4xl font-bold mb-2">{{ $tour->name }}</h1>
                <p class="text-2xl">{{ number_format($tour->price) }}đ</p>
            </div>
        </div>
    </div>

    <!-- Tour Details Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Giới thiệu</h2>
                    <div class="prose max-w-none">
                        {!! $tour->description !!}
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Lịch trình</h2>
                    <div class="prose max-w-none">
                        {!! $tour->itinerary !!}
                    </div>
                </div>

                @if ($tour->images->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold mb-4">Hình ảnh</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($tour->images as $index => $image)
                                <div class="relative group cursor-pointer" onclick="openGallery({{ $index }})">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        class="w-full h-48 object-cover rounded-lg transition-transform duration-300 group-hover:scale-105" 
                                        alt="{{ $tour->name }}">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Điểm đến</h2>
                    <div class="space-y-4">
                        @foreach ($tour->destinations as $destination)
                            <div class="flex items-center space-x-4">
                                <img src="{{ $destination->images->first() ? asset('storage/' . $destination->images->first()->image_path) : 'https://images.unsplash.com/photo-1596422846543-75c6fc197f11?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80' }}"
                                    class="w-16 h-16 object-cover rounded-lg" alt="{{ $destination->name }}">
                                <div>
                                    <h3 class="font-semibold">{{ $destination->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $destination->address }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Đặt tour</h2>
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Họ và tên</label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                            <input type="tel" name="phone" required value="{{ old('phone') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ngày khởi hành</label>
                            <input type="date" name="start_date" required value="{{ old('start_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Số người lớn</label>
                            <input type="number" name="people" id="people" min="1" required value="{{ old('people', 1) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('people')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Số trẻ em</label>
                            <input type="number" name="children" id="children" min="0" value="{{ old('children', 0) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('children')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ghi chú</label>
                            <textarea name="note" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('note') }}</textarea>
                            @error('note')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tổng tiền:</span>
                            <span class="text-xl font-bold text-blue-600"
                                id="total-price">{{ number_format($tour->price) }}đ</span>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                            Đặt ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg w-[1000px] h-[500px] relative">
            <button onclick="closeGallery()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="swiper-container h-full">
                <div class="swiper-wrapper">
                    @foreach ($tour->images as $image)
                        <div class="swiper-slide flex items-center justify-center p-6">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="w-full h-full object-contain rounded-lg" 
                                alt="{{ $tour->name }}">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all"></div>
                <div class="swiper-button-prev !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all"></div>
                <div class="swiper-pagination !bottom-4"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let gallerySwiper = null;

        function openGallery(index) {
            document.getElementById('galleryModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Destroy existing swiper if it exists
            if (gallerySwiper) {
                gallerySwiper.destroy(true, true);
                gallerySwiper = null;
            }

            // Initialize new swiper
            gallerySwiper = new Swiper('.swiper-container', {
                initialSlide: index,
                loop: true,
                keyboard: {
                    enabled: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    type: 'fraction',
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                observer: true,
                observeParents: true,
                watchSlidesProgress: true,
                watchSlidesVisibility: true,
                preloadImages: false,
                lazy: {
                    loadPrevNext: true,
                    loadPrevNextAmount: 2,
                },
            });

            // Force update to ensure correct slide is shown
            gallerySwiper.update();
            gallerySwiper.slideTo(index, 0);
        }

        function closeGallery() {
            if (gallerySwiper) {
                gallerySwiper.destroy(true, true);
                gallerySwiper = null;
            }
            document.getElementById('galleryModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close gallery on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeGallery();
            }
        });

        // Close gallery when clicking outside
        document.getElementById('galleryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeGallery();
            }
        });

        // Calculate total price
        const tourPrice = {{ $tour->price }};
        const peopleInput = document.getElementById('people');
        const childrenInput = document.getElementById('children');
        const totalPriceElement = document.getElementById('total-price');

        function calculateTotal() {
            const people = parseInt(peopleInput.value) || 0;
            const children = parseInt(childrenInput.value) || 0;
            const total = (people + children * 0.7) * tourPrice;
            totalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
        }

        peopleInput.addEventListener('input', calculateTotal);
        childrenInput.addEventListener('input', calculateTotal);
    </script>
    @endpush
</x-app-layout>
