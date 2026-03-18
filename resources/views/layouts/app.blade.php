<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', __('messages.app_name')) }} - @yield('title', __('messages.nav.dashboard'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|space+grotesk:500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Dark mode styles */
        .dark body { background-color: #111827; }
        .dark .bg-white { background-color: #1f2937; }
        .dark .bg-gray-50 { background-color: #374151; }
        .dark .bg-gray-100 { background-color: #111827; }
        .dark .text-gray-900, .dark .text-gray-800, .dark .text-gray-700 { color: #f3f4f6; }
        .dark .text-gray-600, .dark .text-gray-500 { color: #d1d5db; }
        .dark .text-gray-400 { color: #9ca3af; }
        .dark .border-gray-200, .dark .border-gray-300 { border-color: #374151; }
        .dark .divide-gray-200 > :not([hidden]) ~ :not([hidden]) { border-color: #374151; }
        .dark .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.4); }
        .dark .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3); }
        .dark .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4); }
        .dark input, .dark select, .dark textarea {
            background-color: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }
        .dark input:focus, .dark select:focus, .dark textarea:focus {
            border-color: #14b8a6;
        }
        .dark .hover\:bg-gray-100:hover { background-color: #374151; }
        .dark .hover\:bg-gray-50:hover { background-color: #374151; }
        .dark .bg-indigo-50 { background-color: rgba(20, 184, 166, 0.15); }
    </style>

    @stack('styles')
</head>
<body class="app-shell font-sans antialiased" x-data="{ sidebarOpen: false }" :class="{ 'dark': darkMode }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 gradient-sidebar transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 sidebar-scroll overflow-y-auto"
               :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            <!-- Logo Area -->
            <div class="flex items-center justify-center h-16 px-4 relative">
                <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-sky-500/20"></div>
                <div class="relative flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-sky-600 flex items-center justify-center shadow-lg glow-indigo">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="font-display text-xl font-bold text-white tracking-wide">QLTHB</span>
                </div>
            </div>

            <nav class="mt-6 px-3 space-y-1">
                <a href="{{ route('dashboard') }}"
                   class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="p-1.5 rounded-lg bg-gradient-to-br from-sky-500/20 to-teal-500/20 mr-3">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('equipment.index') }}"
                   class="sidebar-nav-item {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <div class="p-1.5 rounded-lg bg-gradient-to-br from-emerald-500/20 to-teal-500/20 mr-3">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="font-medium">{{ __('messages.nav.equipment') }}</span>
                </a>

                <a href="{{ route('borrow.index') }}"
                   class="sidebar-nav-item {{ request()->routeIs('borrow.*') ? 'active' : '' }}">
                    <div class="p-1.5 rounded-lg bg-gradient-to-br from-blue-500/20 to-cyan-500/20 mr-3">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="font-medium">{{ __('messages.nav.borrow') }}</span>
                </a>

                <a href="{{ route('reservations.index') }}"
                   class="sidebar-nav-item {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                    <div class="p-1.5 rounded-lg bg-gradient-to-br from-amber-500/20 to-orange-500/20 mr-3">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">{{ __('messages.nav.reservations') }}</span>
                </a>

                <a href="{{ route('ai.chat') }}"
                   class="sidebar-nav-item {{ request()->routeIs('ai.*') ? 'active' : '' }}">
                    <div class="p-1.5 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 mr-3">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <span class="font-medium">{{ __('messages.nav.ai_assistant') }}</span>
                </a>

                <a href="{{ route('teaching-plans.index') }}"
                   class="sidebar-nav-item {{ request()->routeIs('teaching-plans.*') ? 'active' : '' }}">
                    <div class="p-1.5 rounded-lg bg-gradient-to-br from-rose-500/20 to-red-500/20 mr-3">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">{{ __('messages.nav.teaching_plans') }}</span>
                </a>

                @if(auth()->user()->isAdmin())
                <div class="mt-8 pt-6 border-t border-white/10">
                    <div class="px-4 mb-3">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center">
                            <span class="w-8 h-px bg-gradient-to-r from-teal-400 to-sky-400 mr-2"></span>
                            {{ __('messages.nav.admin') }}
                        </span>
                    </div>

                    <a href="{{ route('admin.rooms.index') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-cyan-500/20 to-teal-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.rooms') }}</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-sky-500/20 to-cyan-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.users') }}</span>
                    </a>

                    <a href="{{ route('admin.departments.index') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-fuchsia-500/20 to-pink-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.departments') }}</span>
                    </a>

                    <a href="{{ route('admin.inventory.index') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-lime-500/20 to-green-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.inventory') }}</span>
                    </a>

                    <a href="{{ route('admin.approvals.index') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.approvals.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-emerald-500/20 to-green-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.approvals') }}</span>
                    </a>

                    <a href="{{ route('admin.reports.index') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-sky-500/20 to-blue-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.reports') }}</span>
                    </a>

                    <a href="{{ route('admin.activity-logs.index') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-teal-500/20 to-cyan-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.activity_logs') }}</span>
                    </a>

                    <a href="{{ route('admin.maintenance.index') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.maintenance.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-amber-500/20 to-yellow-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.maintenance') }}</span>
                    </a>

                    <a href="{{ route('admin.import.equipment.form') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.import.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-teal-500/20 to-cyan-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.import') }}</span>
                    </a>

                    <a href="{{ route('admin.damage-reports.index') }}"
                       class="sidebar-nav-item {{ request()->routeIs('admin.damage-reports.*') ? 'active' : '' }}">
                        <div class="p-1.5 rounded-lg bg-gradient-to-br from-red-500/20 to-rose-500/20 mr-3">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ __('messages.nav.damage_reports') }}</span>
                    </a>
                </div>
                @endif
            </nav>

            <!-- Sidebar Footer -->
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-slate-900/50 to-transparent">
                <div class="flex items-center space-x-3 px-3 py-2">
                    <div class="avatar-ring">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-500 to-sky-600 flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Header -->
            <header class="glass-header sticky top-0 z-30">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <h1 class="font-display text-xl font-bold text-gray-800 dark:text-white tracking-tight">@yield('title', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center space-x-3">
                        <!-- Global Search -->
                        <form action="{{ route('search') }}" method="GET" class="hidden md:block">
                            <div class="relative group">
                                <input type="text" name="q" placeholder="{{ __('messages.search') }}"
                                       value="{{ request('q') }}"
                                       class="w-64 pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:border-teal-500 transition-all duration-200 group-hover:bg-white dark:group-hover:bg-gray-700">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </form>

                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                                class="relative p-2.5 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition-all duration-200"
                                :title="darkMode ? '{{ __('messages.theme.light_mode') }}' : '{{ __('messages.theme.dark_mode') }}'">
                            <!-- Sun icon (shown in dark mode) -->
                            <svg x-show="darkMode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 rotate-90" x-transition:enter-end="opacity-100 rotate-0" class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <!-- Moon icon (shown in light mode) -->
                            <svg x-show="!darkMode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -rotate-90" x-transition:enter-end="opacity-100 rotate-0" class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>

                        <!-- Notification Bell -->
                        <div x-data="{ open: false, count: 0 }" x-init="
                            fetch('{{ route('notifications.unread-count') }}')
                                .then(r => r.json())
                                .then(d => count = d.count);
                            setInterval(() => {
                                fetch('{{ route('notifications.unread-count') }}')
                                    .then(r => r.json())
                                    .then(d => count = d.count);
                            }, 30000);
                        " class="relative">
                            <a href="{{ route('notifications.index') }}" class="relative p-2.5 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 inline-flex">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span x-show="count > 0" x-text="count > 9 ? '9+' : count"
                                      x-transition:enter="transition ease-out duration-200"
                                      x-transition:enter-start="transform scale-0"
                                      x-transition:enter-end="transform scale-100"
                                      class="notification-badge"></span>
                            </a>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-3 pl-3 border-l border-gray-200 dark:border-gray-700">
                            <a href="{{ route('profile.show') }}" class="flex items-center space-x-2 hover:opacity-80 transition-opacity">
                                <div class="avatar-ring">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-500 to-sky-600 flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">{{ auth()->user()->name }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200" title="{{ __('messages.auth.logout') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Impersonation Banner -->
            @if(session('impersonator_id'))
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 shadow-lg">
                <div class="flex items-center justify-between max-w-7xl mx-auto">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="font-medium">{{ __('messages.user.impersonating', ['name' => auth()->user()->name]) }}</span>
                    </div>
                    <form action="{{ route('admin.impersonate.stop') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 hover:scale-105">
                            {{ __('messages.user.stop_impersonate') }}
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6">
                <div class="animate-fade-in mx-auto w-full max-w-[1500px]">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Toast Notifications -->
        <div x-data="toastNotifications()" x-init="init()" class="fixed top-4 right-4 z-50 space-y-3">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast.visible"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-8 scale-95"
                     x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 transform translate-x-8 scale-95"
                     class="flex items-center p-4 w-80 rounded-xl shadow-lg backdrop-blur-sm border"
                     :class="{
                         'bg-emerald-50/90 border-emerald-200 dark:bg-emerald-900/50 dark:border-emerald-700': toast.type === 'success',
                         'bg-rose-50/90 border-rose-200 dark:bg-rose-900/50 dark:border-rose-700': toast.type === 'error',
                         'bg-blue-50/90 border-blue-200 dark:bg-blue-900/50 dark:border-blue-700': toast.type === 'info',
                         'bg-amber-50/90 border-amber-200 dark:bg-amber-900/50 dark:border-amber-700': toast.type === 'warning'
                     }">
                    <div class="flex-shrink-0">
                        <template x-if="toast.type === 'success'">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <div class="w-8 h-8 rounded-full bg-rose-100 dark:bg-rose-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'info'">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                        </template>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium"
                           :class="{
                               'text-emerald-800 dark:text-emerald-200': toast.type === 'success',
                               'text-rose-800 dark:text-rose-200': toast.type === 'error',
                               'text-blue-800 dark:text-blue-200': toast.type === 'info',
                               'text-amber-800 dark:text-amber-200': toast.type === 'warning'
                           }"
                           x-text="toast.message"></p>
                    </div>
                    <button @click="removeToast(toast.id)" class="ml-4 flex-shrink-0 rounded-lg p-1 transition-colors"
                            :class="{
                                'text-emerald-400 hover:text-emerald-600 hover:bg-emerald-100': toast.type === 'success',
                                'text-rose-400 hover:text-rose-600 hover:bg-rose-100': toast.type === 'error',
                                'text-blue-400 hover:text-blue-600 hover:bg-blue-100': toast.type === 'info',
                                'text-amber-400 hover:text-amber-600 hover:bg-amber-100': toast.type === 'warning'
                            }">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <script>
            function toastNotifications() {
                return {
                    toasts: [],
                    init() {
                        @if(session('success'))
                            this.addToast('success', @json(session('success')));
                        @endif
                        @if(session('error'))
                            this.addToast('error', @json(session('error')));
                        @endif
                        @if(session('info'))
                            this.addToast('info', @json(session('info')));
                        @endif
                        @if(session('warning'))
                            this.addToast('warning', @json(session('warning')));
                        @endif
                    },
                    addToast(type, message) {
                        const id = Date.now();
                        this.toasts.push({ id, type, message, visible: true });
                        setTimeout(() => this.removeToast(id), 5000);
                    },
                    removeToast(id) {
                        const toast = this.toasts.find(t => t.id === id);
                        if (toast) {
                            toast.visible = false;
                            setTimeout(() => {
                                this.toasts = this.toasts.filter(t => t.id !== id);
                            }, 300);
                        }
                    }
                }
            }
        </script>
    </div>

    @stack('scripts')
</body>
</html>
