<?php
# Definições de pastas
define('URL', 'http://localhost/phpmvc/public/');
define('LIBS', '../app/core/');
define('CONTROLLERS', '../app/controllers/');
define('MODELS', '../app/models/');
define('VIEWS', '../app/views/');
define('TITLE', 'Curso de PHP + MVC');

# Configurações de bancos de dados
define('_DB_TYPE', 'mysql');
define('_DB_HOST', 'localhost');
define('_DB_USER', 'root');
define('_DB_PASS', '010203');
define('_DB_NAME', 'phpmvc');

# Configurações de segurança
define('HASH_ALGO', 'sha512');
define('HASH_KEY', 'minha_chave');
define('HASH_SECRET', 'minha_palavra_secreta');
define('SECRET_WORD', 'so_secret');

# Visualiar erros do PHP
define('DISPLAYERRORS', 1); /* 0 = Não; 1 = Sim */