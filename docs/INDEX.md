# Travel Tour API
## Sommario
 
- API Reference
- Progetto

## API Reference

### Auth
API relative all'autenticazione e alla registrazione da parte degli admin degli utenti.

<details>
    <summary><code>POST</code> <code>/api/v1/auth/login</code></summary>

##### Request
```json
{
    "email": "string",
    "password": "string"
}
```

##### Responses
| Code | Description                   | Contents                 |
|------|-------------------------------|--------------------------|
| 200  | Login effettuato con successo | access_token, token_type |
| 401  | Errore nella richiesta        |                          |
| 404  | Utente non trovato            |                          |

</details>

<details>
    <summary><code>POST</code> <code>/api/v1/auth/register</code> as <code>admin</code></summary>

##### Request
```json
{
    "name": "string",
    "email": "string",
    "password": "string"
}
```

##### Responses
| Code | Description                | Contents        |
|------|----------------------------|-----------------|
| 201  | Utente creato con successo | id, name, email |
| 422  | Errore nella richiesta     |                 |
| 403  | Utente non autorizzato     |                 |
</details>

### Travel
API relative alle funzionalità di gestione dei viaggi.

<details>
    <summary><code>GET</code> <code>/api/v1/travels</code></summary>

##### Responses
| Code | Description               | 
|------|---------------------------|
| 200  | Lista dei viaggi paginata | 
</details>

<details>
    <summary><code>GET</code> <code>/api/v1/travels/{slug}/tours</code></summary>

##### Query Parameters

| Name      | Type   | Description                                            |
|-----------|--------|--------------------------------------------------------|
| dateFrom  | date   | Data in formato Y-m-d per filtrare la data di partenza |
| dateTo    | date   | Data in formato Y-m-d per filtrare la data di partenza |
| priceFrom | int    | Prezzo minimo per filtrare i tour                      |
| priceTo   | int    | Prezzo massimo per filtrare i tour                     |
| priceSort | string | Ordinamento prezzo                                     |
</details>

<details>
    <summary><code>POST</code> <code>/api/v1/travels</code> as <code>admin</code></summary>

##### Request

```json
{
    "name": "string",
    "slug": "string|null",
    "description": "string",
    "isPublic": "boolean",
    "numberOfDays": "int",
    "moods": {
        "mood": "mood_value"
    }
}
```

##### Responses
| Code | Description            | Contents                                               |
|------|------------------------|--------------------------------------------------------|
| 201  | Viaggio creato         | name, slug, description, isPublic, numberOfDays, moods |
| 422  | Errore nella richiesta |                                                        |
| 403  | Utente non autorizzato |                                                        |
</details>

<details>
    <summary><code>PUT</code> <code>/api/v1/travels/{id}</code> as <code>editor, admin</code></summary>

##### Request
```json
{
    "name": "string|null",
    "slug": "string|null",
    "description": "string|null",
    "isPublic": "boolean|null",
    "numberOfDays": "int|null",
    "moods": {
        "mood": "mood_value"
    }
}
```
##### Responses

| Code | Description            | Contents                                               |
|------|------------------------|--------------------------------------------------------|
| 200  | Viaggio aggiornato     | name, slug, description, isPublic, numberOfDays, moods |
| 422  | Errore nella richiesta |                                                        |
| 403  | Utente non autorizzato |                                                        |
</details>

<details>
    <summary><code>POST</code> <code>/api/v1/travels/{id}/tours</code> as <code>admin</code></summary>

##### Request
```json
{
    "name": "string",
    "startingDate": "date",
    "endingDate": "date",
    "price": "int"
}
```

##### Responses
| Code | Description            | Contents                                  |
|------|------------------------|-------------------------------------------|
| 201  | Tour creato            | id, name, startingDate, endingDate, price |
| 422  | Errore nella richiesta |                                           |
| 403  | Utente non autorizzato |                                           |
</details>

## Progetto

L'architettura del sistema è basata su pattern che derivano dal DDD (Domain Driven Design) e sono stati implementati con l'ausilio di Laravel. Nella fattispecie abbiamo i 
controller che validano la richiesta con le FormRequest di Laravel, creano un DTO che poi verrà passato a una Action che eseguirà l'azione richiesta. In questo modo abbiamo una
separazione netta fra la logica di business e i controller, che fanno parte del layer di comunicazione.

Inoltre ho usato il pattern Repository per la gestione dei dati e dei filtri, usando le Pipeline di Laravel per la gestione delle query. Questo ci permette di avere pulizia nel codice
e facilità di modifica delle query stesse. Un'altra soluzione sarebbe potuta essere l'utilizzo di un trait sul modello che contiene gli scope specifici, ma ho preferito usare le Pipeline
per avere un codice estendibile.

### Value Objects
- Travel/Mood.php per la gestione del singolo mood
- Travel/Moods.php per la gestione di tutti i mood
- Travel/NumberOfDays per la gestione del numero di giorni di un viaggio
- Tour/Price.php per la gestione del prezzo di un tour in centesimi

### DTOs
- User/User.php per la gestione dei dati di un utente in creazione
- Travel/Travel.php per la gestione dei dati di un viaggio in creazione
- Travel/TravelUpdate.php per la gestione dei dati di un viaggio in aggiornamento
- Tour/Tour.php per la gestione dei dati di un tour in creazione

### Migliorie possibili
- Adesione più stretta allo standard JSON API
- Standardizzazione dei messaggi di errore (sempre con formattazione JSON API)
- Implementazione di altri endpoint per le risorse travel e tour da parte di admin ed editor (CRUD)
- Unit Testing delle singole actions e dei repository 
- Validazione migliore per quanto riguarda creazione e aggiornamento travel nella sezione mood
- Validazione sui dati passati ai filtri per la ricerca dei tour
- Implementazione di actions upsert per evitare duplicazione di codice fra insert e update di risorse come travel e tour

### Librerie utilizzate
- laravel-ide-helper per la generazione dei commenti per l'autocompletamento
- laravel-shift/blueprint per la generazione del modello e delle migrations

### Librerie implementabili
- laravel-permission per la gestione dei ruoli e dei permessi
- laravel-json-api per la gestione delle risorse

