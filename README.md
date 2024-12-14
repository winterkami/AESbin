# COMP307-project
## URLS
Code repository: https://github.com/eli0009/COMP307-project
## Team Contributions

### Enlai Li
#### Backend & Database: User content submission, Dynamic URL generation, and User content encryption
- Each user submission is assigned a unique randomly generated identifier `id`.
- The user submitted text is encrypted with AES using the optional password (if provided)
- All submitted content is stored in a MySQL database. 
- After submission, the user is redirected to a dynamically generated URL in the format http://localhost/COMP307-project/pastes/[id].
#### Files
- display.php
- submit.php (the html at the bottom of the file used Aaron's index.html as template)
- pastes/.htaccess 
- Aaron's index.html (connecting it to submit.php)
#### Backend & Frontend & Database: Display user content with dynamically generated page, decryption of user content
- Retrieve user content from SQL database and generate html page to display the content
- Handle decryption of content using a user provided password
#### File
- submit.php 
### Aaron Elcheson
#### Front end: Responsive and interactive landing_page
- landing_page (home page) using flexbox to accommodate multiple devices.
- Project logo.PNG and css animation falling letters in the header.
- Header links to 'About', 'Home', and 'Login' pages.
- Main content area for user to write and submit text with an option to password protect the text.
#### Files
- index.html
- logo.PNG
- animation.css
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
