@extends('layouts.app')

@section('title', __('messages.ai.title'))

@section('content')
<div class="resource-shell max-w-5xl mx-auto" x-data="aiChat()">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.ai.assistant_name') }}</p>
        <h2 class="resource-title">{{ __('messages.ai.subtitle') }}</h2>
        <p class="resource-copy">
            {{ __('messages.ai.description') }}
        </p>
    </section>

    <section class="card overflow-hidden animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="px-6 py-5 bg-gradient-to-r from-teal-700 to-cyan-700">
            <h3 class="font-display text-xl font-bold text-white">{{ __('messages.ai.assistant_name') }}</h3>
            <p class="text-cyan-100 text-sm">{{ __('messages.ai.subtitle') }}</p>
        </div>

        <div class="h-[28rem] overflow-y-auto p-6 space-y-4 bg-gray-50/60 dark:bg-gray-900/40" id="chatMessages">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-100 dark:bg-cyan-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-700 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-3 bg-white dark:bg-gray-800 rounded-xl px-4 py-3 max-w-lg border border-gray-200 dark:border-gray-700">
                    <p class="text-gray-800 dark:text-gray-100">{{ __('messages.ai.greeting', ['name' => auth()->user()->name]) }}</p>
                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">{{ __('messages.ai.example') }}</p>
                </div>
            </div>

            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex items-start" :class="{ 'justify-end': msg.role === 'user' }">
                    <template x-if="msg.role === 'ai'">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-100 dark:bg-cyan-900/40 flex items-center justify-center">
                                <svg class="w-5 h-5 text-cyan-700 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-3 bg-white dark:bg-gray-800 rounded-xl px-4 py-3 max-w-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-800 dark:text-gray-100" x-text="msg.content"></p>
                            </div>
                        </div>
                    </template>
                    <template x-if="msg.role === 'user'">
                        <div class="bg-teal-700 text-white rounded-xl px-4 py-3 max-w-lg shadow-sm">
                            <p x-text="msg.content"></p>
                        </div>
                    </template>
                </div>
            </template>

            <div x-show="loading" class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-100 dark:bg-cyan-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-700 dark:text-cyan-300 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div class="ml-3 bg-white dark:bg-gray-800 rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700">
                    <p class="text-gray-500 dark:text-gray-400">{{ __('messages.ai.processing') }}</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4">
            <form @submit.prevent="sendMessage" class="flex space-x-3">
                <input
                    type="text"
                    x-model="newMessage"
                    placeholder="{{ __('messages.ai.placeholder') }}"
                    class="flex-1 form-input rounded-full"
                    :disabled="loading"
                >
                <button
                    type="submit"
                    class="btn-primary rounded-full px-6"
                    :disabled="loading || !newMessage.trim()"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </section>

    <section class="animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="flex flex-wrap gap-2">
            <button @click="newMessage = 'Mượn kính hiển vi cho tiết 2 sáng mai'" class="table-pill bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition">
                {{ __('messages.ai.quick_actions.borrow_microscope') }}
            </button>
            <button @click="newMessage = 'Kiểm tra còn bao nhiêu bộ thực hành Động điện'" class="table-pill bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition">
                {{ __('messages.ai.quick_actions.check_inventory') }}
            </button>
            <button @click="newMessage = 'Mượn máy chiếu cho lớp 11A3 tiết 5'" class="table-pill bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition">
                {{ __('messages.ai.quick_actions.borrow_projector') }}
            </button>
        </div>
        <div class="mt-4">
            <a href="{{ route('borrow.create') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">
                {{ __('messages.ai.manual_form') }}
            </a>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
function aiChat() {
    return {
        messages: [],
        newMessage: '',
        loading: false,

        async sendMessage() {
            if (!this.newMessage.trim()) return;

            const userMessage = this.newMessage;
            this.messages.push({ role: 'user', content: userMessage });
            this.newMessage = '';
            this.loading = true;

            try {
                const response = await fetch('{{ route("ai.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ message: userMessage }),
                });

                const data = await response.json();

                if (data.success && data.intent === 'create_booking') {
                    this.messages.push({
                        role: 'ai',
                        content: data.message + ' {{ __('messages.ai.redirecting') }}'
                    });

                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    this.messages.push({
                        role: 'ai',
                        content: data.message || '{{ __('messages.ai.error') }}'
                    });
                }
            } catch (error) {
                this.messages.push({
                    role: 'ai',
                    content: '{{ __('messages.ai.connection_error') }}'
                });
            }

            this.loading = false;
            this.$nextTick(() => {
                document.getElementById('chatMessages').scrollTop = 999999;
            });
        }
    };
}
</script>
@endpush
