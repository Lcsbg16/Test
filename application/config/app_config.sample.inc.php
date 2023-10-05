<?php

/*
 * Arquivo de configurações
 */

error_reporting(E_ALL & E_DEPRECATED);

////// Configuração de BD
define("APP_DB_SERVER", "localhost");
define("APP_DB_USERNAME", "root");
define("APP_DB_PASSWORD", "");
define("APP_DB_DATABASE", "projeto_telemetria");
//define("APP_DB_DATABASE", "telemetria_teste");
define("APP_DB_PORT", 3306);
define("DB_DRIVER", 'mysqli');

/**
 * Debug da base de dados
 */
define('DB_DEBUG', true);

/**
 * Nome da sessão
 */
define('SESSION_NAME', 'telemetria');

/**
 * Título da aplicação
 */
define('APPLICATION_TITLE', 'Sistema de Telemetria');

/**
 * URL base da aplicação
 */
define("WEBSITE_BASE_URL", "http://localhost/telemetria-web");

//define('ADMIN_EMAIL', 'lbguimaraes16@gmail.com');
