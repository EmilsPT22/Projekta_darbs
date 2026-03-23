@props(['align' => 'right', 'width' => '96'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    '64' => 'w-64',
    '96' => 'w-96',
    default => $width,
};
@endphp

<div class="relative" 
     x-data="notificationComponent()"
     x-init="initNotifications()"
     @click.outside="open = false" 
     @close.stop="open = false"
     @notification-updated.window="updateNotifications()">
    
    <div @click="open = ! open; if(open) fetchNotifications()">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-white">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                <div class="flex items-center gap-2">
                    <span x-show="unreadCount > 0" 
                          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <span x-text="unreadCount"></span>
                    </span>
                    <button x-show="unreadCount > 0"
                            @click.stop="markAllAsRead()"
                            class="text-xs text-blue-600 hover:text-blue-800">
                        Mark all read
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="max-h-96 overflow-y-auto">
                <template x-if="loading">
                    <div class="px-4 py-8 text-center">
                        <svg class="animate-spin h-6 w-6 text-gray-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm text-gray-500 mt-2">Loading...</p>
                    </div>
                </template>

                <template x-if="!loading && notifications.length === 0">
                    <div class="px-4 py-8 text-center">
                        <svg class="h-12 w-12 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <p class="text-sm text-gray-500 mt-2">No notifications</p>
                    </div>
                </template>

                <template x-for="notification in notifications" :key="notification.id">
                    <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer"
                         :class="{'bg-blue-50': !notification.read}"
                         @click="markAsRead(notification.id)">
                        <div class="flex gap-3">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <template x-if="notification.icon === 'application'">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </template>
                                <template x-if="notification.icon === 'status'">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </template>
                                <template x-if="notification.icon === 'reminder'">
                                    <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </template>
                                <template x-if="!['application', 'status', 'reminder'].includes(notification.icon)">
                                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </div>
                                </template>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                <p class="text-sm text-gray-500 truncate" x-text="notification.message"></p>
                                <p class="text-xs text-gray-400 mt-1" x-text="notification.created_at"></p>
                            </div>

                            <!-- Delete button -->
                            <button @click.stop="deleteNotification(notification.id)"
                                    class="text-gray-400 hover:text-red-500 flex-shrink-0">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="px-4 py-2 border-t border-gray-200 bg-gray-50 rounded-b-md">
                <a href="#" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                    View all notifications
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function notificationComponent() {
    return {
        open: false,
        loading: false,
        notifications: [],
        unreadCount: 0,
        pollInterval: null,

        initNotifications() {
            this.fetchUnreadCount();
            // Poll for new notifications every 30 seconds
            this.pollInterval = setInterval(() => {
                this.fetchUnreadCount();
                if (this.open) {
                    this.fetchNotifications();
                }
            }, 30000);
        },

        async fetchNotifications() {
            this.loading = true;
            try {
                const response = await fetch('{{ route("notifications.index") }}');
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (error) {
                console.error('Error fetching notifications:', error);
            } finally {
                this.loading = false;
            }
        },

        async fetchUnreadCount() {
            try {
                const response = await fetch('{{ route("notifications.unread-count") }}');
                const data = await response.json();
                this.unreadCount = data.count;
            } catch (error) {
                console.error('Error fetching unread count:', error);
            }
        },

        updateNotifications() {
            this.fetchNotifications();
        },

        async markAsRead(id) {
            try {
                const response = await fetch(`{{ route('notifications.index') }}/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.unreadCount = data.unread_count;
                const notification = this.notifications.find(n => n.id === id);
                if (notification) {
                    notification.read = true;
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch(`{{ route('notifications.read-all') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.unreadCount = 0;
                this.notifications.forEach(n => n.read = true);
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },

        async deleteNotification(id) {
            try {
                const response = await fetch(`{{ route('notifications.index') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.unreadCount = data.unread_count;
                this.notifications = this.notifications.filter(n => n.id !== id);
            } catch (error) {
                console.error('Error deleting notification:', error);
            }
        }
    }
}
</script>
