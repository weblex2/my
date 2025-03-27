<meta name="csrf-token" content="{{ csrf_token() }}">
@extends('layouts.laravelMyAdmin')

@section('content')

    <div id ="result">result</div>
@stop

<script>
async function executeMigration() {
    const url = "/laravelMyAdmin/exec-migration"; // Deine API-Route
    const migrationFile = "2025_03_27_215940_create_tests_table.php";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    try {
        // 1. Migration ausführen
        let response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken, // CSRF-Token hinzufügen
            },
            body: JSON.stringify({ migration: migrationFile }),
        });
        console.log("Migration Response:", response);
        let data = await response.json();
        console.log("Migration Response:", data);

        // 2. Rollback nach erfolgreicher Migration
        response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken, // CSRF-Token hinzufügen
            },
            body: JSON.stringify({ migration: migrationFile, rollback: true }),
        });
        console.log("Rollback Response:", response);
        data = await response.json();
        console.log("Rollback Response:", data);
    } catch (error) {
        console.error("Fehler beim Ausführen der Migration:", error);
    }
}

// Funktion aufrufen
executeMigration();
</script>
