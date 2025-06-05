<!-- Posts Section -->
<div class="space-y-6">
    @auth
        <div class="flex justify-end space-x-4 mb-6">
            <a href="{{ route('profile.posts') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                Bài viết của tôi
            </a>
            <button onclick="openPostModal()"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Viết bài mới
            </button>
        </div>
    @endauth

    <!-- Display Posts -->
    @php
        $posts = $destination->posts()->where('is_approved', true)->latest()->get();
    @endphp
    @if ($posts->count() > 0)
        <div class="relative max-w-5xl mx-auto px-4">
            <div class="swiper posts-slider">
                <div class="swiper-wrapper">
                    @foreach ($posts as $post)
                        <div class="swiper-slide">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden h-full">
                                <div class="p-4">
                                    <div class="flex items-center mb-3">
                                        <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                                            alt="{{ $post->user->name }}"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                        <div class="ml-3">
                                            <h3 class="text-base font-semibold text-gray-900">{{ $post->user->name }}
                                            </h3>
                                            <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $post->title }}</h4>
                                    <div class="prose max-w-none text-gray-600 mb-3 text-sm">
                                        {!! nl2br(e($post->content)) !!}
                                    </div>

                                    @if ($post->images)
                                        @php
                                            $images = is_array($post->images)
                                                ? $post->images
                                                : json_decode($post->images);
                                            $firstImage = $images[0] ?? null;
                                        @endphp
                                        @if ($firstImage)
                                            <div class="mb-3">
                                                <div class="relative group">
                                                    <img src="{{ asset('storage/' . $firstImage) }}" alt="Post image"
                                                        class="w-full h-40 object-cover rounded-lg cursor-pointer"
                                                        onclick="openImageModal(this.src)">
                                                    <div
                                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
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
                    @endforeach
                </div>
                <!-- Add Navigation -->
                <div
                    class="swiper-button-next !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all">
                </div>
                <div
                    class="swiper-button-prev !w-12 !h-12 !bg-white/80 !rounded-full !text-gray-600 hover:!bg-white/90 transition-all">
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination !bottom-0"></div>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                </path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bài viết nào</h3>
            <p class="mt-1 text-sm text-gray-500">Hãy là người đầu tiên chia sẻ trải nghiệm về địa điểm này.</p>
            @auth
                <div class="mt-6">
                    <button onclick="openPostModal()"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Viết bài mới
                    </button>
                </div>
            @endauth
        </div>
    @endif
</div>

<!-- Create Post Modal -->
<div id="postModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="destination_id" value="{{ $destination->id }}">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                Viết bài mới
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Tiêu
                                        đề</label>
                                    <input type="text" name="title" id="title" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Nhập tiêu đề bài viết">
                                </div>

                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700">Nội
                                        dung</label>
                                    <textarea name="content" id="content" rows="4" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Chia sẻ trải nghiệm của bạn về địa điểm này..."></textarea>
                                </div>

                                <div>
                                    <label for="images" class="block text-sm font-medium text-gray-700">Hình
                                        ảnh</label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="images"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Tải ảnh lên</span>
                                                    <input type="file" name="images[]" id="images" multiple
                                                        accept="image/*" class="sr-only"
                                                        onchange="previewImages(this)">
                                                </label>
                                                <p class="pl-1">hoặc kéo thả ảnh vào đây</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 2MB</p>
                                        </div>
                                    </div>
                                    <div id="imagePreview" class="mt-4 grid grid-cols-4 gap-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Đăng bài
                    </button>
                    <button type="button" onclick="closePostModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.posts-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                    dynamicMainBullets: 3
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    }
                },
            });
        });

        function openPostModal() {
            document.getElementById('postModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePostModal() {
            document.getElementById('postModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function previewImages(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'image-preview-item';
                        div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-image" onclick="removeImage(${index})">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    `;
                        preview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        function removeImage(index) {
            const input = document.getElementById('images');
            const dt = new DataTransfer();
            const {
                files
            } = input;

            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }

            input.files = dt.files;
            previewImages(input);
        }

        // Close modal when clicking outside
        document.getElementById('postModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePostModal();
            }
        });

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePostModal();
            }
        });

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
