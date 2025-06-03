<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.tours.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Tên tour')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Mô tả')" />
                            <textarea id="description" name="description" class="mt-1 block w-full">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Giá')" />
                            <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="itinerary" :value="__('Lịch trình')" />
                            <textarea id="itinerary" name="itinerary" class="mt-1 block w-full">{{ old('itinerary') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('itinerary')" />
                        </div>

                        <div>
                            <x-input-label for="destinations" :value="__('Điểm đến')" />
                            <select id="destinations" name="destinations[]" class="mt-1 block w-full" multiple required>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}" {{ in_array($destination->id, old('destinations', [])) ? 'selected' : '' }}>
                                        {{ $destination->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('destinations')" />
                        </div>

                        <div>
                            <x-input-label for="images" :value="__('Hình ảnh (có thể chọn nhiều)')" />
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="mt-2 block w-full" onchange="previewImages(this)">
                            <div id="imagesPreview" class="mt-2 flex flex-wrap gap-2"></div>
                            <x-input-error class="mt-2" :messages="$errors->get('images')" />
                            <x-input-error class="mt-2" :messages="$errors->get('images.*')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Lưu') }}</x-primary-button>
                            <a href="{{ route('admin.tours.index') }}" 
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

            ClassicEditor
                .create(document.querySelector('#itinerary'))
                .catch(error => {
                    console.error(error);
                });

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
