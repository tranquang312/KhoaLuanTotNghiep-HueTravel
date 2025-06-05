<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard - HueTravel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transition-transform duration-300 transform"
            id="sidebar">
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <span class="text-xl font-semibold">Admin Panel</span>
            </div>
            <nav class="mt-5">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Dashboard
                </a>
                @can('view destinations')
                <a href="{{ route('admin.destinations.index') }}"
                    class="flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.destinations.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Quản lý Điểm đến
                </a>
                @endcan
                @can('view tours')
                <a href="{{ route('admin.tours.index') }}"
                    class="flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.tours.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Quản lý Tour
                </a>
                @endcan
                @can('manage-bookings')
                <!-- Quản lý đặt tour -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-700' : '' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <span>Quản lý đặt tour</span>
                        </span>
                        <svg class="w-4 h-4" :class="{ 'transform rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" class="pl-4">
                        <div class="space-y-1">
                            <a href="{{ route('admin.bookings.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <i class="fas fa-calendar-check mr-4 text-gray-400 group-hover:text-gray-300"></i>
                                Đơn đặt tour
                            </a>
                        </div>
                    </div>
                </div>
                @endcan
                <!-- Tour Departures -->
                <a href="{{ route('admin.tour-departures.index') }}"
                    class="flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.tour-departures.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    Quản lý chuyến đi
                </a>
                @can('manage-posts')
                <a href="{{ route('admin.posts.index') }}"
                    class="flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.posts.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </svg>
                    Quản lý bài viết
                </a>
                @endcan
                @can('manage-reviews')
                <a href="{{ route('admin.reviews.index') }}"
                    class="flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    Quản lý Đánh giá
                </a>
                @endcan
                @can('manage-users')
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    Quản lý Người dùng
                </a>
                @endcan

                @can('manage-roles')
                <a href="{{ route('admin.roles.index') }}"
                    class="flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                    Quản lý Vai trò
                </a>
                @endcan

                @can('manage-permissions')
                <a href="{{ route('admin.permissions.index') }}"
                    class="flex items-center px-6 py-3 text-gray-100 hover:bg-gray-700 {{ request()->routeIs('admin.permissions.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                    Quản lý Quyền hạn
                </a>
                @endcan                
            </nav>
        </div>

        <!-- Main Content -->
        <div class="ml-64">
            <!-- Top Navigation -->
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Toggle Sidebar Button -->
                            <button type="button"
                                class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600"
                                id="sidebarToggle">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center">
                            <!-- Profile Dropdown -->
                            <div class="ml-3 relative">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                            <div>{{ Auth::user()->name }}</div>
                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Flash Messages -->
            <x-flash-message type="success" />
            <x-flash-message type="error" />
            <x-flash-message type="warning" />
            <x-flash-message type="info" />

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        // Toggle Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.ml-64, .ml-0');

            sidebar.classList.toggle('-translate-x-full');

            if (sidebar.classList.contains('-translate-x-full')) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
            } else {
                mainContent.classList.remove('ml-0');
                mainContent.classList.add('ml-64');
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
