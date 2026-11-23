CREATE TABLE `groups`
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    deleted BOOLEAN NOT NULL DEFAULT 0
);
CREATE TABLE students
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    deleted BOOLEAN NOT NULL DEFAULT 0
);
CREATE TABLE students_groups
(
    stud_id INT,
    group_id INT,
    PRIMARY KEY (stud_id, group_id),
    FOREIGN KEY (stud_id) REFERENCES students(id),
    FOREIGN KEY (group_id) REFERENCES `groups`(id)
);
