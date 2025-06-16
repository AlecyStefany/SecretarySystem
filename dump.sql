
CREATE DATABASE secretaryDb;

USE secretaryDb;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY ,
    name VARCHAR(100) NOT NULL,
    birthDate DATE NOT NULL,
    document VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO students (name, birthDate, document)
VALUES 
    ('João Silva', '2001-04-15', '64633013025'),
    ('Maria Oliveira', '1999-08-22', '48075408071'),
    ('Carlos Souza', '2002-12-03', '55282657031'),
    ('Ana Pereira', '2000-07-19', '67007557007');


CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

INSERT INTO courses (name, description)
VALUES
    ('Introdução à Programação', 'Curso básico sobre lógica e algoritmos.'),
    ('Banco de Dados Relacional', 'Conceitos de bancos relacionais e SQL.'),
    ('Desenvolvimento Web', 'Aprenda a criar sites com HTML, CSS e JavaScript.'),
    ('Inteligência Artificial', 'Fundamentos de aprendizado de máquina e redes neurais.'),
    ('Gestão de Projetos', 'Técnicas de planejamento e controle de projetos.');

CREATE TABLE registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_registration_student
        FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_registration_course
        FOREIGN KEY (course_id)
        REFERENCES courses(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    UNIQUE KEY uq_student_course (student_id, course_id)
);

INSERT INTO registration (student_id, course_id)
VALUES
    (1, 2),  
    (2, 3), 
    (3, 4), 
    (4, 1),  
    (1, 5);  