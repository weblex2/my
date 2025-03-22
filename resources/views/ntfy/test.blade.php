@extends('layouts.ntfy')
@section('nav')
     <!-- Livewire-Komponente -->
     @livewire('navigation-menu')  <!-- Hier binden wir die Livewire-Komponente innerhalb der section ein -->

@stop
@section('content')
<div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600">
    <div class="w-full p-8 bg-white shadow-xl sm:w-3/4 lg:w-1/2 bg-opacity-90 rounded-xl">
        <div class="mb-8 text-center">
            <h1 class="mb-4 text-4xl font-bold text-gray-800">NTFY Docker Demo</h1>
            <p class="text-lg text-gray-600">
                Willkommen zur NTFY-Demo!
                Hier siehst du, wie einfach es ist, NTFY in einem Docker-Container zu nutzen.
                Mit dieser Lösung kannst du Benachrichtigungen schnell und unkompliziert versenden.
                Du wirst sehen, wie einfach es ist, NTFY zu installieren und zu konfigurieren.<br/>
                (Beschreibung kommt noch)</p>
        </div>

        <!-- Flexbox für die Bilder, kleinerer Abstand bei großen Bildschirmen -->
        <div class="flex flex-col mt-6 sm:flex-row sm:justify-around sm:gap-4 lg:gap-2">
            <div class="w-full cursor-pointer sm:w-1/2" onclick="openModal('{{ asset('img/ntfy-setup1.jpeg') }}')">
                <img src="{{ asset('img/ntfy-setup1.jpeg') }}" alt="Setup Step 1" class="w-full max-w-[150px] mx-auto rounded-lg shadow-xl">
            </div>
            <div class="w-full cursor-pointer sm:w-1/2" onclick="openModal('{{ asset('img/ntfy-setup2.jpeg') }}')">
                <img src="{{ asset('img/ntfy-setup2.jpeg') }}" alt="Setup Step 2" class="w-full max-w-[150px] mx-auto rounded-lg shadow-xl">
            </div>
        </div>

        <div class="mt-8">
            <p class="mb-4 text-xl text-gray-700">Sende eine Testnachricht:</p>
            <form name="createPost" action="{{ route('ntfy.sendTestMessage') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <input type="hidden" name="user_id" value="{{ isset(Auth()->user()->id) ? Auth()->user()->id : '' }}">
                    <input type="text" id="ntfy-topic" name="msg" class="w-full p-4 transition duration-300 border-2 border-teal-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 hover:border-teal-500" placeholder="Gib deine Testnachricht ein">
                </div>
                <div class="flex justify-center">
                    <input type="submit" name="Save" class="px-6 py-3 font-semibold text-white transition duration-300 transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-teal-400 to-teal-600 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </form>
        </div>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">Braucht du Hilfe? Schau dir die <a href="https://docs.ntfy.sh/" target="_blank" class="text-blue-500 transition duration-300 hover:text-blue-700">NTFY-Dokumentation</a> an.</p>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="imageModal" class="fixed inset-0 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="relative">
        <span class="absolute top-0 right-0 p-4 text-white cursor-pointer" onclick="closeModal()">X</span>
        <img id="modalImage" src="" alt="Image" class="max-w-[90%] max-h-[90vh] rounded-lg shadow-xl">
    </div>
</div>

<script>
    // Funktion, um das Modal mit dem Bild zu öffnen
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;  // Setzt das Bild im Modal
        modal.classList.remove('hidden');  // Zeigt das Modal an
    }

    // Funktion, um das Modal zu schließen
    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');  // Versteckt das Modal
    }

    // Optional: Wenn auf den schwarzen Hintergrund außerhalb des Modals geklickt wird, Modal schließen
    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

@endsection
