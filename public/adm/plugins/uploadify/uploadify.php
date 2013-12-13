<?php

/*
  Uploadify
  Copyright (c) 2012 Reactive Apps, Ronnie Garcia
  Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

try {
// Define a destination
  $targetFolder = '/uploads'; // Relative to the root
  $targetFolder = ini_get("upload_tmp_dir");

  $verifyToken = md5('unique_salt' . $_POST['timestamp']);

  if ( !empty($_FILES) && $_POST['token'] == $verifyToken ) {
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $targetFile = rtrim($targetFolder, '/') . '/' . md5(date('YmdHisu') . microtime(true) . $tempFile);

    // Validate the file type
    $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
    $fileParts = pathinfo($_FILES['Filedata']['name']);

    if ( in_array(strtolower($fileParts['extension']), $fileTypes) ) {
      move_uploaded_file($tempFile, $targetFile);

      $tmpname = basename($targetFile);
      $name = $_FILES['Filedata']['name'];
      $response = array('error' => '0', 'tmpname' => $tmpname, 'name' => $name, 'file_id'=>$_POST['file_id']);
      echo json_encode($response);
    } else {
      echo json_encode(array('error' => '1'));
    }
  }
} catch (Exception $e) {
  echo json_encode(array('error' => '1', 'msg' => $e->getMessage()));
}
?>