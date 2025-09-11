@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Notifications</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Stay updated with your hospital activities</p>
            </div>
            
            @if($notifications->where('read_at', null)->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Mark All as Read
                </button>
            </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0 {{ $notification->read_at ? 'opacity-75' : '' }}">
                    <a href="{{ route('notifications.read', $notification->id) }}" 
                       class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $notification->getIconBackgroundClass() }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 {{ $notification->getIconColorClass() }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $notification->getIconPath() }}"></path>
                                </svg>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                            {{ $notification->title }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                                            {{ $notification->message }}
                                        </p>
                                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-500">
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $notification->getPriorityBadgeClass() }}">
                                                {{ ucfirst($notification->priority) }} Priority
                                            </span>
                                            @if($notification->read_at)
                                            <span class="text-green-600 dark:text-green-400 text-xs">
                                                ✓ Read
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Unread indicator -->
                                    @if(!$notification->read_at)
                                    <div class="w-3 h-3 {{ $notification->getPriorityDotClass() }} rounded-full flex-shrink-0 mt-2"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
                
                <!-- Pagination -->
                @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $notifications->links() }}
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No notifications yet</h3>
                    <p class="text-gray-600 dark:text-gray-400">When you have notifications, they'll appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection