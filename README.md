# README.md

# RCCIIT

This repository contains the custom `wp-content` directory for the project.

WordPress core files are intentionally excluded from version control. Only project-specific code inside `wp-content` is tracked.

---

## Prerequisites

Ensure you have the following installed:

- PHP (compatible with WordPress)
- MySQL / MariaDB
- Web server (Apache / Nginx)
- WordPress (latest stable recommended)
- Git

---

## Recommended Local Environment

We recommend using **Local (by Flywheel)** for setting up WordPress locally.

Download Local here:  
https://localwp.com/

---

## Setup Instructions

### 1. Fork the Repository

Fork this repository to your own GitHub account.

Click the **Fork** button at the top-right of this repository page.

---

### 2. Create a Fresh WordPress Site

Create a new WordPress site locally using **Local (by Flywheel)** or any setup of your choice.

After setup, ensure:
- The site loads correctly
- WordPress admin panel is accessible

---

### 3. Remove the Default `wp-content` Directory

From the WordPress root directory, remove the existing `wp-content` folder:

```bash
rm -rf wp-content
```

---

### 4. Clone your forked repository

```bash
git clone <forked-repo> wp-content
```
