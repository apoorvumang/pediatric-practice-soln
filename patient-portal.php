<?php
include('patient-header.php');

$patient_id = (int)$_SESSION['patient_id'];

// Fetch patient info
$stmt = mysqli_prepare($link, "SELECT * FROM patients WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $patient_id);
mysqli_stmt_execute($stmt);
$patient = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$patient) {
    echo "<h3>Patient record not found.</h3>";
    include("patient-footer.php");
    exit;
}
?>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab-container').easytabs({animate: false, defaultTab: "li:first-child"});
});

// Pagination for visits
jQuery(function($) {
    var items = $("#visits_section > tr");
    var numItems = items.length;
    var perPage = 6;
    items.slice(perPage).hide();
    $(".pagination-page").pagination({
        items: numItems,
        itemsOnPage: perPage,
        cssStyle: "light-theme",
        onPageClick: function(pageNumber) {
            var showFrom = perPage * (pageNumber - 1);
            var showTo = showFrom + perPage;
            items.hide().slice(showFrom, showTo).show();
        }
    });
});
</script>

<style>
    .etabs { margin: 0; padding: 0; }
    .tab { display: inline-block; zoom:1; *display:inline; background: #eee; border: solid 1px #999; border-bottom: none; -moz-border-radius: 4px 4px 0 0; -webkit-border-radius: 4px 4px 0 0; }
    .tab a { font-size: 14px; line-height: 2em; display: block; padding: 0 10px; outline: none; }
    .tab a:hover { text-decoration: underline; }
    .tab.active { background: #fff; padding-top: 6px; position: relative; top: 1px; border-color: #666; }
    .tab a.active { font-weight: bold; }
    .tab-container .panel-container { background: #fff; border: solid #666 1px; padding: 10px; -moz-border-radius: 0 4px 4px 4px; -webkit-border-radius: 0 4px 4px 4px; }
    .panel-container { margin-bottom: 10px; }
    .tab-container .outer-div {border:1px solid black;padding-right: 20px; padding-left: 20px}
    .vac-upcoming { background: #FFFDE7; }
    .vac-overdue { background: #FFEBEE; }
    .vac-given { background: #E8F5E9; }
</style>

<h3>
    Welcome, <?php echo htmlspecialchars($patient['name']); ?> (ID: <?php echo $patient['id']; ?>)
</h3>

<div id="tab-container" class="tab-container" animate="false">
    <ul class='etabs'>
        <li class='tab'><a href="#vaccination-tab">Upcoming Vaccinations</a></li>
        <li class='tab'><a href="#patient-info-tab">My Information</a></li>
        <li class='tab'><a href="#medcert-tab">Medical Certificates</a></li>
        <li class='tab'><a href="#visits-tab">Visit History</a></li>
    </ul>

    <!-- ============== UPCOMING VACCINATIONS TAB ============== -->
    <div id="vaccination-tab" class="outer-div">
        <h3>Vaccination Schedule</h3>
        <p>
            <strong><a href="<?php echo "patient-pdf-schedule.php?id={$patient_id}"; ?>" target="_blank">View/Print complete vaccination record (PDF)</a></strong>
        </p>

        <?php
        // Fetch upcoming (not given) vaccinations ordered by date
        $q = "SELECT vs.id, vs.date, vs.date_given, vs.given, v.name as vaccine_name, vm.name as make_name
              FROM vac_schedule vs
              JOIN vaccines v ON vs.v_id = v.id
              LEFT JOIN vac_make vm ON vs.make = vm.id
              WHERE vs.p_id = ?
              ORDER BY vs.given ASC, vs.date ASC";
        $stmt = mysqli_prepare($link, $q);
        mysqli_stmt_bind_param($stmt, "i", $patient_id);
        mysqli_stmt_execute($stmt);
        $vac_result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        $upcoming = array();
        $given_list = array();
        $today = date('Y-m-d');

        while ($row = mysqli_fetch_assoc($vac_result)) {
            if ($row['given'] == 'Y') {
                $given_list[] = $row;
            } else {
                $upcoming[] = $row;
            }
        }
        ?>

        <h4>Upcoming / Pending Vaccinations</h4>
        <?php if (count($upcoming) > 0) { ?>
        <table border="1">
            <tr>
                <th>S.No.</th>
                <th>Vaccine</th>
                <th>Scheduled Date</th>
                <th>Status</th>
            </tr>
            <?php
            $i = 1;
            foreach ($upcoming as $row) {
                $sched_date = $row['date'];
                $is_overdue = ($sched_date < $today && $sched_date != '0000-00-00');
                $is_upcoming_soon = (!$is_overdue && $sched_date != '0000-00-00' && strtotime($sched_date) <= strtotime('+30 days'));
                $css_class = '';
                $status = 'Scheduled';
                if ($is_overdue) {
                    $css_class = 'vac-overdue';
                    $status = 'Overdue';
                } elseif ($is_upcoming_soon) {
                    $css_class = 'vac-upcoming';
                    $status = 'Due Soon';
                }
                ?>
                <tr class="<?php echo $css_class; ?>">
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
                    <td><?php echo ($sched_date != '0000-00-00') ? date('j M Y', strtotime($sched_date)) : 'TBD'; ?></td>
                    <td><strong><?php echo $status; ?></strong></td>
                </tr>
            <?php } ?>
        </table>
        <?php } else { ?>
        <p>All vaccinations are up to date!</p>
        <?php } ?>

        <h4>Completed Vaccinations</h4>
        <?php if (count($given_list) > 0) { ?>
        <table border="1">
            <tr>
                <th>S.No.</th>
                <th>Vaccine</th>
                <th>Date Given</th>
                <th>Product</th>
            </tr>
            <?php
            $i = 1;
            foreach ($given_list as $row) {
                $date_given = $row['date_given'];
                ?>
                <tr class="vac-given">
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
                    <td><?php echo ($date_given && $date_given != '0000-00-00') ? date('j M Y', strtotime($date_given)) : 'Given (date not recorded)'; ?></td>
                    <td><?php echo htmlspecialchars($row['make_name'] ?? '-'); ?></td>
                </tr>
            <?php } ?>
        </table>
        <?php } else { ?>
        <p>No vaccinations recorded yet.</p>
        <?php } ?>
    </div>

    <!-- ============== PATIENT INFORMATION TAB ============== -->
    <div id="patient-info-tab" class="outer-div">
        <h3>Patient Information</h3>

        <h4><strong>ID: <?php echo $patient['id']; ?></strong></h4>

        <table style="margin: 0px 0px 0px 0px;border:none;">
            <tr>
                <td>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['name']); ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo date('d-F-Y', strtotime($patient['dob'])); ?></p>
                    <p><strong>Age:</strong>
                    <?php
                        $from = new DateTime($patient['dob']);
                        $to = new DateTime('tomorrow');
                        $age = $from->diff($to);
                        echo $age->y . " years " . $age->m . " months " . $age->d . " days";
                    ?>
                    </p>
                    <p><strong>Sex:</strong> <?php echo htmlspecialchars($patient['sex']); ?></p>
                </td>
                <td>
                    <p><strong>Father's Name:</strong>
                    <?php
                        echo htmlspecialchars($patient['father_name'] ?? '');
                        if ($patient['father_occ']) echo ", " . htmlspecialchars($patient['father_occ']);
                    ?>
                    </p>
                    <p><strong>Mother's Name:</strong>
                    <?php
                        echo htmlspecialchars($patient['mother_name'] ?? '');
                        if ($patient['mother_occ']) echo ", " . htmlspecialchars($patient['mother_occ']);
                    ?>
                    </p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone'] ?? ''); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email'] ?? ''); ?></p>
                </td>
            </tr>
        </table>

        <h4>Additional Details</h4>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($patient['address'] ?? ''); ?></p>
        <p><strong>Birth Weight:</strong> <?php echo htmlspecialchars($patient['birth_weight'] ?? ''); ?> grams</p>
        <p><strong>Mode of Delivery:</strong> <?php echo htmlspecialchars($patient['mode_of_delivery'] ?? ''); ?></p>
        <p><strong>Gestation:</strong> <?php echo htmlspecialchars($patient['gestation'] ?? ''); ?></p>
        <p><strong>Date of Registration:</strong> <?php echo ($patient['date_of_registration']) ? date('d-F-Y', strtotime($patient['date_of_registration'])) : ''; ?></p>
    </div>

    <!-- ============== MEDICAL CERTIFICATES TAB ============== -->
    <div id="medcert-tab" class="outer-div">
        <h3>Medical Certificates</h3>
        <?php
        mysqli_query($link, "SET time_zone = '+5:30';");
        $stmt = mysqli_prepare($link, "SELECT id, timestamp FROM medcerts WHERE p_id = ? ORDER BY timestamp DESC");
        mysqli_stmt_bind_param($stmt, "i", $patient_id);
        mysqli_stmt_execute($stmt);
        $medcert_result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        $cert_count = 0;
        ?>
        <ul>
        <?php
        while ($row = mysqli_fetch_assoc($medcert_result)) {
            $cert_count++;
            $madeOn = date('M j, Y g:i A', strtotime($row['timestamp']));
            echo "<li><a href='patient-show-medcert.php?pdf_id={$row['id']}' target='_blank'>Certificate {$cert_count} - generated on {$madeOn}</a></li>";
        }
        ?>
        </ul>
        <?php if ($cert_count == 0) { ?>
        <p>No medical certificates have been generated yet.</p>
        <?php } ?>
    </div>

    <!-- ============== VISIT HISTORY TAB ============== -->
    <div id="visits-tab" class="outer-div">
        <h3>Visit History</h3>

        <?php
        $stmt = mysqli_prepare($link, "SELECT id, date, note, height, weight FROM notes WHERE p_id = ? ORDER BY date DESC");
        mysqli_stmt_bind_param($stmt, "i", $patient_id);
        mysqli_stmt_execute($stmt);
        $visits_result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        $visit_rows = array();
        while ($row = mysqli_fetch_assoc($visits_result)) {
            $visit_rows[] = $row;
        }
        ?>

        <?php if (count($visit_rows) > 0) { ?>
        <div class="pagination-page"></div>
        <table border="1" width="700px">
            <tr>
                <th>Date</th>
                <th>Height (cm)</th>
                <th>Weight (kg)</th>
                <th>BMI</th>
                <th>Notes</th>
                <th>Prescriptions</th>
                <th>Invoices</th>
            </tr>
            <tbody id="visits_section">
            <?php foreach ($visit_rows as $row) { ?>
                <tr>
                    <td><?php echo date('j M Y', strtotime($row['date'])); ?></td>
                    <td style="text-align:center;"><?php echo htmlspecialchars($row['height'] ?? ''); ?></td>
                    <td style="text-align:center;"><?php echo htmlspecialchars($row['weight'] ?? ''); ?></td>
                    <td style="text-align:center;">
                    <?php
                    if (isset($row['height'], $row['weight']) && is_numeric($row['height']) && is_numeric($row['weight'])) {
                        $height = $row['height'] / 100.0;
                        $weight = $row['weight'];
                        if ($height != 0) {
                            echo number_format((float)($weight / ($height * $height)), 2, '.', '');
                        } else {
                            echo "NA";
                        }
                    } else {
                        echo "NA";
                    }
                    ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['note'] ?? ''); ?></td>
                    <td>
                    <?php
                    // Fetch prescriptions for this visit
                    $visit_id = $row['id'];
                    $presc_stmt = mysqli_prepare($link, "SELECT id, url FROM prescriptions WHERE visit_id = ?");
                    mysqli_stmt_bind_param($presc_stmt, "i", $visit_id);
                    mysqli_stmt_execute($presc_stmt);
                    $presc_result = mysqli_stmt_get_result($presc_stmt);
                    $p_count = 0;
                    while ($presc = mysqli_fetch_assoc($presc_result)) {
                        $p_count++;
                        $prescUrl = htmlspecialchars($presc['url']);
                        echo "<a href='{$prescUrl}' target='_blank'>Prescription {$p_count}</a><br>";
                    }
                    mysqli_stmt_close($presc_stmt);
                    if ($p_count == 0) echo "-";
                    ?>
                    </td>
                    <td>
                    <?php
                    // Fetch invoices for this visit
                    $inv_stmt = mysqli_prepare($link, "SELECT invoice_id FROM visit_invoices WHERE visit_id = ?");
                    mysqli_stmt_bind_param($inv_stmt, "i", $visit_id);
                    mysqli_stmt_execute($inv_stmt);
                    $inv_result = mysqli_stmt_get_result($inv_stmt);
                    $inv_count = 0;
                    while ($inv = mysqli_fetch_assoc($inv_result)) {
                        $inv_count++;
                        $invoiceId = (int)$inv['invoice_id'];
                        echo "<a href='patient-pdf-invoice.php?id={$invoiceId}' target='_blank' style='color:#2c76a6;'>";
                        echo "<span style='background-color:#2c76a6;padding:3px 6px;margin:2px;display:inline-block;color:white;font-size:11px;'>{$invoiceId}</span></a> ";
                    }
                    mysqli_stmt_close($inv_stmt);
                    if ($inv_count == 0) echo "-";
                    ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <p>No visits recorded yet.</p>
        <?php } ?>
    </div>

</div>

<?php include('patient-footer.php'); ?>
