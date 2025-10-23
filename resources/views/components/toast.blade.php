<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        
        init() {
            // Listen for toast events
            window.addEventListener('show-toast', (event) => {
                this.showToast(event.detail.message, event.detail.type);
            });
            
            // Check for Laravel flash messages
            @if(session('success'))
                this.showToast('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                this.showToast('{{ session('error') }}', 'error');
            @endif
            
            @if(session('warning'))
                this.showToast('{{ session('warning') }}', 'warning');
            @endif
            
            @if($errors->any())
                this.showToast('{{ $errors->first() }}', 'error');
            @endif
        },
        
        showToast(message, type = 'success') {
            this.message = message;
            this.type = type;
            this.show = true;
            
            setTimeout(() => {
                this.show = false;
            }, 5000);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-4 right-4 z-50 min-w-[300px] max-w-md"
    style="display: none;"
>
    <div 
        class="rounded-lg shadow-2xl p-4 border-2"
        :class="{
            'bg-green-50 border-green-400': type === 'success',
            'bg-red-50 border-red-400': type === 'error',
            'bg-yellow-50 border-yellow-400': type === 'warning',
            'bg-blue-50 border-blue-400': type === 'info'
        }"
    >
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <template x-if="type === 'success'">
                    <span class="text-3xl">✅</span>
                </template>
                <template x-if="type === 'error'">
                    <span class="text-3xl">❌</span>
                </template>
                <template x-if="type === 'warning'">
                    <span class="text-3xl">⚠️</span>
                </template>
                <template x-if="type === 'info'">
                    <span class="text-3xl">ℹ️</span>
                </template>
            </div>
            <div class="ml-3 flex-1">
                <p 
                    class="text-sm font-medium"
                    :class="{
                        'text-green-800': type === 'success',
                        'text-red-800': type === 'error',
                        'text-yellow-800': type === 'warning',
                        'text-blue-800': type === 'info'
                    }"
                    x-text="message"
                ></p>
            </div>
            <button 
                @click="show = false" 
                class="ml-3 flex-shrink-0 text-gray-400 hover:text-gray-600 transition"
            >
                <span class="text-xl">×</span>
            </button>
        </div>
    </div>
</div>

