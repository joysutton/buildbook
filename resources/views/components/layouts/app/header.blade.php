<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gray-900">
        <flux:header container class="border-b border-gray-600 bg-gray-800">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('dashboard') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:tooltip :content="__('Search')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                </flux:tooltip>
            </flux:navbar>

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile
                    class="cursor-pointer"
                    :initials="auth()->user()->initials()"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-gray-600 text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->username }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-e border-gray-600 bg-gray-800">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Sewing Projects')">
                    <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="folder-git-2" :href="route('projects.index')" :current="request()->routeIs('projects.*')" wire:navigate>{{ __('Projects') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <!-- Recent Projects -->
            <div class="px-3 py-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Recent Projects</h3>
                <div id="mobile-recent-projects" class="space-y-1">
                    <!-- Projects will be loaded here -->
                    <div class="text-center py-2">
                        <p class="text-xs text-gray-500">Loading...</p>
                    </div>
                </div>
            </div>

            <flux:spacer />
        </flux:sidebar>

        {{ $slot }}

        <script>
            // Load recent projects for mobile sidebar
            document.addEventListener('DOMContentLoaded', function() {
                loadMobileSidebarProjects();
            });

            function loadMobileSidebarProjects() {
                console.log('Loading mobile sidebar projects...');
                fetch('/api/recent-projects?limit=5', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Mobile response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(response => {
                    console.log('Mobile projects response:', response);
                    const projects = response.data || [];
                    const container = document.getElementById('mobile-recent-projects');
                    
                    if (projects.length === 0) {
                        container.innerHTML = '<div class="text-center py-2"><p class="text-xs text-gray-500">No projects yet</p></div>';
                        return;
                    }
                    
                    container.innerHTML = projects.map(project => `
                        <a href="/projects/${project.id}" 
                           class="block px-2 py-1.5 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded transition-colors ${window.location.pathname === '/projects/${project.id}' ? 'bg-gray-700 text-white' : ''}"
                           wire:navigate>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-400 flex-shrink-0"></div>
                                <span class="truncate">${project.name}</span>
                            </div>
                        </a>
                    `).join('');
                })
                .catch(error => {
                    console.error('Error loading mobile sidebar projects:', error);
                    const container = document.getElementById('mobile-recent-projects');
                    container.innerHTML = '<div class="text-center py-2"><p class="text-xs text-gray-500">Error loading projects</p></div>';
                });
            }
        </script>

        @fluxScripts
    </body>
</html>
