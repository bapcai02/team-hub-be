<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;

class SettingsController extends BaseController
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Get all user settings
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $settings = $this->settingsService->getUserSettings($user);
        
        return response()->json([
            'success' => true,
            'data' => [
                'profile' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'phone' => $user->phone,
                    'birth_date' => $user->birth_date,
                    'gender' => $user->gender,
                    'bio' => $settings->bio,
                    'location' => $settings->location,
                ],
                'app' => [
                    'language' => $settings->language,
                    'timezone' => $settings->timezone,
                    'theme' => $settings->theme,
                    'layout' => $settings->layout,
                    'sidebar_collapsed' => $settings->sidebar_collapsed,
                    'dashboard_widgets' => $settings->dashboard_widgets,
                    'shortcuts' => $settings->shortcuts,
                ],
                'notifications' => $settings->notification_preferences,
                'security' => [
                    'two_factor_enabled' => $settings->two_factor_enabled,
                    'login_history' => $settings->login_history,
                    'trusted_devices' => $settings->trusted_devices,
                ],
                'integrations' => $settings->integrations,
                'privacy' => $settings->privacy_settings,
                'accessibility' => $settings->accessibility_settings,
            ]
        ]);
    }

    /**
     * Update profile settings
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'birth_date' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'bio' => 'sometimes|string|max:500',
            'location' => 'sometimes|string|max:255',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $data = $request->all();
            
            // Handle avatar file
            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar');
            }

            $profile = $this->settingsService->updateProfile($user, $data);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'profile' => $profile
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update app settings
     */
    public function updateApp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'language' => 'sometimes|string|in:en,vi',
            'timezone' => 'sometimes|string',
            'theme' => 'sometimes|in:light,dark',
            'layout' => 'sometimes|in:compact,comfortable',
            'sidebar_collapsed' => 'sometimes|boolean',
            'dashboard_widgets' => 'sometimes|array',
            'shortcuts' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $app = $this->settingsService->updateApp($user, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'App settings updated successfully',
                'data' => [
                    'app' => $app
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update app settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'preferences' => 'required|array',
            'preferences.*.category' => 'required|string',
            'preferences.*.channels' => 'sometimes|array',
            'preferences.*.frequency' => 'sometimes|string',
            'preferences.*.quiet_hours' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $notifications = $this->settingsService->updateNotifications($user, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Notification preferences updated successfully',
                'data' => [
                    'notifications' => $notifications
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required_with:new_password|string',
            'new_password' => 'sometimes|string|min:8|confirmed',
            'two_factor_enabled' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $security = $this->settingsService->updateSecurity($user, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Security settings updated successfully',
                'data' => [
                    'security' => $security
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update security settings: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $privacy = $this->settingsService->updatePrivacy($user, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Privacy settings updated successfully',
                'data' => [
                    'privacy' => $privacy
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update privacy settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update accessibility settings
     */
    public function updateAccessibility(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $accessibility = $this->settingsService->updateAccessibility($user, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Accessibility settings updated successfully',
                'data' => [
                    'accessibility' => $accessibility
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update accessibility settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export user data
     */
    public function exportData(): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = $this->settingsService->exportData($user);

            return response()->json([
                'success' => true,
                'message' => 'Data exported successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export data: ' . $e->getMessage()
            ], 500);
        }
    }
} 