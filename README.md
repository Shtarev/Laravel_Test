# TEST-AUFGABE

Laravel Full Stack Developer (Backend-Schwerpunkt).

### Beschreibung

Entwickle eine RESTful API für ein einfaches Aufgaben-Management-System mit einem Schwerpunkt auf dem Backend. Benutzer sollen in der Lage sein, Aufgaben zu erstellen, zu bearbeiten, zu löschen und aufzulisten. Jede Aufgabe soll einen Titel, eine Beschreibung und einen Status enthalten.

#### Anforderungen Grundprüfung

1. **Datenbankmodell:**
   - Erstelle ein Datenbankmodell für Aufgaben mit den notwendigen Feldern
   - Verwende Laravel-Migrationen zur Erstellung der Datenbankstruktur.
2. **Eloquent-Modell:**
   - Implementiere ein Eloquent-Modell für Aufgaben mit den entsprechenden Beziehungen.
3. **Controller:**
   - Erstelle einen Controller für Aufgaben mit CRUD-Operationen (Create, Read, Update, Delete).
   - Validiere Benutzereingaben gemäß den Anforderungen.
4. **RESTful-Routen:**
   - Lege RESTful-Routen für die Aufgaben-Controller-Methoden fest.
5. **Middleware:**
   - Implementiere ein Middleware, um sicherzustellen, dass alle Anfragen authentifiziert sind.
6. **Authentifizierung:**
   - Implementiere Benutzer-Authentifizierung für API-Anfragen.
   - Nur authentifizierte Benutzer dürfen Aufgaben erstellen, bearbeiten oder löschen.
7. **Tests:**
   - Schreibe PHPUnit-Tests für wichtige API-Funktionen (Erstellung, Aktualisierung, Löschung usw.).

## Erweiterterung-1

1. **Datenbankmodell:**
    - Erweitere das Datenbankmodell für Aufgaben um ein neues Feld "deadline" für den Fälligkeitszeitpunkt der Aufgaben.
    - Füge zwei zusätzliche Eloquent-Modelle für die Entitäten "Benutzer" und "Projekte" hinzu.
    - Stelle Beziehungen zwischen den Modellen her:
        - Ein Benutzer kann mehrere Aufgaben haben.
        - Eine Aufgabe kann zu einem Projekt gehören, und ein Projekt kann mehrere Aufgaben haben.
    - Implementiere Migrationen, um die Datenbankstruktur zu aktualisieren.
2. **Controller:**
    - Passe den Aufgaben-Controller an, um die neuen Beziehungen zu berücksichtigen.
    - Implementiere Methoden, um Aufgaben für einen bestimmten Benutzer oder ein bestimmtes Projekt abzurufen.
    - Erweitere das Projekt um die Möglichkeit, die Deadline einer Aufgabe zu aktualisieren.
    - Implementiere eine zusätzliche Methode, um alle überfälligen Aufgaben zurückzugeben.
3. **RESTful-Routen:**
    - Füge RESTful-Routen hinzu, um die neuen Controller-Methoden anzusprechen.
4. **Middleware:**
    - Implementiere eine Middleware, um sicherzustellen, dass nur autorisierte Benutzer Aufgaben mit überfälligen Deadlines bearbeiten können.
    - Füge eine Middleware hinzu, um sicherzustellen, dass ein Benutzer nur auf seine eigenen Aufgaben zugreifen kann.
5. **Authentifizierung (optional):**
    - Erweitere die Authentifizierung, um die Rolle des Benutzers zu berücksichtigen.
    - Nur Benutzer mit der Rolle "Admin" dürfen nicht nur ihre eigenen Aufgaben bearbeiten, sondern auch generell Aufgaben anderer Benutzer mit überfälligen Deadlines. Diese Regelung ist sinnvoll, um sicherzustellen, dass Administratoren die Flexibilität haben, dringende Aufgaben im gesamten System zu verwalten und nicht nur auf ihre eigenen beschränkt sind. Implementiere daher die Authentifizierung so, dass sie die notwendige Autorisierung für Admin-Benutzer sicherstellt, um eine umfassende Bearbeitung von überfälligen Aufgaben zu ermöglichen.
6. **Event-Listener:**
    - Implementiere einen Event-Listener, der aufgerufen wird, wenn eine Aufgabe aktualisiert wurde.
    - Der Listener soll überprüfen, ob die Deadline abgelaufen ist, und gegebenenfalls eine Benachrichtigung (Notification) an den Benutzer senden.
7. **Tests:**
    - Schreibe PHPUnit-Tests, um die neuen Funktionen, die Leistungsfähigkeit der API, den Event-Listener und die Beziehungen zwischen Benutzern, Aufgaben und Projekten zu überprüfen.
8. **Dokumentation:**
   - Ergänze die README-Datei um detaillierte Anweisungen zur Installation und Verwendung der erweiterten Funktionen.

## Erweiterterung-2

1. **Titel und Beschreibung:**
    - Begrenze die maximale Länge des Titels auf 255 Zeichen.
    - Stelle sicher, dass Titel und Beschreibung erforderlich sind.
2. **Status:**
    - Stelle sicher, dass der Status nur bestimmte Werte annehmen kann: "todo", "in_progress" und "done".
    "done" свяжи это с проверкой Deadline
3. **Fälligkeitsdatum (Deadline):**
    - Überprüfe, ob das Fälligkeitsdatum ein gültiges Datum ist und in der Zukunft liegt.

---
---
ERGEBNIS:

#### Eine kurze Beschreibung, was ich getan habe und wie man es benutzen kann

### Folgende Konzepte werden in der Arbeit verwendet:
- Aufgabe -> Task
- Benutzer -> User
### Ergänzung zur EventListenerUmsetzung und Aufgabe:
- der Event-Listener aufgerufen wird, wenn eine Aufgabe aktualisiert wurde. Das habe ich verschtanden als "wenn eine Aufgabe updated wurde"

---
- PHP 8.2
- MySQL 8
---
# Install
- Hinweis- laravel.loc - ist ein Beispiel und könnte anders sein
- git clone https://github.com/Shtarev/Laravel_Test_Aufgabe.git laravel.loc
- cd laravel.loc
## PostInstall
- Redaktieren Sie .env nach seine DB Daten
### Öffnen Sie Terminal im RootProjekt und geben:
- sudo chmod -R 777 storage
- sudo chmod -R 777 bootstrap/cache
- composer install
- php artisan jwt:secret
- php artisan key:generate
- php artisan migrate
---
## Seeder (optional):
### Wenn Sie die Datenbank mit Fake-Daten füllen möchten
- php artisan db:seed
- Dadurch wird ein Benutzer Admin erstellt, mit Daten(email => admin@admin.com | password => password)
- und Benutzer User mit Daten(email => user@user.com | password => password)
- und 50 Aufgaben
- P.S. es wird sehr bequem im Postman...
---
## Tests
- php artisan optimize:clear
- php artisan test
---
#Verwendung mit Postman
## Autorisierung erforderlich!!!

- url: http://laravel.loc/* ist ein Beispiel und könnte anders sein
---
- WICHTIG: Alle Requestanfrage außer 'http://laravel.loc/register' und 'http://laravel.loc/login' sollen mit Token sein!!!
- Der Token bekommen Sie bei Registrierung oder Anmeldung
- Bevor Sie Button 'Senden' drücken, gehen Sie wie folgt vor
- Authorisation wählen
- Folgende Daten eingeben
- Type: Bearer Token
- Token: der Token, der kopiert wurde geben
- und jetzt Button 'Senden' drücken
---

## Neuen Benutzer registrieren (für Admin role 1 geben)
### REQUEST
- method: POST
- url: http://laravel.loc/register
- Body->form-data wählen
- Eingegebene Daten
- key: name => Value- Newuser
- key: email => Value- newuser@newuser.com
- key: password => Value- password
- key: password_confirmation => Value- password
- key: role => Value- 2
- Button 'Senden' drücken

### RESPONSE
- Siehe in Response->Body "token"
- Kopieren Sie der Token (Speichern)

## Benutzeranmeldung (Beispiel)
### REQUEST
- method: POST
- url: http://laravel.loc/login
- Body->form-data wählen
- Eingegebene Daten:
- key: email => Value- newuser@newuser.com
- key: password => Value- password
- Button 'Senden' drücken

### RESPONSE
- Siehe in Response->Body "token"
- Kopieren Sie der Token (Speichern)

## Benutzerabmeldung
### REQUEST
- method: POST
- url: http://laravel.loc/logout

## Benutzerlöschen
### REQUEST
- method: GET
- url: http://laravel.loc/destroy/{id}

---
---

# AUFGABE Schnittstelle
- Um eine Aufgabe zu erstellen, benötigen Sie eine Projekt-ID(erstellen Sie ein Projekt, wenn es keine gibt)

## Alle Aufgaben ansehen
### REQUEST
- method: GET
- url: http://laravel.loc/api/task

## Eine Aufgabe ansehen
### REQUEST
- method: GET
- url: http://laravel.loc/api/task/show/{id}

## Eine Aufgabe erstellen
### REQUEST
- method: POST
- url: http://laravel.loc/api/task/store
- Body->form-data wählen
- Eingegebene Daten
- key: title => Value- string
- key: description => Value- string
- key: text => Value- string
- key: deadline => Value- dateTimeString (Beispiel- 2025-02-10 13:01:54)
- key: project_id => Value- number
- key: status => Value- "todo", "in_progress" oder "done"

## Eine Aufgabe bearbeiten
### REQUEST
- method: PUT
- url: http://laravel.loc/api/task/update/{id}
- Params wählen
- Eingegebene Daten (für Bearbeitung):
- key: title => Value- string
- key: description => Value- string
- key: text => Value- string
- key: deadline => Value- dateTimeString (Beispiel- 2025-02-10 13:01:54)
- key: project_id => Value- number
- key: status => Value- "todo", "in_progress" oder "done"

## Eine Aufgabe löschen
### REQUEST
- method: DELETE
- url: http://laravel.loc/api/task/destroy/{id}

---

# PROJECT Schnittstelle

## Ein Project ansehen
### REQUEST
- method: GET
- url: http://laravel.loc/api/project

## Ein Project ansehen
### REQUEST
- method: GET
- url: http://laravel.loc/api/project/show/{id}

## Ein Project erstellen
### REQUEST
- method: POST
- url: http://laravel.loc/api/project/store
- Body->form-data wählen
- Eingegebene Daten
- key: title => Value- string
- key: description => Value- string

## Ein Project bearbeiten
### REQUEST
- method: PUT
- url: http://laravel.loc/api/project/update/{id}
- Params wählen
- Eingegebene Daten (für Bearbeitung):
- key: title => Value- string
- key: description => Value- string

## Ein Project löschen
### REQUEST
- method: DELETE
- url: http://laravel.loc/api/project/destroy/{id}
