<?php
include('patient-header.php');

// If already logged in as patient, redirect to portal
if ($patient_logged_in) {
    patientRedirect("patient-portal.php");
    exit;
}

if ($_POST['patient_login']) {
    $err = array();

    $patient_id = trim($_POST['patient_id'] ?? '');
    $dob = trim($_POST['dob'] ?? '');

    if (!$patient_id || !$dob) {
        $err[] = 'Both Patient ID and Date of Birth are required.';
    }

    if (!is_numeric($patient_id)) {
        $err[] = 'Patient ID must be a number.';
    }

    if (!count($err)) {
        $patient_id = (int)$patient_id;
        $dob_formatted = date('Y-m-d', strtotime($dob));

        if (!$dob_formatted || $dob_formatted == '1970-01-01') {
            $err[] = 'Invalid date of birth format.';
        }
    }

    if (!count($err)) {
        // Search across all doctor databases for this patient
        $found = false;
        $doctors_result = mysqli_query($link_root, "SELECT username, db, db_user, db_pass FROM doctors");

        while ($doctor = mysqli_fetch_assoc($doctors_result)) {
            if (!$doctor['db'] || !$doctor['db_user']) continue;

            $temp_link = @mysqli_connect($db_host, $doctor['db_user'], $doctor['db_pass'], $doctor['db']);
            if (!$temp_link) continue;

            $stmt = mysqli_prepare($temp_link, "SELECT id, name, dob FROM patients WHERE id = ? AND dob = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "is", $patient_id, $dob_formatted);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $patient = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);

                if ($patient) {
                    // Found the patient - regenerate session to prevent fixation
                    session_regenerate_id(true);
                    $_SESSION['patient_id'] = (int)$patient['id'];
                    $_SESSION['patient_name'] = $patient['name'];
                    $_SESSION['patient_db'] = $doctor['db'];
                    $_SESSION['patient_db_user'] = $doctor['db_user'];
                    $_SESSION['patient_db_pass'] = $doctor['db_pass'];

                    $found = true;
                    mysqli_close($temp_link);
                    break;
                }
            }
            mysqli_close($temp_link);
        }

        if ($found) {
            patientRedirect("patient-portal.php");
            exit;
        } else {
            $err[] = 'No patient found with the given ID and Date of Birth.';
        }
    }

    if ($err) {
        echo '<div style="margin: 10px 25px; padding: 10px; background: #FFE0E0; border: 1px solid #FF9999; color: #CC0000;">';
        echo implode('<br />', $err);
        echo '</div>';
    }
}
?>

<h3>Patient Login</h3>
<p>Welcome to the patient portal. Please log in with your Patient ID and Date of Birth.</p>

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
        <input type="hidden" name="patient_login" value="1" />
        <input type="submit" value="Login" class="bt_login" />
    </p>
</form>

<script>
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

<p style="margin: 10px 25px;"><small>Don't know your Patient ID? Please contact the clinic.</small></p>

<?php include('patient-footer.php'); ?>
