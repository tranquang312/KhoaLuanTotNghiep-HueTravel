<x-app-layout>

    <div class="relative w-full h-[480px] overflow-hidden">
        <video autoplay muted loop playsinline class="absolute top-0 left-0 w-full h-full object-cover">
            <source src="{{ asset('storage/banner/banner.mp4') }}" type="video/mp4">
        </video>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-40 z-10"></div>

        <!-- Banner Content -->
        <div class="relative z-20 text-center w-full flex flex-col items-center justify-center h-full">
            <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-white drop-shadow-lg">
                Chào mừng đến với <span class="text-yellow-400">&lt;Hue&gt;</span> <span class="text-white">Travel</span>
            </h1>
            <p class="text-gray-200 text-lg mt-2 drop-shadow">Khám phá những điểm đến tuyệt vời cùng chúng tôi</p>
            <a class="px-5 py-3 text-lg text-white bg-yellow-500 rounded mt-8 inline-block font-semibold shadow-lg hover:bg-yellow-600 transition"
                href="{{ route('tours.index') }}">Khám phá ngay
            </a>
        </div>
    </div>




    <!-- Featured Tours Section -->
    <section id="tours" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 id="featured-tours" class="text-3xl font-bold text-center mb-12">Tour Nổi Bật</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($tours as $tour)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="{{ $tour->images->first() ? asset('storage/' . $tour->images->first()->image_path) : 'https://images.unsplash.com/photo-1596422846543-75c6fc197f11?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80' }}"
                            class="w-full h-48 md:h-56 object-cover" alt="{{ $tour->name }}">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ $tour->name }}</h3>
                            <p class="text-gray-600 mb-4">{!! Str::limit($tour->description, 100) !!}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-bold">{{ number_format($tour->price) }}đ</span>
                                <a href="{{ route('tours.show', $tour) }}"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Destinations Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Điểm Đến Phổ Biến</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($destinations as $destination)
                    <div class="relative group">
                        <img src="{{ $destination->images->first() ? asset('storage/' . $destination->images->first()->image_path) : 'https://images.unsplash.com/photo-1596422846543-75c6fc197f11?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80' }}"
                            class="w-full h-64 object-cover rounded-lg" alt="{{ $destination->name }}">
                        <div
                            class="absolute inset-0 bg-black bg-opacity-40 rounded-lg group-hover:bg-opacity-30 transition duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                <h3 class="text-xl font-semibold">{{ $destination->name }}</h3>
                                <p class="text-sm">{!! Str::limit($destination->short_description, 100) !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Tại Sao Chọn Chúng Tôi</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Tiết Kiệm Thời Gian</h3>
                    <p class="text-gray-600">Lên kế hoạch và đặt tour nhanh chóng, tiện lợi</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Giá Cả Hợp Lý</h3>
                    <p class="text-gray-600">Tour chất lượng với giá cả phải chăng</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Dịch Vụ Chuyên Nghiệp</h3>
                    <p class="text-gray-600">Đội ngũ hướng dẫn viên nhiệt tình, giàu kinh nghiệm</p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.featured-tours-slider', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                }
            }
        });
    });
</script>
@endpush
