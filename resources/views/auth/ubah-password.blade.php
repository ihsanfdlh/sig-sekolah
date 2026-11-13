@extends('layouts.app')
@section('title', 'Ubah Kata Sandi')
@push('styles')
    <style>
        .password-page {
            --pw-primary: #5F91AC;
            --pw-dark: #294B63;
            --pw-line: #D6E5ED;
            --pw-muted: #607C91;
            max-width: 640px;
            margin: 0 auto;
            color: var(--pw-dark)
        }

        .password-heading {
            margin-bottom: 24px
        }

        .password-heading h1 {
            font-size: 26px;
            line-height: 1.25;
            font-weight: 800;
            letter-spacing: -.6px;
            color: var(--pw-dark);
            margin: 0
        }

        .password-heading p {
            font-size: 14px;
            color: var(--pw-muted);
            margin: 7px 0 0;
            line-height: 1.6
        }

        .password-card {
            position: relative;
            overflow: hidden;
            border: 1px solid #D6E5ED;
            background: rgba(255, 255, 255, .92);
            border-radius: 20px;
            padding: clamp(20px, 4vw, 32px);
            box-shadow: 0 16px 45px rgba(41, 75, 99, .09);
            backdrop-filter: blur(14px)
        }

        .password-profile {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 22px;
            margin-bottom: 25px;
            border-bottom: 1px solid #E3EDF3
        }

        .password-avatar {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 52px;
            height: 52px;
            border-radius: 15px;
            background: #E1EFF6;
            border: 1px solid #CDE2EE;
            color: #416D86;
            font-size: 20px;
            font-weight: 800
        }

        .password-profile-name {
            font-size: 15px;
            font-weight: 750;
            color: var(--pw-dark);
            margin: 0;
            overflow-wrap: anywhere
        }

        .password-profile-email {
            font-size: 13px;
            color: var(--pw-muted);
            margin: 4px 0 0;
            overflow-wrap: anywhere
        }

        .password-field {
            margin-bottom: 20px
        }

        .password-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #365C74
        }

        .password-control {
            position: relative
        }

        .password-input {
            display: block;
            width: 100%;
            height: 48px;
            padding: 0 50px 0 15px;
            border: 1px solid #D4E3EB;
            border-radius: 11px;
            background: #F8FBFD;
            color: var(--pw-dark);
            outline: none;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s;
            box-sizing: border-box
        }

        .password-input:focus {
            border-color: #79A7BF;
            box-shadow: 0 0 0 3px rgba(95, 145, 172, .14)
        }

        .password-input::placeholder {
            color: #9EB2BF
        }

        .password-eye {
            position: absolute;
            right: 7px;
            top: 6px;
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #66879B;
            cursor: pointer
        }

        .password-eye:hover {
            background: #E6F0F6
        }

        .password-eye svg {
            width: 19px;
            height: 19px
        }

        .password-help {
            margin: 6px 0 0;
            font-size: 12px;
            color: #71899A
        }

        .password-error {
            margin: 6px 0 0;
            font-size: 12px;
            color: #B45353
        }

        .password-actions {
            display: flex;
            justify-content: flex-end;
            gap: 11px;
            flex-wrap: wrap;
            padding-top: 21px;
            margin-top: 26px;
            border-top: 1px solid #E3EDF3
        }

        .password-cancel,
        .password-save {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 45px;
            padding: 0 19px;
            border-radius: 11px;
            font-size: 13px;
            font-weight: 750;
            text-decoration: none;
            transition: background .2s, transform .2s;
            cursor: pointer
        }

        .password-cancel {
            background: #F8FBFD;
            border: 1px solid #D4E3EB;
            color: #365C74
        }

        .password-cancel:hover {
            background: #EAF3F8
        }

        .password-save {
            background: #5F91AC;
            border: 1px solid #5F91AC;
            color: #fff;
            box-shadow: 0 8px 18px rgba(95, 145, 172, .18)
        }

        .password-save:hover {
            background: #4B7D98;
            transform: translateY(-1px)
        }

        #password-page-skeleton {
            position: fixed;
            inset: 0;
            z-index: 1500;
            pointer-events: none;
            opacity: 1;
            transition: opacity .3s ease;
            overflow: hidden
        }

        #password-page-skeleton.is-finished {
            opacity: 0
        }

        #password-page-skeleton .pw-sk-shape {
            position: absolute;
            box-sizing: border-box;
            overflow: hidden;
            background: #DDEAF2;
            border-radius: 8px
        }

        #password-page-skeleton .pw-sk-shape::after {
            content: "";
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .75), transparent);
            animation: pw-shimmer 1.4s linear infinite
        }

        @keyframes pw-shimmer {
            to {
                transform: translateX(100%)
            }
        }

        @media(max-width:480px) {
            .password-heading h1 {
                font-size: 23px
            }

            .password-card {
                border-radius: 16px
            }

            .password-actions {
                flex-direction: column-reverse
            }

            .password-cancel,
            .password-save {
                width: 100%
            }
        }

        @media(prefers-reduced-motion:reduce) {
            #password-page-skeleton .pw-sk-shape::after {
                animation: none
            }
        }
    </style>
@endpush
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
