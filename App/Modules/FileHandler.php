<?php
namespace App\Modules;

class FileHandler{
    public static function handle_file_upload($file, $allowed_types, $upload_dir,$CoverName, $max_size = 5242880) {
        if ($file['error'] !== 0) {
            return ['success' => false, 'error' => 'Upload failed'];
        }
        
        if ($file['size'] > $max_size) {
            return ['success' => false, 'error' => 'File too large'];
        }
        
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $file['tmp_name']);
        finfo_close($file_info);
        
        if (!in_array($mime_type, $allowed_types)) {
            return ['success' => false, 'error' => 'Invalid file type'];
        }
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = $CoverName . '.' . $ext;
        $destination = $upload_dir . $new_filename;
        
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'error' => 'Move failed'];
        }
        
        return [
            'success' => true,
            'filename' => $new_filename,
            'path' => $destination
        ];
    }
}



