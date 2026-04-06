# CLAUDE.md — noppal.de

## Projekt-Überblick

**noppal.de** ist eine persönliche Laravel-Portfolio- und Experimentierseite von Alexander.
Sie dient als Spielwiese für neue Technologien und zeigt verschiedene selbst entwickelte Features.

---

## Tech-Stack

| Layer | Technologie |
|---|---|
| Backend | PHP 8.x, Laravel 11 |
| Admin-Panel | Filament (mit Filament Shield) |
| Frontend | Blade, Tailwind CSS, jQuery, Alpine.js, React (Experimente) |
| Realtime | Laravel Reverb (WebSocket), Pusher |
| Datenbank | MySQL, Redis |
| Storage | AWS S3 (league/flysystem-aws-s3-v3) |
| Auth | Laravel Jetstream + Sanctum + Passport |
| Reaktiv | Livewire |
| PDF | barryvdh/laravel-dompdf, spatie/laravel-pdf |
| Queue | Laravel Queue (Redis) |
| Notifications | NTFY (selbst gehostet), Twilio |
| OAuth | Laravel Socialite (Facebook) |
| E-Mail | PHPMailer, Webklex IMAP (Zimbra) |
| Video | pbmedia/laravel-ffmpeg, norkunas/youtube-dl-php |
| Build-Tool | Vite |
| Deployment | Docker, AWS EC2, Apache |

---

## Features / Module

### Öffentlich zugänglich
| Route | Beschreibung |
|---|---|
| `/` | Startseite mit Vorstellung + Memory-Karten-Karussell |
| `/blog` | Persönlicher Blog (Kategorien, Kommentare) |
| `/travel-blog` | Reiseblog / Foto-Galerie mit Kartenpunkten |
| `/cv` | Online-Lebenslauf mit PDF-Export |
| `/futter` | Futter-Manager (Mahlzeitenplaner) |
| `/chat` | WebSocket-Chat via Laravel Reverb |
| `/notify/test` | NTFY Push-Notifications |
| `/askGemeni` | Google Gemini API Integration |
| `/yt2mp3` | YouTube-zu-MP3 Konverter |
| `/friese` | Friesen-Lookup (PLZ-basiert) |
| `/arcade` | Mini-Games |
| `/react` | React-Experimente |

### Nur eingeloggt (auth)
| Route | Beschreibung |
|---|---|
| `/dashboard` | Redirect zum Blog |
| `/bank-transactions` | Bankumsätze hochladen & anzeigen |
| `/sk` | Schafkopf (bayerisches Kartenspiel) |
| `/weg` | WEG-Dokumentenverwaltung |
| `/kb` | Knowledge Base (Links/Notizen) |
| `/logs` | Server-Logs |
| `/laravelMyAdmin` | Eigener DB-Admin (wie phpMyAdmin) |
| `/zimbra/emails` | E-Mail Import via IMAP (Zimbra) |
| `/notify/create` | NTFY Notifications verwalten |
| `/filament/manageFields` | Filament Felder-Manager |
| `/phpmyadmin` | Redirect zu phpMyAdmin |

### Filament Admin
- CRM-ähnliches Modul mit Filament
- Kundenverwaltung, Rechnungen, Produkte, Angebote, Kontakte

---

## Wichtige Dateipfade

```
app/
  Http/Controllers/     # Alle Controller
  Models/               # Eloquent Models
  Livewire/             # Livewire Components
  Filament/             # Filament Ressourcen
resources/
  views/                # Blade Templates
  views/layouts/        # Layout-Komponenten (x-noppal = Haupt-Layout)
routes/
  web.php               # Alle Web-Routen
  api.php               # API-Routen
database/migrations/    # Migrationen
```

---

## Konventionen

- **Layout-Komponente**: `<x-noppal>` ist das Haupt-Layout für alle Seiten
- **Index-Karten**: `<x-index.card>` für die Startseiten-Feature-Karten
- **Auth-Guard**: Standard Laravel Sanctum/Jetstream
- **CSS-Framework**: Tailwind CSS (via Vite)
- **Namensgebung Routes**: `controller.aktion` (z.B. `blog.index`, `futter.save`)

---

## Deployment

- Server: AWS EC2 (noppal.de)
- Webserver: Apache
- Container: Docker (docker-compose.yml vorhanden)
- Git-Pull über `/git/pull` Route möglich
- Zertifikate: Let's Encrypt (noppal.de.pem)

---

## Hinweise für Claude

- Der Owner heißt **Alexander** und ist ein erfahrener PHP/Laravel-Entwickler
- Die Seite ist ein **persönliches Experimentierfeld** — kein Produktiv-System für Kunden
- Deutsch ist die bevorzugte Kommunikationssprache
- Code wird auf dem Remote-Server über Git deployed
- Viele Features sind Work-in-Progress / Experimente
- Bei neuen Features: Tailwind für Styling, Blade für Templates
- Keine unnötigen Abstraktionen — direkt und pragmatisch arbeiten
