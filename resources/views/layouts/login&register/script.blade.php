<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const schoolContainer = document.getElementById('school-container');
        const schoolSelect = document.getElementById('school');
        
        if(roleSelect.value === 'researcher') {
            schoolSelect.value = '';
            schoolSelect.disabled = true;
            schoolContainer.classList.add('opacity-50');
        }
        
        roleSelect.addEventListener('change', function() {
            if(this.value === 'researcher') {
                schoolSelect.value = '';
                schoolSelect.disabled = true;
                schoolContainer.classList.add('opacity-50');
            } else {
                schoolSelect.disabled = false;
                schoolContainer.classList.remove('opacity-50');
            }
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            if(roleSelect.value === 'researcher') {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'school';
                hiddenInput.value = '';
                this.appendChild(hiddenInput);
                
                schoolSelect.removeAttribute('disabled');
                schoolSelect.name = 'school_original';
            }
        });
    });
</script>