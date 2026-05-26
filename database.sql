CREATE DATABASE IF NOT EXISTS db;

USE db;

CREATE TABLE IF NOT EXISTS eoi (
  EOInumber INT AUTO_INCREMENT PRIMARY KEY,
  JobReferenceNumber VARCHAR(5) NOT NULL,
  FirstName VARCHAR(20) NOT NULL,
  LastName VARCHAR(20) NOT NULL,
  DOB VARCHAR(10) NOT NULL,
  Gender VARCHAR(20) NOT NULL,
  StreetAddress VARCHAR(40) NOT NULL,
  SuburbTown VARCHAR(40) NOT NULL,
  State VARCHAR(3) NOT NULL,
  Postcode CHAR(4) NOT NULL,
  EmailAddress VARCHAR(100) NOT NULL,
  PhoneNumber VARCHAR(12) NOT NULL,
  Skills TEXT,
  OtherSkills TEXT,
  Status VARCHAR(20) DEFAULT 'New',
  DateSubmitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reference_number CHAR(5) NOT NULL,
  title VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  salary VARCHAR(100) NOT NULL,
  reports_to VARCHAR(100) NOT NULL
);

INSERT INTO jobs 
(reference_number, title, description, salary, reports_to)
VALUES
(
  'FE123',
  'Front-End Developer',
  'We are looking for a Front-End Developer to build responsive and accessible client websites. This role focuses on translating visual concepts into functional webpages using HTML5 and CSS3.',
  '$68,000 – $80,000 per year',
  'Lead Developer'
),
(
  'WD245',
  'Web Designer',
  'We are seeking a creative Web Designer to produce visually engaging and client-focused website designs. This role involves layout planning, visual styling, and contributing to brand identity across digital platforms.',
  '$65,000 – $76,000 per year',
  'Creative Director'
);

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

INSERT IGNORE INTO users (username, password) VALUES ('admin', 'admin');