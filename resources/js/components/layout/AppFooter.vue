<template>
    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 py-6 text-sm text-gray-600 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                © {{ year }} Furkan Adıgüzel
            </div>

            <a
                href="https://www.furkanadiguzel.com/"
                target="_blank"
                rel="noopener"
                class="underline hover:text-gray-900 transition"
            >
                furkanadiguzel.com
            </a>
        </div>
    </footer>
</template>

<script setup lang="ts">
const year = new Date().getFullYear()
</script>
