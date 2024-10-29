@layout('layout/auth',['title'=>'Create Account'])

<form wire:submit="login" class="space-y-3">

    <div class="grid gap-1">
        <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
        <input type="text" required
            class="border-gray-300 px-3 py-2 block w-full border  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            name="name" id="name" />

    </div>

    <div class="grid gap-1">
        <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
        <input type="email" required
            class="border-gray-300 px-3 py-2 block w-full border  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            name="email" id="email" />

    </div>


    <div class="grid gap-1">
        <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
        <input type="password" required
            class="border-gray-300 px-3 py-2 block w-full border  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            name="password" id="password" />

    </div>


    <div class="grid gap-1">
        <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Password Confirmation</label>
        <input type="password" required
            class="border-gray-300 px-3 py-2 block w-full border  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            name="password_confirmation" id="password_confirmation" />

    </div>


    <!-- Remember Me -->
    <div class="block mt-4">
        <label for="remember" class="inline-flex items-center">
            <input wire:model="form.remember" id="remember" type="checkbox"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <span class="ms-2 text-sm text-gray-600">Accept terms And Conditions</span>
        </label>
    </div>

    <div class="flex items-center justify-end mt-4 space-x-2">

        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            href="/auth/login" wire:navigate>
            Already Have An Account?
        </a>

        <button
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Create Account
        </button>
    </div>
</form>