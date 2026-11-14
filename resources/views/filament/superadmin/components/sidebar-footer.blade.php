<div style="padding: 0.75rem 0.875rem; border-top: 1px solid rgba(255, 255, 255, 0.08); background: rgba(2, 11, 31, 0.4); user-select: none;">
    <form method="POST" action="{{ route('filament.superadmin.auth.logout') }}" style="margin: 0;">
        @csrf
        <button 
            type="submit" 
            title="Keluar dari Sistem Super Admin"
            style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.6rem 0.85rem; border-radius: 0.625rem; background: rgba(239, 68, 68, 0.12); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.25); font-size: 0.8125rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease; font-family: 'Plus Jakarta Sans', sans-serif;"
            onmouseover="this.style.background='rgba(239, 68, 68, 0.22)'; this.style.color='#ffffff'; this.style.borderColor='rgba(239, 68, 68, 0.5)';"
            onmouseout="this.style.background='rgba(239, 68, 68, 0.12)'; this.style.color='#fca5a5'; this.style.borderColor='rgba(239, 68, 68, 0.25)';"
        >
            <svg style="width: 1rem; height: 1rem; max-width: 1rem; max-height: 1rem; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span>Keluar (Logout)</span>
        </button>
    </form>
</div>
