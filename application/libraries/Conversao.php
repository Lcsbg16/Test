<?php
class Conversao {
    public static function velVentoParakmH($velocidadeMPS) {
        // Fórmula para converter m/s para km/h: 1 m/s = 3,6 km/h
        return $velocidadeMPS * 3.6;
    }
}

