CREATE DATABASE ranking_db;
USE ranking_db;

CREATE TABLE ranking (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  time VARCHAR(50) NOT NULL,
  platform VARCHAR(50) NOT NULL
);