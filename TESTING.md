# QA Testing Environment

This project has a separate browser-testing environment. It must never use the production `.env`, production database, or production SMTP credentials.

## Environment

- Environment: `testing`
- Database: MySQL database `sekolah_app_testing`
- Mailer: Laravel `log`
- Mail log: `storage/logs/testing.log`
- Application URL: `http://127.0.0.1:8001`
- QA admin username: `qa_admin`
- QA admin password: `qa_admin_123`
- QA admin role: existing `tu`

The QA credential is only for local testing. Do not copy it into `.env` or production secrets.

## Setup

Run these commands from the project root in PowerShell:

The current migration history contains a legacy MySQL index migration that fails on a fresh database because the index is still required by a foreign key. Do not run `migrate:fresh` against production or use it as the testing setup until that migration is fixed separately.

Provision the isolated test schema without copying production rows:

```powershell
$env:APP_ENV = 'testing'
$mysqldump = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe'
$mysql = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe'
New-Item -ItemType Directory -Force storage\testing | Out-Null
& $mysqldump --protocol=tcp -h 127.0.0.1 -P 3306 -u root --no-data --skip-triggers sekolah_app | Set-Content -Encoding utf8 storage\testing\schema.sql
cmd /c "`"$mysql`" --protocol=tcp -h 127.0.0.1 -P 3306 -u root sekolah_app_testing < storage\testing\schema.sql"
php artisan db:seed --class=Database\\Seeders\\TestingSeeder --env=testing --force
```

The dump uses `--no-data`; it copies schema only and must target `sekolah_app_testing`.

Start the testing server with the testing environment:

```powershell
php artisan serve --env=testing --host=127.0.0.1 --port=8001
```

The database name is intentionally different from the production database. Confirm the active environment before running destructive commands:

```powershell
php artisan about --env=testing
```

## Email verification

Testing uses the Laravel `log` mailer and the isolated `testing` log channel. Email creation is verifiable from `storage/logs/testing.log`, including recipient, sender, subject, and rendered message content.

This is not an inbox-level mail capture server. Email delivery to an inbox and client rendering remain BLOCKED until an approved local Mailpit, MailHog, or Mailtrap service is provided. Do not point the testing environment at the production Gmail SMTP server.

For every email test, report separately:

- `EMAIL CREATED`: the application invoked the Mailable.
- `EMAIL CAPTURED`: the message appears in `storage/logs/testing.log`.
- `EMAIL CONTENT VERIFIED`: the logged HTML contains the expected fields/link.
- `EMAIL DELIVERY VERIFIED`: a test inbox received the message.

## Cleanup

After QA, remove only testing data:

```powershell
Remove-Item storage/logs/testing.log -ErrorAction SilentlyContinue
```

Do not run these commands without `--env=testing`.
