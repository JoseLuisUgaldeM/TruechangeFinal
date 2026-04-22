<?php

require "Database.php";
    

 class Articulos_fotos{

        private $db;

        public function __construct(Database $database) {
            $this->db = $database->getConnection();
        }


        
        public function subirFotoArticulo($articulo_id, $ruta_foto){

            $sql = "INSERT INTO articulos_fotos ( articulo_id, ruta_foto ) VALUES ( :articulo_id, :ruta_foto)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([

            ':articulo_id' => $articulo_id,
            ':ruta_foto' => $ruta_foto,
       

        ]);

        }
}