<?php
// This file was auto-generated from sdk-root/src/data/neptune-graph/2023-11-29/waiters-2.json
return [ 'version' => 2, 'waiters' => [ 'GraphAvailable' => [ 'description' => 'Wait until Graph is Available', 'delay' => 60, 'maxAttempts' => 480, 'operation' => 'GetGraph', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'DELETING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'AVAILABLE', ], ], ], 'GraphDeleted' => [ 'description' => 'Wait until Graph is Deleted', 'delay' => 60, 'maxAttempts' => 60, 'operation' => 'GetGraph', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status != \'DELETING\'', 'state' => 'failure', 'expected' => true, ], [ 'matcher' => 'error', 'state' => 'success', 'expected' => 'ResourceNotFoundException', ], ], ], 'GraphSnapshotAvailable' => [ 'description' => 'Wait until GraphSnapshot is Available', 'delay' => 60, 'maxAttempts' => 120, 'operation' => 'GetGraphSnapshot', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'DELETING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'AVAILABLE', ], ], ], 'GraphSnapshotDeleted' => [ 'description' => 'Wait until GraphSnapshot is Deleted', 'delay' => 60, 'maxAttempts' => 60, 'operation' => 'GetGraphSnapshot', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status != \'DELETING\'', 'state' => 'failure', 'expected' => true, ], [ 'matcher' => 'error', 'state' => 'success', 'expected' => 'ResourceNotFoundException', ], ], ], 'ImportTaskCancelled' => [ 'description' => 'Wait until Import Task is Cancelled', 'delay' => 60, 'maxAttempts' => 60, 'operation' => 'GetImportTask', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status != \'CANCELLING\'', 'state' => 'failure', 'expected' => true, ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'CANCELLED', ], ], ], 'ImportTaskSuccessful' => [ 'description' => 'Wait until Import Task is Successful', 'delay' => 60, 'maxAttempts' => 480, 'operation' => 'GetImportTask', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'CANCELLING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'CANCELLED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'ROLLING_BACK', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'SUCCEEDED', ], ], ], 'PrivateGraphEndpointAvailable' => [ 'description' => 'Wait until PrivateGraphEndpoint is Available', 'delay' => 10, 'maxAttempts' => 180, 'operation' => 'GetPrivateGraphEndpoint', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'DELETING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'AVAILABLE', ], ], ], 'PrivateGraphEndpointDeleted' => [ 'description' => 'Wait until PrivateGraphEndpoint is Deleted', 'delay' => 10, 'maxAttempts' => 180, 'operation' => 'GetPrivateGraphEndpoint', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status != \'DELETING\'', 'state' => 'failure', 'expected' => true, ], [ 'matcher' => 'error', 'state' => 'success', 'expected' => 'ResourceNotFoundException', ], ], ], ],];
