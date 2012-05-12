<?php

// Обновляем шаблоны из SVN-репозитория vnukovo-transagency.googlecode.com
print( '<h2>Обновление шаблонов сайта из SVN-репозитория vnukovo-transagency.googlecode.com</h2>' );
print ( '<code><pre>' );
system ( 'svn export https://vnukovo-transagency.googlecode.com/svn/trunk/bitrix/templates/ bitrix/templates/ --username klisin@gmail.com --force' );
print ( '</pre></code>' );

print( '<h2>Листинг папки /bitrix/templates/</h2>' );
print ( '<code><pre>' );
system ( 'ls -l bitrix/templates/' );
print ( '</pre></code>' );

?>