<?php

namespace App\Models;

use App\Enums\UserRole;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $attributes = [
        'role' => UserRole::Admin->value,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isAdminHost(): bool
    {
        return $this->role === UserRole::AdminHost;
    }

    public function isSuperadmin(): bool
    {
        return $this->role === UserRole::Superadmin;
    }

    public function hasLimitedAdminAccess(): bool
    {
        return $this->isAdminHost();
    }

    public function roleTheme(): array
    {
        if ($this->isSuperadmin()) {
            return [
                'name' => 'superadmin',
                'accent' => 'gold',
                'text' => 'text-gold-soft',
                'text_subtle' => 'text-gold-soft/80',
                'text_heading' => 'text-gold-soft/75',
                'bg_glow_1' => 'bg-gold/6',
                'bg_glow_2' => 'bg-gold/4',
                'badge_border' => 'border-gold/25',
                'badge_bg' => 'bg-gold/10',
                'badge_text' => 'text-gold-soft/90',
                'dot' => 'bg-gold',
                'active_sidebar' => 'border-gold bg-gradient-to-r from-gold/18 via-gold/8 to-transparent text-gold-soft font-semibold shadow-sm',
                'active_child' => 'border-l-2 border-gold bg-gold/15 text-gold-soft font-semibold',
                'avatar_border' => 'border-gold/25',
                'avatar_bg' => 'bg-gold/12',
                'avatar_text' => 'text-gold-soft',
                'btn_primary' => 'bg-gold text-obsidian hover:bg-gold-soft shadow-[0_4px_20px_rgba(199,161,90,0.35)]',
                'topbar_shimmer' => 'via-gold/40',
                'topbar_dot' => 'bg-gold',
                'topbar_text' => 'text-gold-soft/80',
                'topbar_box' => 'border-gold/30 bg-gold/15 text-gold-soft',
                'hero_border' => 'border-gold/25',
                'hero_bg' => 'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.2),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)]',
                'hero_shimmer' => 'via-gold/50',
                'hero_glow' => 'bg-gold/10',
                'gradient_text' => 'from-gold-soft via-champagne to-gold-soft',
                'role_label' => 'Superadmin',
                'role_badge_bg' => 'bg-gold/15 text-gold-soft border-gold/30',
            ];
        }

        if ($this->isAdminHost()) {
            return [
                'name' => 'admin_host',
                'accent' => 'blue',
                'text' => 'text-blue-300',
                'text_subtle' => 'text-blue-300/80',
                'text_heading' => 'text-blue-300/75',
                'bg_glow_1' => 'bg-blue-500/8',
                'bg_glow_2' => 'bg-blue-500/5',
                'badge_border' => 'border-blue-500/35',
                'badge_bg' => 'bg-blue-500/10',
                'badge_text' => 'text-blue-300',
                'dot' => 'bg-blue-500',
                'active_sidebar' => 'border-blue-500 bg-gradient-to-r from-blue-500/18 via-blue-500/8 to-transparent text-blue-300 font-semibold shadow-sm',
                'active_child' => 'border-l-2 border-blue-500 bg-blue-500/15 text-blue-300 font-semibold',
                'avatar_border' => 'border-blue-500/30',
                'avatar_bg' => 'bg-blue-500/12',
                'avatar_text' => 'text-blue-300',
                'btn_primary' => 'bg-blue-500 text-white hover:bg-blue-600 shadow-[0_4px_20px_rgba(59,130,246,0.4)]',
                'topbar_shimmer' => 'via-blue-500/40',
                'topbar_dot' => 'bg-blue-500',
                'topbar_text' => 'text-blue-300/90',
                'topbar_box' => 'border-blue-500/30 bg-blue-500/15 text-blue-300',
                'hero_border' => 'border-blue-500/25',
                'hero_bg' => 'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(59,130,246,0.18),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)]',
                'hero_shimmer' => 'via-blue-500/50',
                'hero_glow' => 'bg-blue-500/10',
                'gradient_text' => 'from-blue-400 via-blue-300 to-blue-400',
                'role_label' => 'Admin Host',
                'role_badge_bg' => 'bg-blue-500/15 text-blue-300 border-blue-500/30',
            ];
        }

        // Default Admin -> Purple
        return [
            'name' => 'admin',
            'accent' => 'purple',
            'text' => 'text-[rgba(196,181,253,0.95)]',
            'text_subtle' => 'text-[rgba(196,181,253,0.8)]',
            'text_heading' => 'text-[rgba(196,181,253,0.75)]',
            'bg_glow_1' => 'bg-[rgba(139,92,246,0.08)]',
            'bg_glow_2' => 'bg-[rgba(139,92,246,0.05)]',
            'badge_border' => 'border-[rgba(139,92,246,0.35)]',
            'badge_bg' => 'bg-[rgba(139,92,246,0.1)]',
            'badge_text' => 'text-[rgba(196,181,253,0.9)]',
            'dot' => 'bg-[rgba(139,92,246,0.9)]',
            'active_sidebar' => 'border-[rgba(139,92,246,0.8)] bg-gradient-to-r from-[rgba(139,92,246,0.18)] via-[rgba(139,92,246,0.08)] to-transparent text-[rgba(196,181,253,0.95)] font-semibold shadow-sm',
            'active_child' => 'border-l-2 border-[rgba(139,92,246,0.8)] bg-[rgba(139,92,246,0.15)] text-[rgba(196,181,253,0.95)] font-semibold',
            'avatar_border' => 'border-[rgba(139,92,246,0.3)]',
            'avatar_bg' => 'bg-[rgba(139,92,246,0.15)]',
            'avatar_text' => 'text-[rgba(196,181,253,0.95)]',
            'btn_primary' => 'bg-[rgba(139,92,246,1)] text-white hover:bg-[rgba(109,40,217,1)] shadow-[0_4px_20px_rgba(139,92,246,0.4)]',
            'topbar_shimmer' => 'via-[rgba(139,92,246,0.4)]',
            'topbar_dot' => 'bg-[rgba(139,92,246,0.9)]',
            'topbar_text' => 'text-[rgba(196,181,253,0.9)]',
            'topbar_box' => 'border-[rgba(139,92,246,0.3)] bg-[rgba(139,92,246,0.15)] text-[rgba(196,181,253,0.9)]',
            'hero_border' => 'border-[rgba(139,92,246,0.25)]',
            'hero_bg' => 'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(139,92,246,0.2),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)]',
            'hero_shimmer' => 'via-[rgba(139,92,246,0.5)]',
            'hero_glow' => 'bg-[rgba(139,92,246,0.1)]',
            'gradient_text' => 'from-[rgba(167,139,250,1)] via-[rgba(196,181,253,0.95)] to-[rgba(167,139,250,0.8)]',
            'role_label' => 'Admin',
            'role_badge_bg' => 'bg-[rgba(139,92,246,0.15)] text-[rgba(196,181,253,0.9)] border-[rgba(139,92,246,0.3)]',
        ];
    }

    public function adminLandingRouteName(): string
    {
        return 'dashboard';
    }

    public function canAccessAdminRoute(?string $routeName): bool
    {
        if (! $this->hasLimitedAdminAccess()) {
            return true;
        }

        if ($routeName === null) {
            return false;
        }

        return collect($this->adminHostAllowedRoutePatterns())
            ->contains(fn (string $pattern): bool => Str::is($pattern, $routeName));
    }

    /**
     * @return array<int, string>
     */
    private function adminHostAllowedRoutePatterns(): array
    {
        return [
            'dashboard',
            'signal.*',
            'signal-categories.*',
            'berita.*',
            'berita-categories.*',
            'ebook.*',
            'ebook-categories.*',
            'tinymce.images.store',
            'logout',
        ];
    }
}
