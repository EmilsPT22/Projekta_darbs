@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Calendar - {{ $internship->name }} - {{ $student->name }}</h2>
        <div>
            <a href="{{ route('entries.student', ['internship' => $internship->id, 'student' => $student->id]) }}" class="btn btn-secondary btn-sm">
                List View
            </a>
            @if(auth()->user()->hasRole('student'))
                <a href="{{ route('entries.create', $internship->id) }}" class="btn btn-success btn-sm ms-2">
                    Add Entry
                </a>
            @endif
        </div>
    </div>

    <div id="calendar"></div>
</div>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        // Prepare events from entries
        var events = [
            @foreach($entries as $entry)
                {
                    title: '{{ $entry->theme->name }} ({{ $entry->credit_hours }}h)',
                    start: '{{ $entry->date }}',
                    backgroundColor: '{{ $entry->location === "on-site" ? "#0d6efd" : "#198754" }}',
                    borderColor: '{{ $entry->location === "on-site" ? "#0a58ca" : "#146c43" }}',
                    extendedProps: {
                        location: '{{ $entry->location }}',
                        duration: '{{ $entry->duration }}',
                        comment: '{{ $entry->intern_comment ?? "No comment" }}',
                        @if($entry->admin_comment)
                        adminComment: '{{ $entry->admin_comment }}',
                        grade: {{ $entry->grade ?? 'null' }},
                        @endif
                    }
                },
            @endforeach
        ];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listWeek'
            },
            events: events,
            eventClick: function(info) {
                var event = info.event;
                var props = event.extendedProps;

                var modalHtml = `
                    <div class="modal fade" id="entryModal" tabindex="-1">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">${event.title}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Location:</strong> ${props.location}</p>
                                    <p><strong>Duration:</strong> ${props.duration}</p>
                                    <p><strong>Comment:</strong> ${props.comment}</p>
                                    ${props.adminComment ? `<p><strong>Admin Comment:</strong> ${props.adminComment}</p>` : ''}
                                    ${props.grade ? `<p><strong>Grade:</strong> ${props.grade}/10</p>` : ''}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Remove existing modal if any
                var existingModal = document.getElementById('entryModal');
                if (existingModal) {
                    existingModal.remove();
                }

                document.body.insertAdjacentHTML('beforeend', modalHtml);
                var modal = new bootstrap.Modal(document.getElementById('entryModal'));
                modal.show();
            },
            eventDidMount: function(info) {
                // Add tooltip
                info.el.title = info.event.title;
            }
        });

        calendar.render();
    });
</script>

<style>
    #calendar {
        background-color: #1e1e1e;
        padding: 15px;
        border-radius: 8px;
        font-size: 13px;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .fc {
        color: #ffffff;
        font-size: 13px;
    }
    
    .fc-theme-standard td, 
    .fc-theme-standard th {
        border-color: #333;
    }
    
    .fc-col-header-cell {
        background-color: #2a2a2a;
        padding: 8px;
        font-size: 12px;
    }
    
    .fc-daygrid-day {
        background-color: #1e1e1e;
    }
    
    .fc-daygrid-day.fc-day-today {
        background-color: rgba(13, 110, 253, 0.15);
    }
    
    .fc-button-primary {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        padding: 4px 8px !important;
        font-size: 12px !important;
    }
    
    .fc-button-primary:hover {
        background-color: #0b5ed7 !important;
        border-color: #0a58ca !important;
    }
    
    .fc-event {
        cursor: pointer;
        border: none;
        font-size: 11px;
        padding: 2px 4px;
    }
    
    .fc-event-title {
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .fc-toolbar-title {
        font-size: 16px !important;
    }
</style>
@endsection
