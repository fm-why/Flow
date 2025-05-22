# Flow
A non-social media like Twitter in PHP. This website was made with PHP 8.2.12, Apache/2.4.58 (Win64), MariaDB-10.4.32 . 
Flow implements these functionss:
- Login/Signup
- Forgot Password with email
- Follow/unfollow system
- Create and respond to posts
- Likes and repost
- Search and view profile
- Admin controll (delete users and posts)
- Modify user info and access info

## Installation
1. **Clone the repository**
   In your terminal use the commad:
   ```git clone https://github.com/fm-why/Flow.git```
3. **Create the database**
   Copy the content of 'db.sql' and run it in your MySql console
   > 📝**Note**
   >
   > You need the privilage to create database and table otherwise it will be useless

   > ⚠️**Warning**
   >
   > If you get the error "ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'data date not null default CURRENT_DATE,
isAdmin boolean not null default FALSE' at line 5"  replace "CURRENT_DATE" with "(curdate())"

4. **Modify env variable**
   Open the file ".htaccess" and put your db credentials (DB_HOST, DB_USERNAME and DB_PASSWORD) and your SMTP server credentials (EMAIL, PASSWORD, PORT, HOST).
   > 📝**Note**
   >
   > If you don't put the SMTP credentials only the Forget Password section will not work

5. **Enjoy your website**
   Read what other users talk about and make new friends along the way

> 📝**Note**
>
> To grant Admin privilege to an account you must change manually the 'isAdmin' value of an existing account inside the table "users"
   
