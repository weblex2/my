# Gallery Administration Panel

## Übersicht
Ein modernes Admin-Panel zur Verwaltung deiner Travel Map Gallery, erstellt mit Tailwind CSS.

## Zugriff
- **URL:** `/gallery-admin`
- **Link:** Direkt in der Sidebar von `/travel-blog/index3` verfügbar ("Administration" Button)

## Features

### 🗂️ Galerien verwalten
- **Neue Galerie erstellen:** Name, Code (z.B. EU2024), Country Map Name
- **Galerie bearbeiten:** Alle Eigenschaften aktualisieren
- **Galerie löschen:** Inkl. aller Orte und Fotos (mit Sicherheitsabfrage)
- **Übersicht:** Anzahl der Orte und Fotos pro Galerie

### 📍 Orte (Mappoints) verwalten
- **Neuen Ort erstellen:** 
  - Galerie auswählen
  - Ortsname
  - GPS-Koordinaten (Lat/Lon)
  - Beschreibung
- **Ort bearbeiten:** Name, Koordinaten, Beschreibung aktualisieren
- **Ort löschen:** Inkl. aller Fotos an diesem Ort
- **Orte anzeigen:** Filter nach Galerie

### 📸 Fotos organisieren
- **Foto-Ansicht:** Grid-Layout mit Thumbnails
- **Filter:** Nach Galerie filtern oder nach Text suchen
- **Foto löschen:** Einzelnes Foto löschen (inkl. aller zugehörigen Daten)
- **Foto verschieben:** 
  - Ziel-Galerie und Ort auswählen
  - Fotos werden vollständig verschoben (inkl. Texte)
- **Bulk-Operationen:** Mehrere Fotos gleichzeitig zuweisen/löschen (in Arbeit)

### 🔧 Technisches
- **Text-Verwaltung:** Foto-Texte bearbeiten (mehrere Sprachen)
- **Datum bearbeiten:** Aufnahme-Datum aktualisieren
- **Reihenfolge ändern:** Sortierung der Fotos anpassen

## API Endpunkte

### Gallery CRUD
- `POST /gallery-admin/gallery` - Neue Galerie erstellen
- `PUT /gallery-admin/gallery/{id}` - Galerie aktualisieren
- `DELETE /gallery-admin/gallery/{id}` - Galerie löschen

### Mappoint CRUD
- `GET /gallery-admin/mappoints/{galleryId}` - Alle Orte einer Galerie
- `POST /gallery-admin/mappoint` - Neuen Ort erstellen
- `PUT /gallery-admin/mappoint/{id}` - Ort aktualisieren
- `DELETE /gallery-admin/mappoint/{id}` - Ort löschen

### Photo Management
- `GET /gallery-admin/photos?gallery_id=X&search=Y` - Fotos laden
- `DELETE /gallery-admin/photo/{id}` - Foto löschen
- `POST /gallery-admin/photos/assign` - Fotos zuweisen
- `PUT /gallery-admin/photo/text/{id}` - Foto-Text aktualisieren
- `PUT /gallery-admin/photo/date/{id}` - Foto-Datum aktualisieren

## Sicherheit
- Alle Lösch-Operationen haben Bestätigungs-Dialoge
- Transaktionssicherheit: Bei Fehlern wird ein Rollback durchgeführt
- CSRF-Schutz für alle POST/PUT/DELETE Requests

## Technologien
- **Backend:** Laravel 10, PHP 8.1+
- **Frontend:** Tailwind CSS, Vanilla JavaScript
- **Icons:** Font Awesome 6
- **Database:** MySQL

## Hinweise
- Das Admin-Panel ersetzt NICHT das Upload-System
- Uploads weiterhin über `/travel-blog/upload-bulk`
- Das Admin-Panel dient der Organisation bereits hochgeladener Inhalte
