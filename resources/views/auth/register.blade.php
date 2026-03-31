@extends('layouts.guest')

@section('title', 'Đăng ký - ' . config('app.name'))
@section('body_class', 'font-sans antialiased register-page')

@push('styles')
<style>
    .register-page {
        min-height: 100vh;
    }

    .register-page .guest-shell {
        min-height: 100vh;
        align-items: stretch;
        padding: clamp(14px, 2vw, 28px);
    }

    .register-page .guest-main {
        width: 100%;
        max-width: none;
        display: flex;
    }

    .register-stage {
        width: 100%;
        min-height: calc(100vh - clamp(28px, 4vw, 56px));
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
        border-radius: 30px;
        overflow: hidden;
        border: 1px solid color-mix(in srgb, var(--line) 84%, transparent);
        box-shadow: 0 24px 56px rgba(15, 23, 42, 0.16);
        background: color-mix(in srgb, var(--surface) 94%, white);
    }

    .register-story {
        position: relative;
        padding: 44px 40px;
        color: #ecfeff;
        background:
            radial-gradient(circle at 18% 20%, rgba(45, 212, 191, 0.32), transparent 35%),
            radial-gradient(circle at 82% 84%, rgba(56, 189, 248, 0.28), transparent 38%),
            linear-gradient(150deg, #0b2f4b 0%, #0f4c81 48%, #0f766e 100%);
    }

    .register-story::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        opacity: 0.26;
        background-image:
            linear-gradient(rgba(236, 254, 255, 0.22) 1px, transparent 1px),
            linear-gradient(90deg, rgba(236, 254, 255, 0.22) 1px, transparent 1px);
        background-size: 32px 32px;
    }

    .register-story > * {
        position: relative;
        z-index: 1;
    }

    .register-story-kicker {
        display: inline-flex;
        font-size: 0.72rem;
        line-height: 1rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.22em;
        color: rgba(236, 254, 255, 0.78);
    }

    .register-story-title {
        margin-top: 1rem;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: clamp(1.9rem, 4vw, 2.4rem);
        line-height: 1.18;
        font-weight: 700;
        letter-spacing: -0.015em;
        color: #f8fafc;
    }

    .register-story-copy {
        margin-top: 1rem;
        max-width: 36ch;
        font-size: 0.97rem;
        color: rgba(226, 232, 240, 0.88);
    }

    .register-story-benefits {
        margin-top: 1.75rem;
        display: grid;
        gap: 0.7rem;
    }

    .register-story-benefit {
        display: inline-flex;
        align-items: flex-start;
        gap: 0.65rem;
        border-radius: 0.75rem;
        padding: 0.65rem 0.85rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #e0f2fe;
        border: 1px solid rgba(186, 230, 253, 0.28);
        background: rgba(8, 47, 73, 0.34);
    }

    .register-story-benefit svg {
        width: 16px;
        height: 16px;
        color: #67e8f9;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .register-story-footnote {
        margin-top: 1.75rem;
        font-size: 0.76rem;
        color: rgba(226, 232, 240, 0.76);
    }

    .register-panel {
        padding: 32px 34px;
        background:
            radial-gradient(circle at 100% 0%, rgba(15, 118, 110, 0.08), transparent 34%),
            linear-gradient(180deg, color-mix(in srgb, var(--surface) 96%, white), var(--surface));
        overflow-y: auto;
    }

    .register-panel-head {
        margin-bottom: 1.5rem;
    }

    .register-panel-kicker {
        font-size: 0.72rem;
        line-height: 1rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.16em;
        color: color-mix(in srgb, var(--brand) 78%, var(--text-secondary));
    }

    .register-panel-title {
        margin-top: 0.5rem;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: 1.7rem;
        line-height: 1.2;
        font-weight: 700;
        letter-spacing: -0.015em;
        color: var(--text-primary);
    }

    .register-panel-copy {
        margin-top: 0.5rem;
        font-size: 0.93rem;
        color: var(--text-secondary);
    }

    .register-alert-success {
        margin-bottom: 1.25rem;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        color: #065f46;
        border: 1px solid #86efac;
        background: #ecfdf5;
    }

    .register-alert-error {
        margin-bottom: 1.25rem;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        color: #7f1d1d;
        border: 1px solid #fca5a5;
        background: #fef2f2;
    }

    .register-form {
        display: grid;
        gap: 0.9rem;
    }

    .register-field {
        display: grid;
        gap: 0.38rem;
    }

    .register-field-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .register-input-wrap {
        position: relative;
    }

    .register-field-icon {
        pointer-events: none;
        position: absolute;
        inset: 0;
        right: auto;
        display: flex;
        align-items: center;
        padding-left: 0.75rem;
        color: #64748b;
    }

    .register-input,
    .register-select {
        width: 100%;
        border-radius: 0.75rem;
        padding: 0.72rem 0.8rem 0.72rem 2.45rem;
        font-size: 0.92rem;
        border: 1px solid var(--line);
        background: color-mix(in srgb, var(--surface-soft) 80%, white);
        color: var(--text-primary);
        transition: all 0.2s ease;
    }

    .register-input:focus,
    .register-select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.18);
        outline: none;
    }

    .register-requirements {
        margin-top: 0.5rem;
        padding: 0.65rem 0.85rem;
        border-radius: 0.5rem;
        background: rgba(241, 245, 249, 0.6);
        font-size: 0.82rem;
        color: var(--text-secondary);
    }

    .register-requirements ul {
        list-style: none;
        padding: 0;
        margin: 0.25rem 0 0 0;
    }

    .register-requirements li {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.15rem 0;
    }

    .register-requirements li::before {
        content: "•";
        color: #0f766e;
        font-weight: bold;
    }

    .register-submit {
        display: inline-flex;
        width: 100%;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        padding: 0.78rem 1rem;
        border: none;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: white;
        transition: all 0.2s ease;
        background: linear-gradient(130deg, #0f766e 0%, #0f4c81 100%);
        box-shadow: 0 10px 26px rgba(15, 76, 129, 0.28);
        margin-top: 0.5rem;
    }

    .register-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 14px 32px rgba(15, 76, 129, 0.34);
    }

    .register-submit:active {
        transform: translateY(0);
    }

    .register-notice {
        margin-top: 1rem;
        padding: 0.75rem 0.85rem;
        border-radius: 0.75rem;
        font-size: 0.86rem;
        color: #0c4a6e;
        border: 1px solid #bae6fd;
        background: linear-gradient(140deg, #f0f9ff 0%, #ecfeff 100%);
        text-align: center;
    }

    .register-login-link {
        margin-top: 1rem;
        text-align: center;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .register-login-link a {
        font-weight: 600;
        color: color-mix(in srgb, var(--brand) 88%, var(--accent));
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .register-login-link a:hover {
        color: color-mix(in srgb, var(--brand-strong) 90%, var(--accent));
    }

    /* Dark mode styles */
    .dark .register-stage {
        border-color: rgba(71, 85, 105, 0.62);
        box-shadow: 0 24px 56px rgba(2, 6, 23, 0.46);
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.95), rgba(17, 24, 39, 0.97));
    }

    .dark .register-story {
        color: #e2e8f0;
        background:
            radial-gradient(circle at 18% 20%, rgba(45, 212, 191, 0.24), transparent 35%),
            radial-gradient(circle at 82% 84%, rgba(56, 189, 248, 0.22), transparent 38%),
            linear-gradient(150deg, #08192e 0%, #0d355a 48%, #0b4d4b 100%);
    }

    .dark .register-story-kicker {
        color: rgba(207, 250, 254, 0.76);
    }

    .dark .register-story-copy,
    .dark .register-story-footnote {
        color: rgba(203, 213, 225, 0.9);
    }

    .dark .register-story-benefit {
        color: #bae6fd;
        border-color: rgba(103, 232, 249, 0.3);
        background: rgba(6, 24, 45, 0.48);
    }

    .dark .register-panel {
        background:
            radial-gradient(circle at 100% 0%, rgba(45, 212, 191, 0.1), transparent 34%),
            linear-gradient(180deg, rgba(15, 23, 42, 0.95), rgba(17, 24, 39, 0.98));
    }

    .dark .register-field-icon {
        color: #94a3b8;
    }

    .dark .register-input,
    .dark .register-select {
        border-color: rgba(71, 85, 105, 0.74);
        background: rgba(15, 23, 42, 0.85);
        color: #e2e8f0;
    }

    .dark .register-input:focus,
    .dark .register-select:focus {
        border-color: #2dd4bf;
        box-shadow: 0 0 0 3px rgba(45, 212, 191, 0.2);
    }

    .dark .register-requirements {
        background: rgba(30, 41, 59, 0.6);
        color: #cbd5e1;
    }

    .dark .register-notice {
        color: #bae6fd;
        border-color: rgba(103, 232, 249, 0.32);
        background: linear-gradient(140deg, rgba(12, 74, 110, 0.32), rgba(15, 23, 42, 0.76));
    }

    .dark .register-alert-success {
        color: #6ee7b7;
        border-color: rgba(52, 211, 153, 0.36);
        background: rgba(6, 78, 59, 0.4);
    }

    .dark .register-alert-error {
        color: #fca5a5;
        border-color: rgba(248, 113, 113, 0.36);
        background: rgba(127, 29, 29, 0.4);
    }

    @media (max-width: 960px) {
        .register-page .guest-shell {
            padding: 0;
        }

        .register-stage {
            min-height: 100vh;
            grid-template-columns: 1fr;
            border-radius: 0;
        }

        .register-story {
            display: none;
        }

        .register-panel {
            padding: 30px 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="register-stage animate-fade-in-up">
    <aside class="register-story">
        <p class="register-story-kicker">HỆ THỐNG QUẢN LÝ THIẾT BỊ</p>
        <h1 class="register-story-title">Tham gia cộng đồng giáo viên sáng tạo</h1>
        <p class="register-story-copy">
            Đăng ký tài khoản để truy cập vào kho thiết bị dạy học phong phú, 
            lập kế hoạch giảng dạy thông minh và quản lý mượn trả hiệu quả.
        </p>

        <div class="register-story-benefits">
            <span class="register-story-benefit">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Mượn trả thiết bị dạy học nhanh chóng, dễ dàng</span>
            </span>
            <span class="register-story-benefit">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Lập kế hoạch giảng dạy và lưu mẫu mượn thường dùng</span>
            </span>
            <span class="register-story-benefit">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span>Nhận thông báo thông minh về lịch mượn trả</span>
            </span>
            <span class="register-story-benefit">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Trợ lý AI hỗ trợ tìm kiếm thiết bị phù hợp</span>
            </span>
        </div>

        <p class="register-story-footnote">
            Hệ thống được phát triển bởi nhà trường, dành riêng cho giáo viên và cán bộ.
        </p>
    </aside>

    <section class="register-panel">
        <div class="register-panel-head">
            <p class="register-panel-kicker">TẠO TÀI KHOẢN MỚI</p>
            <h2 class="register-panel-title">Đăng ký tài khoản</h2>
            <p class="register-panel-copy">Điền thông tin để tạo tài khoản giáo viên</p>
        </div>

        @if(session('success'))
        <div class="register-alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="register-alert-error">
            <strong>Có lỗi xảy ra:</strong>
            <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf

            <div class="register-field">
                <label for="name" class="register-field-label">Họ và tên</label>
                <div class="register-input-wrap">
                    <span class="register-field-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        value="{{ old('name') }}"
                        class="register-input"
                        placeholder="Nguyễn Văn A"
                    >
                </div>
            </div>

            <div class="register-field">
                <label for="email" class="register-field-label">Email</label>
                <div class="register-input-wrap">
                    <span class="register-field-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        autocomplete="email"
                        value="{{ old('email') }}"
                        class="register-input"
                        placeholder="email@truong.edu.vn"
                    >
                </div>
            </div>

            <div class="register-field">
                <label for="phone" class="register-field-label">Số điện thoại <span style="font-weight: normal; color: #94a3b8;">(không bắt buộc)</span></label>
                <div class="register-input-wrap">
                    <span class="register-field-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </span>
                    <input
                        id="phone"
                        name="phone"
                        type="text"
                        value="{{ old('phone') }}"
                        class="register-input"
                        placeholder="0901234567"
                    >
                </div>
            </div>

            <div class="register-field">
                <label for="department_id" class="register-field-label">Tổ chuyên môn</label>
                <div class="register-input-wrap">
                    <span class="register-field-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </span>
                    <select
                        id="department_id"
                        name="department_id"
                        required
                        class="register-select"
                    >
                        <option value="">-- Chọn tổ chuyên môn --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="register-field">
                <label for="password" class="register-field-label">Mật khẩu</label>
                <div class="register-input-wrap">
                    <span class="register-field-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="register-input"
                        placeholder="••••••••"
                    >
                </div>
            </div>

            <div class="register-field">
                <label for="password_confirmation" class="register-field-label">Xác nhận mật khẩu</label>
                <div class="register-input-wrap">
                    <span class="register-field-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </span>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="register-input"
                        placeholder="••••••••"
                    >
                </div>
            </div>

            <div class="register-requirements">
                <strong>Yêu cầu mật khẩu:</strong>
                <ul>
                    <li>Tối thiểu 8 ký tự</li>
                    <li>Nên có cả chữ hoa, chữ thường và số</li>
                </ul>
            </div>

            <button type="submit" class="register-submit">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Đăng ký tài khoản
            </button>
        </form>

        <div class="register-notice">
            <svg class="h-5 w-5 inline-block mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Sau khi đăng ký, vui lòng kiểm tra email để xác thực tài khoản
        </div>

        <div class="register-login-link">
            Đã có tài khoản? 
            <a href="{{ route('login') }}">Đăng nhập ngay</a>
        </div>
    </section>
</div>
@endsection