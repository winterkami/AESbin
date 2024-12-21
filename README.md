# COMP307-project
## URLS
Code repository: https://github.com/eli0009/COMP307-project
## Project setup
1. Install XAMPP version 8.2.12 / PHP 8.2.12: https://www.apachefriends.org/download.html
2. Navigate to `C:\xampp\htdocs\` or wherever you installed XAMPP's `htdocs` and unzip the project zip file. Or run the following inside `htdocs`:
```
git clone https://github.com/eli0009/COMP307-project.git
```
3. Open XAMPP Control Panel and start Apache and MySQL modules
4. Open http://localhost/COMP307-project/ or http://localhost/COMP307-project-main in the browser (depending on the folder name of the project in htdocs)

## Team Contributions

### Enlai Li
#### Backend & Database: User content submission public page
- PHP backend that stores data sent from the frontend into a SQL database, along with other data such as the date submitted
- Each user submission is assigned a unique randomly generated id
- The user submitted text is encrypted with AES using the optional password (if provided)
- After submission, the user is redirected to a dynamically generated URL using Apache's .htaccess config
#### Backend & Frontend & Database: Dynamically generated user content display private page
- PHP backend that retrieve user content from SQL database and generate an interactive public html page to display the content
- Handle decryption of content using a user provided password, and performs the appropriate error handling in case of wrong/empty password.
- The page is different depending on whether the content is encrypted or not
- Tracking the number of visits to a certain page and keeping track of it in the database
#### Backend & Frontend & Database: Recent paste public page to view all user submitted entries
- PHP backend that handles fetching content from MySQL database and sends it to the frontend according to sort options, search terms, and page number
- Javascript to handle behavior of dynamic elements like: sorting buttons (Recent, popular, oldest), page buttons, and search bar
- Javascript also updates the content/text on the website dynamically by sending request to recent.php
#### Files
- display.php
- submit.php 
- recent.php
- recent_script.js
- database_create.php
- pastes/.htaccess
- README.md project setup instruction
- Aaron's index.html (connecting it to submit.php)
- Aaron's recent_pastes.html (connecting it to recent.php and recent_script.js)
### Aaron Elcheson
#### Front end: Responsive and interactive landing_page
- landing_page (home page) using flexbox to accommodate multiple devices.
- Project logo.PNG and css animation falling letters in the header.
- Header links to 'About', 'Home', and 'Login' pages.
- Main content area for user to write and submit text with an option to password protect the text.
#### Front end: Responsive and interactive recent_pastes public page
- recent_pastes page using flexbox and media queries for multiple devices
- custom style sheet for flex elements (table of recent pastes)
- table area has search options for pastes and page buttons to navigate table
#### Front end: Responsive and interactive private user dashboard page
- private user page with custom dashboard banner image
- using flexbox and media queries for multiple devices
- recent pastes are displayed in a flex container
- user information like name, registration date, and number of pastes is displayed in a flex container
- dynamic service canada weather widget also displayed in flex container
- #### Front end: Responsive and interactive public About page
- public about pages with site information, custom photos, and youtube video embed
- using inline flexbox styles for responsive layout

#### Files
- index.html
- logo.PNG
- nebula.jpg
- animation.css
- recent_pastes_v2.html
- recent_pastes_styles.css
- dashboard.html
- general_styles.css
- about.html
- moon.jpg
- stars.jpg
  
### Chenxuan Jin
#### Backend & Database: User Authentication System (Login and Registration)
- **PHP backend** to handle user registration and login, storing user credentials (email and hashed passwords) securely in a MySQL database.
- **Registration form (`register.php`)** validates input fields, hashes passwords with a secure algorithm (e.g., bcrypt), and stores user details.
- **Login form (`login.php`)** validates user credentials by comparing the hashed password from the database with the provided input.
- A **PHP session** is initiated upon successful login, storing the user's session data (e.g., `user_id`).
- Secure handling of session state ensures unauthorized users cannot access restricted pages like the `Recent` page.
- Includes **`check_session.php` endpoint** to validate the login status of users across different pages.

---

#### Backend, Frontend & Database: User-Specific Recent Content Viewing
- **PHP backend (`recent.php`)** retrieves user-specific data from the database using the `user_id` stored in the session, ensuring that only logged-in users can view their past entries.
- Dynamic fetching of recent user submissions, sorted by submission date, and limited by pagination for efficient display.
- The **frontend verifies the user's login status** via a `checkLoginStatus` function in `common.js`, redirecting unauthorized users to the login page.
- The table content dynamically displays the entries with fields such as date submitted, content preview, unique ID, and visit count.

---

#### Files
- `register.php`: Handles user registration by securely storing user details.
- `login.php`: Authenticates users and initializes sessions upon successful login.
- `check_session.php`: Verifies the login status of a user.
- `recent.php`: Fetches recent entries specific to the logged-in user from the database.
- `recent_script.js`: Implements dynamic table updates, sorting, searching, and pagination.
- `common.js`: Handles shared functionalities like login state verification and event listeners.
- `database_create.php`: Initializes the database schema for user authentication and content storage.
- `register.html`: User registration form connected to `register.php`.
- `recent_pastes_v2.html`: Displays user-specific recent entries with dynamic interaction powered by `recent.php` and `recent_script.js`.



## Quick Start

Navigate to index.html
