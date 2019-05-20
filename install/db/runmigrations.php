<?php
chdir('db');

echo shell_exec('./../../vendor/bin/phinx rollback -t 0');
echo "<br><br>";
echo shell_exec('./../../vendor/bin/phinx rollback -t 0');
echo "<br><br>";
