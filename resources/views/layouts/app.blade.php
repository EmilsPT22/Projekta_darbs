<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>praksesvietas.lv</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-body-tertiary">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">

    <div class="container">

        <a class="navbar-brand" href="{{ url('/') }}">Home</a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
           <ul class="navbar-nav ms-auto text-right">
                @auth
                    @if(auth()->user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('classgroups.index') }}">Classes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.students-grade') }}">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.activity-log') }}">Activity Log</a>
                    </li>
                    @endif
                    @if(auth()->user()->hasRole('teacher'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="teacherDropdown" role="button" data-bs-toggle="dropdown">
                            My Classes
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('teacher.my-classes') }}">My Classes</a></li>
                            <li><a class="dropdown-item" href="{{ route('teacher.my-students') }}">My Students</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('teacher.manage-students') }}">Manage Students</a></li>
                        </ul>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('internships.index') }}">Internships</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
                    </li>
                    <!-- Notification Bell -->
                    <li class="nav-item" x-data="notificationComponent()" x-init="initNotifications()">
                        <button class="nav-link position-relative" @click="$dispatch('open-notifications')" style="background: none; border: none;">
                            <svg class="h-5 w-5" style="height: 20px; width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span x-show="unreadCount > 0"
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                  style="font-size: 0.6rem; padding: 0.25em 0.4em;"
                                  x-text="unreadCount">
                            </span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">Log Out</button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-muted small border-start border-secondary ps-3">
                            {{ str_replace('_', ' ', ucfirst(auth()->user()->getRoleNames()->first())) }}
                        </span>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Log In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>

    </div>
</nav>

<div class="container py-4">
    @yield('content')
</div>
</body>

@yield('scripts')

<!-- Notification Modal -->
<div x-data="notificationModal()" 
     @open-notifications.window="openModal()"
     class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">Notifications</h5>
                <div class="d-flex align-items-center gap-2">
                    <span x-show="unreadCount > 0" 
                          class="badge bg-danger" x-text="unreadCount"></span>
                    <button x-show="unreadCount > 0"
                            @click="markAllAsRead()"
                            class="btn btn-link btn-sm text-primary p-0">
                        Mark all read
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0" style="max-height: 400px; overflow-y: auto;">
                <template x-if="loading">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 small">Loading notifications...</p>
                    </div>
                </template>
                <template x-if="!loading && notifications.length === 0">
                    <div class="text-center py-4">
                        <svg class="text-muted mx-auto mb-2" style="width: 48px; height: 48px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <p class="text-muted small mb-0">No notifications</p>
                    </div>
                </template>
                <template x-for="notification in notifications" :key="notification.id">
                    <div class="p-3 border-bottom d-flex gap-3 align-items-start"
                         :class="{'bg-primary bg-opacity-10': !notification.read}"
                         @click="markAsRead(notification.id)">
                        <div class="flex-shrink-0">
                            <template x-if="notification.icon === 'application'">
                                <div class="bg-success bg-opacity-25 rounded-circle p-2">
                                    <svg class="text-success" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </template>
                            <template x-if="notification.icon === 'status'">
                                <div class="bg-info bg-opacity-25 rounded-circle p-2">
                                    <svg class="text-info" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </template>
                            <template x-if="notification.icon === 'reminder'">
                                <div class="bg-warning bg-opacity-25 rounded-circle p-2">
                                    <svg class="text-warning" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </template>
                            <template x-if="!['application', 'status', 'reminder'].includes(notification.icon)">
                                <div class="bg-secondary bg-opacity-25 rounded-circle p-2">
                                    <svg class="text-secondary" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </div>
                            </template>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <p class="fw-semibold mb-1 small" x-text="notification.title"></p>
                            <p class="text-muted small mb-1 text-truncate" x-text="notification.message"></p>
                            <p class="text-muted small mb-0" style="font-size: 0.7rem;" x-text="notification.created_at"></p>
                        </div>
                        <button @click.stop="deleteNotification(notification.id)" class="btn btn-link btn-sm text-muted p-0">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
function notificationComponent() {
    return {
        unreadCount: 0,
        pollInterval: null,

        initNotifications() {
            this.fetchUnreadCount();
            this.pollInterval = setInterval(() => {
                this.fetchUnreadCount();
            }, 30000);
        },

        async fetchUnreadCount() {
            try {
                const response = await fetch('{{ route("notifications.unread-count") }}');
                const data = await response.json();
                this.unreadCount = data.count;
            } catch (error) {
                console.error('Error fetching unread count:', error);
            }
        }
    }
}

function notificationModal() {
    return {
        loading: false,
        notifications: [],
        unreadCount: 0,
        modal: null,

        openModal() {
            if (!this.modal) {
                this.modal = new bootstrap.Modal(document.getElementById('notificationModal'));
            }
            this.modal.show();
            this.fetchNotifications();
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

        async markAsRead(id) {
            try {
                const response = await fetch(`/notifications/${id}/read`, {
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
                const response = await fetch(`/notifications/read-all`, {
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
                const response = await fetch(`/notifications/${id}`, {
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
