document.addEventListener('DOMContentLoaded', function() {
    const inputFields = document.querySelectorAll('.input-container input');
    
    inputFields.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('active');
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentElement.classList.remove('active');
            }
        });

        input.addEventListener('input', function() {
            if (this.value !== '') {
                this.style.fontWeight = 'bold';
            } else {
                this.style.fontWeight = 'normal';
            }
        });
    });
});







