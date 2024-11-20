# Rubik Internal Redirect Updater

### Plugin Name: Rubik Internal Redirect Updater

**Version:** 1.2  
**Author:** Matteo Morreale  
**Description:** Questo plugin permette di aggiornare i link interni di un sito WordPress che puntano a redirect 301, sostituendoli con il loro target finale, evitando i passaggi di redirect inutili e migliorando le performance.

--- ATTENZIONE --- è necessario che sia installato il plugin [Rubik Link Analyzer](https://github.com/matteomorreale/rubik-link-analyzer) e che sia stata effettuata la scansione.

## Caratteristiche

- **Scansione dei link interni:** Scansiona i post del sito per individuare link interni che rimandano a redirect 301.
- **Aggiornamento automatico:** Aggiorna automaticamente i link per puntare direttamente all'URL finale, migliorando la SEO e riducendo i tempi di caricamento.
- **Interfaccia semplice:** Fornisce un'interfaccia amministrativa per avviare la scansione e visualizzare il log delle modifiche.
- **Simulazione dell'aggiornamento:** È possibile simulare l'aggiornamento dei post per verificare il funzionamento senza effettivamente apportare modifiche.
- **Tracciamento dello stato:** I link elaborati vengono tracciati in una tabella di supporto per evitare ripetizioni e loop infiniti.

## Requisiti

- **Plugin Rubik Link Analyzer** con scansione già effettuata
- **WordPress 5.0+**
- **PHP 7.0+**
- **cURL** abilitato per le richieste HTTP

## Installazione

1. **Carica il plugin:** Carica la cartella del plugin nella directory `/wp-content/plugins/`.
2. **Attiva il plugin:** Vai nella sezione Plugin di WordPress e attiva "Rubik Internal Redirect Updater".
3. **Opzioni di amministrazione:** Troverai una nuova voce di menu chiamata "Redirect Updater" nel pannello di amministrazione di WordPress.

## Utilizzo

1. **Accesso al plugin:** Vai su **Redirect Updater** nel menu amministrativo di WordPress.
2. **Avvia la scansione:** Clicca sul pulsante "Avvia scansione e aggiornamento link" per iniziare a scansionare i post.
3. **Log delle modifiche:** Durante la scansione, il plugin mostrerà un log dei link elaborati, inclusi:
   - Il **titolo del post** in cui il link è stato trovato (con link al post stesso).
   - L'**URL originale** del link.
   - Lo **stato** della verifica (es. OK, Ignorato, Errore).
   - Il **codice di risposta HTTP** per ogni link (es. 301, 404, ecc.).

## Funzionamento

- **Scansione Link:** Il plugin effettua una scansione dei link all'interno dei post per determinare se puntano a redirect 301.
- **Elaborazione Redirect:** I link interni vengono aggiornati con la destinazione finale se la redirezione viene confermata.
- **Log Completo:** Viene generato un log con il risultato di ogni operazione, inclusi errori e codici di risposta HTTP.
- **Tracciamento dei Link Elaborati:** Per evitare loop e ripetizioni, i link già elaborati vengono tracciati in una tabella separata (`rubik_processed_links`).

## Tabelle Utilizzate

- **`rubik_link_data`**: Tabella esistente, utilizzata per la scansione dei link.
- **`rubik_processed_links`**: Tabella di supporto creata dal plugin per memorizzare i link già elaborati e prevenire ripetizioni.

## Note di Sicurezza

- Il plugin utilizza **nonce** per la protezione delle chiamate AJAX e per prevenire attacchi CSRF.
- La verifica dei link è effettuata con **cURL**, assicurando che il plugin possa seguire i redirect senza esporre vulnerabilità.

## Limitazioni

- Attualmente il plugin **non modifica i link esterni**, ma li marca come ignorati nel log.
- Se un link subisce una catena di redirect, il plugin segue tutti i redirect fino a un massimo di 10 per evitare possibili **loop infiniti**.

## Disinstallazione

Quando il plugin viene disattivato, la tabella di supporto (`rubik_processed_links`) viene rimossa per liberare spazio nel database.

## Contributi

Contributi e suggerimenti sono benvenuti! Sentiti libero di aprire issue o pull request su [GitHub](https://github.com/matteomorreale/rubik-internal-redirect-updater).

## Licenza

Questo plugin è distribuito sotto la **GPLv2** o successive. Sentiti libero di modificarlo e ridistribuirlo.

## Contatti

Per ulteriori informazioni o assistenza, contatta [Matteo Morreale](mailto:matteo.morreale@gmail.com) [sito web](https://matteomorreale.it)

---
Grazie per aver utilizzato Rubik Internal Redirect Updater! Speriamo che possa aiutarti a migliorare la gestione dei link e le performance del tuo sito.