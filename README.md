# 📅 Attendance & Task Management System (ATMS)

A robust, full-stack Laravel application designed for educational institutes and corporate environments to manage daily attendance, leave requests, and academic tasks with automated WhatsApp notifications.

---

## 🚀 Key Features

### 🏢 Multi-Role Dashboard
- **Admin:** Full system oversight, student management, and global reporting.
- **HR:** Dedicated approval queue for new registrations and staff directory management.
- **Teacher:** Task assignment via CKEditor, submission grading, and attendance reporting.
- **Student:** One-click attendance, multi-day leave requests, and task submission console.

### ✅ Attendance & Grading
- **Smart Marking:** Students can mark attendance once per session.
- **Automated Grading:** System calculates grades (A-D) based on monthly attendance percentages.
- **Manual Adjustments:** Admins can backdate or edit attendance for specific users.

### 📝 Leave Management
- **Multi-Day Range:** Supports inclusive date ranges for leave requests.
- **Validation:** Prevents marking attendance on approved leave dates.
- **Status Tracking:** Real-time tracking of Pending, Approved, and Rejected requests.

### 🛠 Task Console
- **Rich Text Assignment:** Teachers assign tasks using CKEditor for professional formatting.
- **File Attachments:** Support for ZIP/PDF task resources and student submissions.
- **Feedback Loop:** Teachers can approve/reject submissions with specific feedback comments.

### 📱 WhatsApp Integration
- Automated notifications via **WhatsAppService** for:
  - Account Approval
  - Attendance Confirmation
  - Leave Status Updates
  - New Task Assignments & Grading Feedback

---

## 🛠 Tech Stack

- **Framework:** Laravel 12.x
- **Frontend:** Tailwind CSS / Blade / Alpine.js
- **Database:** MySQL (Polyglot persistence architecture)
- **Real-time:** WhatsApp API Integration
- **Utilities:** Carbon (Dates), CKEditor (Rich Text)

---

## 📦 Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/yourusername/attendance-system.git](https://github.com/yourusername/attendance-system.git)
   cd attendance-system