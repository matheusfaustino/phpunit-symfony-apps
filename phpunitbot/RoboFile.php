<?php

include_once './vendor/autoload.php';

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    // define public methods as commands
    public function watchTests($container = '')
    {
        $insideContainer = file_exists('/.dockerenv');
        if (!$insideContainer && empty($container)) {
            throw new \Exception('you must pass the container name as parameter');
        }

        $pathRobo = pathinfo(__DIR__);
        $projectPath = $pathRobo['dirname'].'/html';

       $xml = simplexml_load_file($projectPath.'/app/phpunit.xml.dist');
        if (!$xml) {
            throw new \Exception('"phpunit.xml.dist" not found');
        }

        $watchDir = [];
        $watchDir[] = $projectPath.'/src';
        $watchDir[] = $projectPath.'/web/tests';

        if ($insideContainer)
            $command = $projectPath.'/bin/phpunit -c '.$projectPath.'/app/';
        else
            /* executes from the outside */
            $command = sprintf("docker exec -i %s %s/bin/phpunit -c ./app", $container, $projectPath);

        /* run the fist time */
        $this->taskExec($command)->run();

        /* watch to run */
        $this->taskWatch()
            ->monitor(
                $watchDir,
                function () use ($command) {
                    $this->taskExec($command)->run();
                }
            )->run();
    }
}
