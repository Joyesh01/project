-- Create database
CREATE DATABASE CSECUCMM;
USE CSECUCMM;

-- Create Users table
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('Admin', 'Teacher', 'Student') NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create Admin table
CREATE TABLE Admin (
    admin_id INT PRIMARY KEY,
    FOREIGN KEY (admin_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Create Teacher table
CREATE TABLE Teacher (
    teacher_id INT PRIMARY KEY,
    FOREIGN KEY (teacher_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Create Student table
CREATE TABLE Student (
    student_id INT PRIMARY KEY,
    FOREIGN KEY (student_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Create Course Materials table
CREATE TABLE CourseMaterials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    material_name VARCHAR(200) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_by_teacher INT NOT NULL,
    FOREIGN KEY (uploaded_by_teacher) REFERENCES Teacher(teacher_id) ON DELETE CASCADE
);

-- Create Access Records table
CREATE TABLE AccessRecords (
    access_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    material_id INT NOT NULL,
    access_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Student(student_id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES CourseMaterials(material_id) ON DELETE CASCADE
);

-- Create Rankings view for Top Contributor
CREATE VIEW TopContributors AS
SELECT t.teacher_id, u.username, COUNT(cm.material_id) AS uploads
FROM Teacher t
JOIN Users u ON t.teacher_id = u.user_id
LEFT JOIN CourseMaterials cm ON t.teacher_id = cm.uploaded_by_teacher
GROUP BY t.teacher_id
ORDER BY uploads DESC;

-- Create Rankings view for Top Reader
CREATE VIEW TopReaders AS
SELECT s.student_id, u.username, COUNT(ar.access_id) AS accesses
FROM Student s
JOIN Users u ON s.student_id = u.user_id
LEFT JOIN AccessRecords ar ON s.student_id = ar.student_id
GROUP BY s.student_id
ORDER BY accesses DESC;

