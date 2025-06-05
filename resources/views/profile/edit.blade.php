<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button onclick="switchTab('profile-tab')" class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" id="profile-tab-button">
                                Thông tin cá nhân
                            </button>
                            <button onclick="switchTab('password-tab')" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" id="password-tab-button">
                                Cập nhật mật khẩu
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="mt-6">
                        <div id="profile-tab" class="tab-content">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                        <div id="password-tab" class="tab-content hidden">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active state from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-indigo-500', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabId).classList.remove('hidden');
            
            // Set active state for selected tab button
            document.getElementById(tabId + '-button').classList.remove('border-transparent', 'text-gray-500');
            document.getElementById(tabId + '-button').classList.add('border-indigo-500', 'text-indigo-600');
        }
    </script>
</x-app-layout>
