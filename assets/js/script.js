document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function (form) {
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

    const elementos = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entradas) {
            entradas.forEach(function (entrada) {
                if (entrada.isIntersecting) {
                    entrada.target.classList.add('visible');
                }
            });
        }, { threshold: 0.15 });
        elementos.forEach(function (el) { observer.observe(el); });
    } else {
        elementos.forEach(function (el) { el.classList.add('visible'); });
    }
});
