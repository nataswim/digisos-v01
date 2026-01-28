<nav class="bg-gray-100 p-3">
    <ul class="flex space-x-4">
        <li><a href="{{ route('home') }}" class="hover:underline">Accueil</a></li>
        <li><a href="{{ route('about') }}" class="hover:underline">A propos</a></li>
        <li><a href="{{ route('contact') }}" class="hover:underline">Contact</a></li>
        <li><a href="{{ route('posts.public.index') }}" class="hover:underline">Articles</a></li>
        <li><a href="{{ route('public.fiches.index') }}" class="hover:underline">Fiches</a></li>
        <li><a href="{{ route('public.videos.index') }}" class="hover:underline">Vidéos</a></li>
        <li><a href="{{ route('ebook.index') }}" class="hover:underline">Téléchargements</a></li>
    </ul>
</nav>