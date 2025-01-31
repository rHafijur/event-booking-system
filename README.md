# EventManager

A web-based event management system that allows users to create, manage, and register for events. The system provides an intuitive interface for attendees to register, view event details, and generate event tickets.

## 🚀 Project Overview
This application enables users to:
- Create and manage events.
- Track available tickets dynamically based on attendee registrations.
- Allow users to register for events via an AJAX-powered form.
- Generate and print event tickets.
- Display event details and booking availability.

## ✨ Features  

### 🛡️ User Roles & Permissions  
- **System Admin**  
  - Can access all events.  
  - Can download the attendee list of specific event as a CSV file.  
  - Cannot edit or delete events.  

- **Event Organizer**  
  - Has full CRUD (Create, Read, Update, Delete) permissions for their own events.  
  - Can download the attendee list for their events as a CSV file.  

### 🎟️ Event & Registration  
- **Event Availability**: Events remain open for registration until the deadline.  
- **Attendee Registration**: AJAX-powered registration form for smooth user experience.  
- **Dynamic Ticket Availability**: System calculates available tickets based on registered attendees.  
- **Event Ticketing**: Attendees receive event tickets after successful registration.  
- **Event Ticket Printing**: Users can print their event tickets.  

### 🛠️ Technical Features  
- **Database Migration & Seeder System**: Easily set up the database with migration and seeder functionality.  
- **Automatic Dependency Injection**: Ensures clean and maintainable code by automatically managing dependencies.  
- **Security**: CSRF protection is included in form submissions.  
- **Responsive UI**: Built with Bootstrap for a seamless experience across devices.  
- **Toast Notifications**: Interactive feedback for user actions (success, errors, etc.).

## 📂 Tech Stack
- **Frontend**: HTML, Bootstrap, JavaScript, AJAX
- **Backend**: PHP, Composer
- **Database**: MySQL

---

## Installation Guide

Follow these steps to set up the system:

1. **Clone the Repository**  
   First, clone the repository to your local machine:
   ```bash
   git clone https://github.com/rHafijur/event-booking-system
   ```

2. **Set Up the Environment**  
   Go to the root folder of the project and rename the `.env.example` file to `.env`:
   ```bash
   mv .env.example .env
   ```

3. **Generate the Autoloader files**  
   Make sure you have composer installed in your computer and go to the root folder of the project and run:
   ```bash
   composer dump-autoload
   ```

4. **Configure Database**  
   Open the `.env` file and update the database credentials as per your environment.

5. **Start PHP Development Server**  
   Run the PHP development server to host the application locally:
   ```bash
   php -S localhost:8000 -t public
   ```

6. **Run Database Migrations**  
   Open your browser and visit:
   ```bash
   http://localhost:8000/migrate/3e5b4559-508f-4daa-b790-928740657bd7
   ```
   to perform the database migration.

8. **Seed Initial Users**  
   After the migration is complete, visit:
   ```bash
   http://localhost:8000/migrate/3e5b4559-508f-4daa-b790-928740657bd7
   ```
   to seed the initial users.

10. **Enjoy the System!**  
   You are now ready to use the system.

---

## Credentials For Test:

1. **System Admin**  
   Email:
   ```bash
   admin@site.com
   ```
   Passowrd:
   ```bash
   123456
   ```
2. **Organizer**  
   Email:
   ```bash
   hafijur@site.com
   ```
   Passowrd:
   ```bash
   123456
   ```

---

## User Manual

For a detailed user guide, please read the [User Manual](USER_MANUAL.md).



