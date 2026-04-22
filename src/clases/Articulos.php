<?php




class Articulos
{

    private $db;

    function __construct(Database $database)
    {

        $this->db = $database->getConnection();
    }

    // Creamos la funcion encargada de subir el producto

    public function subirArticulo($usuario_id, $titulo, $descripcion, $categoria, $estado, $cambio)
    {
        $sql = "INSERT INTO articulos ( usuario_id, titulo, descripcion , categoria, estado, cambio ) VALUES ( :usuario_id, :titulo, :descripcion ,:categoria, :estado, :cambio)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([

            ':usuario_id' => $usuario_id,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':categoria' => $categoria,
            ':estado' => $estado,
            ':cambio' => $cambio

        ]);
    }


    public function buscarArticuloPorTitulo($titulo)
    {
        $sql = "SELECT * FROM articulos WHERE titulo = :titulo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':titulo' => $titulo]);
        return $stmt->fetch();
    }

    public function buscarArticuloPorCategoria($categoria)
    {
        $sql = "SELECT * FROM articulos WHERE categoria = :categoria";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':categoria' => $categoria]);
        return $stmt->fetch();
    }

    public function buscarArticuloPorEstado($estado)
    {
        $sql = "SELECT * FROM articulos WHERE estado = :estado";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':estado' => $estado]);
        return $stmt->fetch();
    }



    public function listarArticulos()
    {
        $sql = "SELECT * FROM articulos";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function listarArticulosConFoto()
    {
        $sql = "SELECT * FROM articulos a INNER JOIN articulos_fotos f ON  a.id = f.articulo_id;";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function idUltimoArticulo()
    {


        $sql = "SELECT MAX(id) FROM articulos ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    public function borrarArticulo($id_a_borrar){
        $sql = "DELETE FROM articulos WHERE id_articulo = :id";
         $stmt = $this->db->query($sql);
        $stmt->execute([":id"=> $id_a_borrar]);
    }
}
