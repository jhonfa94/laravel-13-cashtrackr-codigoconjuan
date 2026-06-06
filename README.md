# 💰 CashTrackr — Gestor de Presupuestos con IA

**CashTrackr** es una aplicación web de gestión de presupuestos y gastos personales construida con **Laravel 13**, **React** (Inertia.js) y potenciada por **Inteligencia Artificial** mediante el paquete `laravel/ai`. Permite a los usuarios crear presupuestos, registrar gastos, analizar su situación financiera conversando con un asistente de IA y gestionar suscripciones premium via **Stripe**.

---

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Stack Tecnológico](#-stack-tecnológico)
- [Arquitectura del Proyecto](#-arquitectura-del-proyecto)
- [Requisitos Previos](#-requisitos-previos)
- [Configuración — Ambiente de Desarrollo](#-configuración--ambiente-de-desarrollo)
  - [Opción A: PostgreSQL (Desarrollo)](#opción-a-postgresql-desarrollo)
  - [Opción B: MySQL (Desarrollo)](#opción-b-mysql-desarrollo)
  - [Opción C: Neon PostgreSQL Serverless (Desarrollo)](#opción-c-neon-postgresql-serverless-desarrollo)
- [Configuración — Ambiente de Producción](#-configuración--ambiente-de-producción)
  - [PostgreSQL en Producción](#postgresql-en-producción)
  - [MySQL en Producción](#mysql-en-producción)
- [Variables de Entorno](#-variables-de-entorno)
- [Comandos Útiles](#-comandos-útiles)
- [Pruebas](#-pruebas)
- [Integración con Stripe](#-integración-con-stripe)
- [Funcionalidades de IA](#-funcionalidades-de-ia)

---

## ✨ Características

- 📊 **Gestión de Presupuestos**: Crear, editar y eliminar presupuestos con seguimiento de gastos en tiempo real
- 💸 **Control de Gastos**: Registro detallado de gastos por categoría asociados a cada presupuesto
- 🤖 **Asistente de IA**: Chat inteligente para analizar gastos, agregar transacciones y buscar información financiera
- 🧾 **Escaneo de Tickets**: Extracción automática de datos de recibos mediante visión artificial
- 💳 **Suscripciones con Stripe**: Planes mensual y anual con gestión de facturación integrada
- 🔐 **Autenticación Completa**: Registro, login, verificación de email y recuperación de contraseña
- 📱 **UI Responsiva**: Interfaz moderna con React, Inertia.js y Tailwind CSS v4

---

## 🛠 Stack Tecnológico

### Backend
| Tecnología | Versión | Uso |
|---|---|---|
| PHP | ^8.3 | Lenguaje principal |
| Laravel | ^13.8 | Framework web |
| Inertia.js (Laravel) | ^3.1 | Adaptador SSR/SPA |
| Laravel Cashier (Stripe) | ^16.5 | Suscripciones y pagos |
| Laravel AI | ^0.7.2 | Agentes e integración con LLMs |
| Ziggy | ^2.6 | Rutas de Laravel en el frontend |

### Frontend
| Tecnología | Versión | Uso |
|---|---|---|
| React | ^19.2 | UI framework |
| TypeScript | — | Tipado estático |
| Vite | ^8.0 | Bundler y dev server |
| Tailwind CSS | ^4.0 | Estilos utilitarios |
| Inertia.js (React) | ^3.3 | SPA sin API REST |
| Zustand | ^5.0 | Gestión de estado |
| React Toastify | ^11.1 | Notificaciones |
| AI SDK (React) | ^3.0 | Streaming del chat de IA |

### Bases de Datos Soportadas
- **SQLite** — Desarrollo rápido (por defecto)
- **PostgreSQL** — Recomendado para producción y desarrollo robusto
- **MySQL / MariaDB** — Alternativa compatible

---

## 🏗 Arquitectura del Proyecto

```
laravelcashtrackr/
├── app/
│   ├── Ai/
│   │   ├── Agents/          # Agente BudgetAssistant (laravel/ai)
│   │   └── Tools/           # Herramientas: AddExpense, SearchExpenses
│   ├── Http/
│   │   ├── Controllers/     # BudgetController, ExpenseController, SubscriptionController...
│   │   ├── Middleware/      # Middleware personalizado (subscribed, etc.)
│   │   └── Requests/        # Form Requests con validación
│   ├── Models/              # User, Budget, Expense
│   ├── Notifications/       # Notificaciones de email
│   └── Policies/            # Autorización por recurso
├── database/
│   ├── migrations/          # 11 migraciones (users, budgets, expenses, cashier, AI...)
│   ├── factories/           # Factories para seeding
│   └── seeders/             # Datos iniciales
├── resources/
│   ├── js/                  # Componentes React e Inertia pages
│   └── views/               # Layouts Blade + vistas auth
├── routes/
│   └── web.php              # Rutas agrupadas por autenticación y suscripción
└── .env                     # Configuración de ambiente
```

---

## 📦 Requisitos Previos

Asegúrate de tener instalado en tu sistema:

- **PHP** >= 8.3 con extensiones: `pdo`, `pdo_pgsql` o `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `gd`
- **Composer** >= 2.x
- **Node.js** >= 20.x y **npm** >= 10.x
- **PostgreSQL** >= 14 **o** **MySQL** >= 8.0 / **MariaDB** >= 10.6
- Cuenta en **Stripe** (para pagos)
- API Key de **OpenRouter** (para el asistente de IA)

---

## 🚀 Configuración — Ambiente de Desarrollo

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/laravelcashtrackr.git
cd laravelcashtrackr
```

### 2. Instalar Dependencias

```bash
# Dependencias PHP
composer install

# Dependencias JavaScript
npm install
```

### 3. Configurar el Archivo `.env`

```bash
cp .env.example .env
php artisan key:generate
```

---

### Opción A: PostgreSQL (Desarrollo)

#### Paso 1 — Crear la base de datos

```sql
-- Conectarse a PostgreSQL
psql -U postgres

-- Crear usuario y base de datos
CREATE USER cashtrackr_user WITH PASSWORD 'tu_password_seguro';
CREATE DATABASE cashtrackr_dev OWNER cashtrackr_user;
GRANT ALL PRIVILEGES ON DATABASE cashtrackr_dev TO cashtrackr_user;
\q
```

#### Paso 2 — Configurar `.env` para PostgreSQL

```dotenv
APP_NAME="CashTrackr"
APP_ENV=local
APP_KEY=           # Se genera con php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

# ─── Base de datos PostgreSQL ───────────────────────────────────────
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cashtrackr_dev
DB_USERNAME=cashtrackr_user
DB_PASSWORD=tu_password_seguro

# ─── Sesiones y caché en base de datos ──────────────────────────────
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# ─── Correo (log en desarrollo) ─────────────────────────────────────
MAIL_MAILER=log

# ─── Stripe ─────────────────────────────────────────────────────────
STRIPE_KEY=pk_test_XXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_XXXXXXXXXXXXXXXX
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXX
CASHIER_CURRENCY=usd
CASHIER_CURRENCY_LOCALE=en
STRIPE_PRICE_AI_MONTHLY=price_XXXXXXXXXXXXXXXX
STRIPE_PRICE_AI_YEARLY=price_XXXXXXXXXXXXXXXX

# ─── IA (OpenRouter) ────────────────────────────────────────────────
OPENROUTER_API_KEY=sk-or-XXXXXXXXXXXXXXXX
```

#### Paso 3 — Verificar la extensión PDO PostgreSQL

```bash
# Ubuntu/Debian
sudo apt install php8.3-pgsql

# Fedora/RHEL
sudo dnf install php8.3-pgsql

# macOS con Homebrew
brew install php
```

#### Paso 4 — Ejecutar migraciones

```bash
php artisan migrate
```

---

### Opción B: MySQL (Desarrollo)

#### Paso 1 — Crear la base de datos

```sql
-- Conectarse a MySQL
mysql -u root -p

-- Crear usuario y base de datos
CREATE DATABASE cashtrackr_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cashtrackr_user'@'localhost' IDENTIFIED BY 'tu_password_seguro';
GRANT ALL PRIVILEGES ON cashtrackr_dev.* TO 'cashtrackr_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Paso 2 — Configurar `.env` para MySQL

```dotenv
APP_NAME="CashTrackr"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# ─── Base de datos MySQL ─────────────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cashtrackr_dev
DB_USERNAME=cashtrackr_user
DB_PASSWORD=tu_password_seguro
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

# ─── Resto de variables igual que PostgreSQL ─────────────────────────
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
MAIL_MAILER=log
STRIPE_KEY=pk_test_XXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_XXXXXXXXXXXXXXXX
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXX
CASHIER_CURRENCY=usd
CASHIER_CURRENCY_LOCALE=en
STRIPE_PRICE_AI_MONTHLY=price_XXXXXXXXXXXXXXXX
STRIPE_PRICE_AI_YEARLY=price_XXXXXXXXXXXXXXXX
OPENROUTER_API_KEY=sk-or-XXXXXXXXXXXXXXXX
```

#### Paso 3 — Verificar la extensión PDO MySQL

```bash
# Ubuntu/Debian
sudo apt install php8.3-mysql

# Fedora/RHEL
sudo dnf install php8.3-mysqlnd

# macOS con Homebrew
brew install php
```

#### Paso 4 — Ejecutar migraciones

```bash
php artisan migrate
```

---

### Opción C: Neon PostgreSQL Serverless (Desarrollo)

Si prefieres usar una base de datos PostgreSQL en la nube como [Neon](https://neon.tech) (tier gratuito disponible):

```dotenv
# Usar DB_URL en lugar de credenciales separadas
DB_CONNECTION=pgsql
DB_URL=postgresql://usuario:password@ep-xxx.us-east-2.aws.neon.tech/cashtrackr_dev?sslmode=require

# No definir DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
# cuando se usa DB_URL
```

> ⚠️ **Importante**: Si defines `DB_URL`, **no** establezcas simultáneamente `DB_HOST`, `DB_DATABASE`, etc. Pueden entrar en conflicto. Verifica también que `DB_CONNECTION=pgsql` esté explícitamente definido, ya que Laravel puede ignorar `DB_URL` si `DB_CONNECTION` apunta a `sqlite`.

---

### 5. Ejecutar el Servidor de Desarrollo

Una vez configurado el `.env`, inicia todos los procesos con un solo comando:

```bash
composer dev
```

Esto levanta en paralelo:
- 🟢 **PHP Artisan Serve** — Servidor de aplicación en `http://localhost:8000`
- 🟣 **Queue Worker** — Procesador de trabajos en cola
- 🩷 **Laravel Pail** — Visor de logs en tiempo real
- 🟠 **Vite Dev Server** — HMR para assets frontend

O ejecutar cada proceso por separado:

```bash
php artisan serve          # Servidor Laravel
npm run dev                # Vite con Hot Module Replacement
php artisan queue:listen   # Queue worker
```

---

## 🏭 Configuración — Ambiente de Producción

### PostgreSQL en Producción

#### Paso 1 — Instalar dependencias en el servidor

```bash
# Ubuntu Server
sudo apt update && sudo apt install -y php8.3 php8.3-fpm php8.3-pgsql php8.3-mbstring \
  php8.3-xml php8.3-bcmath php8.3-gd php8.3-curl php8.3-zip composer nodejs npm

# Instalar PostgreSQL (si se auto-hospeda)
sudo apt install -y postgresql postgresql-contrib
```

#### Paso 2 — Crear base de datos de producción

```sql
-- Como superusuario de PostgreSQL
sudo -u postgres psql

CREATE USER cashtrackr_prod WITH PASSWORD 'password_muy_seguro_produccion';
CREATE DATABASE cashtrackr_production OWNER cashtrackr_prod;
GRANT ALL PRIVILEGES ON DATABASE cashtrackr_production TO cashtrackr_prod;

-- Habilitar SSL (recomendado en producción)
ALTER SYSTEM SET ssl = on;
\q
```

#### Paso 3 — Variables de entorno para producción

```dotenv
APP_NAME="CashTrackr"
APP_ENV=production
APP_KEY=              # Generar con: php artisan key:generate
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# ─── Base de datos PostgreSQL ────────────────────────────────────────
DB_CONNECTION=pgsql
DB_HOST=tu-host-postgresql.com      # IP interna del servidor de BD
DB_PORT=5432
DB_DATABASE=cashtrackr_production
DB_USERNAME=cashtrackr_prod
DB_PASSWORD=password_muy_seguro_produccion
DB_SSLMODE=require                  # Forzar SSL en producción

# ─── Sesiones y caché ────────────────────────────────────────────────
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true               # Encriptar sesiones en producción
CACHE_STORE=database
QUEUE_CONNECTION=database

# ─── Correo (configurar proveedor real) ─────────────────────────────
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org          # O sendgrid, ses, postmark, etc.
MAIL_PORT=587
MAIL_USERNAME=tu_usuario
MAIL_PASSWORD=tu_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@tu-dominio.com
MAIL_FROM_NAME="CashTrackr"

# ─── Logs ────────────────────────────────────────────────────────────
LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=error

# ─── Stripe (claves de producción) ──────────────────────────────────
STRIPE_KEY=pk_live_XXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_live_XXXXXXXXXXXXXXXX
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXX
CASHIER_CURRENCY=usd
CASHIER_CURRENCY_LOCALE=en
STRIPE_PRICE_AI_MONTHLY=price_live_XXXXXXXXXXXXXXXX
STRIPE_PRICE_AI_YEARLY=price_live_XXXXXXXXXXXXXXXX

# ─── IA ──────────────────────────────────────────────────────────────
OPENROUTER_API_KEY=sk-or-XXXXXXXXXXXXXXXX
```

---

### MySQL en Producción

#### Paso 1 — Instalar dependencias

```bash
# Ubuntu Server
sudo apt update && sudo apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-bcmath php8.3-gd php8.3-curl php8.3-zip composer nodejs npm

# Instalar MySQL (si se auto-hospeda)
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

#### Paso 2 — Crear base de datos de producción

```sql
-- Conectarse como root
sudo mysql -u root -p

CREATE DATABASE cashtrackr_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cashtrackr_prod'@'%' IDENTIFIED BY 'password_muy_seguro_produccion';
GRANT ALL PRIVILEGES ON cashtrackr_production.* TO 'cashtrackr_prod'@'%';
FLUSH PRIVILEGES;
EXIT;
```

#### Paso 3 — Variables de entorno para producción (MySQL)

```dotenv
APP_NAME="CashTrackr"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# ─── Base de datos MySQL ─────────────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=tu-host-mysql.com
DB_PORT=3306
DB_DATABASE=cashtrackr_production
DB_USERNAME=cashtrackr_prod
DB_PASSWORD=password_muy_seguro_produccion
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

# ─── Resto igual que el ejemplo de PostgreSQL en producción ──────────
SESSION_DRIVER=database
SESSION_ENCRYPT=true
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stack
LOG_LEVEL=error
# ... (completar con las demás variables)
```

---

### Pasos de Despliegue (Ambas Bases de Datos)

Ejecutar en orden en el servidor de producción:

```bash
# 1. Clonar el repositorio
git clone https://github.com/tu-usuario/laravelcashtrackr.git /var/www/cashtrackr
cd /var/www/cashtrackr

# 2. Instalar dependencias de PHP sin dev
composer install --no-dev --optimize-autoloader

# 3. Copiar y configurar variables de entorno
cp .env.example .env
# ⚠️ Editar .env con las credenciales de producción antes de continuar
nano .env

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Instalar dependencias JS y compilar assets
npm ci
npm run build

# 6. Ejecutar migraciones en producción
php artisan migrate --force

# 7. Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 8. Configurar permisos de storage
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 9. Reiniciar servicios
sudo systemctl restart php8.3-fpm nginx
```

#### Configurar el Queue Worker con Supervisor (Producción)

Las colas son necesarias para el procesamiento de emails, notificaciones y trabajos de IA:

```ini
; /etc/supervisor/conf.d/cashtrackr-worker.conf
[program:cashtrackr-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/cashtrackr/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/cashtrackr/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start cashtrackr-worker:*
```

---

## 🔐 Variables de Entorno

A continuación, un resumen de todas las variables requeridas por la aplicación:

| Variable | Requerida | Descripción |
|---|---|---|
| `APP_NAME` | ✅ | Nombre de la aplicación |
| `APP_ENV` | ✅ | `local` \| `production` |
| `APP_KEY` | ✅ | Clave de encriptación (generada con `artisan key:generate`) |
| `APP_DEBUG` | ✅ | `true` solo en desarrollo |
| `APP_URL` | ✅ | URL base de la aplicación |
| `DB_CONNECTION` | ✅ | `pgsql` \| `mysql` \| `sqlite` |
| `DB_HOST` | ✅* | Host de la base de datos |
| `DB_PORT` | ✅* | Puerto: `5432` (PostgreSQL) / `3306` (MySQL) |
| `DB_DATABASE` | ✅* | Nombre de la base de datos |
| `DB_USERNAME` | ✅* | Usuario de la base de datos |
| `DB_PASSWORD` | ✅* | Contraseña de la base de datos |
| `STRIPE_KEY` | ✅ | Clave pública de Stripe |
| `STRIPE_SECRET` | ✅ | Clave secreta de Stripe |
| `STRIPE_WEBHOOK_SECRET` | ✅ | Secret del webhook de Stripe |
| `CASHIER_CURRENCY` | ✅ | Moneda (ej: `usd`, `eur`, `cop`) |
| `STRIPE_PRICE_AI_MONTHLY` | ✅ | ID del precio mensual en Stripe |
| `STRIPE_PRICE_AI_YEARLY` | ✅ | ID del precio anual en Stripe |
| `OPENROUTER_API_KEY` | ✅ | API key de OpenRouter para el asistente IA |
| `MAIL_MAILER` | ✅ | Driver de correo (`log`, `smtp`, `ses`, etc.) |

> *No requeridas si se usa `DB_URL` con la cadena de conexión completa.

---

## ⚙️ Comandos Útiles

```bash
# ── Desarrollo ─────────────────────────────────────────────────────

# Iniciar todos los servicios en paralelo (servidor + queue + logs + vite)
composer dev

# Solo el servidor de desarrollo
php artisan serve

# Solo el frontend con Hot Module Replacement
npm run dev

# Ejecutar migraciones frescas con seeders
php artisan migrate:fresh --seed

# Limpiar todas las cachés en desarrollo
php artisan optimize:clear

# Abrir Tinker (REPL interactivo)
php artisan tinker


# ── Base de Datos ───────────────────────────────────────────────────

# Ejecutar migraciones pendientes
php artisan migrate

# Revertir la última migración
php artisan migrate:rollback

# Ver estado de las migraciones
php artisan migrate:status

# Refrescar base de datos con seeders (⚠️ elimina todos los datos)
php artisan migrate:fresh --seed


# ── Producción ─────────────────────────────────────────────────────

# Compilar assets para producción
npm run build

# Cachear toda la configuración de la app
php artisan optimize

# Limpiar caché de producción
php artisan optimize:clear

# Ejecutar migraciones en producción (con --force para no pedir confirmación)
php artisan migrate --force


# ── Cola de Trabajos ────────────────────────────────────────────────

# Iniciar el worker de colas
php artisan queue:listen --tries=3

# Ver trabajos fallidos
php artisan queue:failed

# Reintentar trabajos fallidos
php artisan queue:retry all


# ── Stripe Webhooks (local con Stripe CLI) ──────────────────────────

# Escuchar webhooks en desarrollo (requiere Stripe CLI)
stripe listen --forward-to http://localhost:8000/stripe/webhook
```

---

## 🧪 Pruebas

El proyecto usa **PestPHP** como framework de pruebas:

```bash
# Ejecutar todas las pruebas
composer test
# o directamente
php artisan test

# Ejecutar una suite específica
php artisan test --testsuite=Feature

# Ejecutar un archivo de prueba específico
php artisan test tests/Feature/BudgetTest.php

# Ejecutar en paralelo (más rápido)
php artisan test --parallel

# Con cobertura de código
php artisan test --coverage
```

> ⚠️ **Nota**: Las pruebas usan una base de datos separada. Configura `phpunit.xml` para usar una base de datos de prueba (`cashtrackr_test`) o SQLite en memoria:
> ```xml
> <env name="DB_CONNECTION" value="sqlite"/>
> <env name="DB_DATABASE" value=":memory:"/>
> ```

---

## 💳 Integración con Stripe

La aplicación usa **Laravel Cashier** para gestionar suscripciones. Para configurar el entorno de pagos:

### 1. Crear los productos en Stripe

Accede a tu [Dashboard de Stripe](https://dashboard.stripe.com) y crea:
- Un producto "CashTrackr AI" con dos precios: **mensual** y **anual**
- Copia los IDs de precio (`price_XXXXXXXXX`) a las variables `STRIPE_PRICE_AI_MONTHLY` y `STRIPE_PRICE_AI_YEARLY`

### 2. Configurar Webhooks

Stripe necesita notificar a tu aplicación sobre eventos de facturación:

```bash
# Desarrollo: usar Stripe CLI
stripe listen --forward-to http://localhost:8000/stripe/webhook

# Producción: crear webhook en el Dashboard de Stripe
# URL: https://tu-dominio.com/stripe/webhook
# Eventos: customer.subscription.*, invoice.*, payment_intent.*
```

### 3. Planes disponibles

| Plan | Variable de entorno | Descripción |
|---|---|---|
| Mensual | `STRIPE_PRICE_AI_MONTHLY` | Suscripción mensual con acceso al asistente IA |
| Anual | `STRIPE_PRICE_AI_YEARLY` | Suscripción anual con descuento |

---

## 🤖 Funcionalidades de IA

El asistente de IA está basado en el paquete **`laravel/ai`** e implementa un agente con herramientas especializadas:

### Herramientas disponibles

| Herramienta | Descripción |
|---|---|
| `AddExpense` | Agrega un gasto a un presupuesto específico mediante lenguaje natural |
| `SearchExpenses` | Busca y filtra gastos del presupuesto actual por criterios |

### Configurar el modelo de IA

La aplicación usa **OpenRouter** como proveedor de LLM. Obtén tu API key en [openrouter.ai](https://openrouter.ai) y configura:

```dotenv
OPENROUTER_API_KEY=sk-or-v1-XXXXXXXXXXXXXXXX
```

> El asistente de IA solo está disponible para usuarios con **suscripción activa**. Los usuarios del plan gratuito son redirigidos a la página de planes.

---

## 📄 Licencia

Este proyecto es de uso educativo, desarrollado como parte del curso **Código Con Juan**. Para uso en producción, revisa los términos de las dependencias utilizadas.

---

<p align="center">
  Construido con ❤️ usando <strong>Laravel 13</strong> + <strong>React 19</strong> + <strong>Stripe</strong> + <strong>IA</strong>
</p>
