document.addEventListener('DOMContentLoaded', function() {
    // Load saved settings
    loadSettings();

    // Save settings
    document.getElementById('saveSettings')?.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('settingsForm'));
        
        fetch('/settings/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                applySettings();
                bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
            }
        });
    });

    function loadSettings() {
        const settings = JSON.parse(localStorage.getItem('userSettings') || '{}');
        
        if (settings.theme) {
            document.querySelector(`input[name="theme"][value="${settings.theme}"]`).checked = true;
        }
        if (settings.font_size) {
            document.querySelector(`select[name="font_size"]`).value = settings.font_size;
        }
        if (settings.font_weight) {
            document.querySelector(`input[name="font_weight"][value="${settings.font_weight}"]`).checked = true;
        }
        if (settings.animations !== undefined) {
            document.getElementById('animationsToggle').checked = settings.animations;
        }
        if (settings.optimize !== undefined) {
            document.getElementById('optimizeToggle').checked = settings.optimize;
        }
        
        applySettings();
    }

    function applySettings() {
        const theme = document.querySelector('input[name="theme"]:checked')?.value || 'light';
        const fontSize = document.querySelector('select[name="font_size"]')?.value || 'medium';
        const fontWeight = document.querySelector('input[name="font_weight"]:checked')?.value || 'normal';
        const animations = document.getElementById('animationsToggle')?.checked ?? true;
        const optimize = document.getElementById('optimizeToggle')?.checked ?? false;

        // Apply theme
        document.body.setAttribute('data-theme', theme);
        
        // Apply font size
        document.body.setAttribute('data-font-size', fontSize);
        
        // Apply font weight
        document.body.setAttribute('data-font-weight', fontWeight);
        
        // Apply animations
        document.body.setAttribute('data-animations', animations ? 'enabled' : 'disabled');
        
        // Apply optimization
        document.body.setAttribute('data-optimize', optimize ? 'enabled' : 'disabled');

        // Save to localStorage
        localStorage.setItem('userSettings', JSON.stringify({
            theme: theme,
            font_size: fontSize,
            font_weight: fontWeight,
            animations: animations,
            optimize: optimize
        }));
    }
});