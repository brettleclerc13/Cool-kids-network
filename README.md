# cool_kids_network
A network for cool kids only 🤙

This project is a WordPress site running in a Docker environment, utilizing a custom child theme of Twenty Twenty-Four. 

## Dependencies

- **Required:**
  - Docker
  - Docker Compose
  - Make

Make sure to have port **8080** free to use.

- **Optional:**
  - PHP executable for the API request script (`change-user-role.php`) in the `/src/Tools` directory.

## Project Overview

The project runs on four Docker containers:

- WordPress
- MariaDB
- Adminer
- Nginx

### Getting Started

1. **Build and Launch Docker Containers:**
   Navigate to the root level where the `Makefile` is present and run the following command in the terminal:

   ```bash
   make
2. **Access the Website:**
   Once the containers are running, you can access the website at: http://localhost:8080

### Environment Variables

Yes I know, there is a `.env` file in the `src` folder, since this is a concept site meant for full transparency in its workings. The usernames and passwords for both WordPress and MariaDB have been shared as follows:

**WordPress Admin Credentials:**
- Username: **webmaster**
- Password: **password123**
- Email: webmaster@student.42nice.fr

**MariaDB Credentials:**
- Username: test_bleclerc
- Password: password123

### Accessing Admin Interfaces

**WordPress Admin Dashboard:**
Access the WordPress dashboard through: http://localhost:8080/wp-admin

**Adminer Dashboard:**
Access the Adminer dashboard through: http://localhost:8080/adminer

**Login Details:**
- Server: mariadb
- Username: test_bleclerc
- Password: password123
- Database: cool-kids-database

**Note:** Ensure that the server is set to mariadb and not the default localhost.

### Changing a User Role Through 3rd Party Integration
In the `src/Tools` directory, you will find a simple PHP script called `change-user-role.php`. This script prompts you to enter the required arguments:
- email
- new role (cool_kid, cooler_kid, coolest_kid)
- first name (optional)
- last name (optional)
- admin username (webmaster)

The script utilizes a curl function for making API requests to update a user's role. The password `JUnS NlzU WVYu Ug6l JSS3 9Mz7` is hard coded in the script. You can also run the following curl command to achieve the same result:

```bash
curl --location 'http://localhost:8080/wp-json/coolkids/v1/update-role?Content-Type=application%2Fjson' \
--header 'Content-Type: application/json' \
--header 'Authorization: Basic d2VibWFzdGVyOkpVblMgTmx6VSBXVll1IFVnNmwgSlNTMyA5TXo3' \
--data-raw '{
    "email": "xyz@gmail.com",
    "new_role": "coolest_kid"
}'
```
Make sure to replace `xyz@gmail.com` with the target user's email and `coolest_kid` with the desired new role. The Authorization header uses Basic authentication with the admin's username and Application Password (WP feature), base64-encoded, as required by the API.

## Project Details

This concept game adheres to the following project [assessment](https://docs.google.com/document/d/1fUxyIzpI7hCwof24Bf2D1QWdOp8M6GGyqEwHRyKepF4/edit?tab=t.0).

A Cool Kids Theme, a child theme of Twenty Twenty-Four, can be found in: /src/Volumes/WordPress/wp-content/themes/coolkidstheme
A PHP CodeSniffer (PHPCS) lint inspection was conducted and passed on all added files to the WordPress website, including the theme functions.

### Questions

If you have any, feel free to contact me at brettleclerc13@gmail.com.

