<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo UIN & Brand -->
                <div class="shrink-0 flex items-center gap-2.5">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <img src="/images/logo-uin.png" alt="Logo UIN SGD" class="h-10 w-auto object-contain">
                        <span class="font-extrabold text-gray-900 tracking-tight hidden md:inline-block">SmartBin IoT UIN</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-6 sm:flex items-center">
                    <x-nav-link :href="route('public.monitor')" :active="request()->routeIs('public.monitor*') || request()->routeIs('home')">
                        {{ __('Live Monitoring') }}
                    </x-nav-link>
                    <x-nav-link :href="route('public.education')" :active="request()->routeIs('public.education')">
                        <span class="flex items-center gap-1 text-emerald-700 font-bold">
                            🌱 {{ __('Edukasi Ekoteologi') }}
                        </span>
                    </x-nav-link>
                    <x-nav-link :href="route('auto.sort')" :active="request()->routeIs('auto.sort')">
                        <span class="flex items-center gap-1.5 font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ __('Pemilah Otomatis') }}
                        </span>
                    </x-nav-link>
                    <x-nav-link :href="route('user.ai.scanner')" :active="request()->routeIs('user.ai.scanner')">
                        {{ __('📷 Scan AI') }}
                    </x-nav-link>
                    <x-nav-link :href="route('public.locations')" :active="request()->routeIs('public.locations')">
                        {{ __('📍 Peta Lokasi') }}
                    </x-nav-link>
                    <x-nav-link :href="route('public.leaderboard')" :active="request()->routeIs('public.leaderboard')">
                        {{ __('🏆 Leaderboard') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('servos.index')" :active="request()->routeIs('servos.*')">
                            {{ __('Kontrol Servo') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings / Login Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-lg text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('user.my.deposits')">
                                {{ __('Riwayat Setor & Poin') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="text-xs font-semibold px-3 py-2 text-slate-700 hover:text-emerald-700 rounded-lg hover:bg-slate-50 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-xs font-bold px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow-sm transition">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('public.monitor')" :active="request()->routeIs('public.monitor')">
                {{ __('Live Monitoring') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('public.education')" :active="request()->routeIs('public.education')">
                🌱 {{ __('Edukasi Ekoteologi') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('auto.sort')" :active="request()->routeIs('auto.sort')">
                {{ __('Auto-Sort (AI)') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('user.ai.scanner')" :active="request()->routeIs('user.ai.scanner')">
                {{ __('📷 Scan AI') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('public.locations')" :active="request()->routeIs('public.locations')">
                {{ __('📍 Peta Lokasi') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('public.leaderboard')" :active="request()->routeIs('public.leaderboard')">
                {{ __('🏆 Leaderboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('user.my.deposits')">
                        {{ __('Riwayat Setor & Poin') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 py-2 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="text-center w-full py-2 bg-gray-100 rounded-lg text-sm font-semibold text-gray-700">Masuk</a>
                    <a href="{{ route('register') }}" class="text-center w-full py-2 bg-emerald-600 rounded-lg text-sm font-bold text-white">Daftar Akun</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
