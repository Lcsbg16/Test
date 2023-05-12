<?php

/*
 * Arquivo de configurações
 */

////// Configuração de BD
define("APP_DB_SERVER", "");
define("APP_DB_USERNAME", "");
define("APP_DB_PASSWORD", "");
define("APP_DB_DATABASE", "");
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
define("WEBSITE_BASE_URL", "http://localhost/mine/telemetria/");

error_reporting(E_ALL & E_DEPRECATED);