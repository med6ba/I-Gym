<x-app-layout>
    <x-slot name="title">{{ __('messages.igyma_assistant') }}</x-slot>
    <x-slot name="header">
        <div class="flex min-w-0 items-center gap-3">
            <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:text-amber-300">
                <x-icon name="sparkles" size="21" />
            </span>
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-600 dark:text-amber-400">{{ __('messages.igyma_fitness_assistant') }}</p>
                <h2 class="truncate text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.igyma_assistant') }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-3 py-4 sm:px-6 lg:px-8 lg:py-6">
        <section class="flex h-[calc(100vh-10rem)] min-h-[520px] max-h-[780px] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900" x-data="igymaChat()">
            <div class="flex min-w-0 flex-1 flex-col">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900 sm:px-5">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="grid size-10 shrink-0 place-items-center rounded-full bg-slate-950 text-amber-300 dark:bg-white dark:text-amber-600">
                            <x-icon name="sparkles" size="18" />
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-black text-slate-950 dark:text-white">{{ __('messages.igyma_assistant') }}</p>
                            <p class="truncate text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('messages.igyma_members_only_assistant') }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-black text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        {{ __('messages.active') }}
                    </span>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto bg-slate-50/80 px-3 py-4 dark:bg-slate-950/35 sm:px-5" @scroll="onScroll" x-ref="messagesContainer">
                    <template x-if="messages.length === 0">
                        <div class="flex min-h-full items-center justify-center py-6">
                            <div class="w-full max-w-2xl text-center">
                                <span class="mx-auto grid size-16 place-items-center rounded-2xl bg-white text-amber-600 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:text-amber-300 dark:ring-slate-800">
                                    <x-icon name="sparkles" size="28" />
                                </span>
                                <h3 class="mt-5 text-xl font-black text-slate-950 dark:text-white">{{ __('messages.igyma_assistant') }}</h3>
                                <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500 dark:text-slate-400">{{ __('messages.igyma_fitness_assistant') }}</p>

                                <div class="mt-6">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ __('messages.igyma_suggested_questions') }}</p>
                                    <div class="mt-3 grid gap-2 sm:grid-cols-2">
                                        <button type="button" @click="setMessage('Create a beginner workout plan')" class="igym-focus flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 text-start transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-amber-800 dark:hover:bg-amber-950/25">
                                            <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300">
                                                <x-icon name="dumbbell" size="16" />
                                            </span>
                                            <span class="text-sm font-bold leading-5 text-slate-700 dark:text-slate-200">Create a beginner workout plan</span>
                                        </button>
                                        <button type="button" @click="setMessage('How can I improve my cardio?')" class="igym-focus flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 text-start transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-amber-800 dark:hover:bg-amber-950/25">
                                            <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-sky-100 text-sky-700 dark:bg-sky-950/50 dark:text-sky-300">
                                                <x-icon name="activity" size="16" />
                                            </span>
                                            <span class="text-sm font-bold leading-5 text-slate-700 dark:text-slate-200">How can I improve my cardio?</span>
                                        </button>
                                        <button type="button" @click="setMessage('Give me a safe warm-up routine')" class="igym-focus flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 text-start transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-amber-800 dark:hover:bg-amber-950/25">
                                            <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300">
                                                <x-icon name="shield" size="16" />
                                            </span>
                                            <span class="text-sm font-bold leading-5 text-slate-700 dark:text-slate-200">Give me a safe warm-up routine</span>
                                        </button>
                                        <button type="button" @click="setMessage('Suggest exercises for muscle gain')" class="igym-focus flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 text-start transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-amber-800 dark:hover:bg-amber-950/25">
                                            <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-violet-100 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300">
                                                <x-icon name="target" size="16" />
                                            </span>
                                            <span class="text-sm font-bold leading-5 text-slate-700 dark:text-slate-200">Suggest exercises for muscle gain</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-for="msg in messages" :key="msg.id">
                        <div x-bind:class="msg.role === 'user' ? 'justify-end' : 'justify-start'" class="mb-4 flex items-end gap-2">
                            <span x-show="msg.role !== 'user'" x-cloak class="grid size-8 shrink-0 place-items-center rounded-full bg-white text-amber-600 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:text-amber-300 dark:ring-slate-800">
                                <x-icon name="sparkles" size="15" />
                            </span>
                            <div x-bind:class="msg.role === 'user' ? 'items-end' : 'items-start'" class="flex max-w-[82%] flex-col sm:max-w-[72%]">
                                <div x-bind:class="msg.role === 'user'
                                    ? 'rounded-2xl rounded-br-sm bg-amber-500 px-4 py-3 text-slate-950 shadow-sm'
                                    : 'rounded-2xl rounded-bl-sm border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100'">
                                    <p class="whitespace-pre-line break-words text-sm leading-6" x-text="msg.content"></p>
                                </div>
                            </div>
                            <span x-show="msg.role === 'user'" x-cloak class="grid size-8 shrink-0 place-items-center rounded-full bg-slate-900 text-white shadow-sm dark:bg-slate-100 dark:text-slate-900">
                                <x-icon name="user" size="15" />
                            </span>
                        </div>
                    </template>

                    <template x-if="loading">
                        <div class="mb-4 flex items-end gap-2">
                            <span class="grid size-8 shrink-0 place-items-center rounded-full bg-white text-amber-600 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:text-amber-300 dark:ring-slate-800">
                                <x-icon name="sparkles" size="15" />
                            </span>
                            <div class="rounded-2xl rounded-bl-sm border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex gap-1.5">
                                    <span class="inline-block size-2 rounded-full bg-slate-400 motion-safe:animate-bounce dark:bg-slate-500"></span>
                                    <span class="inline-block size-2 rounded-full bg-slate-400 motion-safe:animate-bounce dark:bg-slate-500"></span>
                                    <span class="inline-block size-2 rounded-full bg-slate-400 motion-safe:animate-bounce dark:bg-slate-500"></span>
                                </div>
                                <p class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('messages.igyma_typing') }}</p>
                            </div>
                        </div>
                    </template>

                    <template x-if="error">
                        <div class="mb-4 flex justify-center">
                            <div class="max-w-md rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800 dark:border-rose-900/30 dark:bg-rose-950/30 dark:text-rose-200">
                                <p x-text="error"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="border-t border-slate-200 bg-white p-3 dark:border-slate-800 dark:bg-slate-900 sm:p-4">
                    <form @submit.prevent="sendMessage" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-2 transition focus-within:border-amber-300 focus-within:bg-white dark:border-slate-800 dark:bg-slate-950/50 dark:focus-within:border-amber-800 dark:focus-within:bg-slate-950">
                        <input
                            type="text"
                            x-model="inputMessage"
                            @keydown.enter="sendMessage"
                            :disabled="loading"
                            :placeholder="'{{ __('messages.igyma_placeholder') }}'"
                            class="min-w-0 flex-1 border-0 bg-transparent px-2 py-2.5 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-0 dark:text-white dark:placeholder-slate-500 disabled:opacity-50"
                        />
                        <button
                            type="submit"
                            :disabled="loading || !inputMessage.trim()"
                            class="igym-focus grid size-10 shrink-0 place-items-center rounded-lg bg-amber-500 text-slate-950 transition hover:bg-amber-400 disabled:bg-slate-300 disabled:text-slate-500 dark:disabled:bg-slate-700 dark:disabled:text-slate-400"
                        >
                            <x-icon name="send" size="17" />
                        </button>
                    </form>
                </div>
            </div>
        </section>
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
