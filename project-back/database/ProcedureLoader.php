<?php

class ProcedureLoader
{
    private $conn;
    private $proceduresPath;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();

        $this->proceduresPath = __DIR__ . '/../database/procedures';
    }

    public static function install() {
        try {
            $loader = new ProcedureLoader();
            $loader->loadProcedures();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function loadProcedures()
    {
        $files = $this->getSqlFiles($this->proceduresPath);
        foreach ($files as $file) {
            try {
                $sql = file_get_contents($file);

                $this->conn->exec($sql);
            } catch (PDOException $e) {
                echo "Error loading procedure from file: " . basename($file) . ". Error: " . $e->getMessage() . PHP_EOL;
            }
        }
        echo "Loaded all procedures successfully." . PHP_EOL;
    }

    private function getSqlFiles($directory)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        $files = [];
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'sql') {
                $files[] = $file->getPathname();
                echo $file->getPathname() . PHP_EOL;
            }
        }

        return $files;
    }
}
