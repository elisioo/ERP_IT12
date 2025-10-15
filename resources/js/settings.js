document.addEventListener('DOMContentLoaded', function() {
    // Load saved settings
    loadSettings();

    // Toggle overlay settings when background changes
    document.querySelectorAll('input[name="sidebar_bg"]').forEach(radio => {
        radio.addEventListener('change', toggleOverlaySettings);
    });

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
                // Apply settings immediately without page refresh
                applySettings();

                // Force a brief reflow to ensure all changes are rendered
                document.body.style.display = 'none';
                document.body.offsetHeight; // Trigger reflow
                document.body.style.display = '';

                // Close modal after a short delay to ensure settings are applied
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
                }, 100);
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
        if (settings.sidebar_bg) {
            document.querySelector(`input[name="sidebar_bg"][value="${settings.sidebar_bg}"]`).checked = true;
        }
        if (settings.overlay_opacity !== undefined) {
            document.getElementById('overlayOpacity').value = settings.overlay_opacity;
        }
        if (settings.overlay_blur !== undefined) {
            document.getElementById('overlayBlur').value = settings.overlay_blur;
        }
        if (settings.animations !== undefined) {
            document.getElementById('animationsToggle').checked = settings.animations;
        }
        if (settings.optimize !== undefined) {
            document.getElementById('optimizeToggle').checked = settings.optimize;
        }

        // Show/hide overlay settings based on background selection
        toggleOverlaySettings();

        applySettings();
    }

    function applySettings() {
        const theme = document.querySelector('input[name="theme"]:checked')?.value || 'light';
        const fontSize = document.querySelector('select[name="font_size"]')?.value || 'medium';
        const fontWeight = document.querySelector('input[name="font_weight"]:checked')?.value || 'normal';
        const sidebarBg = document.querySelector('input[name="sidebar_bg"]:checked')?.value || 'default';
        const overlayOpacity = document.getElementById('overlayOpacity')?.value || 40;
        const overlayBlur = document.getElementById('overlayBlur')?.value || 2;
        const animations = document.getElementById('animationsToggle')?.checked ?? true;
        const optimize = document.getElementById('optimizeToggle')?.checked ?? false;

        // Apply theme
        document.body.setAttribute('data-theme', theme);

        // Apply font size
        document.body.setAttribute('data-font-size', fontSize);

        // Apply font weight
        document.body.setAttribute('data-font-weight', fontWeight);

        // Apply sidebar background
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            // Remove existing background classes
            sidebar.classList.remove('sidebar-bg-photo1', 'sidebar-bg-photo2');

            if (sidebarBg === 'photo1') {
                sidebar.classList.add('sidebar-bg-photo1');
                sidebar.style.backgroundImage = `url('/img/set_photo_sidebar1.jpg')`;
            } else if (sidebarBg === 'photo2') {
                sidebar.classList.add('sidebar-bg-photo2');
                sidebar.style.backgroundImage = `url('/img/set_photo_sidebar2.jpg')`;
            } else {
                sidebar.style.backgroundImage = 'none';
            }

            // Apply overlay settings
            sidebar.style.setProperty('--overlay-opacity', overlayOpacity / 100);
            sidebar.style.setProperty('--overlay-blur', `${overlayBlur}px`);
        }

        // Apply animations
        document.body.setAttribute('data-animations', animations ? 'enabled' : 'disabled');

        // Apply optimization
        document.body.setAttribute('data-optimize', optimize ? 'enabled' : 'disabled');

        // Force icon re-rendering when optimization changes
        if (optimize) {
            // Trigger a reflow to apply new rendering rules
            document.body.style.display = 'none';
            document.body.offsetHeight; // Trigger reflow
            document.body.style.display = '';
        }

        // Save to localStorage
        localStorage.setItem('userSettings', JSON.stringify({
            theme: theme,
            font_size: fontSize,
            font_weight: fontWeight,
            sidebar_bg: sidebarBg,
            overlay_opacity: overlayOpacity,
            overlay_blur: overlayBlur,
            animations: animations,
            optimize: optimize
        }));
    }

    function toggleOverlaySettings() {
        const sidebarBg = document.querySelector('input[name="sidebar_bg"]:checked')?.value || 'default';
        const overlaySettings = document.getElementById('overlaySettings');

        if (sidebarBg === 'photo1' || sidebarBg === 'photo2') {
            overlaySettings.style.display = 'block';
        } else {
            overlaySettings.style.display = 'none';
        }
    }
});
