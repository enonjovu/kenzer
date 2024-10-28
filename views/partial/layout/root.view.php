<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kenzer' }}</title>

    <link rel="stylesheet" href="/assets/build/style.css">
</head>

<body>
    <div class="grid grid-cols-[auto,1fr] w-full h-screen">
        <aside class="w-64 bg-neutral-200 h-full px-4">


            <h1 class="text-3xl font-medium px-4">Kenzer</h1>

            <div class="flex flex-col mt-4 divide-y divide-neutral-600">
                <a class="w-full py-2 hover:bg-neutral-300 duration-200 px-4" href="/">Home</a>
                <a class="w-full py-2 hover:bg-neutral-300 duration-200 px-4" href="/projects">Projects</a>
                <a class="w-full py-2 hover:bg-neutral-300 duration-200 px-4" href="/teams">Teams</a>
                <a class="w-full py-2 hover:bg-neutral-300 duration-200 px-4" href="/teams/my-team/members">Members</a>
                <a class="w-full py-2 hover:bg-neutral-300 duration-200 px-4" href="/profile">Profile</a>
            </div>
        </aside>
        <main>
            @slot
        </main>
    </div>
</body>

</html>