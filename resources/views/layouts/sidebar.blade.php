    
                <aside class="w-64 min-h-screen bg-white border-r">
                    <!-- Navigation Links -->
                    <div class="mt-4 space-y-1">
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            <i class="fas fa-dollar-sign icon"></i>
                            {{ __('Financial Insights') }}
                        </x-nav-link>
                    </div>
                    <div class="mt-4 space-y-1">
                        <x-nav-link :href="route('admin.ProgressAnalytics')" :active="request()->routeIs('admin.ProgressAnalytics')">
                            <i class="fas fa-chart-line icon"></i>
                            {{ __('Progress Analytics') }}
                        </x-nav-link>
                    </div>
                    <div class="mt-4 space-y-1" x-data="{ openPages: false }">
                        <a @click="openPages = !openPages" class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none transition duration-150 ease-in-out">
                            <i class="fas fa-file-alt icon"></i> 
                            {{ __('Pages') }}
                            <svg class="ml-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        
                        <ul x-show="openPages" @click.outside="openPages = false">
                            <li>
                                <a href="{{ route('admin.business-ideas.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" :class="{'bg-gray-100': request()->routeIs('admin.business-ideas.index')}">
                                    <i class="fas fa-lightbulb icon"></i>    {{ __('Business Ideas') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-4 space-y-1">
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                            <i class="fas fa-users icon"></i>
                            {{ __('User Management') }}
                        </x-nav-link>
                    </div>
                    <div class="mt-4 space-y-1">
                        <x-nav-link :href="route('admin.videos.index')" :active="request()->routeIs('admin.videos.index')">
                            <i class="fas fa-video icon" style="margin-right: 0.75rem;"></i>
                            {{ __('Video Management') }}
                        </x-nav-link>
                    </div>
                </aside>