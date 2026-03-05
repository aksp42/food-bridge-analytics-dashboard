# 🍲 Food Bridge – Food Waste Management System with Analytics Dashboard

**Food Bridge** is a full-stack web application designed to reduce food waste by connecting **surplus food vendors** with **NGOs in need of food resources**.

The platform provides a **data-driven ecosystem** where restaurants, event organizers, and vendors can quickly list surplus food while NGOs can search and request available donations in real time.

Unlike traditional donation platforms, Food Bridge integrates an **analytics-powered admin dashboard** to monitor platform activity, donation impact, and community engagement.

---

## 🌍 Problem Statement

A significant amount of edible food is wasted every day while many communities struggle with food scarcity.

Food Bridge addresses this issue by creating a **digital bridge between food donors and NGOs**, ensuring that surplus food reaches people who need it the most.

---

## 🚀 Key Features

### 📊 Admin Analytics Dashboard

The **Admin Panel** acts as a centralized control system for monitoring the platform.

Features include:

- Real-time platform analytics using **Chart.js**
- Vendor vs NGO user distribution charts
- Food inventory monitoring (**Fresh vs Expired**)
- Request success tracking (**Completed vs Rejected**)
- Community feedback and sentiment trend visualization
- User management and approval workflows

---

### 🔍 NGO Search & Food Request System

NGOs can access a **Live Food Feed** to find available food donations.

Features include:

- Search by **food item**
- Filter by **Veg / Non-Veg category**
- Filter by **city location**
- Real-time listing updates

Request tracking with status badges:

- 🟡 Pending  
- 🟢 Approved  
- 🔴 Rejected  

---

### ⏱️ Vendor Food Listing Portal

The vendor dashboard allows restaurants and event managers to quickly donate surplus food.

Features include:

- Fast food listing interface
- Add food name, quantity, category, and expiry time
- Location-based listing
- Donation history tracking

**Gamified donation ranking system**

- Bronze Donor
- Silver Donor
- Gold Donor
- Platinum Donor

---

### 🛡️ Food Safety & Expiry Tracking

To ensure safe distribution:

- Food listings include expiry tracking
- Expired items are automatically filtered
- NGOs only see **safe and fresh food listings**

---

### 💬 Community Feedback System

The platform includes a **feedback and rating system** to build trust between vendors and NGOs.

Features include:

- ⭐ Star rating system
- 📝 Testimonials
- Community feedback monitoring

---

## 🛠️ Tech Stack

**Frontend**

- HTML5  
- CSS3  
- Tailwind CSS  
- Responsive UI

**Backend**

- PHP  
- Session Management  
- Multi-user Authentication  

**Database**

- MySQL  
- Optimized Relational Schema  

**Data Visualization**

- Chart.js

---

## 📂 Project Structure

```
food-wastage-system/
│
├── index.php
├── login.php
├── register.php
├── about.php
├── feedback.php
│── logout.php
├── vendor/
│   ├── dashboard.php
│
├── ngo/
│   ├── dashboard.php
│
├── admin/
│   ├── dashboard.php
│
├── includes/
│   ├── db.php
│   ├── navbar.php
│   ├── footer.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── images/
```

---

## 💻 Installation Guide

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/aksp42/food-bridge-analytics-dashboard
```

---

### 2️⃣ Setup Database

1. Open **MySQL / phpMyAdmin**
2. Create a database:

```sql
food_wastage_db
```

3. Import the provided `.sql` file.

---

### 3️⃣ Configure Database Connection

Open:

```
includes/db_connect.php
```

Update credentials:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "food_bridge";
```

---

### 4️⃣ Run the Project

Move the project folder to:

```
htdocs (XAMPP)
```

or

```
www (WAMP)
```

Then open in browser:

```
http://localhost/food-wastage-system/
```

---

## 🎯 Future Improvements

Planned upgrades include:

- 📍 Google Maps integration for pickup location
- 🔔 Real-time notification system
- 🤖 AI-based food demand prediction
- 📱 Progressive Web App (PWA)
- 📊 Advanced donation impact analytics

---

## 🌱 Project Status

Food Bridge is currently in the **prototype stage** and is being developed as a **student-led project aimed at solving food waste problems using technology.**

---

## 🤝 Contributing

Contributions and suggestions are welcome.

1. Fork the repository  
2. Create a new branch  
3. Submit a pull request

## 🧑‍💻 Author

* **Akanksha Singh** - https://www.linkedin.com/in/akanksha-singh-4715a0351/



---

## 📜 License

This project is created for **educational and social impact purposes**.
