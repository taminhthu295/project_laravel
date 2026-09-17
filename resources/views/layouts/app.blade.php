<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Thư viện sách')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📖</text></svg>">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a href="{{ route('home') }}" class="site-header__title">Thư viện sách</a>
            <nav class="site-header__nav">
                <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'active' : '' }}">Sách</a>
                <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Thể loại</a>
            </nav>
        </div>
    </header>

    @if(session('success'))
        <div class="toast-popup toast-popup--success" id="toastNotification">
            <div class="toast-popup__icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>
            <div class="toast-popup__content">
                <div class="toast-popup__title">Thành công</div>
                <div class="toast-popup__message">{{ session('success') }}</div>
            </div>
            <button type="button" class="toast-popup__close" onclick="closeToast(this)" aria-label="Đóng">&times;</button>
            <div class="toast-popup__progress"></div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast-popup toast-popup--error" id="toastNotification">
            <div class="toast-popup__icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>
            <div class="toast-popup__content">
                <div class="toast-popup__title">Thông báo lỗi</div>
                <div class="toast-popup__message">{{ session('error') }}</div>
            </div>
            <button type="button" class="toast-popup__close" onclick="closeToast(this)" aria-label="Đóng">&times;</button>
            <div class="toast-popup__progress"></div>
        </div>
    @endif

    <main class="container">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-footer__inner">
            <p>© {{ date('Y') }} Thư viện sách. All rights reserved.</p>
        </div>
    </footer>

    {{-- Custom Confirmation Modal --}}
    <div id="confirmModal" class="confirm-modal" aria-hidden="true">
        <div class="confirm-modal__backdrop" onclick="closeConfirmModal()"></div>
        <div class="confirm-modal__dialog" role="dialog" aria-modal="true">
            <div class="confirm-modal__icon">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="confirm-modal__body">
                <h3 class="confirm-modal__title" id="confirmModalTitle">Xác nhận xóa</h3>
                <p class="confirm-modal__message" id="confirmModalMessage">Bạn có chắc chắn muốn thực hiện hành động này? Thao tác này không thể hoàn tác.</p>
            </div>
            <div class="confirm-modal__actions">
                <button type="button" class="btn confirm-modal__btn-cancel" onclick="closeConfirmModal()">Hủy bỏ</button>
                <button type="button" class="btn confirm-modal__btn-confirm" id="confirmModalSubmitBtn">Xác nhận</button>
            </div>
        </div>
    </div>

    <script>
        // Toast Notification Handler
        function closeToast(btn) {
            const toast = btn.closest('.toast-popup');
            if (toast) {
                toast.classList.add('toast-popup--hide');
                setTimeout(() => toast.remove(), 300);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toastNotification');
            if (toast) {
                setTimeout(() => {
                    toast.classList.add('toast-popup--hide');
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            }
        });

        // Custom Confirm Modal Handler
        let pendingFormToSubmit = null;

        function openConfirmModal(message, formOrSubmitFn, title = 'Xác nhận xóa') {
            const modal = document.getElementById('confirmModal');
            const msgEl = document.getElementById('confirmModalMessage');
            const titleEl = document.getElementById('confirmModalTitle');
            
            if (msgEl && message) msgEl.textContent = message;
            if (titleEl && title) titleEl.textContent = title;

            if (typeof formOrSubmitFn === 'string') {
                pendingFormToSubmit = document.getElementById(formOrSubmitFn);
            } else if (formOrSubmitFn instanceof HTMLFormElement) {
                pendingFormToSubmit = formOrSubmitFn;
            } else if (typeof formOrSubmitFn === 'function') {
                pendingFormToSubmit = formOrSubmitFn;
            }

            if (modal) {
                modal.classList.add('confirm-modal--active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            if (modal) {
                modal.classList.remove('confirm-modal--active');
                document.body.style.overflow = '';
            }
            pendingFormToSubmit = null;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const confirmBtn = document.getElementById('confirmModalSubmitBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    if (typeof pendingFormToSubmit === 'function') {
                        pendingFormToSubmit();
                    } else if (pendingFormToSubmit && typeof pendingFormToSubmit.submit === 'function') {
                        pendingFormToSubmit.submit();
                    }
                    closeConfirmModal();
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeConfirmModal();
            });
        });
    </script>
</body>
</html>