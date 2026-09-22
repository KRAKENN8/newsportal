<!-- CyberPulse High-Tech Action Confirmation Modal -->
<div class="cp-modal-backdrop" id="cpConfirmModalBackdrop" role="dialog" aria-modal="true" aria-labelledby="cpModalTitle" aria-describedby="cpModalMessage" style="display:none;">
    <div class="cp-modal-dialog">
        <div class="cp-modal-accent-bar"></div>
        <div class="cp-modal-header">
            <div class="cp-modal-icon-badge" id="cpModalIcon">
                <i class="fa fa-trash"></i>
            </div>
            <div class="cp-modal-header-text">
                <h3 class="cp-modal-title" id="cpModalTitle">Confirm Action</h3>
                <span class="cp-modal-subtitle" id="cpModalSubtitle">SECURITY VERIFICATION</span>
            </div>
            <button type="button" class="cp-modal-close-btn" id="cpModalCloseBtn" aria-label="Close dialog">&times;</button>
        </div>
        <div class="cp-modal-body">
            <p class="cp-modal-message" id="cpModalMessage">Are you sure you want to proceed with this operation? This action cannot be undone.</p>
        </div>
        <div class="cp-modal-footer">
            <button type="button" class="cp-btn cp-btn-outline" id="cpModalCancelBtn">
                <i class="fa fa-times"></i> Cancel
            </button>
            <button type="button" class="cp-btn cp-btn-danger" id="cpModalConfirmBtn">
                <i class="fa fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    function initCpConfirmModal() {
        var modal = document.getElementById('cpConfirmModalBackdrop');
        if (!modal || modal.__cpInitialized) return;
        modal.__cpInitialized = true;

        var titleEl = document.getElementById('cpModalTitle');
        var subtitleEl = document.getElementById('cpModalSubtitle');
        var messageEl = document.getElementById('cpModalMessage');
        var confirmBtn = document.getElementById('cpModalConfirmBtn');
        var cancelBtn = document.getElementById('cpModalCancelBtn');
        var closeBtn = document.getElementById('cpModalCloseBtn');
        var iconEl = document.getElementById('cpModalIcon');

        var pendingAction = null;

        function closeModal() {
            modal.classList.remove('cp-modal-active');
            setTimeout(function() {
                modal.style.display = 'none';
                pendingAction = null;
            }, 230);
        }

        function openModal(options) {
            options = options || {};
            var title = options.title || 'Confirm Action';
            var subtitle = options.subtitle || 'SECURITY VERIFICATION';
            var message = options.message || 'Are you sure you want to proceed? This action cannot be undone.';
            var btnText = options.confirmBtn || 'Delete';
            var btnIcon = options.confirmIcon || 'fa-trash';
            var type = options.type || 'danger';

            modal.className = 'cp-modal-backdrop cp-modal-theme-' + type;
            if (titleEl) titleEl.textContent = title;
            if (subtitleEl) subtitleEl.textContent = subtitle;
            if (messageEl) messageEl.textContent = message;

            if (iconEl) {
                var iconName = (type === 'danger') ? 'fa-trash' : ((type === 'warning') ? 'fa-exclamation-triangle' : 'fa-info-circle');
                iconEl.innerHTML = '<i class="fa ' + iconName + '"></i>';
            }

            if (confirmBtn) {
                confirmBtn.innerHTML = '<i class="fa ' + btnIcon + '"></i> ' + btnText;
                if (type === 'danger') {
                    confirmBtn.className = 'cp-btn cp-btn-danger';
                } else if (type === 'warning') {
                    confirmBtn.className = 'cp-btn cp-btn-primary';
                } else {
                    confirmBtn.className = 'cp-btn cp-btn-primary';
                }
            }

            pendingAction = options.onConfirm || null;
            modal.style.display = 'flex';
            void modal.offsetWidth;
            modal.classList.add('cp-modal-active');
            if (confirmBtn) confirmBtn.focus();
        }

        window.cpConfirm = openModal;

        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('cp-modal-active')) {
                closeModal();
            }
        });

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var action = pendingAction;
                closeModal();
                if (typeof action === 'function') {
                    action();
                }
            });
        }

        document.addEventListener('click', function(e) {
            var target = e.target.closest('[data-confirm]');
            if (!target) return;

            e.preventDefault();
            e.stopPropagation();

            var message = target.getAttribute('data-confirm');
            var title = target.getAttribute('data-confirm-title') || 'Confirm Action';
            var subtitle = target.getAttribute('data-confirm-subtitle') || 'SECURITY VERIFICATION';
            var btnText = target.getAttribute('data-confirm-btn') || 'Delete';
            var btnIcon = target.getAttribute('data-confirm-icon') || 'fa-trash';
            var type = target.getAttribute('data-confirm-type') || 'danger';

            openModal({
                title: title,
                subtitle: subtitle,
                message: message,
                confirmBtn: btnText,
                confirmIcon: btnIcon,
                type: type,
                onConfirm: function() {
                    if (target.tagName.toLowerCase() === 'a' && target.href) {
                        window.location.href = target.href;
                    } else if (target.type === 'submit' && target.form) {
                        target.form.submit();
                    } else if (typeof target.onclick === 'function') {
                        target.onclick();
                    }
                }
            });
        }, true);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCpConfirmModal);
    } else {
        initCpConfirmModal();
    }
})();
</script>
