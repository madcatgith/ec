<?php

if (filter_input(INPUT_GET, 'action') == 'confirmEmail') {
    Subscribe::getInstance()->confirmEmail(filter_input(INPUT_GET, 'app'));
}