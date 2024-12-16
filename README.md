# COMP307-project
## URLS
Code repository: https://github.com/eli0009/COMP307-project
## Team Contributions

### Enlai Li
#### Backend & Database: User content submission page, User data storage in SQL database, Dynamic URL generation, and User content encryption
- PHP backend that stores data sent from the frontend into a SQL database
- Each user submission is assigned a unique randomly generated id
- The user submitted text is encrypted with AES using the optional password (if provided)
- All submitted content is stored in a MySQL database
- After submission, the user is redirected to a dynamically generated URL
#### Backend & Frontend & Database: Dynamically generated user content display public page, decryption of user content, tracking page visits 
- PHP backend that retrieve user content from SQL database and generate an interactive public html page to display the content
- Handle decryption of content using a user provided password, and performs the appropriate action depending on whether the password is correct.
- The page is different depending on whether the content is encrypted or not
- The number of visits to a certain page is kept track of in the database
#### Backend & Frontend & Database: Recent paste public page
- Javascript to handle behavior of sorting buttons (Recent, popular, oldest), page buttons, and updating displayed rows and text
- PHP backend that handles fetching content from MySQL database and sending it to the frontend
- Support for the search bar that searches specific content in the database and displays them
#### Files
- display.php
- submit.php 
- recent.php
- recent_script.js
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
#### Files
- index.html
- logo.PNG
- animation.css
- recent_pastes_v2.html
- recent_pastes_styles.css
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
