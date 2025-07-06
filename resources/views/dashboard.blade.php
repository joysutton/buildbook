<x-layouts.app :title="__('Dashboard')">
    <div class="min-h-screen bg-gray-900">
        <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
            <!-- Welcome Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Welcome back, {{ auth()->user()->username }}!
                    </h1>
                    <p class="text-gray-300">
                        Let's get sewing! Here's what's happening with your projects.
                    </p>
                </div>
                <div class="flex gap-3">
                    <x-button href="{{ route('projects.create') }}" icon="plus" variant="primary">
                        New Project
                    </x-button>
                </div>
            </div>

        <!-- Stats Overview -->
        <livewire:dashboard-stats />

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Quick Actions -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="rounded-xl border border-gray-600 bg-gray-800">
                    <div class="border-b border-gray-600 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">Quick Actions</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('projects.create') }}" class="flex items-center gap-3 rounded-lg border border-gray-600 p-3 transition-colors hover:bg-gray-700">
                                <div class="rounded-lg bg-blue-600/20 p-2">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-white">New Project</p>
                                    <p class="text-sm text-gray-400">Start a new sewing project</p>
                                </div>
                            </a>

                            <a href="{{ route('projects.index') }}" class="flex items-center gap-3 rounded-lg border border-gray-600 p-3 transition-colors hover:bg-gray-700">
                                <div class="rounded-lg bg-green-600/20 p-2">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-white">View Projects</p>
                                    <p class="text-sm text-gray-400">Manage your sewing projects</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>


</x-layouts.app>
