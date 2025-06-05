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
                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px" aria-label="Tabs">
                        <button onclick="switchTab('detail')" id="detail-tab"
                            class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm tab-button active">
                            Chi tiết
                        </button>
                        <button onclick="switchTab('posts')" id="posts-tab"
                            class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm tab-button">
                            Bài viết
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <div id="detail-content" class="tab-content">
                        @include('destinations.partials.detail')
                    </div>
                    <div id="posts-content" class="tab-content hidden">
                        @include('destinations.partials.posts')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg w-[1000px] h-[600px] relative">
            <button onclick="closeGallery()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
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
                    @foreach ($destination->images as $image)
                        <div class="swiper-slide flex items-center justify-center p-6">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="w-full h-full object-contain rounded-lg" alt="{{ $destination->name }}">
                        </div>
                    @endforeach
                </div>
                <div
                    class="swiper-button-next !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all">
                </div>
                <div
                    class="swiper-button-prev !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all">
                </div>
                <div class="swiper-pagination !bottom-4"></div>
            </div>

            <!-- Posts Images Gallery -->
            <div id="posts-gallery" class="swiper-container h-[calc(100%-57px)] hidden">
                <div class="swiper-wrapper">
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
                                        <div class="flex-grow mb-4">
                                            <img src="{{ asset('storage/' . $images[0]) }}"
                                                class="w-full h-full object-cover rounded-lg" alt="Post image">
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
                                            <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
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
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div
                    class="swiper-button-next !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all">
                </div>
                <div
                    class="swiper-button-prev !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all">
                </div>
                <div class="swiper-pagination !bottom-4"></div>
            </div>
        </div>
    </div>

    <!-- Post Image Modal -->
    <div id="postImageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg w-[1000px] h-[600px] relative">
            <button onclick="closePostImageModal()"
                class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <div class="h-full flex flex-col">
                <!-- Main Image -->
                <div class="flex-grow p-6">
                    <img id="modalPostImage" src="" alt="Post image"
                        class="w-full h-full object-contain rounded-lg">
                </div>
                <!-- Thumbnail Swiper -->
                <div class="h-24 px-6 pb-6">
                    <div id="modalThumbnailSwiper" class="swiper">
                        <div class="swiper-wrapper">
                            <!-- Thumbnails will be added dynamically -->
                        </div>
                        <div
                            class="swiper-button-next !w-8 !h-8 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all">
                        </div>
                        <div
                            class="swiper-button-prev !w-8 !h-8 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let destinationGallerySwiper = null;
            let postsGallerySwiper = null;
            let modalThumbnailSwiper = null;

            function openGallery(index) {
                document.getElementById('galleryModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Initialize destination gallery
                if (!destinationGallerySwiper) {
                    destinationGallerySwiper = new Swiper('#destination-gallery', {
                        initialSlide: index,
                        loop: true,
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
                }

                // Initialize posts gallery
                if (!postsGallerySwiper) {
                    postsGallerySwiper = new Swiper('#posts-gallery', {
                        loop: true,
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

                // Force update to ensure correct slide is shown
                destinationGallerySwiper.update();
                destinationGallerySwiper.slideTo(index, 0);
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

            function openPostImageModal(mainImageContainer) {
                const mainImage = mainImageContainer.querySelector('img');
                const modal = document.getElementById('postImageModal');
                const modalImage = document.getElementById('modalPostImage');
                const thumbnailWrapper = document.querySelector('#modalThumbnailSwiper .swiper-wrapper');

                // Set main image
                modalImage.src = mainImage.src;

                // Get all thumbnails from the current post
                const thumbnails = mainImageContainer.closest('.swiper-slide').querySelectorAll(
                    '.thumbnail-swiper .swiper-slide img');

                // Clear and populate thumbnail wrapper
                thumbnailWrapper.innerHTML = '';
                thumbnails.forEach(thumb => {
                    const slide = document.createElement('div');
                    slide.className = 'swiper-slide cursor-pointer';
                    slide.innerHTML =
                        `<img src="${thumb.src}" class="w-full h-full object-cover rounded-lg" alt="Post thumbnail">`;
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

            function closePostImageModal() {
                document.getElementById('postImageModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                if (modalThumbnailSwiper) {
                    modalThumbnailSwiper.destroy();
                    modalThumbnailSwiper = null;
                }
            }

            function togglePostForm() {
                const form = document.getElementById('postForm');
                form.classList.toggle('hidden');
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
                        // Update like count in both gallery and posts section
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

            function switchTab(tabName) {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active class from all tabs
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('active', 'border-blue-500', 'text-blue-600');
                    button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                        'hover:border-gray-300');
                });

                // Show selected tab content
                document.getElementById(`${tabName}-content`).classList.remove('hidden');

                // Add active class to selected tab
                const selectedTab = document.getElementById(`${tabName}-tab`);
                selectedTab.classList.add('active', 'border-blue-500', 'text-blue-600');
                selectedTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                    'hover:border-gray-300');
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
                } else if (tabName === 'posts' && postsGallerySwiper) {
                    postsGallerySwiper.update();
                }
            }

            function switchMainImage(thumbnail, imageSrc) {
                const mainImage = thumbnail.closest('.swiper-slide').querySelector('.flex-grow img');
                mainImage.src = imageSrc;
            }

            // Close gallery on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeGallery();
                    closePostImageModal();
                }
            });

            // Close gallery when clicking outside
            document.getElementById('galleryModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeGallery();
                }
            });

            // Close image modal when clicking outside
            document.getElementById('postImageModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePostImageModal();
                }
            });

            // Initialize thumbnail swipers
            document.addEventListener('DOMContentLoaded', function() {
                const thumbnailSwipers = document.querySelectorAll('.thumbnail-swiper');
                thumbnailSwipers.forEach(swiper => {
                    new Swiper(swiper, {
                        slidesPerView: 'auto',
                        spaceBetween: 8,
                        watchSlidesProgress: true,
                        navigation: {
                            nextEl: swiper.querySelector('.swiper-button-next'),
                            prevEl: swiper.querySelector('.swiper-button-prev'),
                        },
                    });
                });
            });

            // Initialize like states on page load
            document.addEventListener('DOMContentLoaded', function() {
                const likeButtons = document.querySelectorAll('.like-button');
                likeButtons.forEach(button => {
                    const svg = button.querySelector('svg');
                    const isLiked = svg.classList.contains('text-red-400');
                    if (isLiked) {
                        svg.classList.remove('text-gray-200');
                        svg.classList.add('text-red-400');
                    } else {
                        svg.classList.remove('text-red-400');
                        svg.classList.add('text-gray-200');
                    }
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .posts-slider {
                width: 100%;
                padding: 20px 0;
            }

            .posts-slider .swiper-slide {
                height: auto;
            }

            .posts-slider .swiper-button-next,
            .posts-slider .swiper-button-prev {
                color: #4B5563;
            }

            .posts-slider .swiper-pagination-bullet {
                background: #4B5563;
            }

            .posts-slider .swiper-pagination-bullet-active {
                background: #2563EB;
            }

            .image-preview-item {
                position: relative;
            }

            .image-preview-item img {
                width: 100%;
                height: 100px;
                object-fit: cover;
                border-radius: 0.5rem;
            }

            .image-preview-item .remove-image {
                position: absolute;
                top: -0.5rem;
                right: -0.5rem;
                background: white;
                border-radius: 50%;
                padding: 0.25rem;
                cursor: pointer;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            /* Add styles for posts gallery */
            #posts-gallery .swiper-slide {
                height: 100%;
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

            /* Thumbnail Swiper Styles */
            .thumbnail-swiper {
                width: 100%;
                height: 100%;
            }

            .thumbnail-swiper .swiper-slide {
                width: 80px;
                height: 80px;
                opacity: 0.6;
                transition: opacity 0.3s;
            }

            .thumbnail-swiper .swiper-slide:hover {
                opacity: 0.8;
            }

            .thumbnail-swiper .swiper-slide.swiper-slide-thumb-active {
                opacity: 1;
                border: 2px solid #2563EB;
            }

            .thumbnail-swiper .swiper-button-next,
            .thumbnail-swiper .swiper-button-prev {
                color: #4B5563;
            }

            /* Like button styles */
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
