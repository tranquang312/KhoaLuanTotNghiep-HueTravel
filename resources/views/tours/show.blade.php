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

                @if ($tour->images->isNotEmpty())
                    @foreach ($tour->destinations as $destination)
                        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                            <h2 class="text-2xl font-bold mb-4">Địa điểm: {{ $destination->name }}</h2>
                            <div class="flex items-center mb-4">
                                <img src="{{ $destination->images->first() ? asset('storage/' . $destination->images->first()->image_path) : 'https://via.placeholder.com/120x120' }}"
                                    class="w-24 h-24 object-cover rounded-lg mr-4" alt="{{ $destination->name }}">
                                <div>
                                    <p class="text-gray-600 mb-1"><strong>Địa chỉ:</strong> {{ $destination->address }}</p>
                                    <a href="{{ route('destinations.show', $destination) }}" class="text-blue-600 hover:underline text-sm">Xem chi tiết địa điểm</a>
                                </div>
                            </div>

                            <!-- Gallery Section -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-4">Hình ảnh</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    @php
                                        $currentIndex = 0;
                                    @endphp
                                    @foreach($destination->images->take(3) as $image)
                                        <div class="relative group cursor-pointer" onclick="openGallery({{ $currentIndex }})">
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                class="w-full h-48 object-cover rounded-lg transition-transform duration-300 group-hover:scale-105"
                                                alt="{{ $destination->name }}">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg"></div>
                                        </div>
                                        @php
                                            $currentIndex++;
                                        @endphp
                                    @endforeach
                                </div>
                            </div>
                           
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Điểm đến</h2>
                    <div class="space-y-4">
                        @foreach ($tour->destinations as $destination)
                            <a href="{{ route('destinations.show', $destination) }}" class="flex items-center space-x-4 hover:bg-gray-100 rounded-lg transition p-2">
                                <img src="{{ $destination->images->first() ? asset('storage/' . $destination->images->first()->image_path) : 'https://images.unsplash.com/photo-1596422846543-75c6fc197f11?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80' }}"
                                    class="w-16 h-16 object-cover rounded-lg" alt="{{ $destination->name }}">
                                <div>
                                    <h3 class="font-semibold text-blue-700 hover:underline">{{ $destination->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $destination->address }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Đặt tour</h2>
                    
                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                        @php
                            $user = auth()->user();
                        @endphp
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Họ và tên</label>
                            <input type="text" name="name" required 
                                value="{{ old('name', $user ? $user->name : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required 
                                value="{{ old('email', $user ? $user->email : '') }}"
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


                        <div x-data="{ date: '{{ old('start_date', \Carbon\Carbon::now()->toDateString()) }}' }">
                            <label class="block text-sm font-medium text-gray-700">Ngày khởi hành</label>
                            <input 
                                type="date" 
                                name="start_date" 
                                required 
                                x-model="date"
                                value="{{ old('start_date', \Carbon\Carbon::now()->toDateString()) }}"
                                min="{{ \Carbon\Carbon::now()->toDateString() }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                @change="date = $event.target.value"
                            >
                            <div class="text-sm text-gray-500 mt-1" x-show="date">
                                Ngày đã chọn: 
                                <span x-text="
                                    (() => {
                                        if (!date) return '';
                                        const [y, m, d] = date.split('-');
                                        return [d, m, y].join('/');
                                    })()
                                "></span>
                            </div>
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
        <div class="bg-white rounded-lg w-[1000px] h-[600px] relative">
            <button onclick="closeGallery()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Gallery Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px" aria-label="Gallery Tabs">
                    <button onclick="switchGalleryTab('destination')" id="destination-gallery-tab"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm gallery-tab-button active">
                        Ảnh địa điểm
                    </button>
                    <button onclick="switchGalleryTab('posts')" id="posts-gallery-tab"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm gallery-tab-button">
                        Ảnh bài viết
                    </button>
                </nav>
            </div>

            <!-- Destination Images Gallery -->
            <div id="destination-gallery" class="swiper-container h-[calc(100%-57px)]">
                <div class="swiper-wrapper">
                    @php
                        $currentIndex = 0;
                    @endphp
                    @foreach ($tour->destinations as $destination)
                        @foreach ($destination->images as $image)
                            <div class="swiper-slide flex flex-col items-center justify-center p-6">
                                <div class="relative w-full h-full">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                        class="w-full h-full object-contain rounded-lg" alt="{{ $destination->name }}">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-3 rounded-b-lg">
                                        <h3 class="text-lg font-semibold">{{ $destination->name }}</h3>
                                        <p class="text-sm opacity-90">{{ $destination->address }}</p>
                                    </div>
                                </div>
                            </div>
                            @php
                                $currentIndex++;
                            @endphp
                        @endforeach
                    @endforeach
                </div>
                <div class="swiper-button-next !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all"></div>
                <div class="swiper-button-prev !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all"></div>
                <div class="swiper-pagination !bottom-4"></div>
            </div>

            <!-- Posts Images Gallery -->
            <div id="posts-gallery" class="swiper-container h-[calc(100%-57px)] hidden">
                <div class="swiper-wrapper">
                    @foreach ($tour->destinations as $destination)
                        @foreach ($destination->posts()->where('is_approved', true)->get() as $post)
                            @if ($post->images)
                                @php
                                    $images = is_array($post->images) ? $post->images : json_decode($post->images);
                                @endphp
                                <div class="swiper-slide">
                                    <div class="flex h-full">
                                        <!-- Image Section -->
                                        <div class="w-1/2 h-full p-6 flex flex-col">
                                            <!-- Main Image -->
                                            <div class="flex-grow mb-4 relative">
                                                <img src="{{ asset('storage/' . $images[0]) }}"
                                                    class="w-full h-full object-cover rounded-lg cursor-pointer"
                                                    onclick="openPostImageModal(this.closest('.swiper-slide'))"
                                                    alt="Post image">
                                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-3 rounded-b-lg">
                                                    <h3 class="text-lg font-semibold">{{ $destination->name }}</h3>
                                                    <p class="text-sm opacity-90">{{ $destination->address }}</p>
                                                </div>
                                            </div>
                                            <!-- Thumbnail Grid -->
                                            @if (count($images) > 1)
                                                <div class="grid grid-cols-4 gap-2">
                                                    @foreach ($images as $index => $image)
                                                        <div class="aspect-square cursor-pointer"
                                                            onclick="switchMainImage(this, '{{ asset('storage/' . $image) }}')">
                                                            <img src="{{ asset('storage/' . $image) }}"
                                                                class="w-full h-full object-cover rounded-lg"
                                                                alt="Post thumbnail">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <!-- Post Details Section -->
                                        <div class="w-1/2 h-full p-6 flex flex-col">
                                            <div class="flex items-center mb-4">
                                                <img src="{{ $post->user->profile_photo_path ? asset('storage/' . $post->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                                                    alt="{{ $post->user->name }}"
                                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                                <div class="ml-3">
                                                    <h3 class="text-base font-semibold text-gray-900">
                                                        {{ $post->user->name }}</h3>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $post->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>

                                            <h4 class="text-xl font-bold text-gray-900 mb-3">{{ $post->title }}</h4>

                                            <div class="prose max-w-none text-gray-600 mb-4 flex-grow">
                                                {!! nl2br(e($post->content)) !!}
                                            </div>

                                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                                <button class="like-button flex items-center space-x-2 transition-colors"
                                                    onclick="likePost({{ $post->id }}, this)"
                                                    data-post-id="{{ $post->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6 {{ $post->likes()->where('user_id', auth()->id())->exists()? 'text-red-400': 'text-gray-200' }} hover:text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                    </svg>
                                                    <span id="likes-count-{{ $post->id }}"
                                                        class="font-medium text-sm">{{ $post->likes_count }}</span>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                            @endif
                        @endforeach
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
        let destinationGallerySwiper = null;
        let postsGallerySwiper = null;
        let currentImageIndex = 0;

        function openGallery(index) {
            currentImageIndex = index;
            document.getElementById('galleryModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Initialize destination gallery
            if (!destinationGallerySwiper) {
                destinationGallerySwiper = new Swiper('#destination-gallery', {
                    initialSlide: currentImageIndex,
                    loop: false,
                    keyboard: {
                        enabled: true,
                    },
                    navigation: {
                        nextEl: '#destination-gallery .swiper-button-next',
                        prevEl: '#destination-gallery .swiper-button-prev',
                    },
                    pagination: {
                        el: '#destination-gallery .swiper-pagination',
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
            } else {
                destinationGallerySwiper.slideTo(currentImageIndex, 0);
            }

            // Initialize posts gallery
            if (!postsGallerySwiper) {
                postsGallerySwiper = new Swiper('#posts-gallery', {
                    loop: false,
                keyboard: {
                    enabled: true,
                },
                navigation: {
                        nextEl: '#posts-gallery .swiper-button-next',
                        prevEl: '#posts-gallery .swiper-button-prev',
                },
                pagination: {
                        el: '#posts-gallery .swiper-pagination',
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
            }

            // Switch to destination gallery tab by default
            switchGalleryTab('destination');
        }

        function closeGallery() {
            if (destinationGallerySwiper) {
                destinationGallerySwiper.destroy(true, true);
                destinationGallerySwiper = null;
            }
            if (postsGallerySwiper) {
                postsGallerySwiper.destroy(true, true);
                postsGallerySwiper = null;
            }
            document.getElementById('galleryModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function switchGalleryTab(tabName) {
            // Hide all gallery contents
            document.querySelectorAll('.swiper-container').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.gallery-tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                    'hover:border-gray-300');
            });

            // Show selected gallery content
            document.getElementById(`${tabName}-gallery`).classList.remove('hidden');

            // Add active class to selected tab
            const selectedTab = document.getElementById(`${tabName}-gallery-tab`);
            selectedTab.classList.add('active', 'border-blue-500', 'text-blue-600');
            selectedTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                'hover:border-gray-300');

            // Update swiper
            if (tabName === 'destination' && destinationGallerySwiper) {
                destinationGallerySwiper.update();
                destinationGallerySwiper.slideTo(currentImageIndex, 0);
            } else if (tabName === 'posts' && postsGallerySwiper) {
                postsGallerySwiper.update();
            }
        }

        function switchMainImage(thumbnail, imageSrc) {
            const mainImage = thumbnail.closest('.swiper-slide').querySelector('.flex-grow img');
            mainImage.src = imageSrc;
        }

        function likePost(postId, button) {
            fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 401) {
                            window.location.href = '/login';
                        }
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update like count
                    const likesCountElements = document.querySelectorAll(`#likes-count-${postId}`);
                    likesCountElements.forEach(element => {
                        element.textContent = data.likes_count;
                    });

                    // Toggle like button state
                    const svg = button.querySelector('svg');
                    if (data.is_liked) {
                        svg.classList.remove('text-gray-200');
                        svg.classList.add('text-red-400');
                    } else {
                        svg.classList.remove('text-red-400');
                        svg.classList.add('text-gray-200');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
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

        function openPostImageModal(postSlide) {
            const mainImage = postSlide.querySelector('.flex-grow img');
            const modal = document.getElementById('postImageModal');
            const modalImage = document.getElementById('modalPostImage');
            const thumbnailWrapper = document.querySelector('#modalThumbnailSwiper .swiper-wrapper');

            // Set main image
            modalImage.src = mainImage.src;

            // Get all thumbnails from the current post
            const thumbnails = postSlide.querySelectorAll('.grid img');

            // Clear and populate thumbnail wrapper
            thumbnailWrapper.innerHTML = '';
            thumbnails.forEach(thumb => {
                const slide = document.createElement('div');
                slide.className = 'swiper-slide cursor-pointer';
                slide.innerHTML = `<img src="${thumb.src}" class="w-full h-full object-cover rounded-lg" alt="Post thumbnail">`;
                slide.onclick = () => {
                    modalImage.src = thumb.src;
                    modalThumbnailSwiper.slideTo(Array.from(thumbnails).indexOf(thumb));
                };
                thumbnailWrapper.appendChild(slide);
            });

            // Initialize or update thumbnail swiper
            if (modalThumbnailSwiper) {
                modalThumbnailSwiper.destroy();
            }
            modalThumbnailSwiper = new Swiper('#modalThumbnailSwiper', {
                slidesPerView: 'auto',
                spaceBetween: 8,
                watchSlidesProgress: true,
                navigation: {
                    nextEl: '#modalThumbnailSwiper .swiper-button-next',
                    prevEl: '#modalThumbnailSwiper .swiper-button-prev',
                },
            });

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    </script>
    @endpush

    @push('styles')
    <style>
        .gallery-tab-button.active {
            border-color: #3B82F6;
            color: #2563EB;
        }

        .gallery-tab-button {
            border-color: transparent;
            color: #6B7280;
        }

        .gallery-tab-button:hover {
            border-color: #D1D5DB;
            color: #374151;
        }

        #posts-gallery .prose {
            max-height: 200px;
            overflow-y: auto;
        }

        #posts-gallery .prose::-webkit-scrollbar {
            width: 4px;
        }

        #posts-gallery .prose::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }

        #posts-gallery .prose::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }

        #posts-gallery .prose::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .like-button {
            transition: all 0.2s ease-in-out;
        }

        .like-button:hover {
            transform: scale(1.05);
        }

        .like-button svg {
            transition: all 0.2s ease-in-out;
        }
    </style>
    @endpush
</x-app-layout>
