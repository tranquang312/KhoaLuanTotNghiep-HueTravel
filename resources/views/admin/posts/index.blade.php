<x-admin-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Flash Messages -->
                    <x-flash-message type="success" />
                    <x-flash-message type="error" />
                    <x-flash-message type="warning" />
                    <x-flash-message type="info" />

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Quản lý bài viết</h2>
                        
                    </div>

                    <!-- Filter Bar -->
                    <div class="mb-6">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.posts.index') }}" 
                               class="px-4 py-2 rounded-md {{ !request('status') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                Tất cả
                            </a>
                            <a href="{{ route('admin.posts.index', ['status' => 'pending']) }}" 
                               class="px-4 py-2 rounded-md {{ request('status') === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                Chờ duyệt
                            </a>
                            <a href="{{ route('admin.posts.index', ['status' => 'approved']) }}" 
                               class="px-4 py-2 rounded-md {{ request('status') === 'approved' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                Đã duyệt
                            </a>
                            <a href="{{ route('admin.posts.index', ['status' => 'rejected']) }}" 
                               class="px-4 py-2 rounded-md {{ request('status') === 'rejected' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                Đã từ chối
                            </a>
                        </div>
                    </div>

                    @if ($posts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Người đăng
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Điểm đến
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tiêu đề
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ngày đăng
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Trạng thái
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($posts as $post)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        @if ($post->user->avatar)
                                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                                src="{{ asset('storage/' . $post->user->avatar) }}" 
                                                                alt="{{ $post->user->name }}">
                                                        @else
                                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                                src="{{ asset('storage/avatars/default_avatar.png') }}" 
                                                                alt="{{ $post->user->name }}">
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $post->user->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if ($post->destination->images->first())
                                                        <img src="{{ asset('storage/' . $post->destination->images->first()->image_path) }}"
                                                            alt="{{ $post->destination->name }}"
                                                            class="w-8 h-8 rounded-lg object-cover">
                                                    @else
                                                        <img src="{{ asset('images/default_destination.jpg') }}"
                                                            alt="{{ $post->destination->name }}"
                                                            class="w-8 h-8 rounded-lg object-cover">
                                                    @endif
                                                    <span class="ml-2 text-sm text-gray-900">{{ $post->destination->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ Str::limit($post->title, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $post->created_at->format('d/m/Y H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($post->is_approved === null)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Chờ duyệt
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $post->is_approved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $post->is_approved ? 'Đã duyệt' : 'Đã từ chối' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.posts.show', $post) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    Xem chi tiết
                                                </a>
                                                @if ($post->is_approved === null)
                                                    <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" 
                                                            class="text-green-600 hover:text-green-900 mr-3"
                                                            onclick="return confirm('Bạn có chắc chắn muốn duyệt bài viết này?')">
                                                            Duyệt
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.posts.reject', $post) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Bạn có chắc chắn muốn từ chối bài viết này?')">
                                                            Từ chối
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $posts->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bài viết nào</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Xóa các hàm JavaScript cũ không cần thiết nữa
        </script>
    @endpush
</x-admin-layout>
