<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Welcome Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
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
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total Projects -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Projects</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-projects">-</p>
                    </div>
                    <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/20">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Tasks -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Tasks</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="active-tasks">-</p>
                    </div>
                    <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/20">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Materials Needed -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Materials Needed</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="materials-needed">-</p>
                    </div>
                    <div class="rounded-lg bg-yellow-100 p-3 dark:bg-yellow-900/20">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed This Week -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed This Week</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="completed-this-week">-</p>
                    </div>
                    <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900/20">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Recent Projects -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Projects</h2>
                            <a href="{{ route('projects.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="recent-projects" class="space-y-4">
                            <!-- Projects will be loaded here -->
                            <div class="flex items-center justify-center py-8">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No projects yet</p>
                                    <a href="{{ route('projects.create') }}" class="mt-2 text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                        Create your first project
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Tasks -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('projects.create') }}" class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                                <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/20">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">New Project</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Start a new sewing project</p>
                                </div>
                            </a>

                            <a href="{{ route('projects.index') }}" class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                                <div class="rounded-lg bg-green-100 p-2 dark:bg-green-900/20">
                                    <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">View Projects</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage your sewing projects</p>
                                </div>
                            </a>

                            <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                                <div class="rounded-lg bg-purple-100 p-2 dark:bg-purple-900/20">
                                    <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">View Tasks</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">See all your project tasks</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Tasks -->
                <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Tasks</h2>
                            <a href="{{ route('tasks.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="recent-tasks" class="space-y-3">
                            <!-- Tasks will be loaded here -->
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No recent tasks</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dashboard data loading
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
        });

        function loadDashboardData() {
            // Load stats
            fetch('/api/dashboard/stats', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-projects').textContent = data.total_projects || 0;
                document.getElementById('active-tasks').textContent = data.active_tasks || 0;
                document.getElementById('materials-needed').textContent = data.materials_needed || 0;
                document.getElementById('completed-this-week').textContent = data.completed_this_week || 0;
            })
            .catch(error => {
                console.error('Error loading dashboard stats:', error);
            });

            // Load recent projects
            fetch('/api/projects?limit=5', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(response => {
                const projects = response.data || [];
                const container = document.getElementById('recent-projects');
                if (projects.length === 0) {
                    return; // Keep the default "no projects" message
                }
                
                container.innerHTML = projects.map(project => `
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center dark:bg-blue-900/20">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">${project.name}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">${project.tasks?.length || 0} tasks</p>
                            </div>
                        </div>
                        <a href="/projects/${project.id}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            View
                        </a>
                    </div>
                `).join('');
            })
            .catch(error => {
                console.error('Error loading recent projects:', error);
            });

            // Load recent tasks
            fetch('/api/tasks?limit=5', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(tasks => {
                const container = document.getElementById('recent-tasks');
                if (tasks.length === 0) {
                    return; // Keep the default "no tasks" message
                }
                
                container.innerHTML = tasks.map(task => `
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center dark:bg-green-900/20">
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">${task.title}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">${task.project?.name || 'No project'}</p>
                            </div>
                        </div>
                        <a href="/tasks/${task.id}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            View
                        </a>
                    </div>
                `).join('');
            })
            .catch(error => {
                console.error('Error loading recent tasks:', error);
            });
        }
    </script>
</x-layouts.app>
