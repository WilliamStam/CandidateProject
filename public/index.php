<?php
header_remove("X-Powered-By");
(require __DIR__ . '/../app/Application.php')->run();