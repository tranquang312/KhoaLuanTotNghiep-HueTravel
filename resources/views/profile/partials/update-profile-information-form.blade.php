<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Thông tin cá nhân
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Cập nhật thông tin cá nhân và địa chỉ email của bạn.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Avatar Section -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    <div class="relative">
                        <div id="avatar-preview" class="h-24 w-24 rounded-full overflow-hidden bg-gray-100">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Ảnh đại diện" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <span class="text-2xl text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <label for="avatar" class="absolute bottom-0 right-0 bg-white rounded-full p-1.5 shadow-lg cursor-pointer hover:bg-gray-50">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </label>
                        <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900">Ảnh đại diện</h3>
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG hoặc GIF (TỐI ĐA 2MB)</p>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>
            </div>
        </div>

        <!-- Name and Email Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="'Họ và tên'" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="'Email'" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            Địa chỉ email của bạn chưa được xác minh.

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Nhấn vào đây để gửi lại email xác minh.
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Bio Section -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <x-input-label for="bio" :value="'Giới thiệu bản thân'" />
            <textarea
                id="bio"
                name="bio"
                rows="4"
                class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Hãy chia sẻ đôi nét về bản thân bạn..."
            >{{ old('bio', $user->bio) }}</textarea>
            <p class="mt-1 text-sm text-gray-500">Chia sẻ một vài thông tin về bạn với mọi người.</p>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Lưu thay đổi</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Đã lưu.</p>
            @endif
        </div>
    </form>
</section>

<script>
function previewImage(input) {
    const preview = document.getElementById('avatar-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-cover" alt="Xem trước">`;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
