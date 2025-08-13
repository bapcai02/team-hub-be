<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class SettingsService
{
    /**
     * Get or create user settings
     */
    public function getUserSettings(User $user): UserSetting
    {
        $settings = $user->settings;
        
        if (!$settings) {
            $settings = $user->settings()->create([
                'language' => 'en',
                'timezone' => 'UTC',
                'theme' => 'light',
                'layout' => 'comfortable',
                'sidebar_collapsed' => false,
                'two_factor_enabled' => false,
            ]);
        }
        
        return $settings;
    }

    /**
     * Update profile settings
     */
    public function updateProfile(User $user, array $data): array
    {
        $settings = $this->getUserSettings($user);

        // Update user fields
        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'phone' => $data['phone'] ?? $user->phone,
            'birth_date' => $data['birth_date'] ?? $user->birth_date,
            'gender' => $data['gender'] ?? $user->gender,
        ]);

        // Handle avatar upload
        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            $avatarPath = $this->uploadAvatar($data['avatar'], $user->avatar);
            $user->avatar = $avatarPath;
        }

        $user->save();

        // Update settings fields
        $settings->fill([
            'bio' => $data['bio'] ?? $settings->bio,
            'location' => $data['location'] ?? $settings->location,
        ]);
        $settings->save();

        return [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'birth_date' => $user->birth_date,
            'gender' => $user->gender,
            'bio' => $settings->bio,
            'location' => $settings->location,
        ];
    }

    /**
     * Update app settings
     */
    public function updateApp(User $user, array $data): array
    {
        $settings = $this->getUserSettings($user);

        $settings->fill($data);
        $settings->save();

        return [
            'language' => $settings->language,
            'timezone' => $settings->timezone,
            'theme' => $settings->theme,
            'layout' => $settings->layout,
            'sidebar_collapsed' => $settings->sidebar_collapsed,
            'dashboard_widgets' => $settings->dashboard_widgets,
            'shortcuts' => $settings->shortcuts,
        ];
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(User $user, array $data): array
    {
        $settings = $this->getUserSettings($user);

        $preferences = [];
        foreach ($data['preferences'] as $pref) {
            $preferences[$pref['category']] = $pref;
        }

        $settings->notification_preferences = $preferences;
        $settings->save();

        return $settings->notification_preferences;
    }

    /**
     * Update security settings
     */
    public function updateSecurity(User $user, array $data): array
    {
        $settings = $this->getUserSettings($user);

        // Update password if provided
        if (isset($data['new_password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                throw new \Exception('Current password is incorrect');
            }

            $user->password = Hash::make($data['new_password']);
            $user->save();
        }

        // Update 2FA setting
        if (isset($data['two_factor_enabled'])) {
            $settings->two_factor_enabled = $data['two_factor_enabled'];
            $settings->save();
        }

        return [
            'two_factor_enabled' => $settings->two_factor_enabled,
            'login_history' => $settings->login_history,
            'trusted_devices' => $settings->trusted_devices,
        ];
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(User $user, array $data): array
    {
        $settings = $this->getUserSettings($user);

        $settings->privacy_settings = $data['settings'];
        $settings->save();

        return $settings->privacy_settings;
    }

    /**
     * Update accessibility settings
     */
    public function updateAccessibility(User $user, array $data): array
    {
        $settings = $this->getUserSettings($user);

        $settings->accessibility_settings = $data['settings'];
        $settings->save();

        return $settings->accessibility_settings;
    }

    /**
     * Export user data
     */
    public function exportData(User $user): array
    {
        $settings = $this->getUserSettings($user);

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'birth_date' => $user->birth_date,
                'gender' => $user->gender,
                'created_at' => $user->created_at,
            ],
            'settings' => [
                'language' => $settings->language,
                'timezone' => $settings->timezone,
                'theme' => $settings->theme,
                'layout' => $settings->layout,
                'notification_preferences' => $settings->notification_preferences,
                'privacy_settings' => $settings->privacy_settings,
                'accessibility_settings' => $settings->accessibility_settings,
            ]
        ];
    }

    /**
     * Upload avatar
     */
    private function uploadAvatar(UploadedFile $file, ?string $oldAvatar = null): string
    {
        // Delete old avatar if exists
        if ($oldAvatar) {
            Storage::delete('public/avatars/' . $oldAvatar);
        }

        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Store new avatar
        $file->storeAs('public/avatars', $filename);
        
        return $filename;
    }
} 