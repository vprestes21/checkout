<?php

/**
 * Base Controller Class
 * All controllers extend this class to inherit common functionality
 */
class Controller {
    /**
     * Render a view
     * 
     * @param string $view The view file to render
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function render($view, $data = []) {
        // Extract data to make variables available to the view
        if (!empty($data)) {
            extract($data);
        }
        
        // Include the view file
        if (file_exists("views/{$view}.php")) {
            require_once "views/{$view}.php";
        } else {
            // Handle error if view doesn't exist
            die("View {$view}.php not found");
        }
    }
    
    /**
     * Redirect to a specific page
     * 
     * @param string $url URL to redirect to
     * @return void
     */
    protected function redirect($url) {
        header("Location: " . BASE_URL . "/" . $url);
        exit;
    }
    
    /**
     * Load a model
     * 
     * @param string $model Model name
     * @return object Model instance
     */
    protected function loadModel($model) {
        if (file_exists("models/{$model}.php")) {
            require_once "models/{$model}.php";
            return new $model();
        }
        return null;
    }
}
