<?php
class ErrorHandler{
    public static function handle(
        Throwable $exception,
        int $statusCode = 500
    ): void {
        // Statuscode setzen
        if (!headers_sent()) {
            http_response_code($statusCode);
        }

        // Logging (immer echte Exception!)
        $log = sprintf(
            "[%s] %s in %s:%d\n%s\n\n",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
        error_log($log);
        echo 'Internal Sever Error';
        exit();
    }
}
//https://www.youtube.com/watch?v=_q5Wv59RqKs