# 09 · Messagerie

Conversations privées entre l'**équipe du club** (admin, éditeur, coach) et **un seul utilisateur à la fois**. Pas de discussion de groupe.

| | |
|---|---|
| Équipe | `/admin/messagerie`, `/editor/messagerie`, `/coach/messagerie` (`routes/messages.php`, partagé) |
| Adhérent | `/user/messages` |
| Liens communs | `/messagerie/ouvrir/{id}` (lien des e-mails), `/messagerie/piece-jointe/{id}` |
| Contrôleurs | `app/Http/Controllers/Messages/` (`StaffMessageController`, `MemberMessageController`, `MessageLinkController`) |
| Logique | `app/Services/MessageService.php` |
| Test | `php artisan test --filter=MessagingTest` |

## Fonctionnement

- **Envoi groupé** (« annonce ») : un message à plusieurs personnes crée **une conversation privée par destinataire**. Les familles ne voient jamais les réponses des autres. Suivi « lu par x / y » et « a répondu ».
- **Destinataires combinables** : personnes (recherche par nom, e-mail ou prénom du nageur), groupes du planning (comptes rattachés aux nageurs), convoqués d'une compétition (tous / sans réponse / confirmés), tous les adhérents, tous les coachs. Un aperçu donne le nombre de destinataires et les nageurs **sans compte** (qui ne recevront rien).
- **Adhérent** : voit uniquement ses conversations, peut écrire à un **coach précis** (ou à l'administrateur) et répondre. Un visiteur peut seulement répondre.
- **Coach / éditeur** : voit ses propres conversations.
- **Administrateur** : voit toutes les conversations (onglet « Toutes »), peut y répondre, et **lui seul supprime**. Pas de suppression automatique.
- **Pièce jointe** : PDF ou image, 5 Mo maximum, stockée dans `storage/app/private/messages/`, servie seulement aux participants et à l'admin.
- **Non-lus** : compteur rouge dans le menu de gauche, la barre du haut et les tableaux de bord.

## E-mails

- Notification `NewMessageNotification` : « Vous avez un nouveau message du CNBB », **sans le contenu**, avec un lien vers le site.
- Un seul e-mail tant que le destinataire n'a pas lu les messages précédents.
- Envoi immédiat (pas de file d'attente). Un échec est journalisé (`storage/logs/laravel.log`) sans bloquer l'envoi du message.
- Dépend de la configuration SMTP du `.env` (voir [02-deploiement-o2switch.md](02-deploiement-o2switch.md)).

## Tables

`message_announcements`, `conversations` (`staff_id`, `user_id`, `staff_read_at`, `user_read_at`, `last_message_at`, `last_message_by`), `conversation_messages`.

## Courriels aux familles hors site

La messagerie n'écrit qu'aux personnes qui ont un compte et n'affiche que du texte. Pour un courriel mis en page (invitation à créer un compte, convocation détaillée…), utiliser sa boîte mail habituelle, avec les adresses en **Cci**.
