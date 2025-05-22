-- Server MySQL Apache/2.4.58 (Win64)
CREATE DATABASE social;
USE social;
CREATE table users( 
    id_user int not null AUTO_INCREMENT PRIMARY KEY,
    username varchar(25) not null, 
    email varchar(255) not null, 
    pass varchar(255) not null, 
    data date not null default CURRENT_DATE, -- altrimenti curdate()
    isAdmin boolean not null default FALSE
);
create table User_info(
    id_info int not null AUTO_INCREMENT primary key,
    id_user int not null,
    nome varchar(20) not null,
    bio varchar(180),
    pfp varchar(255),
    FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE
);
CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content varchar(255),
    img varchar(255),
    parent_id INT DEFAULT NULL, -- Per le risposte ai post
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES posts(post_id) ON DELETE CASCADE
);

-- Tabella like
CREATE TABLE likes (
    like_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
    UNIQUE (user_id, post_id) -- Un utente può mettere un solo like per post
);

-- Tabella repost
CREATE TABLE reposts (
    repost_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE
);
create table Segue(
    id_user int not null,
    id_following int not null,
    primary key(id_user,id_following),
    foreign key(id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    foreign key(id_following) REFERENCES users(id_user) ON DELETE CASCADE
); 
CREATE TABLE `password_reset_temp` (
  `email` varchar(250) NOT NULL,
  `key` varchar(250) NOT NULL,
  `expDate` datetime NOT NULL
)