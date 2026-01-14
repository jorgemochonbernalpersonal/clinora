@props(['type' => 'info', 'message' => ''])

<div 
    x-data="{ 
        show: false, 
        message: '{{ $message }}', 
        type: '{{ $type }}',
        progress: 100,
        interval: null,
        timeout: null,
        showToast(type, message) {
            if (this.timeout) clearTimeout(this.timeout);
            if (this.interval) clearInterval(this.interval);
            
            this.type = type;
            this.message = message;
            this.show = true;
            this.progress = 100;
            
            const duration = 5000;
            const step = 10;
            const decrement = (step / duration) * 100;
            
            this.interval = setInterval(() => {
                this.progress -= decrement;
                if (this.progress <= 0) {
                    clearInterval(this.interval);
                }
            }, step);

            this.timeout = setTimeout(() => { 
                this.show = false;
            }, duration);
        }
    }"
    x-init="
        @if(session('success')) showToast('success', '{{ session('success') }}'); @endif
        @if(session('error')) showToast('error', '{{ session('error') }}'); @endif
        @if(session('warning')) showToast('warning', '{{ session('warning') }}'); @endif
        @if(session('info')) showToast('info', '{{ session('info') }}'); @endif
    "
    @show-toast.window="showToast($event.detail.type, $event.detail.message)"
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:-translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed bottom-4 left-4 z-50 max-w-md w-full"
    style="display: none;"
>

    <div 
        class="rounded-lg shadow-lg p-4 flex items-start gap-3"
        :class="{
            'bg-green-50 border border-green-200': type === 'success',
            'bg-red-50 border border-red-200': type === 'error',
            'bg-yellow-50 border border-yellow-200': type === 'warning',
            'bg-blue-50 border border-blue-200': type === 'info'
        }"
    >
        <!-- Icon -->
        <div class="flex-shrink-0">
            <template x-if="type === 'success'">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <template x-if="type === 'warning'">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </template>
            <template x-if="type === 'info'">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
        </div>
        
        <!-- Message -->
        <div class="flex-1">
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
        
        <!-- Close Button -->
        <button 
            @click="show = false"
            class="flex-shrink-0 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
            :class="{
                'text-green-500 hover:text-green-600 focus:ring-green-500': type === 'success',
                'text-red-500 hover:text-red-600 focus:ring-red-500': type === 'error',
                'text-yellow-500 hover:text-yellow-600 focus:ring-yellow-500': type === 'warning',
                'text-blue-500 hover:text-blue-600 focus:ring-blue-500': type === 'info'
            }"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <!-- Progress Bar -->
    <div class="h-1 w-full bg-black/5 rounded-b-lg overflow-hidden">
        <div 
            class="h-full transition-all ease-linear duration-100"
            :class="{
                'bg-green-500': type === 'success',
                'bg-red-500': type === 'error',
                'bg-yellow-500': type === 'warning',
                'bg-blue-500': type === 'info'
            }"
            :style="`width: ${progress}%`"
        ></div>
    </div>
</div>
