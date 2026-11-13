@extends('layouts.app')
@section('title', 'Ubah Kata Sandi')
@section('content')
    <div class="password-page" id="password-page">
        <div class="password-heading">
            <h1>Ubah Kata Sandi</h1>
            <p>Perbarui kata sandi akun Anda untuk menjaga keamanan.</p>
        </div>
        <div class="password-card">
            <div class="password-profile">
                <div class="password-avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <p class="password-profile-name">{{ auth()->user()->name }}</p>
                    <p class="password-profile-email">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <div class="password-field">
                    <label for="password_lama" class="password-label">Kata Sandi Lama</label>
                    <div class="password-control">
                        <input type="password" id="password_lama" name="password_lama" required
                            autocomplete="current-password" class="password-input" placeholder="Masukkan kata sandi lama">
                        <button type="button" class="password-eye" data-password-target="password_lama"
                            aria-label="Tampilkan kata sandi lama" aria-pressed="false"><svg viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg></button>
                    </div>
                    @error('password_lama')
                        <p class="password-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="password-field">
                    <label for="password" class="password-label">Kata Sandi Baru</label>
                    <div class="password-control">
                        <input type="password" id="password" name="password" required minlength="8"
                            autocomplete="new-password" class="password-input" placeholder="Masukkan kata sandi baru">
                        <button type="button" class="password-eye" data-password-target="password"
                            aria-label="Tampilkan kata sandi baru" aria-pressed="false"><svg viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg></button>
                    </div>
                    <p class="password-help">Minimal 8 karakter.</p>
                    @error('password')
                        <p class="password-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="password-field">
                    <label for="password_confirmation" class="password-label">Konfirmasi Kata Sandi Baru</label>
                    <div class="password-control">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            minlength="8" autocomplete="new-password" class="password-input"
                            placeholder="Ulangi kata sandi baru">
                        <button type="button" class="password-eye" data-password-target="password_confirmation"
                            aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false"><svg viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg></button>
                    </div>
                </div>
                <div class="password-actions">
                    <a href="{{ route('dashboard') }}" class="password-cancel">Batal</a>
                    <button type="submit" class="password-save">Simpan Kata Sandi</button>
                </div>
            </form>
        </div>
    </div>
    <div id="password-page-skeleton" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script>
        (function() {
            const root = document.getElementById('password-page');
            const overlay = document.getElementById('password-page-skeleton');
            if (!root || !overlay) return;
            root.querySelectorAll('[data-password-target]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const input = document.getElementById(button.dataset.passwordTarget);
                    if (!input) return;
                    const visible = input.type === 'password';
                    input.type = visible ? 'text' : 'password';
                    button.setAttribute('aria-pressed', String(visible));
                    button.setAttribute('aria-label', (visible ? 'Sembunyikan ' : 'Tampilkan ') + input
                        .name.replaceAll('_', ' '));
                });
            });
            let done = false;

            function shape(element, background) {
                if (!element || !element.getClientRects().length) return;
                const rect = element.getBoundingClientRect();
                if (rect.width < 2 || rect.height < 2 || rect.bottom <= 0 || rect.top >= innerHeight) return;
                const node = document.createElement('div');
                node.className = 'pw-sk-shape';
                node.style.left = rect.left + 'px';
                node.style.top = rect.top + 'px';
                node.style.width = rect.width + 'px';
                node.style.height = rect.height + 'px';
                if (background) node.style.background = background;
                if (element.matches('.password-card')) node.style.borderRadius = '20px';
                if (element.matches('.password-avatar')) node.style.borderRadius = '15px';
                overlay.appendChild(node);
            }

            function draw() {
                if (done) return;
                overlay.replaceChildren();
                shape(root.querySelector('.password-card'), '#F1F7FB');
                root.querySelectorAll(
                    '.password-heading h1,.password-heading p,.password-avatar,.password-profile-name,.password-profile-email,.password-label,.password-input,.password-eye,.password-help,.password-error,.password-cancel,.password-save'
                    ).forEach(function(el) {
                    shape(el)
                });
            }

            function finish() {
                if (done) return;
                done = true;
                overlay.classList.add('is-finished');
                setTimeout(function() {
                    overlay.remove()
                }, 350);
            }
            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', draw, {
                once: true
            });
            else draw();
            if (document.readyState === 'complete') requestAnimationFrame(finish);
            else window.addEventListener('load', finish, {
                once: true
            });
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) finish()
            });
            setTimeout(finish, 8000);
        })();
    </script>
@endpush
