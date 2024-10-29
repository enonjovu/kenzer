@layout('layout/auth',['title'=>'Login'])

<form method="POST" action="/auth/login">
    <!-- Email Address -->
    <div class="grid gap-1">
        <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
        <input type="email" required
            class="border-gray-300 px-3 py-2 block w-full border  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            name="email" id="email" />

    </div>

    <div class="mt-4 grid gap-1">
        <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
        <input type="password" required
            class="border-gray-300 px-3 py-2 block w-full border  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            name="password" id="password" />
    </div>

    <!-- Remember Me -->
    <div class="block mt-4">
        <label for="remember" class="inline-flex items-center">
            <input wire:model="form.remember" id="remember" type="checkbox"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <span class="ms-2 text-sm text-gray-600">Remember me</span>
        </label>
    </div>

    <div class="flex items-center justify-end mt-4 space-x-2">

        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            href="/auth/register" wire:navigate>
            Create Account?
        </a>

        <button
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Log in
        </button>
    </div>
</form>