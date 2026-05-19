<x-app-layout>
    <x-slot name="title">{{ __('messages.igyma_assistant') }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-amber-500/15 text-amber-600 dark:text-amber-400">
                    <x-icon name="sparkles" size="20" />
                </span>
                <div>
                    <p class="text-xs uppercase tracking-widest text-slate-500">{{ __('messages.igyma_fitness_assistant') }}</p>
                    <h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.igyma_assistant') }}</h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900" x-data="igymaChat()">
            <div class="grid h-[calc(100vh-20rem)] grid-cols-1 lg:h-[600px]">
                <!-- Messages Area -->
                <div class="flex flex-col overflow-hidden">
                    <!-- Header -->
                    <div class="border-b border-slate-200 bg-gradient-to-r from-amber-50 to-orange-50 p-4 dark:border-slate-700 dark:from-slate-800 dark:to-slate-800">
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            🏋️ {{ __('messages.igyma_members_only_assistant') }}
                        </p>
                    </div>

                    <!-- Chat Messages -->
                    <div class="flex-1 overflow-y-auto p-4" @scroll="onScroll" x-ref="messagesContainer">
                        <template x-if="messages.length === 0">
                            <div class="flex h-full flex-col items-center justify-center text-center">
                                <span class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400">
                                    <x-icon name="sparkles" size="32" />
                                </span>
                                <h3 class="mb-2 text-lg font-bold text-slate-900 dark:text-white">{{ __('messages.igyma_assistant') }}</h3>
                                <p class="mb-6 max-w-xs text-sm text-slate-600 dark:text-slate-400">
                                    {{ __('messages.igyma_fitness_assistant') }}
                                </p>
                                <div class="w-full space-y-2 pt-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.igyma_suggested_questions') }}</p>
                                    <button @click="setMessage('Create a beginner workout plan')" class="block w-full rounded-lg border border-slate-200 p-3 text-left text-sm text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                        💪 Create a beginner workout plan
                                    </button>
                                    <button @click="setMessage('How can I improve my cardio?')" class="block w-full rounded-lg border border-slate-200 p-3 text-left text-sm text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                        🏃 How can I improve my cardio?
                                    </button>
                                    <button @click="setMessage('Give me a safe warm-up routine')" class="block w-full rounded-lg border border-slate-200 p-3 text-left text-sm text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                        🔥 Give me a safe warm-up routine
                                    </button>
                                    <button @click="setMessage('Suggest exercises for muscle gain')" class="block w-full rounded-lg border border-slate-200 p-3 text-left text-sm text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                        🦾 Suggest exercises for muscle gain
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template x-for="msg in messages" :key="msg.id">
                            <div x-bind:class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'" class="mb-4">
                                <div x-bind:class="msg.role === 'user' 
                                    ? 'max-w-xs rounded-2xl rounded-tr-none bg-amber-500 px-4 py-3 text-white dark:bg-amber-600' 
                                    : 'max-w-xs rounded-2xl rounded-tl-none border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100'">
                                    <p class="text-sm" x-text="msg.content"></p>
                                </div>
                            </div>
                        </template>

                        <template x-if="loading">
                            <div class="flex justify-start">
                                <div class="rounded-2xl rounded-tl-none border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                                    <div class="flex gap-1">
                                        <span class="inline-block h-2 w-2 rounded-full bg-slate-400 motion-safe:animate-bounce dark:bg-slate-500"></span>
                                        <span class="animation-delay-200 inline-block h-2 w-2 rounded-full bg-slate-400 motion-safe:animate-bounce dark:bg-slate-500"></span>
                                        <span class="animation-delay-400 inline-block h-2 w-2 rounded-full bg-slate-400 motion-safe:animate-bounce dark:bg-slate-500"></span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('messages.igyma_typing') }}</p>
                                </div>
                            </div>
                        </template>

                        <template x-if="error">
                            <div class="mb-4 flex justify-center">
                                <div class="max-w-xs rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900/30 dark:bg-rose-950/30 dark:text-rose-200">
                                    <p x-text="error"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Input Area -->
                    <div class="border-t border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                        <form @submit.prevent="sendMessage" class="flex gap-2">
                            <input 
                                type="text" 
                                x-model="inputMessage" 
                                @keydown.enter="sendMessage"
                                :disabled="loading"
                                :placeholder="'{{ __('messages.igyma_placeholder') }}'"
                                class="flex-1 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-900 placeholder-slate-400 transition focus:border-amber-400 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder-slate-500 disabled:opacity-50"
                            />
                            <button 
                                type="submit" 
                                :disabled="loading || !inputMessage.trim()"
                                class="inline-flex items-center gap-2 rounded-full bg-amber-500 px-4 py-2 font-semibold text-white transition hover:bg-amber-600 disabled:bg-slate-300 dark:disabled:bg-slate-600"
                            >
                                <x-icon name="send" size="16" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function igymaChat() {
            return {
                messages: [],
                inputMessage: '',
                loading: false,
                error: null,
                messageId: 0,

                init() {
                    this.loadMessages();
                },

                loadMessages() {
                    const stored = localStorage.getItem('igyma-messages');
                    if (stored) {
                        this.messages = JSON.parse(stored);
                    }
                },

                saveMessages() {
                    localStorage.setItem('igyma-messages', JSON.stringify(this.messages));
                },

                setMessage(text) {
                    this.inputMessage = text;
                },

                async sendMessage() {
                    const message = this.inputMessage.trim();
                    if (!message) return;

                    this.error = null;
                    this.inputMessage = '';

                    // Add user message
                    this.messages.push({
                        id: ++this.messageId,
                        role: 'user',
                        content: message,
                    });
                    this.saveMessages();
                    this.$nextTick(() => this.scrollToBottom());

                    this.loading = true;

                    try {
                        const response = await fetch('{{ route("member.igyma.chat") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ message }),
                        });

                        if (!response.ok) {
                            this.error = '{{ __("messages.igyma_unavailable") }}';
                            this.loading = false;
                            return;
                        }

                        const data = await response.json();

                        if (data.success) {
                            this.messages.push({
                                id: ++this.messageId,
                                role: 'assistant',
                                content: data.reply,
                            });
                            this.saveMessages();
                        } else {
                            this.error = data.error || '{{ __("messages.igyma_unavailable") }}';
                        }
                    } catch (err) {
                        this.error = '{{ __("messages.igyma_unavailable") }}';
                    } finally {
                        this.loading = false;
                        this.$nextTick(() => this.scrollToBottom());
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.messagesContainer;
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                },

                onScroll() {
                    // Placeholder for scroll handling if needed
                },
            };
        }
    </script>
</x-app-layout>
