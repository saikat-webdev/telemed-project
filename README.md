# HealthHub

HealthHub is a role-based telemedicine web application built with Laravel. It provides separate portals for patients, doctors, and administrators so online consultation workflows can be managed from booking to payment to post-visit review.

## Overview

This project is designed to support a basic digital healthcare flow:

- Patients can register, browse doctors, book appointments, pay consultation fees, join online consultations, chat with doctors, and leave reviews.
- Doctors can manage appointments, confirm or cancel consultations, reply to patient messages, join consultation rooms, and generate prescriptions.
- Admins can monitor the overall platform, manage doctors and categories, review patients and appointments, and access analytics.

The application uses server-rendered Blade views with a Vite-powered frontend build pipeline. Despite the Vite setup, the current codebase is not using React; the UI is primarily built with Blade, Tailwind CSS, Alpine.js, and custom JavaScript.

## Tech Stack

### Backend

- PHP `^8.2`
- Laravel `^12`
- Laravel Sanctum
- Laravel Telescope
- Laravel Cashier
- Spatie Laravel Permission

### Frontend

- Blade templating
- Vite
- Tailwind CSS `v4`
- Alpine.js
- Axios
- Font Awesome

### Payments and Realtime/External Integrations

- Stripe via Laravel Cashier for appointment payments
- Jitsi Meet External API for video consultation rooms

### Database

- MySQL or any Laravel-supported relational database

## What the Project Does

This system acts as a telemedicine platform where three main user types interact:

### Patient Portal

The patient side focuses on discovery, booking, and care follow-up.

Features currently present:

- Patient registration and login
- Patient dashboard with appointment summary
- Browse and filter doctors by name and specialization
- Book appointments with date, time slot, and reason/comment
- View appointment history and status
- Cancel pending or confirmed appointments
- Pay for confirmed appointments using Stripe
- Join consultation room after payment
- Send messages to doctors
- View patient inbox/chat threads
- Submit appointment reviews after completed consultations

### Doctor Portal

The doctor side is built around appointment handling and patient coordination.

Features currently present:

- Doctor dashboard
- Doctor appointment listing and filtering
- Appointment detail page
- Confirm pending appointments
- Cancel pending appointments
- Mark paid appointments as completed
- Join consultation room
- Access prescription page
- Doctor-to-patient messaging inbox and chat
- Unread message indicator on dashboard

### Admin Portal

The admin side is used for management and reporting.

Features currently present:

- Admin dashboard with high-level statistics
- Doctor management
- Patient listing and detail views
- Appointment management and status updates
- Doctor category management
- Analytics view for status distribution and basic reporting

## Main Modules

The project is organized around these functional modules:

### 1. Authentication and Role Management

- Login and registration
- Role-based redirection after login
- Roles: `admin`, `doctor`, `patient`
- Permissions handled via Spatie Laravel Permission

### 2. User and Profile Management

- User accounts stored in `users`
- Doctor profiles stored in `doctors`
- Role-specific navigation and dashboards

### 3. Doctor Directory

- Doctor listing page for patients
- Filtering by specialization/category
- Fees support for paid consultations

### 4. Appointment Management

- Appointment creation by patients
- Availability conflict checking
- Appointment status handling
- Appointment status updates by doctor/admin

Current appointment status flow in the app:

- `0` = Pending
- `1` = Confirmed
- `2` = Fees Paid
- `3` = Completed
- `4` = Cancelled

### 5. Messaging

- Patient-to-doctor chat
- Doctor-to-patient chat
- Conversation listing/inbox
- Unread message tracking

### 6. Payments

- Stripe checkout integration through Laravel Cashier
- Transaction records linked to appointments
- Appointment status upgrade after successful payment

### 7. Consultation Room

- Shared doctor/patient video room
- Jitsi-generated room name per appointment

### 8. Reviews

- Patients can submit a rating and written review after completed appointments

### 9. Prescription View

- Doctor-facing prescription UI
- Medication row management in frontend

Note:
- The prescription page currently exists as a UI/form screen, but it does not yet persist prescription records into a dedicated database table.

## Project Structure

Important areas in the codebase:

- `app/Http/Controllers`
  Handles patient, doctor, admin, auth, payment, consultation, and prescription workflows.
- `app/Models`
  Contains core entities such as `User`, `Doctor`, `Appointment`, `Message`, `Transaction`, and `AppointmentReview`.
- `resources/views`
  Blade templates for patient, doctor, admin, auth, and shared modules.
- `routes/web.php`
  Main web routes for all portals.
- `database/migrations`
  Database schema definitions.
- `database/seeders`
  Roles, sample users, doctor data, and test/demo records.
- `resources/js` and `resources/css`
  Frontend assets compiled through Vite.

## Installation From Scratch

### Prerequisites

Make sure the following are installed:

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL or MariaDB
- Git

Optional but useful in local Windows/XAMPP setup:

- XAMPP Apache + MySQL

### 1. Clone the Repository

```bash
git clone https://github.com/saikat-webdev/telemed-project
cd telemed-project
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Create Environment File

```bash
copy .env.example .env
```

On macOS/Linux:

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure Database

Update the `.env` file with your database settings:

```env
APP_NAME=HealthHub
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=telemed_project
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Seed Initial Data

For the default seeded accounts:

```bash
php artisan db:seed
```

For richer demo/test data:

```bash
php artisan db:seed --class=TestDataSeeder
```

### 9. Configure Stripe

Add Stripe credentials to `.env`:

```env
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
```

Important:
- Payments rely on Stripe and Laravel Cashier.
- If Stripe keys are not configured, payment flows will not work properly.

### 10. Start the Application

Backend:

```bash
php artisan serve
```

Frontend development server:

```bash
npm run dev
```

Open:

```text
http://127.0.0.1:8000
```

### Optional One-Command Setup

The project includes a Composer setup script:

```bash
composer run setup
```

This installs dependencies, creates `.env`, generates an app key, runs migrations, installs npm packages, and builds frontend assets.

## Development Commands

### Run Full Local Dev Stack

```bash
composer run dev
```

This starts:

- Laravel development server
- Queue listener
- Laravel Pail logs
- Vite dev server

### Run Tests

```bash
php artisan test
```

or

```bash
composer run test
```

### Clear Cached Config/Routes/Views

```bash
php artisan optimize:clear
```

## Seeded Demo Credentials

If you run `php artisan db:seed`, the default credentials are:

### Admin

- Email: `admin@admin.com`
- Password: `12345678`

### Patient

- Email: `patient@patient.com`
- Password: `12345678`

### Doctor

- Email: `doctor@doctor.com`
- Password: `12345678`

If you run `TestDataSeeder`, additional sample records are created, including:

- `admin@telemed.com`
- several doctor users like `sarah@telemed.com`
- several patient users like `john@patient.com`

All of those are seeded with password:

- `12345678`

## Routing Summary

### Public

- `/login`
- `/register`

### Patient Routes

- `/patient/dashboard`
- `/patient/doctors`
- `/patient/appointments`
- `/patient/messages`
- `/patient/chat/{doctor}`

### Doctor Routes

- `/doctor/dashboard`
- `/doctor/appointments`
- `/doctor/messages`
- `/doctor/chat/{patient}`

### Admin Routes

- `/admin/dashboard`
- `/admin/doctors`
- `/admin/patients`
- `/admin/appointments`
- `/admin/categories`
- `/admin/analytics`

## Core Data Models

Main entities used by the application:

- `User`
- `Doctor`
- `DoctorCategory`
- `Appointment`
- `Transaction`
- `Message`
- `AppointmentReview`

## Current Integrations

### Stripe

Used for consultation payment flow. After successful payment:

- a transaction record is created
- the appointment is linked to the transaction
- the appointment status is updated to `Fees Paid`

### Jitsi Meet

Used to provide a live consultation room between patient and doctor using a generated room name per appointment.

## Known Limitations / Important Notes

These are useful to know before production use:

- Prescription creation currently has UI but no persistent storage or downloadable prescription model yet.
- Some seeders are inconsistent in how doctor specialization is stored; the app expects specialization/category relationships for doctor browsing and management.
- Queue, notifications, and email reminders are not fully implemented for appointment lifecycle events.
- No dedicated API-first architecture is currently used; the app is primarily web/Blade-driven.
- React is not currently used in this repository even though the build stack supports Vite.
- There is no advanced scheduling engine yet beyond conflict checks on exact doctor/date/time slots.
- Production deployment hardening, audit logging, and medical-compliance features would still need additional work.

## Suggested Next Improvements

Good next steps for this project:

- Persist prescriptions to the database
- Add downloadable PDF prescriptions
- Add appointment reminders via email/SMS
- Add doctor profile editing
- Add patient medical history records
- Add reschedule flow for appointments
- Add pagination/search to message inboxes
- Add API endpoints for mobile app support
- Add automated tests for patient, doctor, and admin workflows

## License

This project is proprietary software.
