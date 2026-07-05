<?php

/**
 * Base controller for API endpoints.
 * Provides helpers for reading request body and sending JSON responses.
 */
class ApiController
{
    /**
     * Get the parsed request body (supports both form POST and JSON).
     */
    protected function body()
    {
        if (class_exists('App') && !App::$isApi) {
            return $_POST;
        }

        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * Send a JSON response.
     */
    protected function json($data, $status = 200)
    {
        Response::json($data, $status);
    }
}