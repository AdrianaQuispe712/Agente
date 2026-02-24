<script setup lang="ts">
import { ref } from 'vue'
import { parse } from 'marked'

type ChatMessage = {
    id?: number
    role: 'user' | 'assistant'
    text: string
    time: string
}

const toggleId = 'chat-toggle'
const message = ref('')
const responses = ref<ChatMessage[]>([])

function handleSend() {
    responses.value.push({
        id: responses.value.length + 1,
        role: 'user',
        text: message.value,
        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    })

    fetch('/Invoke-agent', {
        method: 'POST',
        body: JSON.stringify({ message: message.value }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
    })
        .then((res) => res.text())
        .then((data) => {
            responses.value.push({
                id: responses.value.length + 1,
                role: 'assistant',
                text: data,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            })
        })

    message.value = ''
}
</script>

<template>
    <div class="relative">
        <input :id="toggleId" type="checkbox" class="peer sr-only" />
        <label
            :for="toggleId"
            class="fixed bottom-6 right-6 z-40 inline-flex h-12 items-center gap-2 rounded-full bg-slate-900 px-4 text-sm font-semibold text-white shadow-lg transition hover:bg-slate-800"
        >
            Chat
            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400" />
        </label>

        <section
            class="fixed bottom-24 right-6 z-40 hidden w-90 max-w-[92vw] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 via-white to-amber-50 shadow-xl peer-checked:flex"
        >
            <!-- Header -->
            <header class="flex items-center justify-between border-b border-slate-200 bg-white/80 px-5 py-4 backdrop-blur">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Atelier Chat</p>
                    <h2 class="text-lg font-semibold text-slate-900">Style Assistant</h2>
                </div>
                <label
                    :for="toggleId"
                    class="cursor-pointer rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-600 transition hover:border-slate-300 hover:text-slate-900"
                >
                    Close
                </label>
            </header>

            <!-- Mensajes -->
            <div class="max-h-90 space-y-4 overflow-y-auto px-5 py-6">
                <div
                    v-for="msg in responses"
                    :key="msg.id"
                    :class="msg.role === 'user' ? 'ml-auto w-full max-w-[75%]' : 'mr-auto w-full max-w-[75%]'"
                >
                    <div
                        :class="msg.role === 'user'
                            ? 'rounded-2xl rounded-tr-sm bg-slate-900 px-4 py-3 text-sm text-white shadow'
                            : 'rounded-2xl rounded-tl-sm bg-white px-4 py-3 text-sm text-slate-700 shadow'"
                        v-html="parse(msg.text)"
                    />
                    <p
                        :class="msg.role === 'user' ? 'mt-1 text-right text-xs text-slate-400' : 'mt-1 text-xs text-slate-400'"
                    >
                        {{ msg.time }}
                    </p>
                </div>
            </div>

            <!-- Input -->
            <div class="border-t border-slate-200 bg-white px-5 py-4">
                <div class="flex items-center gap-3">
                    <input
                        v-model="message"
                        type="text"
                        placeholder="Ask about sizing, stock, or order status..."
                        class="flex-1 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 outline-none"
                        @keyup.enter="handleSend"
                    />
                    <button
                        type="button"
                        class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
                        @click="handleSend"
                    >
                        Send
                    </button>
                </div>
            </div>
        </section>
    </div>
</template>