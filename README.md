# EduKho-AI

# Local Run Guide

This project lives inside iCloud (`Desktop/files`). If iCloud keeps files as dataless placeholders,
Laravel autoload can hang and browser will show `ERR_CONNECTION_REFUSED` or timeout.

Use the commands below to avoid this recurring issue.

## One-time check

```bash
bash scripts/dev-doctor.sh
```

## Safe startup (recommended)

```bash
composer run dev:up
```

What this does:

1. Detects dataless files in `app/bootstrap/config/database/public/resources/routes/vendor`.
2. Requests iCloud to download missing local files.
3. Fails fast with unresolved file list if timeout is reached.
4. Starts php server at `http://127.0.0.1:8000`.

## If startup reports unresolved files

Run this first, then retry:

```bash
bash scripts/ensure-local-files.sh
composer run dev:up
```
# EduKho-AI.
