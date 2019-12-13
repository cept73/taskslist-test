<?php

namespace Todo;

use Jenssegers\Blade\Blade;

function View($fileName, $params = [])
{
    // Render some Blade view
    $blade = new Blade('views', 'cache');
    try {
        // Render it
        echo $blade->render($fileName, $params);
    }
    catch (\Exception $ex) {
        // Anti-forever-recursion
        if ($fileName == 'error')
            die('<h2>Application corrupt</h2>' . json_encode($ex));

        // Render error template
        return View('error', [
            'fileName' => $fileName, 
            'params' => $params
            ]);
    }

    // Return true and only true!
    return true;
}
