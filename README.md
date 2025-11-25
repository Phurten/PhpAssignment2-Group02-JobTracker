# JobTracker - Group 2 Assignment

A modern web application for tracking job applications, built with PHP, MySQL, and Bootstrap. JobTracker helps students organize, filter, and manage their job search efficiently, with a clean interface.

## Features

### Core Functionality

- **Job Application Tracking**: Add, update, and delete job applications with company details, status, and application date.
- **User Management**: Admins can add and remove users, and assign jobs to users.
- **Filtering**: 
  - Admins can filter jobs by user.
  - Regular users can filter jobs by application status.
- **Company Management**: View and manage companies associated with job applications.
- **Modern UI**: Visually modern design with header navigation, card-based job display, and styled forms/buttons.
- **Authentication**: Secure login system with support for both hashed and legacy plain-text passwords.
- **Access Control**: Admin-only features and protected routes.

## Tech Stack

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Styling**: Custom CSS, Google Fonts

## Application Structure

- **Landing Page**: Modern hero section with call-to-action.
- **Jobs Page**: List, filter, update, and delete job applications.
- **Companies Page**: View and manage companies.
- **Users Page**: (Admin only) Manage users and add new users.
- **Add/Update Job**: Two-column, centered forms for job entry and editing.
- **Add User**: (Admin only) Form for adding new users with validation and password hashing.

## Database

- **Tables**: `users`, `jobs`, `companies`
- **Relationships**: Jobs are linked to users and companies.
- **Sample Data**: Pre-populated with users and jobs for demonstration.

## Future Enhancements

- **Job Notes**: Add notes or comments to job applications.
- **Export Data**: Download job application data as CSV.
- **Analytics**: Visualize application progress and statistics.

## Acknowledgments

- **Bootstrap**: For responsive UI components.
- **Google Fonts**: For modern typography.
- **XAMPP**: For local development environment.
- **PIXABAY**: For background image of landing page.

## Author & Contributors

- **Ankit Kumar**: Developed all major CRUD (Create, Read, Update, Delete) functions for jobs and companies.
- **Thamil**: Implemented login/logout authentication and all delete functions (users, jobs, companies).
- **Phurten Jang Sherpa**: Implemented all styles, Add User, and admin-only features.

Web Development Project – HTTP5225  
