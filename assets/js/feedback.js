// feedback-form.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('feedbackForm');
    const starRating = document.querySelector('.star-rating');
    
    // Star rating interaction
    if (starRating) {
        const stars = starRating.querySelectorAll('label');
        const inputs = starRating.querySelectorAll('input');
        
        stars.forEach((star, index) => {
            star.addEventListener('mouseenter', function() {
                highlightStars(stars.length - index);
            });
            
            star.addEventListener('mouseleave', function() {
                const checkedInput = starRating.querySelector('input:checked');
                if (checkedInput) {
                    const checkedIndex = Array.from(inputs).indexOf(checkedInput);
                    highlightStars(stars.length - checkedIndex);
                } else {
                    clearStars();
                }
            });
        });
        
        function highlightStars(count) {
            stars.forEach((star, index) => {
                if (index >= stars.length - count) {
                    star.style.color = '#ffd700';
                } else {
                    star.style.color = '#ddd';
                }
            });
        }
        
        function clearStars() {
            stars.forEach(star => {
                star.style.color = '#ddd';
            });
        }
    }
    
    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            showSuccessMessage();
            // Here you would typically send the data to your server
            console.log('Form submitted successfully');
        }
    });
    
    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                clearFieldError(field);
            }
        });
        
        // Email validation
        const emailField = form.querySelector('#email');
        if (emailField.value && !isValidEmail(emailField.value)) {
            showFieldError(emailField, 'Please enter a valid email address');
            isValid = false;
        }
        
        return isValid;
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showFieldError(field, message) {
        clearFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        errorDiv.style.color = '#e74c3c';
        errorDiv.style.fontSize = '0.9rem';
        errorDiv.style.marginTop = '5px';
        
        field.style.borderColor = '#e74c3c';
        field.parentNode.appendChild(errorDiv);
    }
    
    function clearFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        field.style.borderColor = '#e1e8ed';
    }
    
    function showSuccessMessage() {
        const successDiv = document.createElement('div');
        successDiv.innerHTML = `
            <div style="
                background: #d4edda;
                color: #155724;
                padding: 20px;
                border-radius: 8px;
                margin: 20px 0;
                text-align: center;
                border: 1px solid #c3e6cb;
            ">
                <h3>Thank you for your feedback!</h3>
                <p>We appreciate you taking the time to share your experience with us.</p>
            </div>
        `;
        
        form.parentNode.insertBefore(successDiv, form);
        form.style.display = 'none';
        
        // Scroll to success message
        successDiv.scrollIntoView({ behavior: 'smooth' });
    }
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                showFieldError(this, 'This field is required');
            } else if (this.type === 'email' && this.value && !isValidEmail(this.value)) {
                showFieldError(this, 'Please enter a valid email address');
            } else {
                clearFieldError(this);
            }
        });
        
        input.addEventListener('input', function() {
            if (this.style.borderColor === 'rgb(231, 76, 60)') {
                clearFieldError(this);
            }
        });
    });
});
