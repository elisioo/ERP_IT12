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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSettings">Save Settings</button>
            </div>
        </div>
    </div>
</div>