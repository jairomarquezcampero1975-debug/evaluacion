document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');
    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            let ok = true;
            form.querySelectorAll('[required]').forEach(function (campo) {
                if (campo.value.trim() === '') {
                    ok = false;
                    campo.classList.add('is-invalid');
                } else {
                    campo.classList.remove('is-invalid');
                }
            });
            if (!ok) {
                e.preventDefault();
                alert('Completa los campos obligatorios');
            }
        });
    });
});
