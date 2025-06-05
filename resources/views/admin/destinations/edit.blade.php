<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa điểm đến') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Chỉnh sửa điểm đến</h2>
                        <a href="{{ route('admin.destinations.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Quay lại
                        </a>
                    </div>
                    <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Tên điểm đến')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $destination->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Mô tả')" />
                            <textarea id="description" name="description" class="mt-1 block w-full">{{ old('description', $destination->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('Địa chỉ')" />
                            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $destination->address)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        <div>
                            <x-input-label for="images" :value="__('Hình ảnh hiện tại')" />
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($destination->images as $image)
                                    <div class="relative" data-image-id="{{ $image->id }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="" class="max-w-[120px] max-h-[120px] rounded border">
                                        <button type="button" onclick="deleteImage({{ $image->id }}, this)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center">&times;</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <x-input-label for="new_images" :value="__('Thêm hình ảnh mới (có thể chọn nhiều)')" />
                            <input type="file" id="new_images" name="new_images[]" multiple accept="image/*" class="mt-2 block w-full" onchange="previewImages(this)">
                            <div id="imagesPreview" class="mt-2 flex flex-wrap gap-2"></div>
                            <x-input-error class="mt-2" :messages="$errors->get('new_images')" />
                            <x-input-error class="mt-2" :messages="$errors->get('new_images.*')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Lưu') }}</x-primary-button>
                            <a href="{{ route('admin.destinations.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Hủy') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });

            function deleteImage(imageId, button) {
                if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                    fetch(`/admin/destinations/delete-image/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.parentElement.remove();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            }

            function previewImages(input) {
                const preview = document.getElementById('imagesPreview');
                preview.innerHTML = '';

                if (input.files) {
                    const files = Array.from(input.files);
                    const fileMap = new Map();

                    files.forEach((file, index) => {
                        fileMap.set(index, file);

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgContainer = document.createElement('div');
                            imgContainer.className = 'relative';
                            imgContainer.dataset.index = index;

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'max-w-[120px] max-h-[120px] rounded border';

                            const removeBtn = document.createElement('button');
                            removeBtn.innerHTML = '&times;';
                            removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center';
                            removeBtn.onclick = function(e) {
                                e.preventDefault();
                                const index = parseInt(imgContainer.dataset.index);
                                fileMap.delete(index);
                                imgContainer.remove();
                                const dt = new DataTransfer();
                                Array.from(fileMap.values()).forEach(file => {
                                    dt.items.add(file);
                                });
                                input.files = dt.files;
                            };

                            imgContainer.appendChild(img);
                            imgContainer.appendChild(removeBtn);
                            preview.appendChild(imgContainer);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            }
        </script>
    @endpush
</x-admin-layout>
