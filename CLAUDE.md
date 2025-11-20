# CLAUDE.md - AI Assistant Guide for Pediatric Practice Management System

## Project Overview

This is a **PHP + MySQL web-based practice management system** designed specifically for pediatricians. The application enables paperless vaccination management, patient registration, appointment scheduling, invoicing, medical certificates, and automated SMS/Email notifications.

**Key Differentiator**: The system can send unlimited SMS messages by configuring an Android device to send messages on behalf of the clinic, eliminating third-party SMS service limitations.

**Codebase Size**: ~8,134 lines of PHP code across 72 PHP files, plus supporting libraries and assets.

---

## Technology Stack

### Backend
- **Language**: PHP 7.x+
- **Database**: MySQL 5.5.16+ (InnoDB/MyISAM engines)
- **Server**: Apache with .htaccess support
- **Session Management**: Custom session handling with cookie-based "Remember Me"

### Frontend
- **JavaScript**: jQuery 1.12+, jQuery UI 1.11.2 and 1.12.1
- **Charting**: Plotly.js (BMI/growth tracking visualizations)
- **UI Components**: jQuery UI DatePicker, Timepicker, SimplePagination, EasyTabs, FileUpload

### External Services & Libraries
- **Email**: PHPMailer (bundled), SendGrid integration
- **PDF Generation**: FPDF library (bundled with font support)
- **SMS Services**:
  - SEMYSYS (primary, via API token)
  - smsgateway.me (legacy support)
  - Twilio (with webhook support, E.164 formatting)
- **Cloud**: AWS SDK for PHP v3.298+
- **Environment**: vlucas/phpdotenv v5.6

### Dependencies Management
```json
{
    "require": {
        "aws/aws-sdk-php": "^3.298",
        "vlucas/phpdotenv": "^5.6",
        "twilio/sdk": "^8.2"
    }
}
```

---

## Directory Structure

```
/
├── css/                    # Stylesheets (coolblue.css, jQuery UI themes)
├── js/                     # JavaScript libraries (jQuery plugins, Plotly)
├── images/                 # UI graphics, icons, backgrounds
├── PHPMailer/             # Email library (3rd party)
├── fpdf/                  # PDF generation library with fonts
├── sendgrid/              # SendGrid email service integration
├── jquery-ui-*/           # jQuery UI libraries (multiple versions)
├── vendor/                # Composer dependencies (git-ignored)
├── *.php                  # 72 PHP application files
├── localhost.sql          # Database schema dump
├── composer.json          # PHP dependency declarations
├── .gitignore            # Git ignore rules
├── .env                  # Environment variables (git-ignored)
└── connect.php           # DB connection config (git-ignored, must create manually)
```

---

## Core Application Architecture

### Entry Point Flow

```
index.php (Login/Home Dashboard)
    ↓ includes
header.php (Navigation, Session Check, Authentication)
    ↓ includes
header_db_link.php (Doctor-Specific DB Connection)
    ↓ authenticated access
[Feature Pages: register.php, search-patient.php, create-invoice.php, etc.]
    ↓ includes
footer.php
```

### Key Entry Point Details

- **`index.php`**: Main login page, session management, redirects authenticated users
  - Session name: `tzLogin`
  - Cookie retention: 2 weeks
  - **Security Note**: Currently uses MD5 password hashing (legacy, should migrate to bcrypt/Argon2)

- **`header.php`**: Template header with navigation menu, page titles, CSS/JS includes, authentication check

- **`header_db_link.php`**: Establishes database connection for logged-in users
  - **Multi-Database Architecture**: Each doctor has their own database with credentials stored in the `doctors` table

### Database Schema (from localhost.sql)

**Core Tables**:
1. **`doctors`** - User accounts for clinic staff
   - PK: `username`
   - Columns: name, password, db, db_user, db_pass, type, email, phone, email_sms, email_pass_onecom

2. **`patients`** - Patient information (~1,104 records capacity)
   - PK: `id` (AUTO_INCREMENT)
   - Columns: name, dob, sex, email, phone, address, family_history, medical_history, etc.

3. **`vaccines`** - Vaccine definitions and scheduling rules
   - PK: `id`
   - Columns: name, no_of_days, dependent, sex

4. **`vac_schedule`** - Patient vaccination schedules
   - Columns: p_id, v_id, date, given
   - Links patients to vaccines with scheduled dates

5. **`vac_make`** - Vaccine manufacturers/products

---

## Key Modules & Files

### 1. Authentication & Session Management
- **`index.php`** - Login page, session initialization, authentication logic
- **`header.php`** - Session validation, role-based access control
- **`header_db_link.php`** - Multi-database connection management
- **`header_db_link_UNSAFE.php`** - Legacy/reference file (do not use)

### 2. Patient Management
- **`register.php`** - New patient registration form
- **`add-patient-func.php`** - Patient validation and creation functions
- **`editpatient.php`** - Edit existing patient information
- **`search-patient.php`** + **`search-patient-results.php`** - Patient search
- **`get_patient.php`** - AJAX endpoint to retrieve patient data

### 3. Vaccination Management
- **`vaccine.php`** - Add new vaccines to system
- **`addvac.php`** + **`addvacmake.php`** - Add vaccine to schedule
- **`changevac.php`** + **`changevacmake.php`** - Modify/delete vaccines
- **`gen-sched-func.php`** - Vaccination schedule generation based on birth date and dependencies
- **`edit-sched.php`** - Edit vaccination schedules (52KB, largest file, complex scheduling logic)
- **`search-sched.php`** + **`search-sched-results.php`** - Search vaccination schedules
- **`search-scheddg.php`** - Search by given date

### 4. Appointment Management
- **`patient-vaccination-appointment-employee.php`** - Employee view for vaccination appointments
- **`patient-consultation-appointment-employee.php`** - Employee view for consultation appointments
- **`search-appt.php`** + **`search-appt-results.php`** - Doctor appointment search

### 5. Visit Tracking
- **`addvisit.php`** - Log patient visits
- **`visits.php`** - View visit logs
- **`visits-results.php`** - Visit search results
- **`visits-today.php`** - Display today's visits

### 6. Invoicing & Payments
- **`create-invoice.php`** - Generate invoices (8.3KB)
- **`search-invoice.php`** - Search existing invoices
- **`invoice-results.php`** - Display invoice search results
- **`payment_due.php`** - View pending payments
- **`email-invoice.php`** + **`email-invoice-ui.php`** - Email invoices to patients

### 7. Medical Certificates
- **`medcert.php`** - Basic medical certificate
- **`medcert_with_fitness.php`** - Certificate with fitness assessment
- **`medcert_with_fitness_and_vac.php`** - Certificate with fitness and vaccination status
- **`show_medcert.php`** - Display medical certificate
- PDF generation files: **`pdf-medcert.php`**, **`pdf-medcert_with_fitness.php`**, etc.

### 8. PDF Generation
- **`pdf-functions.php`** - Core PDF utility functions (5KB)
- **`pdf-functions-invoice.php`** - Invoice PDF generation (5.2KB)
- **`pdf-functions-medcert.php`** - Medical certificate PDF functions (3.9KB)
- **`pdf.php`** + **`pdf-invoice.php`** - PDF render endpoints
- Uses FPDF library (bundled in `/fpdf/` directory)

### 9. Communication (Email & SMS)
- **`email.php`** - Send emails to patients (vaccine schedules, general communication)
- **`email-smtp-auth.php`** - SMTP configuration (One.com: send.one.com:2525 with TLS)
- **`smsGateway.php`** - SMS gateway integration with smsgateway.me and SEMYSYS services (2.4KB)
  - **Security**: SEMYSYS token loaded from environment variable `SEMYSYS_TOKEN`
- **`send_message_twilio.php`** - Twilio SMS integration with message templates and E.164 number formatting
- **`twilio_webhook.php`** - Webhook handler for Twilio message status updates
- **`get_pending_sms.php`** - Retrieve pending SMS messages
- Test files: **`test-email.php`**, **`test_twilio.php`**, **`testsms.php`**

### 10. Data Visualization
- **`plotly.php`** - Plotly.js integration for BMI charts
- **`male2-20years_plotly.js`** (368KB) - Male BMI reference data
- **`female2-20years_plotly.js`** (30KB) - Female BMI reference data

### 11. Prescription Management
- **`prescription.php`** - Add/manage prescriptions
- **`add-picture-prescription.php`** - Upload prescription images
- **`delete-prescription-ajax.php`** - AJAX endpoint to delete prescriptions

### 12. Settings & Admin
- **`settings.php`** - Doctor/clinic settings
- **`ajax_refresh.php`** - AJAX data refresh endpoint
- **`upload-file-via-backend.php`** - File upload handler

### 13. Deployment & Utilities
- **`deploy.php`** - Deployment script
- **`deploy.sh`** - Shell deployment script
- **`git-pull.sh`** - Git pull automation
- **`phpinfo.php`** - PHP configuration information

---

## Development Workflows

### Setting Up Local Environment

1. **Install Dependencies**:
   ```bash
   composer install
   ```

2. **Create Database Configuration** (`connect.php`):
   ```php
   <?php
   // This file is git-ignored - create it manually
   $servername = "localhost";
   $username = "your_mysql_user";
   $password = "your_mysql_password";
   ?>
   ```

3. **Import Database**:
   ```bash
   mysql -u root -p < localhost.sql
   ```

4. **Update Doctor Credentials**:
   - Modify `doctors` table in `drmahima_com_db_root` database
   - Set correct `db_user` and `db_pass` for all entries (should match local MySQL credentials)

5. **Configure Environment Variables** (`.env`):
   ```
   SEMYSYS_TOKEN=your_semysys_token_here
   TWILIO_ACCOUNT_SID=your_twilio_sid
   TWILIO_AUTH_TOKEN=your_twilio_token
   ```

### Git Workflow

**Current Branch**: `claude/claude-md-mi7pdifbll6fo57t-01XEm5sWZBi6zcsxRcoS5PwN` (feature branch)

**Branch Naming Convention**:
- Feature branches: `claude/claude-md-*`
- All development should occur on designated feature branches
- **CRITICAL**: Branch must start with `claude/` and end with matching session ID, otherwise push will fail with 403

**Commit Message Style** (based on recent history):
- Descriptive, action-oriented messages
- Examples: "Update smsGateway.php", "Load SEMYSYS token from environment"
- Use present tense for actions

**Git Operations**:
```bash
# Always push with -u flag to set upstream
git push -u origin <branch-name>

# If push fails due to network errors, retry with exponential backoff (2s, 4s, 8s, 16s)

# Fetch specific branches
git fetch origin <branch-name>

# Pull latest changes
git pull origin <branch-name>
```

**Recent Commits** (security focus):
- d99488f - Update smsGateway.php
- 50e1a62 - Update smsGateway.php
- 2ac022a - Merge pull request #44 (vulnerability fix in smsgateway.php)
- 8a736f6 - Load SEMYSYS token from environment

### Deployment Process

1. Use `deploy.sh` or `deploy.php` for automated deployment
2. Run `git-pull.sh` to pull latest changes
3. Ensure `.env` and `connect.php` are properly configured on production
4. Verify database migrations if schema changes exist

---

## Coding Conventions & Best Practices

### File Organization
- **Page Files**: Main user-facing pages (e.g., `register.php`, `search-patient.php`)
- **Function Files**: Reusable logic (e.g., `add-patient-func.php`, `gen-sched-func.php`)
- **AJAX Endpoints**: Files for asynchronous operations (e.g., `get_patient.php`, `ajax_refresh.php`)
- **PDF Generators**: Separate PDF rendering logic (e.g., `pdf-functions.php`, `pdf-invoice.php`)

### Naming Patterns
- **Search pages**: `search-*.php` + `search-*-results.php` (paired files)
- **Add/Edit pages**: `add*.php` + `edit*.php`
- **AJAX endpoints**: `get_*.php`, `*-ajax.php`
- **PDF files**: `pdf-*.php`, `pdf-functions-*.php`
- **Test files**: `test-*.php`, `test*.php`

### Database Connections
- **NEVER hardcode database credentials** - use `header_db_link.php` or `connect.php`
- **Multi-database architecture**: Each doctor has separate database credentials
- Always use prepared statements or parameterized queries (avoid SQL injection)

### Session Management
- Session name: `tzLogin`
- Always include session checks for authenticated pages
- Use `header.php` for consistent authentication validation

### Include Pattern
```php
// Typical page structure
<?php
include('header.php');
include('header_db_link.php');
?>
<!-- Page content here -->
<?php include('footer.php'); ?>
```

---

## Security Considerations

### Critical Security Notes

1. **Password Hashing**:
   - **CURRENT**: Uses MD5 hashing (legacy, insecure)
   - **TODO**: Migrate to bcrypt or Argon2 for password storage
   - Location: `index.php` authentication logic

2. **Environment Variables**:
   - **ALWAYS** use environment variables for API tokens and credentials
   - Load via `vlucas/phpdotenv` library
   - Example: `SEMYSYS_TOKEN` in `smsGateway.php`

3. **Git-Ignored Sensitive Files**:
   - `connect.php` - Database credentials
   - `.env` - API tokens and secrets
   - `vendor/` - Composer dependencies
   - Never commit these files to version control

4. **SQL Injection Prevention**:
   - Use prepared statements and parameterized queries
   - Validate and sanitize all user inputs
   - Especially critical in search and patient management modules

5. **XSS Prevention**:
   - Sanitize output when displaying user-generated content
   - Use `htmlspecialchars()` or equivalent for HTML output
   - Be cautious with prescription uploads and patient notes

6. **CSRF Protection**:
   - Implement CSRF tokens for state-changing operations
   - Particularly important for patient edits, invoice creation, and settings changes

7. **File Upload Security**:
   - Validate file types and sizes
   - Sanitize filenames
   - Store uploads outside web root if possible
   - Relevant files: `upload-file-via-backend.php`, `add-picture-prescription.php`

8. **SMS Gateway Security**:
   - Recent vulnerability fixes in `smsGateway.php` (PR #44)
   - Always validate SMS parameters before sending
   - Use environment variables for API tokens

### Recent Security Improvements

- Migration from hardcoded SEMYSYS token to environment variable (commit 8a736f6)
- Security vulnerability fixes in `smsGateway.php` (PR #44, commits d99488f, 50e1a62)

---

## Common Development Tasks

### Adding a New Patient Field

1. **Update Database Schema**: Modify `patients` table in `localhost.sql`
2. **Update Registration Form**: Edit `register.php`
3. **Update Validation**: Modify `add-patient-func.php`
4. **Update Edit Form**: Edit `editpatient.php`
5. **Update Display**: Check `search-patient-results.php` and related views

### Adding a New Vaccine

1. **Add to Database**: Insert into `vaccines` table via `vaccine.php`
2. **Configure Schedule**: Set `no_of_days`, `dependent`, `sex` fields
3. **Update Schedule Generation**: May need to modify `gen-sched-func.php` for complex dependencies
4. **Test**: Use `edit-sched.php` to verify schedule appears correctly

### Modifying Invoice Template

1. **Edit PDF Functions**: Modify `pdf-functions-invoice.php`
2. **Update Invoice Creation**: Edit `create-invoice.php` if adding new fields
3. **Test PDF Generation**: Use `pdf-invoice.php` to verify output
4. **Email Integration**: Update `email-invoice.php` if email content changes

### Adding a New Communication Channel

1. **Create Integration File**: Follow pattern of `send_message_twilio.php` or `smsGateway.php`
2. **Add Environment Variables**: Update `.env` with new credentials
3. **Create Webhook Handler**: If needed (e.g., `twilio_webhook.php`)
4. **Test Integration**: Create corresponding `test-*.php` file
5. **Update Settings**: Add configuration options in `settings.php`

### Creating a New Report/Search

1. **Create Search Page**: `search-newfeature.php` with form
2. **Create Results Page**: `search-newfeature-results.php` with display logic
3. **Database Queries**: Use prepared statements, optimize with indexes
4. **Pagination**: Use `jquery.simplePagination.js` for large result sets

---

## Testing Guidelines

### Manual Testing Files
- **`test-email.php`** - Test email functionality
- **`test_twilio.php`** - Test Twilio SMS integration
- **`testsms.php`** - Test SMS gateway functionality
- **`phpinfo.php`** - Verify PHP configuration

### Testing Checklist
- [ ] Patient registration and search
- [ ] Vaccination schedule generation and editing
- [ ] Invoice creation and PDF generation
- [ ] Email and SMS sending (use test files)
- [ ] Medical certificate generation
- [ ] Appointment scheduling
- [ ] Payment tracking
- [ ] Multi-database support (test with different doctor accounts)

---

## API & External Service Integration

### Email Services

**PHPMailer** (Primary):
- Location: `/PHPMailer/` directory
- Configuration: `email-smtp-auth.php`
- SMTP Server: send.one.com:2525 (TLS)
- Usage: `email.php`, `email-invoice.php`

**SendGrid** (Alternative):
- Location: `/sendgrid/` directory
- Documentation: `/sendgrid/README.md`
- Configuration: Via environment variables

### SMS Services

**SEMYSYS** (Primary):
- Configuration: `smsGateway.php`
- Environment Variable: `SEMYSYS_TOKEN`
- **Security**: Token must be in `.env`, never hardcoded

**Twilio** (Secondary):
- Configuration: `send_message_twilio.php`
- Environment Variables: `TWILIO_ACCOUNT_SID`, `TWILIO_AUTH_TOKEN`
- Features: Message templates, E.164 number formatting, webhook support
- Webhook Handler: `twilio_webhook.php`

**smsgateway.me** (Legacy):
- Still supported in `smsGateway.php`
- Consider deprecating in favor of SEMYSYS/Twilio

### AWS SDK
- Version: 3.298+
- Potential use: S3 storage for prescription images, backups
- Currently included but may not be fully integrated

---

## Performance Considerations

### Large Files
- **`edit-sched.php`** (52KB) - Complex scheduling logic, may need optimization
- **`male2-20years_plotly.js`** (368KB) - Large BMI dataset, consider lazy loading
- **Plotly.js**: Heavy visualization library, load only when needed

### Database Optimization
- Use indexes on frequently queried fields (patient ID, vaccine ID, dates)
- Optimize vaccination schedule queries (can be complex with dependencies)
- Consider caching for frequently accessed data (vaccine lists, doctor settings)

### Multi-Database Architecture
- Each doctor has separate database connection
- Ensure connection pooling and proper resource cleanup
- Monitor connection limits on production servers

---

## Troubleshooting

### Common Issues

**Database Connection Errors**:
- Verify `connect.php` exists and has correct credentials
- Check `doctors` table has correct `db_user` and `db_pass` values
- Ensure MySQL service is running

**SMS/Email Not Sending**:
- Verify environment variables in `.env`
- Check credentials in `email-smtp-auth.php` for email
- Test with `test-email.php`, `test_twilio.php`, `testsms.php`
- Check webhook URLs for Twilio are accessible

**PDF Generation Fails**:
- Verify FPDF library is present in `/fpdf/` directory
- Check font files are available in `/fpdf/font/`
- Review error logs in PDF generation scripts

**Session Issues**:
- Clear browser cookies (session name: `tzLogin`)
- Check session storage permissions on server
- Verify `session_start()` is called in `header.php`

---

## Documentation References

- **Project README**: `/home/user/pediatric-practice-soln/README.md`
- **SendGrid Docs**: `/sendgrid/README.md`, `/sendgrid/lib/*/README.md`
- **PHPMailer Docs**: `/PHPMailer/README.md`, `/PHPMailer/changelog.md`
- **Database Schema**: `/home/user/pediatric-practice-soln/localhost.sql`
- **Limitations**: `/home/user/pediatric-practice-soln/limitations`
- **TODO Items**: `/home/user/pediatric-practice-soln/todo`

---

## AI Assistant Guidelines

### When Working on This Codebase

1. **Always Check Security**:
   - Never hardcode credentials or API tokens
   - Use environment variables via `phpdotenv`
   - Sanitize user inputs (SQL injection, XSS prevention)
   - Use prepared statements for database queries

2. **Follow Existing Patterns**:
   - Paired search files: `search-*.php` + `search-*-results.php`
   - Include structure: `header.php` → `header_db_link.php` → content → `footer.php`
   - Function files for reusable logic

3. **Respect Multi-Database Architecture**:
   - Each doctor has separate database
   - Connection credentials in `doctors` table
   - Use `header_db_link.php` for authenticated database access

4. **Test Communication Features**:
   - Use provided test files: `test-email.php`, `test_twilio.php`, `testsms.php`
   - Verify environment variables are loaded
   - Check webhook endpoints are accessible

5. **PDF Generation**:
   - Use FPDF library (bundled)
   - Follow patterns in `pdf-functions.php`, `pdf-functions-invoice.php`
   - Test PDF output before committing

6. **Git Workflow**:
   - Work on designated feature branch (starts with `claude/`)
   - Commit with descriptive messages
   - Push with `-u origin <branch-name>`
   - Retry on network failures with exponential backoff

7. **Code Review Checklist**:
   - [ ] No hardcoded credentials
   - [ ] SQL injection prevention (prepared statements)
   - [ ] XSS prevention (sanitized output)
   - [ ] Environment variables for secrets
   - [ ] Proper error handling
   - [ ] Follow existing naming conventions
   - [ ] Update documentation if adding new features

8. **Performance**:
   - Be mindful of large files and complex queries
   - Consider pagination for large result sets
   - Optimize database queries with indexes

9. **Deployment**:
   - Never commit `.env` or `connect.php`
   - Use deployment scripts: `deploy.sh`, `git-pull.sh`
   - Verify production environment variables

10. **Documentation**:
    - Update this CLAUDE.md when adding major features
    - Add inline comments for complex logic
    - Update README.md for installation/setup changes

---

## Changelog

**Last Updated**: 2025-11-20

**Recent Changes**:
- Initial creation of CLAUDE.md
- Documented complete codebase structure
- Added security guidelines and recent vulnerability fixes
- Documented multi-database architecture
- Added development workflows and common tasks

**Recent Security Fixes**:
- Migration of SEMYSYS token to environment variable (commit 8a736f6)
- smsGateway.php vulnerability fixes (PR #44, commits d99488f, 50e1a62)

---

## Quick Reference

**Entry Point**: `index.php`
**Authentication**: `header.php` + `header_db_link.php`
**Session Name**: `tzLogin`
**Database Config**: `connect.php` (git-ignored, create manually)
**Environment Config**: `.env` (git-ignored, create manually)
**PDF Library**: FPDF (`/fpdf/`)
**Email Library**: PHPMailer (`/PHPMailer/`)
**Dependencies**: `composer install`
**Database Import**: `mysql -u root -p < localhost.sql`

**Git Branch**: `claude/claude-md-mi7pdifbll6fo57t-01XEm5sWZBi6zcsxRcoS5PwN`
**Push Command**: `git push -u origin <branch-name>`

---

## Support & Contact

For issues or questions:
- Check existing documentation in `/README.md`
- Review TODO items in `/todo`
- Check limitations in `/limitations`
- Review recent commit history for context
