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


