<div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
    <!-- Total Projects -->
    <div class="rounded-xl border border-gray-600 bg-gray-800 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-300">Total Projects</p>
                <p class="text-2xl font-bold text-white">{{ $totalProjects }}</p>
            </div>
            <div class="rounded-lg bg-blue-600/20 p-3">
                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Tasks -->
    <div class="rounded-xl border border-gray-600 bg-gray-800 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-300">Active Tasks</p>
                <p class="text-2xl font-bold text-white">{{ $activeTasks }}</p>
            </div>
            <div class="rounded-lg bg-green-600/20 p-3">
                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Materials Needed -->
    <div class="rounded-xl border border-gray-600 bg-gray-800 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-300">Materials Needed</p>
                <p class="text-2xl font-bold text-white">{{ $materialsNeeded }}</p>
            </div>
            <div class="rounded-lg bg-yellow-600/20 p-3">
                <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Completed This Week -->
    <div class="rounded-xl border border-gray-600 bg-gray-800 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-300">Completed This Week</p>
                <p class="text-2xl font-bold text-white">{{ $completedThisWeek }}</p>
            </div>
            <div class="rounded-lg bg-purple-600/20 p-3">
                <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
