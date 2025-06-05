<x-app-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-semibold text-gray-900 mb-6">Đánh giá chuyến đi {{ $booking->tour->name }}</h1>

                <form action="{{ route('tour.submit-review', $booking) }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="rating">
                            Đánh giá của bạn
                        </label>
                        <div class="flex items-center space-x-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                    <svg class="w-8 h-8 text-gray-300 peer-checked:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="comment">
                            Nhận xét của bạn
                        </label>
                        <textarea
                            name="comment"
                            id="comment"
                            rows="4"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Hãy chia sẻ trải nghiệm của bạn về chuyến đi..."
                            required
                        ></textarea>
                        @error('comment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Gửi đánh giá
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add hover effect for star rating
        document.querySelectorAll('input[name="rating"]').forEach((input, index) => {
            input.addEventListener('change', function() {
                const stars = document.querySelectorAll('input[name="rating"]');
                stars.forEach((star, i) => {
                    const svg = star.nextElementSibling;
                    if (i <= index) {
                        svg.classList.add('text-yellow-400');
                        svg.classList.remove('text-gray-300');
                    } else {
                        svg.classList.add('text-gray-300');
                        svg.classList.remove('text-yellow-400');
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 