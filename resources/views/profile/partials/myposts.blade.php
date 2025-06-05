<!-- My Posts Section -->
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Bài viết của tôi</h2>

                <!-- Destination Filter -->
                <div class="flex items-center space-x-2">
                    <label for="destination_filter" class="text-sm font-medium text-gray-700">Lọc theo địa điểm:</label>
                    <select id="destination_filter" onchange="filterByDestination(this.value)"
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tất cả địa điểm</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id }}"
                                {{ request('destination_id') == $destination->id ? 'selected' : '' }}>
                                {{ $destination->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if ($posts->count() > 0)
                <div class="space-y-6">
                    @foreach ($posts as $post)
                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <img src="{{ $post->destination->images->first() ? asset('storage/' . $post->destination->images->first()->image_path) : 'https://via.placeholder.com/100' }}"
                                        alt="{{ $post->destination->name }}" class="w-12 h-12 rounded-lg object-cover">
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $post->destination->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $post->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button onclick="deletePost({{ $post->id }})"
                                        class="text-red-600 hover:text-red-800 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $post->title }}</h4>
                            <div class="prose max-w-none text-gray-600 mb-4">
                                {!! nl2br(e($post->content)) !!}
                            </div>

                            @if ($post->images)
                                @php
                                    $images = is_array($post->images) ? $post->images : json_decode($post->images);
                                @endphp
                                @if (count($images) > 0)
                                    <div class="grid grid-cols-4 gap-4 mt-4">
                                        @foreach ($images as $image)
                                            <div class="aspect-square">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Post image"
                                                    class="w-full h-full object-cover rounded-lg">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif

                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center text-gray-500">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        {{ $post->likes_count }}
                                    </span>
                                </div>
                                <span
                                    class="px-3 py-1 text-sm rounded-full {{ $post->is_approved === true ? 'bg-green-100 text-green-800' : ($post->is_approved === null ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $post->is_approved === true ? 'Đã duyệt' : ($post->is_approved === null ? 'Đang chờ duyệt' : 'Bị từ chối') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bài viết nào</h3>
                    <p class="mt-1 text-sm text-gray-500">Bắt đầu chia sẻ trải nghiệm của bạn về các địa điểm.</p>
                    <div class="mt-6">
                        <a href="{{ route('destinations.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Khám phá địa điểm
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Post Modal -->
<div id="deletePostModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Xóa bài viết
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Bạn có chắc chắn muốn xóa bài viết này? Hành động này không thể hoàn tác.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="confirmDeletePost()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Xóa
                </button>
                <button type="button" onclick="closeDeletePostModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Hủy
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let currentPostId = null;

        function deletePost(postId) {
            currentPostId = postId;
            document.getElementById('deletePostModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeletePostModal() {
            document.getElementById('deletePostModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentPostId = null;
        }

        function confirmDeletePost() {
            if (!currentPostId) return;

            fetch(`/posts/${currentPostId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Có lỗi xảy ra khi xóa bài viết');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        closeDeletePostModal();
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Có lỗi xảy ra khi xóa bài viết');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Có lỗi xảy ra khi xóa bài viết');
                    closeDeletePostModal();
                });
        }

        // Close modal when clicking outside
        document.getElementById('deletePostModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeletePostModal();
            }
        });

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeletePostModal();
            }
        });

        function filterByDestination(destinationId) {
            const url = new URL(window.location.href);
            if (destinationId) {
                url.searchParams.set('destination_id', destinationId);
            } else {
                url.searchParams.delete('destination_id');
            }
            window.location.href = url.toString();
        }
    </script>
@endpush
