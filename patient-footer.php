
<div style="margin:50px 0 0 25px;"><a href="javascript:history.go(-1);">Back</a></div>
<!-- /main -->
		</div>



    <!-- content -->
	</div>

<!-- /content-out -->
</div>

<!-- footer-bottom -->
<div id="footer-bottom">

	<p class="bottom-left">
		Design by <a href="http://www.styleshout.com/">styleshout</a>
	</p>

	<p class="bottom-right">
		<a href="patient-portal.php">Dashboard</a> |
		<a href="patient-header.php?logout=1">Logout</a> |
      <strong><a href="#top">Back to Top</a></strong>
   </p>
<?php if (isset($link) && $link) { mysqli_close($link); } ?>
<!-- /footer-bottom-->
</div>

</body>
</html>
