document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => { const bsAlert = bootstrap.Alert.getOrCreateInstance(alert); bsAlert.close(); }, 5000);
    });

    // Confirm delete actions
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm(form.dataset.confirm || 'Are you sure?')) e.preventDefault();
        });
    });

    // Toggle all checkboxes
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', () => {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = selectAll.checked);
        });
    }

    // Sidebar toggle for mobile
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        document.addEventListener('click', e => {
            if (!sidebar.contains(e.target) && !e.target.matches('[onclick*="sidebar"]')) {
                sidebar.classList.remove('show');
            }
        });
    }

    // Format currency inputs
    document.querySelectorAll('[data-currency]').forEach(input => {
        input.addEventListener('blur', () => {
            const val = parseFloat(input.value) || 0;
            input.value = val.toFixed(2);
        });
    });
});
