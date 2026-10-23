# Secure File Storage Web App (PHP + MySQL)

A file storage web app where users can register, log in, upload files, and view a list of their own files. Built as a course lab exercise at BINUS University.

> **Credit:** The UI layout was provided as part of a university course lab. I implemented all backend PHP logic.

## Security controls implemented

| Threat | Control | File |
|---|---|---|
| SQL injection | PDO prepared statements for all queries | `doRegister.php`, `doLogin.php`, `doUpload.php`, `list.php` |
| Password exposure | bcrypt hashing with `password_hash()` / `password_verify()` | `doRegister.php`, `doLogin.php` |
| Session fixation | `session_regenerate_id(true)` after login | `doLogin.php` |
| Malicious file upload | Extension allowlist (pdf, docx, jpg, jpeg, png), 5 MB limit, random 128-bit stored filenames | `doUpload.php` |
| XSS | `htmlspecialchars()` on user-supplied output | `list.php`, `login.php`, `register.php`, `upload.php` |
| IDOR | File list filtered by the user ID from the server-side session | `list.php` |

## Known limitations

- No CSRF tokens on forms
- No login rate limiting or account lockout
- Uploaded files are stored inside the web root and can be accessed directly by URL
- File content is not verified (extension check only)
- Download and Delete buttons are not implemented yet
- Database credentials are hardcoded (local XAMPP defaults)
- The sample admin account in `database.sql` uses a plaintext password and cannot log in

## Run locally (XAMPP)

1. Start Apache and MySQL.
2. In phpMyAdmin, create a database named `db_upload_exercise` and import `database.sql`.
3. Create an empty `uploads/` folder in the project root.
4. Open `http://localhost/<folder-name>/login.php`.