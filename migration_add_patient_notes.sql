-- Migration script to add notes column to patients table
-- Run this on existing databases that need the notes field

ALTER TABLE patients ADD COLUMN notes TEXT DEFAULT NULL;

-- Verify the column was added
DESCRIBE patients;
