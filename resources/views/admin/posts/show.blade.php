<x-admin-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Chi tiết bài viết</h2>
                        <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Quay lại
                        </a>
                    </div>
                    
                   
                    <div class="space-y-6">
                        <!-- User Info -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                @if ($post->user->avatar)
                                    <img class="h-12 w-12 rounded-full object-cover" 
                                        src="{{ asset('storage/' . $post->user->avatar) }}" 
                                        alt="{{ $post->user->name }}">
                                @else
                                    <img class="h-12 w-12 rounded-full object-cover" 
                                        src="{{ asset('storage/avatars/default_avatar.png') }}" 
                                        alt="{{ $post->user->name }}">
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $post->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $post->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Destination Info -->
                        <div class="flex items-center">
                            @if ($post->destination->images->first())
                                <img src="{{ asset('storage/' . $post->destination->images->first()->image_path) }}"
                                    alt="{{ $post->destination->name }}"
                                    class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <img src="{{ asset('images/default_destination.jpg') }}"
                                    alt="{{ $post->destination->name }}"
                                    class="w-10 h-10 rounded-lg object-cover">
                            @endif
                            <span class="ml-2 text-lg font-medium text-gray-900">{{ $post->destination->name }}</span>
                        </div>

                        <!-- Post Content -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $post->title }}</h2>
                            <div class="prose max-w-none">
                                {!! nl2br(e($post->content)) !!}
                            </div>
                        </div>

                        <!-- Post Images -->
                        @if ($post->images)
                            @php
                                $images = is_array($post->images) ? $post->images : json_decode($post->images);
                            @endphp
                            @if (count($images) > 0)
                                <div class="grid grid-cols-4 gap-4">
                                    @foreach ($images as $image)
                                        <div class="aspect-square">
                                            <img src="{{ asset('storage/' . $image) }}" 
                                                alt="Post image"
                                                class="w-full h-full object-cover rounded-lg">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        <!-- Status Update -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Cập nhật trạng thái</h3>
                            <form action="{{ route('admin.posts.update-status', $post) }}" method="POST" class="flex items-center space-x-4">
                                @csrf
                                @method('PUT')
                                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="approved" {{ $post->is_approved === true ? 'selected' : '' }}>Đã duyệt</option>
                                    <option value="rejected" {{ $post->is_approved === false ? 'selected' : '' }}>Đã từ chối</option>
                                </select>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Cập nhật
                                </button>
                            </form>
                        </div>

                        <!-- Current Status -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Trạng thái hiện tại</h3>
                            <div class="flex items-center">
                                @if ($post->is_approved === null)
                                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                                        Chờ duyệt
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm rounded-full {{ $post->is_approved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $post->is_approved ? 'Đã duyệt' : 'Đã từ chối' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 