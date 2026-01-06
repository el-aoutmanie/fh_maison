<!-- Alert Dialog Component -->
<div 
    x-data="alertDialog"
    x-show="isOpen"
    x-cloak
    class="alert-dialog-overlay"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="close()"
    style="display: none;"
>
    <div class="alert-dialog-backdrop" @click="close()"></div>
    
    <div 
        class="alert-dialog-container"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
    >
        <div class="alert-dialog-content">
            <!-- Icon -->
            <div class="alert-dialog-icon" :class="{
                'alert-dialog-icon-success': dialogType === 'success',
                'alert-dialog-icon-warning': dialogType === 'warning',
                'alert-dialog-icon-danger': dialogType === 'danger',
                'alert-dialog-icon-info': dialogType === 'info'
            }">
                <!-- Success Icon -->
                <template x-if="dialogType === 'success'">
                    <svg class="alert-dialog-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                
                <!-- Warning Icon -->
                <template x-if="dialogType === 'warning'">
                    <svg class="alert-dialog-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                
                <!-- Danger Icon -->
                <template x-if="dialogType === 'danger'">
                    <svg class="alert-dialog-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                
                <!-- Info Icon -->
                <template x-if="dialogType === 'info'">
                    <svg class="alert-dialog-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </div>
            
            <!-- Title -->
            <h3 class="alert-dialog-title" x-text="dialogTitle"></h3>
            
            <!-- Message -->
            <p class="alert-dialog-message" x-text="dialogMessage"></p>
            
            <!-- Actions -->
            <div class="alert-dialog-actions">
                <button 
                    type="button" 
                    class="btn btn-outline-secondary"
                    @click="cancel()"
                    x-show="showCancel"
                >
                    <span x-text="cancelText"></span>
                </button>
                
                <button 
                    type="button" 
                    class="btn"
                    :class="{
                        'btn-success': dialogType === 'success',
                        'btn-warning': dialogType === 'warning',
                        'btn-danger': dialogType === 'danger',
                        'btn-clay': dialogType === 'info'
                    }"
                    @click="confirm()"
                >
                    <span x-text="confirmText"></span>
                </button>
            </div>
        </div>
    </div>
</div>
