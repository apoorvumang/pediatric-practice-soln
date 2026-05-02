<?php
// Process login BEFORE any HTML output so header() and setcookie() work correctly
session_name('tzLogin');
session_set_cookie_params(2*7*24*60*60);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'connect.php';
$patient_cookie_secret = hash_hmac('sha256', 'patient-portal', $password . $servername);

// Already logged in
if (isset($_SESSION['patient_id']) && isset($_SESSION['patient_db'])) {
    header('Location: patient-portal.php');
    exit;
}

$login_errors = [];

if (!empty($_POST['patient_login'])) {
    $patient_id_raw = trim($_POST['patient_id'] ?? '');
    $dob_raw        = trim($_POST['dob'] ?? '');

    if (!$patient_id_raw || !$dob_raw) {
        $login_errors[] = 'Both Patient ID and Date of Birth are required.';
    }
    if ($patient_id_raw && !is_numeric($patient_id_raw)) {
        $login_errors[] = 'Patient ID must be a number.';
    }

    if (empty($login_errors)) {
        $patient_id_int = (int)$patient_id_raw;
        $dob_formatted  = date('Y-m-d', strtotime($dob_raw));

        if (!$dob_formatted || $dob_formatted === '1970-01-01') {
            $login_errors[] = 'Invalid date of birth format.';
        }
    }

    if (empty($login_errors)) {
        $found = false;
        $doctors_result = mysqli_query($link_root, "SELECT username, db, db_user, db_pass FROM doctors");

        while ($doctor = mysqli_fetch_assoc($doctors_result)) {
            if (!$doctor['db'] || !$doctor['db_user']) continue;

            $temp_link = @mysqli_connect($db_host, $doctor['db_user'], $doctor['db_pass'], $doctor['db']);
            if (!$temp_link) continue;

            $stmt = mysqli_prepare($temp_link, "SELECT id, name, dob FROM patients WHERE id = ? AND dob = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "is", $patient_id_int, $dob_formatted);
                mysqli_stmt_execute($stmt);
                $result  = mysqli_stmt_get_result($stmt);
                $patient = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);

                if ($patient) {
                    session_regenerate_id(true);
                    $_SESSION['patient_id']       = (int)$patient['id'];
                    $_SESSION['patient_name']     = $patient['name'];
                    $_SESSION['patient_db']       = $doctor['db'];
                    $_SESSION['patient_db_user']  = $doctor['db_user'];
                    $_SESSION['patient_db_pass']  = $doctor['db_pass'];

                    if (!empty($_POST['remember_me'])) {
                        $expires        = time() + 30 * 24 * 60 * 60;
                        $cookie_payload = $patient_id_int . '|' . $dob_formatted . '|' . $expires;
                        $mac            = hash_hmac('sha256', $cookie_payload, $patient_cookie_secret);
                        setcookie('tzPatientRemember', $cookie_payload . '|' . $mac, $expires, '/', '', false, true);
                    }

                    $found = true;
                    mysqli_close($temp_link);
                    header('Location: patient-portal.php');
                    exit;
                }
            }
            mysqli_close($temp_link);
        }

        if (!$found) {
            $login_errors[] = 'No patient found with the given ID and Date of Birth.';
        }
    }
}
?>
<?php include('patient-header.php'); ?>
<script type="text/javascript">
$(function() {
    $("#dob").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1950:2030",
        dateFormat: "d M yy",
        maxDate: new Date()
    });
});
</script>

<h3>Patient Login</h3>
<p>Welcome to the patient portal. Please log in with your Patient ID and Date of Birth.</p>

<?php if (!empty($login_errors)) { ?>
<div style="margin: 10px 25px; padding: 10px; background: #FFE0E0; border: 1px solid #FF9999; color: #CC0000;">
    <?php echo implode('<br />', array_map('htmlspecialchars', $login_errors)); ?>
</div>
<?php } ?>

<form class="clearfix" action="" method="post" style="width:400px;">
    <h3 style="color: #2C76A6;">Patient Login</h3>
    <p>
        <label class="grey" for="patient_id">Patient ID:</label><br />
        <input class="field" type="text" name="patient_id" id="patient_id" value="<?php echo htmlspecialchars($_POST['patient_id'] ?? ''); ?>" size="23" />
    </p>
    <p>
        <label class="grey" for="dob">Date of Birth:</label><br />
        <input class="field" type="text" name="dob" id="dob" size="23" readonly />
    </p>
    <p>
        <label style="font-weight:normal;">
            <input type="checkbox" name="remember_me" value="1" <?php echo !empty($_POST['remember_me']) ? 'checked' : ''; ?> />
            &nbsp;Remember me for 30 days
        </label>
    </p>
    <p>
        <input type="hidden" name="patient_login" value="1" />
        <input type="submit" value="Login" class="bt_login" />
    </p>
</form>

<p style="margin: 10px 25px;"><small>Don't know your Patient ID? Please contact the clinic.</small></p>

<?php include('patient-footer.php'); ?>
