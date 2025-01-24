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
