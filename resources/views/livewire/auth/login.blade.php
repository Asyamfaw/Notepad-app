<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<div class="min-h-screen bg-[#050505] flex items-center justify-center px-4 relative overflow-hidden">

    <!-- Background Glow -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.06),transparent_60%)]"></div>

    <!-- Card -->
    <div
        class="relative w-full max-w-md rounded-3xl border border-white/10
               bg-white/[0.03] backdrop-blur-2xl
               shadow-[0_20px_80px_rgba(0,0,0,0.6)]
               p-10"
    >

        <!-- Logo -->
        <div class="flex justify-center">
            <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-8 h-8 text-white"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 6h8M8 12h8M8 18h4" />
                </svg>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mt-6">
            <h1 class="text-5xl font-bold text-white">
                Noteku
            </h1>

            <p class="text-zinc-500 mt-2">
                Atmospheric Obsidian Workspace
            </p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="login" class="mt-10 space-y-5">

            <!-- Email -->
            <div>
                <label class="text-sm text-zinc-300 mb-2 block">
                    Email Address
                </label>

                <div class="relative">
                    <input
                        type="email"
                        wire:model.defer="email"
                        placeholder="name@company.com"
                        class="w-full h-14 rounded-xl
                               bg-black/50
                               border border-white/5
                               text-white
                               placeholder:text-zinc-600
                               px-4
                               focus:outline-none
                               focus:border-white/20"
                    >
                </div>

                @error('email')
                    <span class="text-red-400 text-xs mt-1 block">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between mb-2">
                    <label class="text-sm text-zinc-300">
                        Password
                    </label>

                    <a href="#"
                       class="text-sm text-zinc-500 hover:text-zinc-300">
                        Forgot password?
                    </a>
                </div>

                <input
                    type="password"
                    wire:model.defer="password"
                    placeholder="••••••••"
                    class="w-full h-14 rounded-xl
                           bg-black/50
                           border border-white/5
                           text-white
                           placeholder:text-zinc-600
                           px-4
                           focus:outline-none
                           focus:border-white/20"
                >

                @error('password')
                    <span class="text-red-400 text-xs mt-1 block">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Remember -->
            <div class="flex items-center gap-3">
                <input
                    type="checkbox"
                    id="remember"
                    wire:model.defer="remember"
                    class="rounded border-zinc-700 bg-black"
                >

                <label
                    for="remember"
                    class="text-zinc-400 text-sm"
                >
                    Remember for 30 days
                </label>
            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full h-14 rounded-xl
                       bg-white
                       text-black
                       font-semibold
                       text-lg
                       hover:bg-zinc-200
                       transition"
            >
                <span wire:loading.remove>
                    Sign In
                </span>

                <span wire:loading>
                    Loading...
                </span>
            </button>

        </form>

        <!-- Divider -->
        <div class="flex items-center gap-4 my-8">
            <div class="flex-1 h-px bg-white/5"></div>

            <span class="text-xs tracking-[0.3em] text-zinc-600">
                OR CONTINUE WITH
            </span>

            <div class="flex-1 h-px bg-white/5"></div>
        </div>

        <!-- Google -->
        <button
            class="w-full h-14 rounded-xl
                   bg-white/[0.03]
                   border border-white/5
                   text-white
                   hover:bg-white/[0.05]
                   transition"
        >
            Google
        </button>

        <!-- Register -->
        <p class="text-center text-zinc-500 mt-8">
            Don't have an account?

            <a
                href="{{ route('register') }}"
                class="text-white font-medium hover:underline"
            >
                Create Account
            </a>
        </p>

    </div>

</div>