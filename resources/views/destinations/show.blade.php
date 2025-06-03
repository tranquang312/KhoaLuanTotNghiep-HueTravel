@section('title', $destination->name . ' - HueTravel')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Cover Image -->
            <div class="relative h-[400px] mb-8 rounded-lg overflow-hidden cursor-pointer" onclick="openGallery(0)">
                <img src="{{ $destination->images->first() ? asset('storage/' . $destination->images->first()->image_path) : 'https://via.placeholder.com/1200x400' }}"
                    class="w-full h-full object-cover" alt="{{ $destination->name }}">
                <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                    <div class="text-white text-center">
                        <h1 class="text-4xl font-bold mb-2">{{ $destination->name }}</h1>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Description -->
                    <div class="prose max-w-none mb-8">
                        <h2 class="text-2xl font-semibold mb-4">Giới thiệu</h2>
                        <p class="text-gray-600">{!! $destination->description !!}</p>
                    </div>

                    <!-- Image Gallery -->
                    @if ($destination->images->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold mb-4">Hình ảnh</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($destination->images as $index => $image)
                                    <div class="relative group cursor-pointer" onclick="openGallery({{ $index }})">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            class="w-full h-48 object-cover rounded-lg transition-transform duration-300 group-hover:scale-105" 
                                            alt="{{ $destination->name }}">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Location -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold mb-4">Vị trí</h2>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <p class="text-gray-600">{{ $destination->address ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>

                    <!-- Related Tours -->
                    @if ($destination->tours->count() > 0)
                        <div>
                            <h2 class="text-2xl font-semibold mb-4">Tour liên quan</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($destination->tours as $tour)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                                        <img src="{{ $tour->images->first() ? asset('storage/' . $tour->images->first()->image_path) : 'https://via.placeholder.com/400x300' }}"
                                            class="w-full h-48 object-cover" alt="{{ $tour->name }}">
                                        <div class="p-4 flex flex-col flex-grow">
                                            <h3 class="text-lg font-semibold mb-2">{{ $tour->name }}</h3>
                                            <p class="text-gray-600 mb-4 flex-grow">{{ Str::limit($tour->description, 100) }}</p>
                                            <div class="flex justify-between items-center mt-auto">
                                                <span class="text-blue-600 font-bold">{{ number_format($tour->price) }}đ</span>
                                                <a href="{{ route('tours.show', $tour) }}"
                                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                                    Chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
                    @foreach ($destination->images as $image)
                        <div class="swiper-slide flex items-center justify-center p-6">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="w-full h-full object-contain rounded-lg" 
                                alt="{{ $destination->name }}">
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
    </script>
    @endpush
</x-app-layout>
