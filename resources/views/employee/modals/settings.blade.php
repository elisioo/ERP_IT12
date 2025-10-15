<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="settingsForm">
                    @csrf
                    <!-- Theme -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Theme</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="theme" id="lightTheme" value="light" checked>
                                <label class="form-check-label" for="lightTheme">Light</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="theme" id="darkTheme" value="dark">
                                <label class="form-check-label" for="darkTheme">Dark</label>
                            </div>
                        </div>
                    </div>

                    <!-- Font Size -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Font Size</label>
                        <select name="font_size" class="form-select">
                            <option value="small">Small</option>
                            <option value="medium" selected>Medium</option>
                            <option value="large">Large</option>
                        </select>
                    </div>

                    <!-- Font Weight -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Font Weight</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="font_weight" id="normalWeight" value="normal" checked>
                                <label class="form-check-label" for="normalWeight">Normal</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="font_weight" id="boldWeight" value="bold">
                                <label class="form-check-label" for="boldWeight">Bold</label>
                            </div>
                        </div>
                    </div>

                    <!-- Animations -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Animations</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="animationsToggle" checked>
                            <label class="form-check-label" for="animationsToggle">Enable animations</label>
                        </div>
                    </div>

                    <!-- Sidebar Background -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sidebar Background</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sidebar_bg" id="sidebarBgDefault" value="default" checked>
                                    <label class="form-check-label" for="sidebarBgDefault">
                                        <img src="{{ asset('img/kdr.png') }}" alt="Default" class="img-thumbnail" style="width: 100px; height: 60px; object-fit: cover;">
                                        <small class="d-block">Default</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sidebar_bg" id="sidebarBgPhoto1" value="photo1">
                                    <label class="form-check-label" for="sidebarBgPhoto1">
                                        <img src="{{ asset('img/set_photo_sidebar1.jpg') }}" alt="Photo 1" class="img-thumbnail" style="width: 100px; height: 60px; object-fit: cover;">
                                        <small class="d-block">Kimbap Peach BG</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sidebar_bg" id="sidebarBgPhoto2" value="photo2">
                                    <label class="form-check-label" for="sidebarBgPhoto2">
                                        <img src="{{ asset('img/set_photo_sidebar2.jpg') }}" alt="Photo 2" class="img-thumbnail" style="width: 100px; height: 60px; object-fit: cover;">
                                        <small class="d-block">Kimbap Brown BG</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Background Overlay Settings -->
                    <div class="mb-3" id="overlaySettings" style="display: none;">
                        <label class="form-label fw-bold">Background Overlay</label>

                        <!-- Overlay Opacity -->
                        <div class="mb-3">
                            <label for="overlayOpacity" class="form-label">Overlay Darkness</label>
                            <input type="range" class="form-range" id="overlayOpacity" name="overlay_opacity" min="0" max="80" value="40" step="10">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Light</small>
                                <small class="text-muted">Dark</small>
                            </div>
                        </div>

                        <!-- Overlay Blur -->
                        <div class="mb-3">
                            <label for="overlayBlur" class="form-label">Background Blur</label>
                            <input type="range" class="form-range" id="overlayBlur" name="overlay_blur" min="0" max="10" value="2" step="1">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">None</small>
                                <small class="text-muted">Heavy</small>
                            </div>
                        </div>
                    </div>

                    <!-- Performance -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Performance</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="optimizeToggle">
                            <label class="form-check-label" for="optimizeToggle">Optimize for performance</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSettings">Save Settings</button>
            </div>
        </div>
    </div>
</div>
