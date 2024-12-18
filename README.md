# COMP307-project
## URLS
Code repository: https://github.com/eli0009/COMP307-project
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
- Aaron's index.html (connecting it to submit.php)
- Aaron's recent_pastes.html (connecting it to recent.php and recent_script.js)
#### File
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
Feature 1: Create login and register page with database store
- login.html
- login.php
- register.php
- register.html
- logout.php
- welcome.php


## Quick Start

Navigate to index.html
