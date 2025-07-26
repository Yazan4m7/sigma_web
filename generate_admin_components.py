import os

# Define the components structure
components = {
    "admin-header": '''<div class="admin-header">
    <div class="header-container">
        <h1 class="admin-title">Dashboard</h1>
        <div class="admin-user-info">
            <span class="admin-user-name">{{ Auth::user()->name }}</span>
            <img src="{{ Auth::user()->profile_photo_url }}" class="admin-user-avatar" alt="{{ Auth::user()->name }}">
            <div class="admin-dropdown">
                <!-- User dropdown menu -->
                <a href="{{ route('profile.show') }}" class="dropdown-item">Profile</a>
                <a href="{{ route('logout') }}" class="dropdown-item">Log Out</a>
            </div>
        </div>
    </div>
</div>''',
    
    "admin-navigation": '''<div class="admin-navigation">
    <ul class="nav-menu">
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.cases.*') ? 'active' : '' }}">
            <a href="{{ route('admin.cases.index') }}" class="nav-link">
                <i class="fas fa-folder"></i> Cases
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="nav-link">
                <i class="fas fa-users"></i> Users
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.machines.*') ? 'active' : '' }}">
            <a href="{{ route('admin.machines.index') }}" class="nav-link">
                <i class="fas fa-cogs"></i> Machines
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reports.index') }}" class="nav-link">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.index') }}" class="nav-link">
                <i class="fas fa-cog"></i> Settings
            </a>
        </li>
    </ul>
</div>''',
    
    "admin-statistics-overview": '''<div class="statistics-overview">
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card primary">
                <div class="stats-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="stats-data">
                    <h3>{{ $totalCases }}</h3>
                    <p>Total Cases</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-data">
                    <h3>{{ $completedCases }}</h3>
                    <p>Completed Cases</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-data">
                    <h3>{{ $pendingCases }}</h3>
                    <p>Pending Cases</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card danger">
                <div class="stats-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-data">
                    <h3>{{ $delayedCases }}</h3>
                    <p>Delayed Cases</p>
                </div>
            </div>
        </div>
    </div>
</div>''',
    
    "admin-recent-cases": '''<div class="recent-cases-section">
    <h4 class="section-title">Recent Cases</h4>
    <div class="cases-table-container">
        <table class="table cases-table">
            <thead>
                <tr>
                    <th>Case ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentCases as $case)
                <tr>
                    <td>{{ $case->id }}</td>
                    <td>{{ $case->patient_name }}</td>
                    <td>{{ $case->client->name }}</td>
                    <td>
                        <span class="status-badge {{ $case->status_class }}">{{ $case->status }}</span>
                    </td>
                    <td>{{ $case->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('view-case', $case->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No recent cases found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="view-all-link">
        <a href="{{ route('admin.cases.index') }}">View All Cases</a>
    </div>
</div>''',
    
    "admin-machine-status": '''<div class="machine-status-section">
    <h4 class="section-title">Machine Status</h4>
    <div class="machines-grid">
        @foreach($machines as $machine)
        <div class="machine-card {{ $machine->status_class }}">
            <div class="machine-icon">
                <img src="{{ asset($machine->img) }}" alt="{{ $machine->name }}">
            </div>
            <div class="machine-info">
                <h5>{{ $machine->name }}</h5>
                <p class="machine-status">{{ $machine->status }}</p>
            </div>
            <div class="machine-badges">
                <span class="badge badge-primary">{{ $machine->active_jobs }}</span>
                <span class="badge badge-secondary">{{ $machine->waiting_jobs }}</span>
            </div>
        </div>
        @endforeach
    </div>
    <div class="view-all-link">
        <a href="{{ route('admin.machines.index') }}">View All Machines</a>
    </div>
</div>''',
    
    "admin-user-activity": '''<div class="user-activity-section">
    <h4 class="section-title">User Activity</h4>
    <div class="activity-list">
        @forelse($userActivities as $activity)
        <div class="activity-item">
            <div class="activity-user-avatar">
                <img src="{{ $activity->user->profile_photo_url }}" alt="{{ $activity->user->name }}">
            </div>
            <div class="activity-details">
                <p class="activity-text">
                    <strong>{{ $activity->user->name }}</strong> {{ $activity->description }}
                </p>
                <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <div class="activity-empty">
            <p>No recent activity found</p>
        </div>
        @endforelse
    </div>
    <div class="view-all-link">
        <a href="{{ route('admin.activity.index') }}">View All Activity</a>
    </div>
</div>''',
    
    "admin-notifications": '''<div class="notifications-section">
    <h4 class="section-title">Notifications</h4>
    <div class="notifications-list">
        @forelse($notifications as $notification)
        <div class="notification-item {{ $notification->read_at ? 'read' : '' }}">
            <div class="notification-icon">
                <i class="{{ $notification->icon }}"></i>
            </div>
            <div class="notification-content">
                <p class="notification-text">{{ $notification->data['message'] }}</p>
                <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
            @if(!$notification->read_at)
            <div class="notification-actions">
                <button type="button" class="mark-read-btn" onclick="markAsRead('{{ $notification->id }}')">
                    <i class="fas fa-check"></i>
                </button>
            </div>
            @endif
        </div>
        @empty
        <div class="notifications-empty">
            <p>No new notifications</p>
        </div>
        @endforelse
    </div>
    <div class="view-all-link">
        <a href="{{ route('admin.notifications.index') }}">View All Notifications</a>
    </div>
</div>''',
    
    "admin-quick-actions": '''<div class="quick-actions-section">
    <h4 class="section-title">Quick Actions</h4>
    <div class="actions-grid">
        <a href="{{ route('create-case') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div class="action-text">New Case</div>
        </a>
        <a href="{{ route('admin.users.create') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="action-text">Add User</div>
        </a>
        <a href="{{ route('admin.reports.generate') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-file-export"></i>
            </div>
            <div class="action-text">Generate Report</div>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-wrench"></i>
            </div>
            <div class="action-text">System Settings</div>
        </a>
    </div>
</div>'''
}

# Main dashboard template
main_dashboard = '''@extends('layouts.main')

@section('content')
<div class="admin-dashboard-container">
    @include('admin.components.admin-header')
    
    <div class="dashboard-content">
        @include('admin.components.admin-navigation')
        
        <div class="dashboard-main">
            @include('admin.components.admin-statistics-overview')
            
            <div class="dashboard-sections">
                <div class="left-column">
                    @include('admin.components.admin-recent-cases')
                    @include('admin.components.admin-user-activity')
                </div>
                
                <div class="right-column">
                    @include('admin.components.admin-machine-status')
                    @include('admin.components.admin-notifications')
                    @include('admin.components.admin-quick-actions')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection'''

def create_component_files():
    # Create directory structure
    try:
        os.makedirs('resources/views/admin/components', exist_ok=True)
        print("✅ Created admin components directory")
    except Exception as e:
        print(f"❌ Error creating directories: {e}")
        return

    # Create each component file
    for component_name, content in components.items():
        file_path = f'resources/views/admin/components/{component_name}.blade.php'
        try:
            with open(file_path, 'w') as f:
                f.write(content)
            print(f"✅ Created {component_name}.blade.php")
        except Exception as e:
            print(f"❌ Error creating {component_name}.blade.php: {e}")
    
    # Create main dashboard file
    try:
        with open('resources/views/admin/dashboard.blade.php', 'w') as f:
            f.write(main_dashboard)
        print("✅ Created admin/dashboard.blade.php")
    except Exception as e:
        print(f"❌ Error creating dashboard.blade.php: {e}")

if __name__ == "__main__":
    print("Starting component generation...")
    create_component_files()
    print("Component generation completed!")
