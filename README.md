# mummy-software
This is a php + mysql solution for pediatric practice management. It started with the aim to enable paperless vaccination management for
pediatricians along with providing certain benefits for the patient (such as vaccination reminders, personalized schedule). It now includes other parts of practice management such as invoicing, medical certificates, payment reminders.

The main thing setting this solution apart from others is the ability to send messages to patients regarding vaccination/upcoming visits/birthdays/out of station messages automatically **without any limit on the number of messages**. This is because your own Android device can be configured to send these automated messages on your behalf and no third-party SMS service is required.

Demo
====
No demo available at this time :(

Steps for installing on new machine
===================================

1. Download both databases - drmahima_com and drmahima_com_db_root

2. Create connect.php in root directory, set parameters according to local settings (ie username and password)

3. Modify doctors table in drmahima_com_db_root and set the correct db_user and db_pass for all entries. This should be the same as the local mysql user and password.
