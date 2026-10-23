# Server Status

Azuriom plugin providing a public status page for your servers: live availability, uptime percentage, daily history and incident tracking, with Discord alerts.

## 🇫🇷 Français

# Server Status — Page de statut publique

Vos joueurs n'ont aujourd'hui aucun moyen de savoir si un serveur est en panne sans le rejoindre directement. **Server Status** affiche une vraie page de statut public (façon Statuspage.io), accessible sans connexion, qui détecte automatiquement les pannes et leur historique.

### Côté public (`/serverstatus`)

- Statut en direct de chaque serveur configuré (en ligne/hors ligne, joueurs connectés).
- **Pourcentage de disponibilité** sur une fenêtre configurable (30 jours par défaut).
- **Historique visuel jour par jour** (90 jours) : opérationnel, coupure partielle, coupure majeure, ou aucune donnée.
- **Temps de réponse** mesuré à chaque contrôle.
- **Historique des incidents** avec catégorie (Panne, Dégradation de service, Maintenance, Information), durée, et statut (en cours/résolu).
- Section dédiée aux **maintenances à venir**, distincte des incidents en cours.
- **Badges de statut** intégrables (`/serverstatus/badge/{serveur}`) pour une signature de forum, un profil Discord ou un README — ne déclenchent jamais de contrôle supplémentaire, donc sans risque même très sollicités.
- Fonctionne même sans cron système configuré : la page se rafraîchit elle-même dès qu'elle est visitée si les données sont trop anciennes.

### Côté staff

- Page de réglages : serveurs surveillés, fenêtre de disponibilité, rétention des contrôles bruts, texte d'introduction.
- **Détection automatique des pannes** et création/clôture automatique des incidents correspondants.
- Ajout manuel d'incidents (maintenance programmée, dégradation, information...) avec titre, catégorie, dates de début/fin.
- Notifications Discord par webhook à chaque coupure et retour en ligne, avec texte entièrement personnalisable.
- Une seule permission (`serverstatus.manage`) pour un contrôle simple des accès admin.

### Compatibilité

- Compatible avec n'importe quel type de serveur pris en charge par Azuriom (Minecraft, autres jeux), réutilise directement les mécanismes de ping du cœur Azuriom.
- Aucune donnée écrite dans les tables du cœur : tout vit dans ses propres tables.
- Traductions complètes en français et en anglais.
- Compatible avec le thème par défaut d'Azuriom, construit en Bootstrap 5 pour s'intégrer à la plupart des autres thèmes.

## 🇬🇧 English

# Server Status — Public status page

Your players currently have no way to know if a server is down without joining it directly. **Server Status** provides a real public status page (Statuspage.io style), accessible without logging in, which automatically detects outages and their history.

### Public side (`/serverstatus`)

- Live status of every configured server (online/offline, connected players).
- **Uptime percentage** over a configurable window (30 days by default).
- **Visual day-by-day history** (90 days): operational, partial outage, major outage, or no data.
- **Response time** measured on every check.
- **Incident history** with a category (Outage, Degraded performance, Maintenance, Information), duration, and status (ongoing/resolved).
- Dedicated **upcoming maintenance** section, separate from ongoing incidents.
- Embeddable **status badges** (`/serverstatus/badge/{server}`) for a forum signature, a Discord profile or a README — never trigger an extra check, so safe even under heavy use.
- Works even without a system cron configured: the page refreshes itself as soon as it's visited if the data is too old.

### Staff side

- Settings page: monitored servers, uptime window, raw check retention, intro text.
- **Automatic outage detection**, opening and closing the matching incident automatically.
- Manually add incidents (scheduled maintenance, degraded performance, information...) with a title, category, and start/end dates.
- Discord webhook notifications on every outage and recovery, with fully customizable message text.
- A single permission (`serverstatus.manage`) for simple admin access control.

### Compatibility

- Works with any server type supported by Azuriom (Minecraft, other games), directly reusing Azuriom's own core ping mechanisms.
- Never writes to core tables: everything lives in its own tables.
- Fully translated in French and English.
- Compatible with Azuriom's default theme, built with Bootstrap 5 to fit most other themes.
