<!DOCTYPE html>
<html lang="en" x-data="{ sidebar:false, dark: localStorage.theme==='dark' }" x-init="if(dark){ document.documentElement.classList.add('dark') }" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hospital ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81'
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Enhanced Notification Animations */
        @keyframes notification-glow {
            0%, 100% { box-shadow: 0 0 5px rgba(99, 102, 241, 0.3); }
            50% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.6), 0 0 30px rgba(99, 102, 241, 0.4); }
        }
        
        @keyframes notification-shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
            20%, 40%, 60%, 80% { transform: translateX(2px); }
        }
        
        @keyframes badge-pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .notification-active {
            animation: notification-glow 2s ease-in-out infinite;
        }
        
        .notification-urgent {
            animation: notification-shake 0.5s ease-in-out infinite;
        }
        
        .badge-animate {
            animation: badge-pop 1s ease-in-out infinite;
        }
        
        /* Notification Bell Ring Animation */
        @keyframes bell-ring {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(10deg); }
            20% { transform: rotate(-8deg); }
            30% { transform: rotate(6deg); }
            40% { transform: rotate(-4deg); }
            50% { transform: rotate(2deg); }
            60% { transform: rotate(0deg); }
            100% { transform: rotate(0deg); }
        }
        
        .bell-ring {
            animation: bell-ring 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-full bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    @if(request()->routeIs('login'))
        @yield('content')
    @else
    <div class="min-h-screen flex">
        <aside :class="sidebar ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed md:static z-40 w-64 shrink-0 bg-white/90 backdrop-blur border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 transform transition-transform">
            <!-- Enhanced Sidebar Header with Hospital Branding -->
            <div class="h-20 px-4 flex flex-col justify-center border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <!-- Hospital Icon -->
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-lg leading-tight">MediCare</div>
                            <div class="text-xs text-white/80">General Hospital</div>
                        </div>
                    </div>
                    <button class="md:hidden text-white/80 hover:text-white" @click="sidebar=false">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="p-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-primary-50 hover:text-primary-700 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 font-medium' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('patients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-primary-50 hover:text-primary-700 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('patients.*') ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 font-medium' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    Patients
                </a>
                <a href="{{ route('appointments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-primary-50 hover:text-primary-700 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('appointments.*') ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 font-medium' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5m-18 0h18" />
                    </svg>
                    Appointments
                </a>
                <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-primary-50 hover:text-primary-700 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('invoices.*') ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 font-medium' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Invoices
                </a>
                <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-primary-50 hover:text-primary-700 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('inventory.*') ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 font-medium' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504 1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    Inventory
                </a>
                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-primary-50 hover:text-primary-700 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('suppliers.*') ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 font-medium' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m0-3.75A1.125 1.125 0 013.375 9h1.5m9.75-3.75H21.75a1.125 1.125 0 011.125 1.125v6.75m0 0a1.125 1.125 0 01-1.125 1.125H19.5m-9.75 0h9.75" />
                    </svg>
                    Suppliers
                </a>
            </nav>
        </aside>
        <div class="flex-1 min-w-0 md:ml-0 ml-0">
            <!-- Enhanced Header with System Branding -->
            <header class="h-16 bg-white/80 backdrop-blur border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" @click="sidebar=true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div class="flex items-center gap-3">
                        <!-- System Icon -->
                        <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-primary-600 dark:text-primary-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-lg text-gray-900 dark:text-gray-100">Hospital ERP System</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">MediCare General Hospital</div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="px-3 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" @click="dark=!dark; if(dark){localStorage.theme='dark';document.documentElement.classList.add('dark')} else {localStorage.theme='light';document.documentElement.classList.remove('dark')}">
                        <span x-show="!dark">🌙</span>
                        <span x-show="dark">☀️</span>
                    </button>

                    <!-- Floating Notification Panel -->
                    <div x-data="notificationDropdown()" class="relative">
                        <button @click="toggleNotifications()" 
                                class="p-2 rounded-lg transition-all duration-300 relative group"
                                :class="{
                                    'text-gray-600 dark:text-gray-300 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700': unreadCount === 0,
                                    'text-primary-600 bg-primary-50 dark:bg-primary-900/30 hover:bg-primary-100 dark:hover:bg-primary-900/50 notification-active': unreadCount > 0 && unreadCount <= 5,
                                    'text-red-600 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 notification-urgent': unreadCount > 5
                                }">
                            <!-- Notification Icon with Enhanced Visual Feedback -->
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                                     class="w-5 h-5 transition-transform duration-200"
                                     :class="{
                                         'bell-ring': unreadCount > 0 && unreadCount <= 5,
                                         'animate-bounce notification-urgent': unreadCount > 5
                                     }">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                
                                <!-- Pulsing Dot Indicator on Bell -->
                                <div x-show="unreadCount > 0" 
                                     class="absolute top-0 right-0 w-2 h-2 rounded-full animate-pulse"
                                     :class="{
                                         'bg-primary-500': unreadCount <= 5,
                                         'bg-red-500': unreadCount > 5
                                     }"></div>
                                
                                <!-- Pulsing Ring Animation for New Notifications -->
                                <div x-show="unreadCount > 0" 
                                     class="absolute inset-0 rounded-full border-2 animate-ping opacity-75"
                                     :class="{
                                         'border-primary-400': unreadCount <= 5,
                                         'border-red-400': unreadCount > 5
                                     }"></div>
                                     
                                <!-- Secondary Ring for Urgent Notifications -->
                                <div x-show="unreadCount > 5" 
                                     class="absolute inset-0 rounded-full border-2 border-orange-400 animate-ping opacity-50" 
                                     style="animation-delay: 0.5s;"></div>
                            </div>
                            
                            <!-- Enhanced Notification Badge -->
                            <span x-show="unreadCount > 0" 
                                  class="absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full flex items-center justify-center shadow-lg"
                                  :class="{
                                      'bg-gradient-to-r from-primary-500 to-primary-600 animate-pulse': unreadCount <= 5,
                                      'bg-gradient-to-r from-red-500 to-red-600 badge-animate': unreadCount > 5
                                  }">
                                <span class="text-xs text-white font-bold px-1" x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                            </span>
                            
                            <!-- Glowing Effect for Notifications -->
                            <div x-show="unreadCount > 0" 
                                 class="absolute inset-0 rounded-lg opacity-20 blur-sm animate-pulse"
                                 :class="{
                                     'bg-primary-400': unreadCount <= 5,
                                     'bg-red-400': unreadCount > 5
                                 }"></div>
                                 
                            <!-- Attention Grabber for Many Notifications -->
                            <div x-show="unreadCount > 10" 
                                 class="absolute -inset-2 rounded-lg bg-gradient-to-r from-red-400 to-orange-400 opacity-30 blur-md animate-pulse"></div>
                        </button>

                        <!-- Floating Notification Window -->
                        <div x-show="isOpen"
                             @click.away="isOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">

                            <!-- Notification Header -->
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                                    <button @click="markAllAsRead()" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Mark all read</button>
                                </div>
                            </div>

                            <!-- Notification List -->
                            <div class="max-h-96 overflow-y-auto">
                                <div x-show="loading" class="px-4 py-8 text-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600 mx-auto"></div>
                                    <p class="text-sm text-gray-500 mt-2">Loading notifications...</p>
                                </div>
                                
                                <div x-show="!loading && notifications.length === 0" class="px-4 py-8 text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">No notifications yet</p>
                                </div>
                                
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div @click="redirectToNotification(notification)" 
                                         class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 cursor-pointer transition-all duration-200 relative"
                                         :class="{
                                             'opacity-75 hover:bg-gray-50 dark:hover:bg-gray-700/50': notification.read_at,
                                             'bg-gradient-to-r from-primary-50 to-transparent dark:from-primary-900/20 hover:from-primary-100 dark:hover:from-primary-900/30 border-l-4 border-l-primary-500': !notification.read_at && notification.priority !== 'high',
                                             'bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 hover:from-red-100 hover:to-orange-100 dark:hover:from-red-900/30 dark:hover:to-orange-900/30 border-l-4 border-l-red-500 animate-pulse': !notification.read_at && notification.priority === 'high'
                                         }">
                                        <!-- Unread Notification Glow Effect -->
                                        <div x-show="!notification.read_at" 
                                             class="absolute inset-0 bg-gradient-to-r opacity-10 pointer-events-none"
                                             :class="{
                                                 'from-primary-400 to-transparent animate-pulse': notification.priority !== 'high',
                                                 'from-red-400 to-orange-400 animate-pulse': notification.priority === 'high'
                                             }"></div>
                                             
                                        <div class="flex items-start gap-3 relative z-10">
                                            <!-- Enhanced Icon with Animation -->
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-transform duration-200"
                                                 :class="{
                                                     ...getNotificationIconClass(notification),
                                                     'animate-bounce': !notification.read_at && notification.priority === 'high',
                                                     'scale-110': !notification.read_at
                                                 }">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"
                                                     :class="getNotificationIconColor(notification)">
                                                    <path stroke-linecap="round" stroke-linejoin="round" :d="getNotificationIconPath(notification)"></path>
                                                </svg>
                                                
                                                <!-- Pulsing Ring for Unread High Priority -->
                                                <div x-show="!notification.read_at && notification.priority === 'high'" 
                                                     class="absolute inset-0 rounded-full border-2 border-red-400 animate-ping opacity-75"></div>
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium transition-colors duration-200"
                                                   :class="{
                                                       'text-gray-900 dark:text-gray-100': !notification.read_at,
                                                       'text-gray-600 dark:text-gray-400': notification.read_at
                                                   }" 
                                                   x-text="notification.title"></p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="notification.message"></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" x-text="formatTime(notification.created_at)"></p>
                                            </div>
                                            
                                            <!-- Enhanced Unread Indicator -->
                                            <div x-show="!notification.read_at" class="flex flex-col items-center gap-1 flex-shrink-0 mt-1">
                                                <div class="w-3 h-3 rounded-full animate-pulse"
                                                     :class="{
                                                         ...getPriorityDotClass(notification),
                                                         'shadow-lg': notification.priority === 'high'
                                                     }"></div>
                                                <div x-show="notification.priority === 'high'" 
                                                     class="text-xs font-bold text-red-600 dark:text-red-400 animate-pulse">!</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Notification Footer -->
                            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('notifications') }}" class="w-full text-center text-sm text-primary-600 hover:text-primary-700 font-medium py-1 block">View All Notifications</a>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary-600 dark:text-primary-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Dr. Admin</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-gray-400">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 ring-1 ring-black ring-opacity-5 border border-gray-200 dark:border-gray-700">
                            <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Your Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <main class="max-w-6xl mx-auto p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200 border border-green-200 dark:border-green-800">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200 border border-red-200 dark:border-red-800">
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mt-0.5 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <div>
                                <div class="font-semibold mb-2">There were some problems with your input:</div>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                {{ $slot ?? '' }}
                @yield('content')
            </main>
            <footer class="max-w-6xl mx-auto px-6 py-4 text-xs text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>&copy; {{ date('Y') }} MediCare General Hospital • Hospital ERP System</div>
                    <div class="text-gray-400">v1.0.0</div>
                </div>
            </footer>
        </div>
    </div>
    @endif
    
    <script>
        function notificationDropdown() {
            return {
                isOpen: false,
                unreadCount: 0,
                notifications: [],
                loading: false,
                
                toggleNotifications() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen && this.notifications.length === 0) {
                        this.fetchNotifications();
                    }
                },
                
                fetchNotifications() {
                    this.loading = true;
                    fetch('/notifications/unread')
                        .then(response => response.json())
                        .then(data => {
                            this.notifications = data.notifications;
                            this.unreadCount = data.unread_count;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Error fetching notifications:', error);
                            this.loading = false;
                        });
                },
                
                markAllAsRead() {
                    fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.notifications.forEach(notification => {
                                notification.read_at = new Date().toISOString();
                            });
                            this.unreadCount = 0;
                        }
                    })
                    .catch(error => {
                        console.error('Error marking notifications as read:', error);
                    });
                },
                
                redirectToNotification(notification) {
                    // Mark as read if not already read
                    if (!notification.read_at) {
                        fetch(`/notifications/${notification.id}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.redirect_url) {
                                window.location.href = data.redirect_url;
                            }
                        })
                        .catch(error => {
                            console.error('Error marking notification as read:', error);
                        });
                    } else if (notification.action_url) {
                        window.location.href = notification.action_url;
                    }
                },
                
                formatTime(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diffInSeconds = Math.floor((now - date) / 1000);
                    
                    if (diffInSeconds < 60) {
                        return 'Just now';
                    } else if (diffInSeconds < 3600) {
                        const minutes = Math.floor(diffInSeconds / 60);
                        return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
                    } else if (diffInSeconds < 86400) {
                        const hours = Math.floor(diffInSeconds / 3600);
                        return `${hours} hour${hours > 1 ? 's' : ''} ago`;
                    } else if (diffInSeconds < 604800) {
                        const days = Math.floor(diffInSeconds / 86400);
                        return `${days} day${days > 1 ? 's' : ''} ago`;
                    } else {
                        return date.toLocaleDateString();
                    }
                },
                
                getNotificationIconClass(notification) {
                    const iconClasses = {
                        'new_patient': 'bg-blue-100 dark:bg-blue-900',
                        'new_appointment': 'bg-green-100 dark:bg-green-900',
                        'appointment_reminder': 'bg-yellow-100 dark:bg-yellow-900',
                        'low_stock': 'bg-red-100 dark:bg-red-900',
                        'expired_items': 'bg-orange-100 dark:bg-orange-900',
                        'system': 'bg-gray-100 dark:bg-gray-700'
                    };
                    return iconClasses[notification.type] || 'bg-gray-100 dark:bg-gray-700';
                },
                
                getNotificationIconColor(notification) {
                    const colorClasses = {
                        'new_patient': 'text-blue-600 dark:text-blue-400',
                        'new_appointment': 'text-green-600 dark:text-green-400',
                        'appointment_reminder': 'text-yellow-600 dark:text-yellow-400',
                        'low_stock': 'text-red-600 dark:text-red-400',
                        'expired_items': 'text-orange-600 dark:text-orange-400',
                        'system': 'text-gray-600 dark:text-gray-400'
                    };
                    return colorClasses[notification.type] || 'text-gray-600 dark:text-gray-400';
                },
                
                getNotificationIconPath(notification) {
                    const iconPaths = {
                        'new_patient': 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
                        'new_appointment': 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5m-18 0h18',
                        'appointment_reminder': 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
                        'low_stock': 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
                        'expired_items': 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
                        'system': 'M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z'
                    };
                    return iconPaths[notification.type] || iconPaths['system'];
                },
                
                getPriorityDotClass(notification) {
                    const priorityClasses = {
                        'high': 'bg-red-500',
                        'medium': 'bg-yellow-500',
                        'low': 'bg-blue-500'
                    };
                    return priorityClasses[notification.priority] || 'bg-blue-500';
                }
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>
