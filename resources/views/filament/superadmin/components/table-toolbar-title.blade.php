@if (request()->routeIs('filament.superadmin.resources.users.index'))
    <div class="fi-ta-toolbar-title" style="display: flex; align-items: center; gap: 0.55rem; margin-right: auto; padding: 0.2rem 0;">
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; border-radius: 0.5rem; background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe;">
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </span>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span style="font-size: 0.9375rem; font-weight: 700; color: #0f172a; letter-spacing: -0.01em;">
                Data Pengguna
            </span>
            <span style="display: inline-flex; align-items: center; padding: 0.15rem 0.55rem; border-radius: 9999px; font-size: 0.725rem; font-weight: 600; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
                {{ \App\Models\User::count() }} Akun
            </span>
        </div>
    </div>
@endif
