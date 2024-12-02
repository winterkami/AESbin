# COMP307-project
## URLS
Code repository: https://github.com/eli0009/COMP307-project
## Team Contributions

### Enlai Li 261068637
Feature 1: User Content Submission and Dynamic URL Generation
- index.html
- display.php
- submit.php
- pastes/.htaccess 
### Aaron Elcheson
### Chenxuan Jin
## Features

1. User Content Submission and Dynamic URL Generation

   - Each user submission is assigned a unique identifier `id` generated using a secure random algorithm.
   - All submitted content is stored in a MySQL database. The database ensures data persistence and allows for retrieval of content using the unique `id`.
   - After submission, the user is redirected to a dynamically generated URL in the format http://localhost/COMP307-project/pastes/[id].
   - This ensures that each submission has its own unique page that displays the content submitted by the user.
   - Comprehensive error handling ensures that users are informed if an invalid or nonexistent id is accessed.Missing query parameters or invalid ids are handled gracefully, displaying appropriate error messages.

## Quick Start

Navigate to index.html
