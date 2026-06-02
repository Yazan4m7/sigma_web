@extends('layouts.app', ['pageSlug' => 'Case Timeline'])

@section('content')
<style>
    .timeline-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e3e6f0;
        margin-bottom: 24px;
    }

    .case-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e8ba3 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 12px 12px 0 0;
        position: relative;
        overflow: hidden;
    }

    .case-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-title i {
        font-size: 28px;
        opacity: 0.9;
    }

    .header-title h3 {
        margin: 0;
        font-weight: 700;
        font-size: 24px;
    }

    .case-number-badge {
        font-weight: 800;
        font-size: 28px;
        color: #ffd700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        background: rgba(255, 255, 255, 0.15);
        padding: 8px 24px;
        border-radius: 8px;
        border: 2px solid rgba(255, 215, 0, 0.3);
    }

    .header-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        position: relative;
        z-index: 1;
    }

    .info-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 12px 16px;
        backdrop-filter: blur(10px);
    }

    .info-card-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
        margin-bottom: 4px;
    }

    .info-card-value {
        font-size: 15px;
        font-weight: 600;
    }

    .filter-section {
        background: #f8fafc;
        padding: 15px 30px;
        border-bottom: 1px solid #e3e6f0;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-right: 10px;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
    }

    .filter-chip:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }

    .filter-chip.active {
        border-color: currentColor;
    }

    .filter-chip input {
        display: none;
    }

    .filter-chip.assignment.active { background: #fef3c7; color: #b45309; border-color: #fcd34d; }
    .filter-chip.started.active { background: #dbeafe; color: #1d4ed8; border-color: #93c5fd; }
    .filter-chip.completion.active { background: #dcfce7; color: #15803d; border-color: #86efac; }
    .filter-chip.note.active { background: #f3f4f6; color: #374151; border-color: #9ca3af; }
    .filter-chip.failure.active { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }
    .filter-chip.financial.active { background: #f3e8ff; color: #7c3aed; border-color: #c4b5fd; }
    .filter-chip.job.active { background: #ffedd5; color: #c2410c; border-color: #fdba74; }
    .filter-chip.created.active { background: #ccfbf1; color: #0d9488; border-color: #5eead4; }

    .filter-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Timeline Styles */
    .timeline {
        position: relative;
        padding: 25px 30px;
        background: #fafbfc;
    }

    .timeline-item {
        display: flex;
        gap: 0;
        margin-bottom: 0;
        position: relative;
    }

    .timeline-item.hidden {
        display: none;
    }

    .timeline-item:nth-child(odd) .timeline-card {
        background: #ffffff;
    }

    .timeline-item:nth-child(even) .timeline-card {
        background: #f8fafc;
    }

    /* Left side - Date/Time */
    .timeline-date {
        width: 130px;
        flex-shrink: 0;
        text-align: right;
        padding-right: 20px;
        padding-top: 3px;
    }

    .timeline-date-day {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 2px;
    }

    .timeline-date-time {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
    }

    /* Center - Dot and Line */
    .timeline-center {
        width: 40px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .timeline-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #6c757d;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #f1f5f9, 0 2px 8px rgba(0,0,0,0.15);
        z-index: 2;
        flex-shrink: 0;
    }

    .timeline-dot.assignment { background: #f59e0b; box-shadow: 0 0 0 3px #fef3c7, 0 2px 8px rgba(245,158,11,0.3); }
    .timeline-dot.started { background: #3b82f6; box-shadow: 0 0 0 3px #dbeafe, 0 2px 8px rgba(59,130,246,0.3); }
    .timeline-dot.completion { background: #22c55e; box-shadow: 0 0 0 3px #dcfce7, 0 2px 8px rgba(34,197,94,0.3); }
    .timeline-dot.note { background: #6b7280; box-shadow: 0 0 0 3px #f3f4f6, 0 2px 8px rgba(107,114,128,0.3); }
    .timeline-dot.failure { background: #ef4444; box-shadow: 0 0 0 3px #fee2e2, 0 2px 8px rgba(239,68,68,0.3); }
    .timeline-dot.financial { background: #8b5cf6; box-shadow: 0 0 0 3px #f3e8ff, 0 2px 8px rgba(139,92,246,0.3); }
    .timeline-dot.job { background: #f97316; box-shadow: 0 0 0 3px #ffedd5, 0 2px 8px rgba(249,115,22,0.3); }
    .timeline-dot.created { background: #14b8a6; box-shadow: 0 0 0 3px #ccfbf1, 0 2px 8px rgba(20,184,166,0.3); }

    .timeline-line {
        width: 2px;
        flex-grow: 1;
        background: linear-gradient(to bottom, #d1d5db, #e5e7eb);
        min-height: 20px;
    }

    .timeline-item:last-child .timeline-line {
        display: none;
    }

    /* Right side - Content */
    .timeline-content {
        flex: 1;
        padding: 0 0 20px 20px;
    }

    .timeline-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 16px 20px;
        transition: all 0.2s ease;
    }

    .timeline-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .timeline-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }

    .badge-assignment { background: #fef3c7; color: #b45309; }
    .badge-started { background: #dbeafe; color: #1d4ed8; }
    .badge-completion { background: #dcfce7; color: #15803d; }
    .badge-note { background: #f3f4f6; color: #374151; }
    .badge-failure { background: #fee2e2; color: #dc2626; }
    .badge-financial { background: #f3e8ff; color: #7c3aed; }
    .badge-job { background: #ffedd5; color: #c2410c; }
    .badge-created { background: #ccfbf1; color: #0d9488; }

    .timeline-description {
        font-weight: 600;
        color: #1f2937;
        font-size: 15px;
        line-height: 1.4;
    }

    .timeline-user {
        color: #6b7280;
        font-size: 13px;
        margin-left: auto;
        font-style: italic;
    }

    .timeline-details {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
        color: #4b5563;
        font-size: 14px;
        line-height: 1.6;
    }

    /* Empty state */
    .timeline-empty {
        text-align: center;
        padding: 60px 30px;
        color: #9ca3af;
    }

    .timeline-empty i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-top {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .case-number-badge {
            font-size: 22px;
            padding: 6px 18px;
        }

        .header-info {
            grid-template-columns: 1fr 1fr;
        }

        .timeline-date {
            width: 95px;
            padding-right: 10px;
        }

        .timeline-date-day {
            font-size: 12px;
        }

        .timeline-date-time {
            font-size: 11px;
        }

        .timeline-center {
            width: 30px;
        }

        .timeline-dot {
            width: 12px;
            height: 12px;
        }

        .timeline-content {
            padding-left: 10px;
        }

        .filter-section {
            padding: 12px 15px;
        }
    }

    @media (max-width: 480px) {
        .header-info {
            grid-template-columns: 1fr;
        }

        .timeline-date {
            width: 85px;
        }

        .timeline-date-day {
            font-size: 11px;
        }

        .timeline-date-time {
            font-size: 10px;
        }

        .timeline-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .timeline-user {
            margin-left: 0;
        }
    }
</style>

<div class="content">
    <div class="container-fluid">
        <div class="timeline-container">
            <!-- Header with Case Info -->
            <div class="case-header">
                <div class="header-top">
                    <div class="header-title">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <h2>Case Timeline</h2>
                    </div>
                    <span class="case-number-badge">#{{ $case->id }}</span>
                </div>
                <div class="header-info">
                    <div class="info-card">
                        <div class="info-card-label">Patient</div>
                        <div class="info-card-value">{{ $case->patient_name }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Doctor</div>
                        <div class="info-card-value">{{ $case->client->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Created</div>
                        <div class="info-card-value">{{ \Carbon\Carbon::parse($case->created_at)->format('M d, Y') }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Total Events</div>
                        <div class="info-card-value">{{ count($timeline) }}</div>
                    </div>
                </div>
            </div>

            <!-- Filter Chips -->
            <div class="filter-section">
                <span class="filter-label">Filter:</span>
                <label class="filter-chip assignment active">
                    <input type="checkbox" class="timeline-filter" value="assignment" checked>
                    <span class="filter-dot"></span>
                    Assigned
                </label>
                <label class="filter-chip started active">
                    <input type="checkbox" class="timeline-filter" value="started" checked>
                    <span class="filter-dot"></span>
                    Started
                </label>
                <label class="filter-chip completion active">
                    <input type="checkbox" class="timeline-filter" value="completion" checked>
                    <span class="filter-dot"></span>
                    Completed
                </label>
                <label class="filter-chip note active">
                    <input type="checkbox" class="timeline-filter" value="note" checked>
                    <span class="filter-dot"></span>
                    Notes
                </label>
                <label class="filter-chip failure active">
                    <input type="checkbox" class="timeline-filter" value="failure" checked>
                    <span class="filter-dot"></span>
                    Failures
                </label>
                <label class="filter-chip financial active">
                    <input type="checkbox" class="timeline-filter" value="financial" checked>
                    <span class="filter-dot"></span>
                    Financial
                </label>
                <label class="filter-chip job active">
                    <input type="checkbox" class="timeline-filter" value="job" checked>
                    <span class="filter-dot"></span>
                    Jobs
                </label>
                <label class="filter-chip created active">
                    <input type="checkbox" class="timeline-filter" value="created" checked>
                    <span class="filter-dot"></span>
                    Created
                </label>
            </div>

            <!-- Timeline -->
            <div class="timeline">
                @forelse($timeline as $event)
                <div class="timeline-item" data-type="{{ $event['type'] }}">
                    <!-- Left: Date -->
                    <div class="timeline-date">
                        <div class="timeline-date-day">{{ \Carbon\Carbon::parse($event['timestamp'])->format('M d, Y') }}</div>
                        <div class="timeline-date-time">{{ \Carbon\Carbon::parse($event['timestamp'])->format('H:i:s') }}</div>
                    </div>

                    <!-- Center: Dot and Line -->
                    <div class="timeline-center">
                        <div class="timeline-dot {{ $event['type'] }}"></div>
                        <div class="timeline-line"></div>
                    </div>

                    <!-- Right: Content -->
                    <div class="timeline-content">
                        <div class="timeline-card">
                            <div class="timeline-card-header">
                                <span class="badge badge-{{ $event['type'] }}">{{ ucfirst($event['type']) }}</span>
                                <span class="timeline-description">{{ $event['description'] }}</span>
                                @if($event['user'] && $event['type'] !== 'completion')
                                    <span class="timeline-user">by {{ $event['user']->first_name ?? $event['user']->name ?? 'Unknown' }}</span>
                                @endif
                            </div>
                            @if($event['details'])
                                <div class="timeline-details">{{ $event['details'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="timeline-empty">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <p>No timeline events found for this case.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function(e) {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            this.classList.toggle('active', checkbox.checked);

            const type = checkbox.value;
            const items = document.querySelectorAll(`.timeline-item[data-type="${type}"]`);
            items.forEach(item => {
                item.classList.toggle('hidden', !checkbox.checked);
            });
        });
    });
});
</script>
@endsection
