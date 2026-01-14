<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 20px;
            size: landscape;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4f46e5;
        }
        .header h1 {
            font-size: 18px;
            color: #1e40af;
            margin: 0;
        }
        .header .subtitle {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
        .summary {
            background: #f8fafc;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-label {
            font-weight: 600;
            color: #4b5563;
        }
        .summary-value {
            font-size: 11px;
            font-weight: 700;
            color: #1e40af;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 8px;
        }
        th {
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #ddd;
        }
        td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .action-created {
            color: #059669;
            background-color: #d1fae5;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
        }
        .action-updated {
            color: #d97706;
            background-color: #fef3c7;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
        }
        .action-deleted {
            color: #dc2626;
            background-color: #fee2e2;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }
        .page-break {
            page-break-before: always;
        }
        .changes-list {
            max-height: 50px;
            overflow: hidden;
        }
        .changes-item {
            display: flex;
            margin-bottom: 2px;
        }
        .changes-field {
            font-weight: 600;
            min-width: 80px;
            color: #4b5563;
        }
        .changes-value {
            flex: 1;
            color: #374151;
        }
        .warning-note {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 8px;
            border-radius: 4px;
            margin: 10px 0;
            font-size: 9px;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">
            Generated on {{ $exported_at->format('Y-m-d H:i:s') }} by {{ $exported_by }}
            @if(!empty($filters))
                | Filtered Results
            @endif
        </div>
    </div>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Records</div>
                <div class="summary-value">{{ $total_count }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Date Range</div>
                <div class="summary-value">
                    @if(isset($filters['date_from']) || isset($filters['date_to']))
                        {{ $filters['date_from'] ?? 'Start' }} to {{ $filters['date_to'] ?? 'End' }}
                    @else
                        All dates
                    @endif
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Export Format</div>
                <div class="summary-value">PDF</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Page</div>
                <div class="summary-value">{{ $page ?? 1 }} of {{ $total_pages ?? 1 }}</div>
            </div>
        </div>
    </div>

    @if(!empty($filters))
    <div class="warning-note">
        <strong>⚠️ Note:</strong> This export is filtered. Applied filters:
        @foreach($filters as $key => $value)
            @if(!empty($value))
                <strong>{{ str_replace('_', ' ', $key) }}:</strong> {{ $value }}
                @if(!$loop->last) | @endif
            @endif
        @endforeach
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Timestamp</th>
                <th>Admin</th>
                <th>Action</th>
                <th>Model</th>
                <th>Model ID</th>
                @if($include_ip)
                <th>IP Address</th>
                @endif
                @if($include_changes)
                <th>Changes</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
                @if($index > 0 && $index % 25 == 0)
                    <!-- Page break after every 25 rows -->
                    </tbody>
                    </table>
                    <div class="page-break"></div>
                    <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Timestamp</th>
                            <th>Admin</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Model ID</th>
                            @if($include_ip)
                            <th>IP Address</th>
                            @endif
                            @if($include_changes)
                            <th>Changes</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                @endif
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $log->causer->name ?? 'System' }}</td>
                    <td>
                        <span class="action-{{ $log->description }}">
                            {{ ucfirst($log->description) }}
                        </span>
                    </td>
                    <td>{{ $log->subject_type ? class_basename($log->subject_type) : 'N/A' }}</td>
                    <td>{{ $log->subject_id ?? 'N/A' }}</td>
                    @if($include_ip)
                    <td>{{ $log->ip ?? 'N/A' }}</td>
                    @endif
                    @if($include_changes)
<td>
    <div class="changes-list">
        @php
            $properties = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
        @endphp
        @if(!empty($properties) && isset($properties['attributes']) && count($properties['attributes']))
            @php
                $attributes = $properties['attributes'];
                $changes = collect($attributes)->take(3);
            @endphp
            @foreach($changes as $key => $value)
                <div class="changes-item">
                    <span class="changes-field">{{ Str::title(str_replace('_', ' ', $key)) }}:</span>
                    <span class="changes-value">
                        @if(is_array($value))
                            {{ json_encode($value) }}
                        @elseif(is_bool($value))
                            {{ $value ? 'Yes' : 'No' }}
                        @else
                            {{ Str::limit($value, 30) }}
                        @endif
                    </span>
                </div>
            @endforeach
            @if(count($attributes) > 3)
                <div class="changes-item">
                    <span class="changes-field">+{{ count($attributes) - 3 }} more changes</span>
                </div>
            @endif
        @else
            No changes recorded
        @endif
    </div>
</td>
@endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>
            Generated by Luxorix  Activity Logs System |
            {{ date('Y-m-d H:i:s') }} |
            Page {{ $page ?? 1 }}
        </p>
        <p>
            <strong>Confidential:</strong> This document contains sensitive system activity information.
            Unauthorized distribution is prohibited.
        </p>
    <p class="text-center font-bold text-purple-600">Admin Malik Bilawal </p>
    </div>
</body>
</html>