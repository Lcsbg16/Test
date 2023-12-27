<?php
class Conversao {
    public static function velVentoParakmH($velocidadeMPS) {
        // Fórmula para converter m/s para km/h
        return $velocidadeMPS * 3.6;
    }
}

