# 🧒💉 Child Vaccination Management System

A **full-stack web application** designed to digitize and manage child vaccination schedules efficiently.  
The system provides a centralized platform for **parents, hospitals/Anganwadi centers, and administrators** to manage vaccinations, appointments, and vaccine stock securely and reliably.

---

## 📌 Project Description

The **Child Vaccination Management System** addresses the challenges of manual vaccination tracking by offering an automated, role-based platform.  
It ensures timely vaccinations, reduces missed doses, and improves coordination between parents and healthcare providers.

This project is developed as an **academic final-year project** with real-world healthcare applicability.

---

## 🎯 Objectives

- Digitize child vaccination records
- Enable online appointment booking
- Maintain real-time vaccine stock
- Provide role-based access control
- Improve vaccination compliance and tracking

---

## 🚀 Key Features

### 👨‍👩‍👧 Parent Module
- Secure registration and login
- View child-specific vaccination schedule
- Book vaccination appointments
- View appointment history and status

### 🏥 Hospital / Anganwadi Module
- Registration with admin approval
- Add and manage vaccines
- Maintain vaccine stock
- View and update appointment status
- Automatic vaccine stock reduction after vaccination

### 🛡 Admin Module
- Admin authentication
- Approve/reject parent and hospital registrations
- Manage Anganwadi centers
- Monitor overall system data

---

## 🛠 Technology Stack

### Frontend
- HTML5  
- CSS3  
- JavaScript  
- Bootstrap  

### Backend
- PHP (Core PHP)

### Database
- MySQL (Relational Database)

### Tools
- XAMPP (Apache + MySQL)
- phpMyAdmin
- Visual Studio Code
- Git & GitHub

---

## 🗂 Database Schema Overview

**Main Tables**
- `users`
- `anganwadi_centers`
- `vaccines`
- `appointments`

**Relationships**
- One Anganwadi Center → Multiple Vaccines  
- One Parent → Multiple Appointments  
- Vaccine stock updates automatically upon vaccination

---

## ⚙ Installation & Setup

1. Clone the repository
   ```bash
   git clone https://github.com/khenikhushi/child-vaccination-management-system.git
