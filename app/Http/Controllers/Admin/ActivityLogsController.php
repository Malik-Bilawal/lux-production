<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ActivityLogsController extends Controller
{
    private const CACHE_TTL = 3600;
    private const CACHE_PREFIX = 'activity_logs:';
    private const LOGS_PER_PAGE = 20;
    private const MAX_EXPORT_LOGS = 5000;

    private const TRACKED_MODELS = [
        'User', 'Admin', 'Order', 'Product', 'Category', 'Referral', 'ContactInfo',
        'ContactMessage',  'HomeVideo',
        'PromoCode', 'ShippingMethod', 'ShippingCountry',
        'ProductGallery', 'ProductImage',
        'Sale',


    ];

    private const ACTION_TYPES = ['created', 'updated', 'deleted', 'restored', 'forceDeleted'];

    public function index(Request $request)
    {
        try {
         
            
            if ($request->ajax()) {
                return $this->getAjaxLogs($request);
            }
            
            $validator = $this->validateFilters($request);
            
            if ($validator->fails()) {
                return redirect()->route('admin.activity-logs.index')
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $validated = $validator->validated();
            
            $query = $this->buildQuery($validated);

$statsQuery = clone $query;

$stats = $this->getStatistics($statsQuery);

$perPage = $validated['per_page'] ?? self::LOGS_PER_PAGE;
$logs = $query
    ->orderBy('created_at', 'desc')
    ->paginate($perPage)
    ->withQueryString();

            $filterData = $this->getFilterData();
            
            return view('admin.activity-logs.index', [
                'logs' => $logs,
                'stats' => $stats,
                'models' => self::TRACKED_MODELS,
                'actions' => self::ACTION_TYPES,
                'admins' => $filterData['admins'],
                'filters' => $validated,
                'totalLogs' => $stats['total'],
                'hasFilters' => $this->hasActiveFilters($validated),
                'request' => $request
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Activity logs error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load activity logs. Please try again.');
        }
    }

    private function getAjaxLogs(Request $request)
    {
        try {
            $validator = $this->validateFilters($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $validated = $validator->validated();
            
            $query = $this->buildQuery($validated);
            
            $stats = $this->getStatistics($query);
            
            $perPage = $validated['per_page'] ?? self::LOGS_PER_PAGE;
            $logs = $query->orderBy('created_at', 'desc')
                         ->paginate($perPage)
                         ->withQueryString();
            
            $filterData = $this->getFilterData();
            
            $html = view('admin.activity-logs.partials.table', [
                'logs' => $logs,
                'stats' => $stats,
                'models' => self::TRACKED_MODELS,
                'actions' => self::ACTION_TYPES,
                'admins' => $filterData['admins'],
                'filters' => $validated,
                'totalLogs' => $stats['total'],
                'hasFilters' => $this->hasActiveFilters($validated)
            ])->render();
            
            $statsHtml = view('admin.activity-logs.partials.stats', [
                'stats' => $stats
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'statsHtml' => $statsHtml,
                'total' => $stats['total'],
                'pagination' => (string) $logs->links(),
                'firstItem' => $logs->firstItem(),
                'lastItem' => $logs->lastItem()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('AJAX activity logs error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to load activity logs. Please try again.'
            ], 500);
        }
    }

    private function validateFilters(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'model' => 'nullable|string|max:100|in:' . implode(',', self::TRACKED_MODELS),
            'user_id' => 'nullable|integer|exists:admins,id',
            'action' => 'nullable|string|max:50|in:' . implode(',', self::ACTION_TYPES),
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:5|max:200',
            'export' => 'nullable|boolean',
            'refresh' => 'nullable|boolean',
            'ip' => 'nullable|ip',
            'subject_id' => 'nullable|integer'
        ]);

        return $validator;
    }

    private function buildQuery(array $filters): Builder
    {
        $query = Activity::query();
        
        $query->with(['causer' => function($q) {
            $q->select('id', 'name', 'email');
        }]);

        if (!empty($filters['model'])) {
            $modelClass = "App\\Models\\{$filters['model']}";
            $query->where('subject_type', $modelClass);
        }

        if (!empty($filters['user_id'])) {
            $adminMorphClass = (new Admin)->getMorphClass();
            $query->where('causer_id', $filters['user_id'])
                  ->where('causer_type', $adminMorphClass);
        }

        if (!empty($filters['action'])) {
            $query->where('description', $filters['action']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%")
                  ->orWhere('subject_id', 'like', "%{$search}%")
                  ->orWhereHas('causer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['ip'])) {
            $query->where('ip', $filters['ip']);
        }

        if (!empty($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }

        return $query;
    }

    private function getStatistics(Builder $query): array
    {
        $statsQuery = clone $query;
        
        return [
            'total' => $statsQuery->count(),
            'creations' => $statsQuery->clone()->where('description', 'created')->count(),
            'updates' => $statsQuery->clone()->where('description', 'updated')->count(),
            'deletions' => $statsQuery->clone()->where('description', 'deleted')->count(),
            'restorations' => $statsQuery->clone()->where('description', 'restored')->count(),
            'oldest_log' => $statsQuery->clone()->orderBy('created_at')->value('created_at'),
            'latest_log' => $statsQuery->clone()->orderByDesc('created_at')->value('created_at'),
            'top_admin' => $this->getTopAdmin($statsQuery),
            'busiest_hour' => $this->getBusiestHour($statsQuery)
        ];
    }

    private function getTopAdmin($query)
    {
        try {
            $adminMorphClass = (new Admin)->getMorphClass();
            return $query->clone()
                ->select('causer_id', DB::raw('COUNT(*) as activity_count'))
                ->where('causer_type', $adminMorphClass)
                ->whereNotNull('causer_id')
                ->groupBy('causer_id')
                ->orderByDesc('activity_count')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getBusiestHour($query)
    {
        try {
            return $query->clone()
                ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
                ->groupBy(DB::raw('HOUR(created_at)'))
                ->orderByDesc('count')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getFilterData(): array
    {
        $cacheKey = self::CACHE_PREFIX . 'filter_data';
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            $adminMorphClass = (new Admin)->getMorphClass();
            
            $adminStats = Activity::query()
                ->select('causer_id', DB::raw('COUNT(*) as activity_count'))
                ->where('causer_type', $adminMorphClass)
                ->whereNotNull('causer_id')
                ->groupBy('causer_id')
                ->orderByDesc('activity_count')
                ->limit(20)
                ->get()
                ->pluck('activity_count', 'causer_id')
                ->toArray();

            $adminIds = array_keys($adminStats);
            $admins = Admin::whereIn('id', $adminIds)
                ->select('id', 'name', 'email')
                ->get()
                ->map(function($admin) use ($adminStats) {
                    return [
                        'id' => $admin->id,
                        'name' => $admin->name,
                        'email' => $admin->email,
                        'activity_count' => $adminStats[$admin->id] ?? 0
                    ];
                })
                ->sortByDesc('activity_count')
                ->values()
                ->toArray();

            return ['admins' => $admins];
        });
    }

    private function hasActiveFilters(array $filters): bool
    {
        $ignored = ['per_page', 'page', 'refresh', '_token', 'ajax'];
        foreach ($filters as $key => $value) {
            if (!in_array($key, $ignored) && !empty($value)) {
                return true;
            }
        }
        return false;
    }

    public function showExportModal($query)
    {
        $totalCount = $query->count();
        $estimatedPages = ceil($totalCount / 100);
        
        return view('admin.activity-logs.export-modal', [
            'totalCount' => $totalCount,
            'maxExport' => self::MAX_EXPORT_LOGS,
            'estimatedPages' => $estimatedPages,
            'filters' => request()->except('export')
        ]);
    }
    public function export(Request $request)
    {


        $request->merge([
            'include_changes' => $request->has('include_changes'),
            'include_ip'      => $request->has('include_ip'),
        ]);
        
    
        $validator = Validator::make($request->all(), [
            'limit'            => 'required|integer|min:1|max:' . self::MAX_EXPORT_LOGS,
            'format'           => 'required|in:pdf,csv',
            'include_changes'  => 'boolean',
            'include_ip'       => 'boolean',
        ]);
    
        if ($validator->fails()) {
         
    
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        try {
            $admin = Auth::guard('admin')->user();
    
            if (!$admin) {
    
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not authenticated',
                ], 401);
            }
    
            activity()
                ->causedBy($admin)
                ->withProperties([
                    'limit'  => $request->limit,
                    'format' => $request->format,
                ])
                ->log('exported_activity_logs');
    
    
    
            $filterValidator = $this->validateFilters($request);
    
            if ($filterValidator->fails()) {
           
                return response()->json([
                    'success' => false,
                    'errors'  => $filterValidator->errors(),
                ], 422);
            }
    
            $filters = $filterValidator->validated();
    
    
            // 6️⃣ Build query
    
            $query = $this->buildQuery($filters);
    
            if (!$query) {
    
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to build export query',
                ], 500);
            }
   
    
            $logs = $query
                ->orderBy('created_at', 'desc')
                ->limit($request->limit)
                ->get();
    
 
            if ($request->input('format') === 'pdf') {
                return $this->exportPdf($logs, $request, $filters);
            }
    
            return $this->exportCsv($logs, $request, $filters);
    
        } catch (\Throwable $e) {
          
            return response()->json([
                'success' => false,
                'message' => 'Export failed. Check logs for details.',
            ], 500);
        }
    }
    
    

    private function exportPdf($logs, Request $request, array $filters)
    {
        $fileName = 'activity-logs-' . date('Y-m-d-H-i-s') . '.pdf';
        
        $data = [
            'logs' => $logs,
            'title' => 'Activity Logs Report',
            'exported_at' => now(),
            'exported_by' => Auth::guard('admin')->user()->name,
            'include_changes' => $request->include_changes ?? true,
            'include_ip' => $request->include_ip ?? true,
            'total_count' => $logs->count(),
            'filters' => $filters
        ];

        $pdf = Pdf::loadView('admin.activity-logs.pdf', $data);
        
        return $pdf->download($fileName);
    }

    private function exportCsv($logs, Request $request, array $filters)
    {
        $fileName = 'activity-logs-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($logs, $request) {
            $file = fopen('php://output', 'w');
            
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            $headers = ['ID', 'Timestamp', 'Admin Name', 'Admin Email', 'Action', 'Model Type', 'Model ID'];
            
            if ($request->include_ip ?? true) {
                $headers[] = 'IP Address';
            }
            
            if ($request->include_changes ?? true) {
                $headers[] = 'Changes';
            }
            
            $headers[] = 'Created At';
            
            fputcsv($file, $headers);

            foreach ($logs as $log) {
                $row = [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->causer->name ?? 'System',
                    $log->causer->email ?? 'system',
                    $log->description,
                    $log->subject_type ? class_basename($log->subject_type) : 'N/A',
                    $log->subject_id ?? 'N/A',
                ];
                
                if ($request->include_ip ?? true) {
                    $row[] = $log->ip ?? 'N/A';
                }
                
                if ($request->include_changes ?? true) {
                    $properties = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                    $row[] = $this->formatChangesForExport($properties);
                }
                
                $row[] = $log->created_at->toDateTimeString();
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function formatChangesForExport($properties): string
    {
        if (empty($properties) || !is_array($properties)) {
            return 'No changes';
        }

        $changes = [];
        
        if (isset($properties['attributes'])) {
            foreach ($properties['attributes'] as $key => $value) {
                $oldValue = $properties['old'][$key] ?? 'N/A';
                if ($oldValue != $value) {
                    $changes[] = ucfirst($key) . ': ' . 
                               $this->formatValue($oldValue) . ' → ' . 
                               $this->formatValue($value);
                }
            }
        }

        return implode(' | ', $changes);
    }

    private function formatValue($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        } elseif (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        } elseif (is_null($value)) {
            return 'NULL';
        }
        
        return (string) $value;
    }

    public function show($id)
    {
        try {
            $log = Activity::with(['causer', 'subject'])->findOrFail($id);
            
            if (is_string($log->properties)) {
                $log->properties = json_decode($log->properties, true);
            }
            
            $relatedLogs = Activity::where('subject_id', $log->subject_id)
                ->where('subject_type', $log->subject_type)
                ->where('id', '!=', $log->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            return view('admin.activity-logs.show', compact('log', 'relatedLogs'));
        } catch (\Exception $e) {
            return redirect()->route('admin.activity-logs.index')
                ->with('error', 'Log not found.');
        }
    }

    public function destroy($id)
    {
        try {
            $log = Activity::findOrFail($id);
            

            
            $log->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Log deleted successfully.'
                ]);
            }
            
            return redirect()->route('admin.activity-logs.index')
                ->with('success', 'Log deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete log.'
                ], 500);
            }
            
            return redirect()->route('admin.activity-logs.index')
                ->with('error', 'Failed to delete log.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:activity_log,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $logs = Activity::whereIn('id', $request->ids)->get();
            

            Activity::whereIn('id', $request->ids)->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' logs deleted successfully.',
                'deleted_count' => count($request->ids)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Bulk delete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete logs. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clearOldLogs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer|min:1|max:365'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $date = Carbon::now()->subDays($request->days);
            $count = Activity::where('created_at', '<', $date)->count();
            
     
            
            Activity::where('created_at', '<', $date)->delete();
            
            // Clear cache
            Cache::forget(self::CACHE_PREFIX . 'filter_data');
            
            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$count} logs older than {$request->days} days."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear old logs: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $validator = $this->validateFilters($request);
            
            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid filters'], 422);
            }
            
            $filters = $validator->validated();
            $query = $this->buildQuery($filters);
            
            $hourlyStats = $this->getHourlyStats($query);
            
            $dailyStats = $this->getDailyStats($query);
            
            $actionStats = $this->getActionStats($query);
            
            return response()->json([
                'hourly' => $hourlyStats,
                'daily' => $dailyStats,
                'actions' => $actionStats,
                'total' => $query->count()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch stats'], 500);
        }
    }

    private function getHourlyStats($query)
    {
        $startDate = Carbon::now()->subHours(24);
        
        return $query->clone()
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->hour => $item->count];
            })
            ->toArray();
    }

    private function getDailyStats($query)
    {
        $startDate = Carbon::now()->subDays(7);
        
        return $query->clone()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->date => $item->count];
            })
            ->toArray();
    }

    private function getActionStats($query)
    {
        return $query->clone()
            ->select('description', DB::raw('COUNT(*) as count'))
            ->groupBy('description')
            ->orderByDesc('count')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->description => $item->count];
            })
            ->toArray();
    }

    public function formatValueForDisplay($value)
    {
        if (is_null($value)) return 'NULL';
        if (is_bool($value)) return $value ? 'Yes' : 'No';
        if (is_array($value)) return json_encode($value);
        if (is_object($value)) return 'Object';
        if (strlen($value) > 100) return Str::limit($value, 100) . '...';
        return $value;
    }
}