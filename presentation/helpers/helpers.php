<?php

function setFlashMessage(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['flash_messages'][$type][] = $message;
}

function getFlashMessages(): array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);

    return $messages;
}

function setOld(string $key, string $value): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION["input_$key"] = $value;
}

function old(string $key, string $value = ''): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $data = $_SESSION["input_$key"] ?? $value;
    unset($_SESSION["input_$key"]);

    return $data;
}

function renderPagination(int $currentPage, int $totalPages, string $baseUrl): string
{

    $html = '<nav aria-label="Page navigation">';
    $html .= '<ul class="pagination justify-content-center">';

    if ($currentPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">Previous</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            $html .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
        }
    }

    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">Next</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
    }

    $html .= '</ul>';
    $html .= '</nav>';

    return $html;
}

function renderPaginationWithQueryParams(int $currentPage, int $totalPages, string $baseUrl, array $queryParams): string
{
    $buildQuery = function (int $page) use ($queryParams) {
        $queryParams['page'] = $page;
        return http_build_query($queryParams);
    };

    $html = '<nav aria-label="Page navigation">';
    $html .= '<ul class="pagination justify-content-center">';

    if ($currentPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?' . $buildQuery($currentPage - 1) . '">Previous</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            $html .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?' . $buildQuery($i) . '">' . $i . '</a></li>';
        }
    }

    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?' . $buildQuery($currentPage + 1) . '">Next</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
    }

    $html .= '</ul>';
    $html .= '</nav>';

    return $html;
}

function uploadImage($uploadPath, $inputName) {
    if (!isset($_FILES[$inputName])) {
        throw new Exception("No file uploaded.");
    }

    if ($_FILES[$inputName]["error"] != 0) {
        throw new Exception("File upload error: " . $_FILES[$inputName]["error"]);
    }

    $fileType = strtolower(pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception("Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    if (!is_dir($uploadPath)) {
        if (!mkdir($uploadPath, 0755, true)) {
            throw new Exception("Failed to create upload directory.");
        }
    }

    $randomString = bin2hex(random_bytes(8));
    $fileName = $randomString . "." . $fileType;
    $targetFilePath = $uploadPath . $fileName;

    if (!move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFilePath)) {
        throw new Exception("Failed to move uploaded file.");
    }

    return $targetFilePath;
}

function deleteFile($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception("File does not exist: " . $filePath);
    }

    if (!is_writable($filePath)) {
        throw new Exception("File is not writable: " . $filePath);
    }

    if (!unlink($filePath)) {
        throw new Exception("Failed to delete file: " . $filePath);
    }

    return true;
}


