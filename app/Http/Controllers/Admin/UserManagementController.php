<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Jobs\SendUserWelcomeEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use App\Services\UserStatisticsService;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use PDF; // Barryvdh\DomPDF\Facade\Pdf


class UserManagementController extends Controller
{
    protected $userStatsService;
    
    public function __construct(UserStatisticsService $userStatsService)
    {
        $this->userStatsService = $userStatsService;

    }
    
    /**
     * Display user management dashboard with advanced statistics
     */
    public function index(Request $request)
    {
        try {
            $stats = $this->userStatsService->getDashboardStats();
            
            $query = User::when($request->filled('search'), function($q) use ($request) {
                    $search = $request->search;
                    $q->where(function($query) use ($search) {
                        $query->where('name', 'like', "$search%")
                              ->orWhere('email', 'like', "$search%")
                              ->orWhere('phone', 'like', "$search%");
                    });
                })
                ->when($request->filled('status'), function($q) use ($request) {
                    $q->where('status', $request->status);
                })
                ->when($request->filled('role'), function($q) use ($request) {
                    $q->whereHas('roles', function($roleQuery) use ($request) {
                        $roleQuery->where('name', $request->role);
                    });
                })
                ->when($request->filled('date_from'), function($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->date_from);
                })
                ->when($request->filled('date_to'), function($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->date_to);
                });

            // Apply sorting
            $sortBy = $request->get('sortBy', 'newest');
            $sortOrder = $request->get('sortOrder', 'desc');
            
            $query = match($sortBy) {
                'name' => $query->orderBy('name', $sortOrder),
                'email' => $query->orderBy('email', $sortOrder),
                'oldest' => $query->orderBy('created_at', 'asc'),
                default => $query->orderBy('created_at', 'desc'),
            };

            // Paginate with optimized query
            $perPage = $request->get('per_page', config('settings.pagination.users', 10));
            $users = $query->paginate($perPage)
                ->withQueryString()
                ->through(fn($user) => $this->formatUserResponse($user));

            // Prepare filters for view
            $availableRoles = Role::pluck('name', 'id');
            
            return view('admin.user-management', [
                'users' => $users,
                'stats' => $stats,
                'filters' => [
                    'statuses' => ['active', 'inactive', 'blocked', 'pending'],
                    'roles' => $availableRoles,
                    'sortOptions' => [
                        'newest' => 'Newest',
                        'oldest' => 'Oldest',
                        'name' => 'Name',
                        'email' => 'Email',
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('User Management Index Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load users. Please try again.');
        }
    }
    
    /**
     * Show individual user with detailed information
     */
    public function show($id)
    {
        try {
            $user = User::with(['profile', 'roles.permissions', 'loginHistory' => function($q) {
                $q->latest()->take(10);
            }, 'activities' => function($q) {
                $q->latest()->take(20);
            }])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'user' => $this->formatUserDetailResponse($user)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }
    
    /**
     * Store new user with enhanced validation
     */
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $userData = $request->validated();
            $userData['password'] = Hash::make($userData['password']);
            $userData['email_verified_at'] = now();
            
            $user = User::create($userData);
            
            // Assign default role
            $user->assignRole($request->input('role', 'user'));
            
            // Dispatch welcome email job
            SendUserWelcomeEmail::dispatch($user, $request->password)
                ->onQueue('emails');
            
            // Clear relevant caches
            Cache::tags(['users', 'user_stats'])->flush();
            
            DB::commit();
            
            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('created user');
            
            return response()->json([
                'success' => true,
                'message' => 'User created successfully!',
                'user' => $this->formatUserResponse($user)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Creation Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Update user with transaction safety
     */
    public function update(UpdateUserRequest $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $user = User::findOrFail($id);
            $userData = $request->validated();
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($userData['password']);
            }
            
            $user->update($userData);
            
            // Sync roles if provided
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }
            
            DB::commit();
            
            Cache::tags(['user_' . $user->id])->flush();
            
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('updated user');
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'user' => $this->formatUserResponse($user)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user.'
            ], 500);
        }
    }
    
    /**
     * Soft delete user
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent self-deletion
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account.'
                ], 403);
            }
            
            $user->delete();
            
            Cache::tags(['user_' . $user->id, 'users'])->flush();
            
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('deleted user');
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user.'
            ], 500);
        }
    }
    
    /**
     * Toggle user block status
     */
    public function toggleBlock(User $user)
    {
        $newStatus = $user->status === 'blocked' ? 'active' : 'blocked';
        $user->update(['status' => $newStatus]);
        
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log(($newStatus === 'blocked' ? 'blocked' : 'unblocked') . ' user');
        
        return response()->json([
            'success' => true,
            'message' => "User has been " . ($newStatus === 'blocked' ? 'blocked' : 'unblocked'),
            'status' => $newStatus
        ]);
    }
    
    /**
     * Bulk actions on users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,block,activate,export',
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);
        
        DB::beginTransaction();

        try {
            $users = User::whereIn('id', $request->users)->get();
            $count = 0;
        
            switch ($request->action) {
                case 'delete':
                    foreach ($users as $user) {
                        if ($user->id !== auth()->id()) {
                            $user->delete();
                            $count++;
                        }
                    }
                    break;
        
                case 'block':
                    User::whereIn('id', $request->users)
                        ->update(['status' => 'blocked']);
                    $count = count($request->users);
                    break;
        
                case 'activate':
                    User::whereIn('id', $request->users)
                        ->update(['status' => 'active']);
                    $count = count($request->users);
                    break;
        
                case 'export':
                    return Excel::download(new UsersExport($request->users), 'users-export-' . now()->format('Y-m-d') . '.xlsx');
        
                case 'pdf':
                    // Generate PDF
                    $pdf = PDF::loadView('admin.users.pdf', compact('users'));
                    $filename = 'users-export-' . now()->format('Y-m-d') . '.pdf';
                    return $pdf->download($filename);
            }
        
            DB::commit();
            
            Cache::tags(['users', 'user_stats'])->flush();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully performed action on {$count} users."
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action.'
            ], 500);
        }
    }
    
    /**
     * Export users to CSV/Excel
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'xlsx'); // default to Excel
    
        $timestamp = now()->format('Y-m-d-H-i');
    
        if ($format === 'pdf') {
            // Fetch users for PDF
            $users = User::whereIn('id', $request->input('users', []))->get();
    
            $pdf = PDF::loadView('admin.users.pdf', compact('users'));
            return $pdf->download('users-export-' . $timestamp . '.pdf');
        } else {
            // Default to Excel export
            return Excel::download(
                new UsersExport($request->all()),
                'users-export-' . $timestamp . '.xlsx'
            );
        }

    }
    /**
     * Get user activity statistics
     */
    public function getActivityStats(Request $request)
    {
        $stats = $this->userStatsService->getActivityStats(
            $request->get('period', 'monthly')
        );
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
    
    /**
     * Format user for response
     */
    private function formatUserResponse(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar_url ?? $this->generateAvatar($user->name, $user->email),
            'status' => $user->status,
            'created_at' => $user->created_at->format('M d, Y H:i'),
            'email_verified' => !is_null($user->email_verified_at),
            'phone' => $user->phone,
            'profile_url' => route('admin.users.show', $user->id)
        ];
    }
    
    /**
     * Generate avatar URL
     */
    private function generateAvatar($name, $email): string
    {
        return "https://ui-avatars.com/api/?name=" . urlencode($name) . 
               "&background=random&color=fff&bold=true&size=128";
    }
    
    /**
     * Format detailed user response
     */
    private function formatUserDetailResponse(User $user): array
    {
        $base = $this->formatUserResponse($user);
        
        return array_merge($base, [
            'profile' => [
                'bio' => $user->profile->bio ?? null,
                'company' => $user->profile->company ?? null,
                'job_title' => $user->profile->job_title ?? null,
                'location' => $user->profile->location ?? null,
                'website' => $user->profile->website ?? null,
                'social_links' => json_decode($user->profile->social_links ?? '[]'),
            ],
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'login_history' => $user->loginHistory->map(function($login) {
                return [
                    'ip_address' => $login->ip_address,
                    'browser' => $login->browser,
                    'platform' => $login->platform,
                    'login_at' => $login->login_at->format('Y-m-d H:i:s'),
                    'success' => $login->success
                ];
            }),
            'activity_summary' => [
                'total_logins' => $user->loginHistory()->count(),
                'failed_logins' => $user->loginHistory()->where('success', false)->count(),
                'last_failed_login' => $user->loginHistory()->where('success', false)->latest()->first()->created_at ?? null,
            ]
        ]);
    }
}