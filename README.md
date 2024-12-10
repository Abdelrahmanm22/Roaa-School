# Sudanese School Management System

## Introduction
The Sudanese School Management System is a comprehensive platform designed to enhance school operations by connecting parents, students, teachers, and administrators. It simplifies access to student results, fees, lesson videos, and administrative processes such as event and trip management.

---

## Problem Definition
Traditional school management systems often lack efficiency, accessibility, and centralized data management. This project addresses these challenges by providing a streamlined, user-friendly solution.

---

## Objective
- Centralize school data and operations.
- Enhance communication between stakeholders.
- Simplify management of events, trips, and student applications.
- Provide secure access to academic and financial information.

---

## System Features

### **General Features**
- Landing page showcasing the school, services, latest news, and trips.
- Parents can apply for their children and upload required data.
- Teachers can apply for job positions through the system.

### **Role-Based Features**

#### **Parent**
- View all children enrolled in the school.
- Access each child’s profile and detailed results.
- Filter results by semester and academic year.
- View fees paid and pending for each child.
- Access upcoming trips and events.
- Switch to the child’s account to assist younger students.

#### **Student**
- Log in and watch videos for current semester lessons.
- View detailed results filtered by semester and academic year.

#### **Data Entry**
- Add, update, or delete events and trips.
- Review and manage parent applications for student admission.
- Approve or reject students (update status).
- Add results for each student.
- View profiles of registered parents and students.

#### **Media Personnel**
- Upload and manage lesson videos.
- Assign videos to specific subjects, semesters, and academic years.

#### **Super Administrator**
- Manage roles for data entry, media personnel, and other administrators.
- Assign permissions for each role.

---

## Project Methodology

1. **Requirements**
    - **Hardware:** Server for hosting, user devices for access.
    - **Software:** Laravel, Next.js, Figma.

2. **Design**
    - Prototypes created with Figma for user-friendly interfaces.
    - Database schemas and models defined for complex relationships.

3. **Implementation**
    - **Backend:** Developed with Laravel for secure and scalable operations.
    - **Frontend:** Built with React (Next.js) for a responsive experience.

4. **Testing**
    - Unit testing for backend functionalities.
    - User acceptance testing for end-user requirements.

5. **Deployment & Maintenance**
    - Deployed on a secure hosting platform.
    - Regular updates for improvements and bug fixes.

---

## Tech Stack
- **UI/UX Design:** Figma
- **Frontend:** React (Next.js)
- **Backend:** Laravel
- **Database:** MySQL

---

## Specifications

### Functional Requirements
- Parent dashboard for child monitoring.
- Student dashboard for video lessons and results tracking.
- Data entry management for events, trips, and student status.
- Media management for video content.
- Role and permission management for administrators.

### Non-Functional Requirements
- Scalable architecture for growing user base.
- High-security standards for sensitive data.
- Responsive design for all devices.

---

## Diagrams
- **Sitemap Diagram**
  ![Sitemap Diagram](https://github.com/Abdelrahmanm22/Roaa-School/blob/main/database.png)
- **Class Diagram**
- **Sequence Diagrams**
    1. Parent retrieves student information.
    2. Data entry updates student status.
    3. Student accesses subjects, videos, and marks them as watched.
- **Entity-Relationship Diagram (ERD)**

*Diagrams are included in the project repository.*

---
