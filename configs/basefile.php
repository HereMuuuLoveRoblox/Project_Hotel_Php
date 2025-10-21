<?php

    function base_url(): string {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'];

        // เอกสารรากของเว็บ (filesystem)
        $docRootFs = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
        // โฟลเดอร์รากโปรเจกต์ (filesystem) — สมมติไฟล์นี้อยู่ใน /Myhotel/configs
        $projectRootFs = str_replace('\\', '/', realpath(dirname(__DIR__)));

        // แปลง path filesystem → path URL สัมพัทธ์
        $relative = trim(str_replace($docRootFs, '', $projectRootFs), '/');

        return $scheme . '://' . $host . '/' . $relative;
    }

    function url(string $path = ''): string {
        return rtrim(base_url(), '/') . '/' . ltrim($path, '/');
    }

?>