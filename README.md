
# Intranet Agglo

## Installation

cette application est a installer dans le dossier **nextcloud/apps/**

une fois dans ce répertoire `git clone https://github.com/STaX-62/intranetagglo.git`

après installation vérifier de bien **définir le propriétaire du dossier comme étant l'utilisateur de nextcloud** (sinon l'enregistrement des photos et documents sera impossible)

```
sudo chown nextcloud-user:nextcloud-user -R intranetagglo
sudo chown nextcloud-user:nextcloud-user -R intranetagglo/*
```

## Développement

```
npm install
```

```
npm run build
```
