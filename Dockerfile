FROM php:7.0-cli

ADD test.php /test.php

VOLUME /mounted

CMD php test.php